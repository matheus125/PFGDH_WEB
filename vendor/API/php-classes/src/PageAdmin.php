<?php

namespace Hcode;

use Hcode\Model\Funcionarios;
use Hcode\Model\Notification;

class PageAdmin extends Page
{
    public function __construct($opts = array(), $tpl_dir = "/admin/")
    {
        $data = isset($opts['data']) && is_array($opts['data']) ? $opts['data'] : [];
        $notificacoes = $data['notificacoes'] ?? Notification::getAll();
        $total = $data['total'] ?? count($notificacoes);
        $usuario = Funcionarios::getFromSession();

        $opts['data'] = array_merge([
            'notificacoes' => $notificacoes,
            'total' => $total,
            'currentProfile' => $usuario->getperfil() ?: '',
            'currentPermissions' => Funcionarios::getPermissions(),
        ], $data);

        parent::__construct($opts, $tpl_dir);
    }
}
