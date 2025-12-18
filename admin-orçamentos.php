<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Orcamentos;


$app->get("/admin/orcamentos", function () {

	// Verifica se o usuário está logado
	Orcamentos::verifyLogin();

	// Busca os orçamentos
	$orcamentos = Orcamentos::lista_orçamentos();

	// Inicializa o template admin
	$page = new PageAdmin();

	// Passa os dados para o template
	$page->setTpl("orcamentos", [
		"orcamentos" => $orcamentos
	]);
});



$app->get("/admin/orcamentos/create", function () {

	orcamentos::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("orcamentos-create");
});

$app->post("/admin/orcamentos/salvar", function () {
	Orcamentos::verifyLogin();

	$data = json_decode(file_get_contents("php://input"), true);

	$orc = new Orcamentos();
	$orc->setData($data);
	$orc->salvar();

	echo json_encode([
		"success" => true,
		"orcamento" => $orc->getValues()
	]);
});



$app->get("/admin/orcamentos/:id", function ($id) {

	Orcamentos::verifyLogin();

	$orcamentos = new Orcamentos();

	$orcamentos->get((int)$id);

	$page = new PageAdmin();

	$page->setTpl("orcamentos-update", array(
		"orcamentos" => $orcamentos->getValues()
	));
});

$app->post("/admin/orcamentos/:id", function ($id) {

	Orcamentos::verifyLogin();

	$orcamentos = new Orcamentos();

	// Garante que o ID está no POST
	$_POST["id_"] = $id;

	$orcamentos->setData($_POST);
	$orcamentos->salvar();

	header("Location: /admin/orcamentos");
	exit;
});
