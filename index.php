<?php
session_start();
require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Middleware\PerfilMiddleware;

$app = new Slim();
$app->config('debug', true);

// 🔐 Middleware GLOBAL (ANTES DAS ROTAS)
$app->add(new PerfilMiddleware());

require_once("functions.php");
require_once("admin.php");
require_once("admin-funcionarios.php");
require_once("admin-dependentes.php");
require_once("admin-clientes.php");
require_once("jwt.php");

backupAutomatico();


$app->run();
