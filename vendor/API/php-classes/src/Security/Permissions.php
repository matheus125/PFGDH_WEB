<?php

namespace Hcode\Security;

class Permissions
{
    public static function map(): array
    {
        return [

            // 🔐 DASHBOARD
            '/admin' => ['ADMIN', 'SUPERVISOR'],

            // 👥 FUNCIONÁRIOS
            '/admin/funcionarios' => ['ADMIN'],
            '/admin/funcionarios/create' => ['ADMIN'],
            '/admin/funcionarios/:id' => ['ADMIN'],
            '/admin/funcionarios/:id/delete' => ['ADMIN'],

            // 👤 CLIENTES
            '/admin/clientes' => ['ADMIN', 'SUPERVISOR'],
            '/admin/clientes/:id' => ['ADMIN', 'SUPERVISOR'],

            // 📦 ORÇAMENTOS
            '/admin/orcamentos' => ['ADMIN', 'SUPERVISOR', 'ASSESSOR'],

        ];
    }
}
