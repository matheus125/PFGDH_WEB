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
        // 🟡 Rotas públicas (sem autenticação)
        $rotasPublicas = [
            '/admin/login',
            '/login'
        ];

        $uri = strtok($_SERVER['REQUEST_URI'], '?');

        if (in_array($uri, $rotasPublicas)) {
            $this->next->call();
            return;
        }

        // 🔐 Não logado
        if (!Funcionarios::checkLogin()) {
            header("Location: /admin/login");
            exit;
        }

        $perfilUsuario = $_SESSION[Funcionarios::SESSION]['perfil'] ?? null;

        $permissoes = Permissions::map();

        foreach ($permissoes as $rota => $perfisPermitidos) {

            // Converte :id para regex
            $rotaRegex = preg_replace('/:\w+/', '\d+', $rota);

            if (preg_match("#^{$rotaRegex}$#", $uri)) {

                if (!in_array($perfilUsuario, $perfisPermitidos)) {

                    // 🔴 LOG DE ACESSO NEGADO
                    self::logAcessoNegado($perfilUsuario, $uri);

                    header("Location: /acesso-negado");
                    exit;
                }
            }
        }

        // 👉 Continua execução normal
        $this->next->call();
    }

    private static function logAcessoNegado($perfil, $rota)
    {
        $sql = new Sql();

        $sql->query(
            "INSERT INTO tb_access_denied 
            (perfil, rota, ip, user_agent, created_at)
            VALUES (:perfil, :rota, :ip, :user_agent, NOW())",
            [
                ':perfil' => $perfil,
                ':rota' => $rota,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN'
            ]
        );
    }
}
