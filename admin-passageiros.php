<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Passageiros;


$app->get("/admin/passageiros", function () {

	Passageiros::verifyLogin();

	$passageiros = Passageiros::lista_passageiros();

	$page = new PageAdmin();

	$page->setTpl("passageiros", array(
		"passageiros" => $passageiros
	));
});


$app->get("/admin/passageiros/create", function () {

	Passageiros::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("passageiros-create");
});

$app->post("/admin/passageiros/create", function () {

	Passageiros::verifyLogin();

	$passageiros = new Passageiros();

	// Pega o conteúdo enviado (json ou formulário)
	$contentType = $_SERVER["CONTENT_TYPE"] ?? '';

	if (strpos($contentType, 'application/json') !== false) {
		$input = json_decode(file_get_contents('php://input'), true);
		$passageiros->setData($input);
	} else {
		$passageiros->setData($_POST);
	}

	try {
		$passageiros->salvar_orçamentos();

		// Resposta JSON
		if (strpos($contentType, 'application/json') !== false) {
			echo json_encode([
				"status" => "success",
				"message" => "Centro de custos salvo com sucesso"
			]);
			return;
		}

		// Se for form tradicional, redireciona
		header("Location: /admin/passageiros");
		exit;
	} catch (Exception $e) {

		http_response_code(500); // para o status do HTTP ser 500 em erro

		if (strpos($contentType, 'application/json') !== false) {
			echo json_encode([
				"status" => "error",
				"message" => $e->getMessage()
			]);
			return;
		} else {
			// pode mostrar erro numa página ou redirecionar com erro
			echo "Erro: " . $e->getMessage();
			exit;
		}
	}
});

$app->get("/admin/passageiros/:id_passageiros", function ($id_passageiros) {

	Passageiros::verifyLogin();

	$passageiros = new Passageiros();

	$passageiros->get((int)$id_passageiros);

	$page = new PageAdmin();

	$page->setTpl("passageiros-update", array(
		"passageiros" => $passageiros->getValues()
	));
});

$app->post("/admin/passageiros/:id_passageiros", function ($id_passageiros) {

	Passageiros::verifyLogin();

	$passageiros = new Passageiros();

	// Garante que o ID está no POST
	$_POST["id_passageiros"] = $id_passageiros;

	$passageiros->setData($_POST);
	$passageiros->salvar_orçamentos();

	header("Location: /admin/passageiros");
	exit;
});
