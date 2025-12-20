<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Clientes;
use Hcode\Model\Funcionarios;
use \Hcode\DB\Sql;

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

$app->get("/api/total-titulares", function () {
	$total = Clientes::total_usuarios(); // esse método deve retornar um número
	echo json_encode(["total" => $total]);
});

$app->get('/api/usuarios-titulares', function () {

	header('Content-Type: application/json; charset=utf-8');

	try {
		$sql = new Sql();

		$dados = $sql->select("
            SELECT 
                id,
                nome_completo,
                telefone
            FROM tb_titular
            ORDER BY nome_completo
        ");

		echo json_encode($dados, JSON_UNESCAPED_UNICODE);
	} catch (Throwable $e) {
		http_response_code(500);
		echo json_encode([
			'erro' => true,
			'mensagem' => $e->getMessage()
		]);
	}
});



$app->get("/api/total-dependentes", function () {
	$total = Clientes::total_dependentes(); // esse método deve retornar um número
	echo json_encode(["total" => $total]);
});

$app->get("/api/total-familias", function () {
	$total = Clientes::total_familias(); // esse método deve retornar um número
	echo json_encode(["total" => $total]);
});

$app->get("/api/grafico-titulares", function () {

	require __DIR__ . "/vendor/autoload.php";


	$sql = new Sql();
	$rows = $sql->select("SELECT data_nascimento FROM tb_titular");

	$faixas = [
		'3 a 17 anos' => 0,
		'18 a 59 anos' => 0,
		'60+ anos' => 0
	];

	foreach ($rows as $row) {
		if (!empty($row['data_nascimento'])) {
			$dt = new DateTime($row['data_nascimento']);
			$idade = $dt->diff(new DateTime())->y;

			if ($idade >= 3 && $idade <= 17) $faixas['3 a 17 anos']++;
			elseif ($idade >= 18 && $idade <= 59) $faixas['18 a 59 anos']++;
			else $faixas['60+ anos']++;
		}
	}

	header('Content-Type: application/json');
	echo json_encode($faixas);
});

$app->get("/api/grafico-titulares", function () {

	require __DIR__ . "/vendor/autoload.php";


	$sql = "SELECT data_nascimento, sexo, pcd FROM tb_titular";
	$result = $conn->query($sql);

	$faixas = [
		'3-17' => ['Masc' => 0, 'Fem' => 0, 'MascPCD' => 0, 'FemPCD' => 0],
		'18-59' => ['Masc' => 0, 'Fem' => 0, 'MascPCD' => 0, 'FemPCD' => 0],
		'60+' => ['Masc' => 0, 'Fem' => 0, 'MascPCD' => 0, 'FemPCD' => 0]
	];

	while ($row = $result->fetch_assoc()) {
		if (empty($row['data_nascimento'])) continue;

		$idade = (new DateTime($row['data_nascimento']))->diff(new DateTime())->y;
		$sexo = strtolower($row['sexo']) == 'feminino' ? 'Fem' : 'Masc';
		$pcd = strtolower($row['pcd']) == 'sim' ? 'PCD' : '';

		if ($idade >= 3 && $idade <= 17) $faixa = '3-17';
		elseif ($idade >= 18 && $idade <= 59) $faixa = '18-59';
		else $faixa = '60+';

		if ($pcd == 'PCD')
			$faixas[$faixa][$sexo . 'PCD']++;
		else
			$faixas[$faixa][$sexo]++;
	}

	echo json_encode($faixas);
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
