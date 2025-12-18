<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once("functions.php");
require_once("admin.php");
require_once("admin-funcionarios.php");
require_once("admin-clientes.php");
require_once("admin-orçamentos.php");
require_once("admin-passageiros.php");
require_once("jwt.php");
$app->run();

 ?>