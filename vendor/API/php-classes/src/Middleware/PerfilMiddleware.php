<?php

namespace Hcode\Middleware;

use Slim\Middleware;
use Hcode\Model\Funcionarios;
use Hcode\Security\Permissions;
use Hcode\DB\Sql;

class PerfilMiddleware extends Middleware
{
    public function call()
    {
        $rotasPublicas = ['/admin/login', '/login'];
        $uri = strtok($_SERVER['REQUEST_URI'], '?');

        if (in_array($uri, $rotasPublicas, true)) {
            $this->next->call();
            return;
        }

        if (!Funcionarios::checkLogin()) {
            header("Location: /admin/login");
            exit;
        }

        $requiredPermission = $this->findRequiredPermission($uri);

        if ($requiredPermission && !Funcionarios::hasPermission($requiredPermission)) {
            self::logAcessoNegado($_SESSION[Funcionarios::SESSION]['perfil'] ?? 'SEM_PERFIL', $uri, $requiredPermission);
            header("Location: /acesso-negado");
            exit;
        }

        $this->next->call();
    }

    private function findRequiredPermission(string $uri): ?string
    {
        foreach (Permissions::routeMap() as $rota => $permission) {
            $rotaRegex = preg_replace('/:\w+/', '[^/]+', $rota);
            $rotaRegex = str_replace('{id}', '[^/]+', $rotaRegex);
            if (preg_match("#^{$rotaRegex}$#", $uri)) {
                return $permission;
            }
        }
        return null;
    }

    private static function logAcessoNegado($perfil, $rota, $permission = null)
    {
        $sql = new Sql();
        $sql->query(
            "INSERT INTO tb_access_denied (perfil, rota, ip, user_agent, created_at) VALUES (:perfil, :rota, :ip, :user_agent, NOW())",
            [
                ':perfil' => $perfil . ($permission ? ' [' . $permission . ']' : ''),
                ':rota' => $rota,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN'
            ]
        );
    }
}
