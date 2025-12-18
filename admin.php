<?php

use \Hcode\PageAdmin;
use Firebase\JWT\JWT;
use Hcode\Model\Funcionarios;

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

$app->get('/', function () {

	// Apenas ADMIN e SUPERVISOR
	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	$page = new PageAdmin();
	$page->setTpl("index");
});

// Rota GET -> exibe o formulário de login (renderiza login.tpl)
$app->get('/admin/login', function () {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("login", [
		'error' => Funcionarios::getError()
	]);
});

/*
|--------------------------------------------------------------------------
| TESTE ESTRUTURA TABELA (APENAS ADMIN)
|--------------------------------------------------------------------------
*/
$app->get('/admin/test-tb-usuario', function () {

	Funcionarios::verifyLogin('ADMIN');

	header('Content-Type: application/json; charset=utf-8');

	try {
		$sql = new \Hcode\DB\Sql();
		$results = $sql->select("DESCRIBE tb_usuario");

		echo json_encode([
			"success"   => true,
			"structure" => $results
		]);
	} catch (Exception $e) {
		echo json_encode([
			"success" => false,
			"error"   => $e->getMessage()
		]);
	}

	exit;
});

/*
|--------------------------------------------------------------------------
| LOGIN (POST)
|--------------------------------------------------------------------------
*/
$app->post('/admin/login', function () {

	header('Content-Type: application/json');

	try {

		$funcionarios = Funcionarios::login($_POST["cpf"], $_POST["senha"]);

		$_SESSION[Funcionarios::SESSION] = $funcionarios->getValues();

		Funcionarios::registerAccess($funcionarios, 'LOGIN');

		echo json_encode(["success" => true]);
		exit;
	} catch (Exception $e) {

		echo json_encode([
			"success" => false,
			"error"   => $e->getMessage()
		]);
		exit;
	}
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
$app->get('/admin/logout', function () {

	$funcionarios = Funcionarios::getFromSession();

	if ($funcionarios && $funcionarios->getid_usuario() > 0) {
		Funcionarios::registerAccess($funcionarios, 'LOGOUT');
	}

	Funcionarios::logout();

	header("Location: /admin/login");
	exit;
});
