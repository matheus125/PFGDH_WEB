<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Funcionarios;

$app->get("/admin/funcionarios/:id_usuario/password", function ($id_usuario) {

	Funcionarios::verifyLogin();

	$funcionarios = new Funcionarios();

	$funcionarios->get((int)$id_usuario);

	$page = new PageAdmin();

	$page->setTpl("funcionarios-password", [
		"funcionarios" => $funcionarios->getValues(),
		"msgError" => Funcionarios::getError(),
		"msgSuccess" => Funcionarios::getSuccess()
	]);
});

$app->post("/admin/funcionarios/:id_usuario/password", function ($id_usuario) {

	Funcionarios::verifyLogin();

	if (!isset($_POST['senha']) || $_POST['senha'] === '') {

		Funcionarios::setError("Preencha a nova senha.");
		header("Location: /admin/funcionarios/$id_usuario/password");
		exit;
	}

	if (!isset($_POST['senha-confirm']) || $_POST['senha-confirm'] === '') {

		Funcionarios::setError("Preencha a confirmação da nova senha.");
		header("Location: /admin/funcionarios/$id_usuario/password");
		exit;
	}

	if ($_POST['senha'] !== $_POST['senha-confirm']) {

		Funcionarios::setError("Confirme corretamente as senhas.");
		header("Location: /admin/funcionarios/$id_usuario/password");
		exit;
	}

	$funcionarios = new Funcionarios();

	$funcionarios->get((int)$id_usuario);

	$funcionarios->setPassword(Funcionarios::getPasswordHash($_POST['senha']));

	Funcionarios::setSuccess("Senha alterada com sucesso.");

	header("Location: /admin/funcionarios/$id_usuario/password");
	exit;
});


$app->get("/admin/funcionarios", function () {

	Funcionarios::verifyLogin();

	$funcionarios = Funcionarios::listAll();

	$page = new PageAdmin();

	$page->setTpl("funcionarios", array(
		"funcionarios" => $funcionarios
	));
});

$app->get("/admin/index", function () {

	Funcionarios::verifyLogin();

	$index = Funcionarios::listAll();

	$page = new PageAdmin();

	$page->setTpl("index", array(
		"index" => $index
	));
});



$app->get("/admin/funcionarios/create", function () {

	Funcionarios::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("funcionarios-create");
});

$app->get("/admin/funcionarios/:idperson/delete", function ($idperson) {

	Funcionarios::verifyLogin();

	$funcionarios = new Funcionarios();

	$funcionarios->get((int)$idperson);

	$funcionarios->delete();

	header("Location: /admin/funcionarios");
	exit;
});

$app->get("/admin/funcionarios/:id_usuario", function ($id_usuario) {

	Funcionarios::verifyLogin();

	$funcionarios = new Funcionarios();

	$funcionarios->get((int)$id_usuario);

	$page = new PageAdmin();

	$page->setTpl("funcionarios-update", array(
		"funcionarios" => $funcionarios->getValues()
	));
});

$app->post("/admin/funcionarios/create", function () {

	Funcionarios::verifyLogin();

	$funcionarios = new Funcionarios();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	// Valida CPF
	if (!Funcionarios::validaCPF($_POST["cpf"])) {
		echo "<script>alert('CPF inválido!'); window.history.back();</script>";
		exit;
	}

	// Valida Telefone
	if (!Funcionarios::validaTelefone($_POST["nrphone"])) {
		echo "<script>alert('Telefone inválido!'); window.history.back();</script>";
		exit;
	}

	// Verifica duplicidade
	if (Funcionarios::checkCpfExists($_POST["cpf"])) {
		echo "<script>alert('CPF já cadastrado!'); window.history.back();</script>";
		exit;
	}

	if (Funcionarios::checkEmailExists($_POST["email"])) {
		echo "<script>alert('E-mail já cadastrado!'); window.history.back();</script>";
		exit;
	}

	if (Funcionarios::checkPhoneExists($_POST["nrphone"])) {
		echo "<script>alert('Telefone já cadastrado!'); window.history.back();</script>";
		exit;
	}
	$funcionarios = new Funcionarios();
	$funcionarios->setData($_POST);

	$funcionarios->save();

	header("Location: /admin/funcionarios");
	exit;
});

$app->post("/admin/funcionarios/:id_usuario", function ($id_usuario) {

	Funcionarios::verifyLogin();

	$funcionarios = new Funcionarios();

	$funcionarios->get((int)$id_usuario);

	$funcionarios->setData($_POST);

	$funcionarios->update();

	header("Location: /admin/funcionarios");
	exit;
});
