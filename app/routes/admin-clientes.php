<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Clientes;
use \Hcode\Model\Funcionarios;
use \Hcode\DB\Sql;

$app->get("/admin/clientes", function () {

	Funcionarios::checkPermission('CLIENTES_VIEW');

	$lista_titulares = Clientes::lista_titulares();

	$page = new PageAdmin();

	$page->setTpl("clientes", array(
		"lista_titulares" => $lista_titulares,
		"msgError" => Clientes::getError(),
		"msgSuccess" => Clientes::getSuccess()
	));
});

$app->get("/admin/clientes/create", function () {

	Funcionarios::checkPermission('CLIENTES_CREATE');

	$page = new PageAdmin();

	$page->setTpl("clientes-create");
});

$app->post("/admin/clientes/create", function () {

	Funcionarios::checkPermission('CLIENTES_CREATE');

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

	Funcionarios::checkPermission('CLIENTES_UPDATE');

	$clientes = new Clientes();

	$clientes->getByTitular((int)$id);

	$values = $clientes->getValues();

	// IDs em JSON (base64) para enviar no form de update
	$ids = [
		"id_titular"  => (int)($values["id"] ?? 0),
		"id_familia"  => (int)($values["id_familia"] ?? 0),
		"id_endereco" => (int)($values["id_endereco"] ?? 0)
	];

	$ids_b64 = base64_encode(json_encode($ids, JSON_UNESCAPED_UNICODE));

	$page = new PageAdmin();

	$page->setTpl("clientes-update", array(
		"clientes" => $values,
		"ids_b64"  => $ids_b64,
		"msgError" => Clientes::getError(),
		"msgSuccess" => Clientes::getSuccess()
	));
});


$app->post("/admin/clientes/update", function () {

	Funcionarios::checkPermission('CLIENTES_UPDATE');

	// DEBUG rápido: adicione ?debug=1 na URL do POST (ou inclua <input name="debug" value="1"> no form)
	$debug = (isset($_GET["debug"]) && $_GET["debug"] == "1") || (isset($_POST["debug"]) && $_POST["debug"] == "1");


	$raw = base64_decode($_POST["ids_b64"] ?? "", true);
	$ids = json_decode($raw ?: "{}", true);

	$id_titular  = (int)($ids["id_titular"] ?? 0);
	$id_familia  = (int)($ids["id_familia"] ?? 0);
	$id_endereco = (int)($ids["id_endereco"] ?? 0);

	if ($debug) {
		echo "<pre>";
		var_dump(["_POST" => $_POST, "ids_raw" => $raw, "ids" => $ids, "id_titular" => $id_titular, "id_familia" => $id_familia, "id_endereco" => $id_endereco]);
		echo "</pre>";
		die();
	}

	if ($id_titular <= 0 || $id_familia <= 0 || $id_endereco <= 0) {
		Clientes::setError("IDs inválidos para atualização (titular/família/endereço).");
		header("Location: /admin/clientes");
		exit;
	}

	$sql = new Sql();

	$genero = trim($_POST["genero_cliente"] ?? "");

	if (!in_array($genero, ["M", "F", "Outro"])) {
		$genero = "Outro";
	}

	try {

		$result = $sql->select("
        CALL sp_atualizar_titular_familia_endereco(
            :id_titular, :id_familia, :id_endereco,
            :cep, :bairro, :rua, :numero, :referencia, :nacionalidade, :naturalidade, :cidade, :tempo_moradia_anos,
            :nome_familia,
            :nome_completo, :nome_social, :cor_cliente, :nome_mae, :telefone, :data_nascimento, :genero, :estado_civil, :rg, :cpf, :nis, :status_cliente
        )
    ", [
			":id_titular" => $id_titular,
			":id_familia" => $id_familia,
			":id_endereco" => $id_endereco,

			":cep" => $_POST["cep"] ?? "",
			":bairro" => $_POST["bairro"] ?? "",
			":rua" => $_POST["rua"] ?? "",
			":numero" => $_POST["numero"] ?? "",
			":referencia" => $_POST["referencia"] ?? "",
			":nacionalidade" => $_POST["nacionalidade"] ?? "",
			":naturalidade" => $_POST["naturalidade"] ?? "",
			":cidade" => $_POST["cidade"] ?? "",
			":tempo_moradia_anos" => $_POST["tempo_moradia_cliente"] ?? "",

			":nome_familia" => $_POST["nome_familia"] ?? "",

			":nome_completo" => $_POST["nome_completo"] ?? "",
			":nome_social" => $_POST["nome_social"] ?? "",
			":cor_cliente" => $_POST["cor_cliente"] ?? "",
			":nome_mae" => $_POST["nome_mae"] ?? "",
			":telefone" => $_POST["telefone"] ?? "",
			":data_nascimento" => $_POST["data_nascimento"] ?? null,
			":genero" => $genero,
			":estado_civil" => $_POST["estado_civil"] ?? "",
			":rg" => $_POST["rg"] ?? "",
			":cpf" => $_POST["cpf"] ?? "",
			":nis" => $_POST["nis"] ?? "",
			":status_cliente" => $_POST["status_cliente"] ?? ""
		]);

		Clientes::setSuccess("Cliente atualizado com sucesso.");
		header("Location: /admin/clientes");
		exit;
	} catch (Exception $e) {
		Clientes::setError($e->getMessage());
		header("Location: /admin/clientes");
		exit;
	}
});


$app->get("/admin/clientes/:id/delete", function ($id) {

	Funcionarios::checkPermission('CLIENTES_DELETE');

	$id = (int)$id;

	if ($id <= 0) {
		Clientes::setError("ID do titular inválido.");
		header("Location: /admin/clientes");
		exit;
	}

	$sql = new Sql();

	try {

		$result = $sql->select("
			CALL sp_excluir_titular_dependentes_endereco(:id_titular)
		", [
			":id_titular" => $id
		]);

		Clientes::setSuccess($result[0]["status"] ?? "Cliente excluído com sucesso.");
		header("Location: /admin/clientes");
		exit;
	} catch (Exception $e) {

		Clientes::setError($e->getMessage());
		header("Location: /admin/clientes");
		exit;
	}
});
