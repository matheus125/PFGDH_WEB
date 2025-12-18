<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Usuarios;
use \Hcode\DB\Sql;

// ✔️ Página de criar usuário
$app->get("/admin/usuarios/create", function () {

	Usuarios::verifyLogin();

	$page = new PageAdmin();
	$page->setTpl("usuarios-create");
});

// Criar titular (ficha completa)
$app->post("/admin/titulares/create", function () {

	Usuarios::verifyLogin();

	try {
		$input = json_decode(file_get_contents('php://input'), true);

		if (!$input) {
			throw new \Exception("Nenhum dado recebido.");
		}

		$usuario = new Usuarios();

		// campos básicos
		$usuario->setNome($input['nome'] ?? null);
		$usuario->setEndereco($input['endereco'] ?? null);
		$usuario->setNumero($input['numero'] ?? null);
		$usuario->setComplemento($input['complemento'] ?? null);
		$usuario->setBairro($input['bairro'] ?? null);
		$usuario->setCidade($input['cidade'] ?? null);
		$usuario->setCep($input['cep'] ?? null);
		$usuario->setUf($input['uf'] ?? null);
		$usuario->setTelefone($input['telefone'] ?? null);
		$usuario->setEmail($input['email'] ?? null);
		$usuario->setRg($input['rg'] ?? null);
		$usuario->setCpf($input['cpf'] ?? null);
		$usuario->setDataNascimento($input['data_nascimento'] ?? null);
		$usuario->setEstadoCivil($input['estado_civil'] ?? null);
		$usuario->setProfissao($input['profissao'] ?? null);

		// extras
		$usuario->setGrauEscolaridade($input['grau_escolaridade'] ?? null);
		$usuario->setTrabalhaAtualmente($input['trabalha'] ?? 'Não');
		$usuario->setRecebeBeneficioGoverno($input['beneficio'] ?? 'Não');
		$usuario->setTipoBeneficio($input['tipo_beneficio'] ?? []);

		$result = $usuario->saveFichaCadastro();

		echo json_encode($result);
		exit;
	} catch (\Exception $e) {
		echo json_encode([
			"status" => "error",
			"message" => $e->getMessage()
		]);
		exit;
	}
});

$app->post("/admin/dependentes/create", function () {

	Usuarios::verifyLogin();

	try {

		$input = json_decode(file_get_contents("php://input"), true);

		if (!$input || !isset($input["dependentes"])) {
			throw new \Exception("Nenhum dependente enviado.");
		}

		$dependentes = $input["dependentes"];
		$dependenteModel = new Usuarios();
		$resultados = [];

		foreach ($dependentes as $dep) {

			// Garante que id_titular veio no front
			if (!isset($dep["id_titular"])) {
				throw new \Exception("ID do titular não informado.");
			}

			// 🔥 IMPORTANTE: SETAR MANUALMENTE NO MODEL
			$dependenteModel->setIdTitular($dep["id_titular"]);

			// Carregar os demais dados
			$dependenteModel->setDataDependente($dep);

			// Salvar
			$resultados[] = $dependenteModel->saveDependente();
		}

		echo json_encode([
			"status" => "success",
			"message" => "Dependentes salvos com sucesso!",
			"saved" => $resultados
		]);
		exit;
	} catch (\Exception $e) {

		echo json_encode([
			"status" => "error",
			"message" => $e->getMessage()
		]);
		exit;
	}
});

// ✔️ Buscar 1 usuário por ID (JSON para o modal)
// rota para obter 1 usuário por id — responde JSON sempre
$app->get('/admin/usuarios/:id', function ($id) {

	Usuarios::verifyLogin();

	$usuarios = Usuarios::getById($id);

	if (!$usuarios) {
		echo json_encode(["error" => "Usuário não encontrado"]);
		return;
	}

	echo json_encode($usuarios);
});

// ✔️ Listar todos os usuários
$app->get("/admin/usuarios/", function () {

	Usuarios::verifyLogin();

	$usuarios = Usuarios::listar_usuarios();

	$page = new PageAdmin();
	$page->setTpl("usuarios", [
		"usuarios" => $usuarios,
		"msgError" => Usuarios::getError(),
		"msgSuccess" => Usuarios::getSuccess()
	]);
});

// ✔️ Listar todos os usuários_titulares_modal_dependente
$app->get("/admin/titulares/list", function () {

	Usuarios::verifyLogin();

	$titulares = Usuarios::listar_usuarios(); // ou listar_titulares()

	echo json_encode($titulares);
});

// ✔️ Listar todos os usuários_dependentes
$app->get("/admin/dependentes/list", function () {

	Usuarios::verifyLogin();

	$dependentes = Usuarios::listar_usuarios_dependentes(); // ou listar_titulares()

	echo json_encode($dependentes);
});

$app->get("/admin/dependentes/:id", function ($id) {

	Usuarios::verifyLogin();

	$sql = new Sql();
	$dep = $sql->select("
        SELECT * FROM tb_dependentes 
        WHERE id_dependente = :id
    ", [
		":id" => $id
	]);

	echo json_encode($dep ? $dep[0] : ["error" => "Dependente não encontrado"]);
});


$app->put("/admin/dependentes/update/:id", function ($id) {

	Usuarios::verifyLogin();

	$input = json_decode(file_get_contents("php://input"), true);

	if (!$input) {
		echo json_encode(["status" => "error", "message" => "Nenhum dado enviado."]);
		return;
	}

	$sql = new Sql();

	$sql->query("
        UPDATE tb_dependentes SET 
            nome_dependente   = :nome,
            parentesco        = :parentesco,
            idade             = :idade,
            escolaridade      = :escolaridade,
            estuda            = :estuda,
            trabalha          = :trabalha,
            ocupacao          = :ocupacao,
            deficiencia       = :deficiencia,
            tipo_deficiencia  = :tipo_deficiencia,
            outros            = :outros
        WHERE id_dependente = :id
    ", [
		":nome"            => $input["nome_dependente"],
		":parentesco"      => $input["parentesco"],
		":idade"           => $input["idade"],
		":escolaridade"    => $input["escolaridade"],
		":estuda"          => $input["estuda"],
		":trabalha"        => $input["trabalha"],
		":ocupacao"        => $input["ocupacao"],
		":deficiencia"     => $input["deficiencia"],
		":tipo_deficiencia" => $input["tipo_deficiencia"],
		":outros"          => $input["outros"],
		":id"              => $id
	]);

	echo json_encode(["status" => "success", "message" => "Dependente atualizado!"]);
});


$app->post("/admin/documentos/upload", function () {
	Usuarios::verifyLogin();

	try {

		$controller = new Usuarios();
		echo json_encode($controller->upload());
	} catch (Exception $e) {
		echo json_encode(["status" => "error", "message" => $e->getMessage()]);
	}
});

$app->get("/admin/documentos/listar/:id", function ($id) {

	Usuarios::verifyLogin();
	$sql = new Sql();

	$docs = $sql->select("
        SELECT * FROM tb_documentos_titular 
        WHERE id_titular = :id
    ", [
		":id" => $id
	]);

	echo json_encode($docs);
});

$app->delete('/admin/documentos/excluir/{id}', function ($id) {
	Usuarios::verifyLogin();

	$sql = new Sql();
	$doc = $sql->select("SELECT * FROM tb_documentos_titular WHERE id = :id", [
		":id" => $id
	]);

	if (!$doc) {
		echo json_encode(["error" => true]);
		return;
	}

	$arquivo = $_SERVER["DOCUMENT_ROOT"] . "/uploads/" . $doc[0]["nome_arquivo"];

	if (file_exists($arquivo)) unlink($arquivo);

	$sql->query("DELETE FROM tb_documentos WHERE id_documento = :id", [
		":id" => $id
	]);

	echo json_encode(["success" => true]);
});



// 📝 SALVAR DADOS SOCIOECONÔMICOS
$app->post("/admin/socioeconomico/save", function () {
	Usuarios::verifyLogin();

	try {
		// Recebe o JSON enviado pelo fetch
		$input = json_decode(file_get_contents("php://input"), true);

		// Instancia a classe
		$usuario = new Usuarios();

		// 🔹 Preenche os setters com os valores recebidos
		$usuario->setIdTitular($input["id_titular"]);
		$usuario->setRendaFixa70($input["renda_fixa_70"]);
		$usuario->setRendaAcima70($input["renda_acima_70"]);
		$usuario->setRenda1Salario($input["renda_1_salario"]);
		$usuario->setRenda2Salarios($input["renda_2_salarios"]);
		$usuario->setRenda3Salarios($input["renda_3_salarios"]);
		$usuario->setRendaOutroValor($input["renda_outro_valor"]);
		$usuario->setPrincipalFonteRenda($input["principal_fonte_renda"]);
		$usuario->setTrabalhadorFormal($input["trabalhador_formal"]);
		$usuario->setTrabalhadorInformal($input["trabalhador_informal"]);
		$usuario->setAutonomo($input["autonomo"]);
		$usuario->setOutrosQuaisRenda($input["outros_quais_renda"]);

		$usuario->setAguaTratada($input["recebimento_agua_tratada"]);
		$usuario->setAguaFiltrada($input["recebimento_agua_filtrada"]);
		$usuario->setAguaPoco($input["recebimento_agua_poco"]);
		$usuario->setPossuiFossa($input["possui_fossa"]);

		$usuario->setLixoColeta($input["lixo_coleta"]);
		$usuario->setLixoQueimado($input["lixo_queimado"]);
		$usuario->setLixoJogadoRua($input["lixo_jogado_rua"]);
		$usuario->setLixoOutros($input["lixo_outros"]);

		$usuario->setCasaPropria($input["casa_propria"]);
		$usuario->setCasaCedida($input["casa_cedida"]);
		$usuario->setCasaAlugada($input["casa_alugada"]);
		$usuario->setCasaMadeira($input["casa_madeira"]);
		$usuario->setCasaAlvenaria($input["casa_alvenaria"]);
		$usuario->setCasaPalafita($input["casa_palafita"]);
		$usuario->setCasaEnchimento($input["casa_enchimento"]);
		$usuario->setCasaEstruturaOutros($input["casa_estrutura_outros"]);

		$usuario->setParticipaComunidade($input["participa_comunidade"]);
		$usuario->setComunidadeQual($input["comunidade_qual"]);
		$usuario->setAssociacoes($input["associacoes"]);
		$usuario->setCooperativas($input["cooperativas"]);
		$usuario->setCentroComunitario($input["centro_comunitario"]);
		$usuario->setOutrasParticipacoes($input["outras_participacoes"]);

		// 🔹 Novos campos
		$usuario->setSaude($input["saude"]);
		$usuario->setAlimentacao($input["alimentacao"]);
		$usuario->setDocumentacao($input["documentacao"]);
		$usuario->setMoradia($input["moradia"]);
		$usuario->setEscolaVotacao($input["escola_votacao"]);
		$usuario->setOutrasInformacoes($input["outras_informacoes"]);

		// 🔹 Agora salva usando sua função
		$result = $usuario->saveDadosSocioeconomicos();

		echo json_encode($result);
	} catch (Exception $e) {
		echo json_encode([
			"status" => "error",
			"message" => $e->getMessage()
		]);
	}
});

$app->get("/admin/usuarios/:id", function ($usuarioID) {

	Usuarios::verifyLogin();

	$sql = new Sql();
	$usuario = $sql->select("
        SELECT * FROM tb_titular
        WHERE id = :id
    ", [
		":id" => $usuarioID
	]);

	echo json_encode($usuario ? $usuario[0] : ["error" => "Usuário não encontrado"]);
});

$app->post("/admin/usuarios/:id", function ($usuarioID) {

	Usuarios::verifyLogin();

	$data = json_decode(file_get_contents("php://input"), true);

	if (!$data) {
		echo json_encode(["error" => "Dados inválidos"]);
		return;
	}

	$sql = new Sql();
	$sql->query("
        UPDATE tb_titular SET
            nome = :nome,
            telefone = :telefone,
            email = :email,
            rg = :rg,
            cpf = :cpf,
            data_nascimento = :data_nascimento,
            estado_civil = :estado_civil,
            grau_escolaridade = :grau_escolaridade
        WHERE id = :id
    ", [
		":nome" => $data['nome'],
		":telefone" => $data['telefone'],
		":email" => $data['email'],
		":rg" => $data['rg'],
		":cpf" => $data['cpf'],
		":data_nascimento" => $data['data_nascimento'],
		":estado_civil" => $data['estado_civil'],
		":grau_escolaridade" => $data['grau_escolaridade'],
		":id" => $usuarioID
	]);

	echo json_encode(["message" => "Usuário atualizado com sucesso"]);
});
