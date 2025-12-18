<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app->get("/admin/users/:id_pessoa/password", function ($id_pessoa) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$id_pessoa);

	$page = new PageAdmin();

	$page->setTpl("users-password", [
		"user" => $user->getValues(),
		"msgError" => User::getError(),
		"msgSuccess" => User::getSuccess()
	]);
});

$app->post("/admin/users/:id_pessoa/password", function ($id_pessoa) {

	User::verifyLogin();

	if (!isset($_POST['senha']) || $_POST['senha'] === '') {

		User::setError("Preencha a nova senha.");
		header("Location: /admin/users/$id_pessoa/password");
		exit;
	}

	if (!isset($_POST['senha-confirm']) || $_POST['senha-confirm'] === '') {

		User::setError("Preencha a confirmação da nova senha.");
		header("Location: /admin/users/$id_pessoa/password");
		exit;
	}

	if ($_POST['senha'] !== $_POST['senha-confirm']) {

		User::setError("As senhas não coincidem.");
		header("Location: /admin/users/$id_pessoa/password");
		exit;
	}

	$user = new User();
	$user->get((int)$id_pessoa);

	$user->setsenha(User::getPasswordHash($_POST['senha']));

	User::setSuccess("Senha alterada com sucesso.");

	header("Location: /admin/users/$id_pessoa/password");
	exit;
});


$app->get("/admin/users", function () {

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users" => $users
	));
});

$app->get("/admin/index", function () {

	User::verifyLogin();

	$index = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("index", array(
		"index" => $index
	));
});



$app->get("/admin/users/create", function () {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});

$app->get("/admin/users/:id_pessoa/delete", function ($id_pessoa) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$id_pessoa);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

$app->get("/admin/users/:id_pessoa", function ($id_pessoa) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$id_pessoa);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user" => $user->getValues()
	));
});

$app->post("/admin/users/create", function () {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;
});


$app->post("/admin/users/:id_pessoa", function ($id_pessoa) {

	User::verifyLogin();

	$user = new User();

	//$_POST["idperfil"] = (isset($_POST["idperfil"]))?1:0;

	$user->get((int)$id_pessoa);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;
});
