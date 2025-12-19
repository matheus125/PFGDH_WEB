<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Clientes;
use \Hcode\DB\Sql;

$app->get("/admin/clientes", function () {

	$lista_titulares = Clientes::lista_titulares();

	$page = new PageAdmin();

	$page->setTpl("clientes", array(
		"lista_titulares" => $lista_titulares,
		"msgError" => Clientes::getError(),
		"msgSuccess" => Clientes::getSuccess()
	));
});

$app->get("/admin/clientes/create", function () {

	$page = new PageAdmin();

	$page->setTpl("clientes-create");
});

$app->post("/admin/clientes/create", function () {

	$clientes = new Clientes();

	$clientes->setData($_POST);

	$clientes->salvar_cliente_titular();

	header("Location: /admin/clientes");
	exit;
});

$app->get("/admin/index", function () {

	// pega lista e total
	$lista_titulares = Clientes::lista_titulares();

	$page = new PageAdmin();

	$page->setTpl("index", [
		"lista_titulares" => $lista_titulares,
		"msgError" => Clientes::getError(),
		"msgSuccess" => Clientes::getSuccess()
	]);
});

$app->get("/admin/clientes/:id", function ($id) {

	$clientes = new Clientes();

	$clientes->getByTitular((int)$id);

	$page = new PageAdmin();

	$page->setTpl("clientes-update", array(
		"clientes" => $clientes->getValues()
	));
});

