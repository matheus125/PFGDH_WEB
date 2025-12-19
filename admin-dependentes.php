<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Dependente;
use \Hcode\DB\Sql;

$app->get("/admin/dependente/create", function () {

    $page = new PageAdmin();

    $page->setTpl("dependente-create");
});

$app->get("/admin/titulares/json", function () {

    $sql = new Sql();

    $results = $sql->select("
        SELECT 
            id,
            nome_completo,
            cpf
        FROM tb_titular
        ORDER BY nome_completo
    ");

    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
});

$app->post('/admin/dependentes/create-json', function () {

    header('Content-Type: application/json; charset=utf-8');

    try {

        $body = json_decode(file_get_contents('php://input'), true);

        if (!$body) {
            throw new Exception('JSON inválido');
        }

        if (empty($body['id_titular'])) {
            throw new Exception('Titular não informado');
        }

        if (empty($body['dependentes']) || !is_array($body['dependentes'])) {
            throw new Exception('Nenhum dependente informado');
        }

        $idTitular = (int) $body['id_titular'];
        $dependentes = $body['dependentes'];

        $sql = new Sql();
        $sql->query("START TRANSACTION");

        foreach ($dependentes as $dep) {

            if (empty($dep['nome'])) {
                throw new Exception('Nome do dependente é obrigatório');
            }

            $sql->query("
                INSERT INTO tb_dependentes (
                    id_titular,
                    nome,
                    rg,
                    cpf,
                    data_nascimento,
                    idade,
                    genero,
                    dependencia_cliente
                ) VALUES (
                    :id_titular,
                    :nome,
                    :rg,
                    :cpf,
                    :data_nascimento,
                    :idade,
                    :genero,
                    :dependencia_cliente                    
                )
            ", [
                ':id_titular'   => $idTitular,
                ':nome'         => $dep['nome'],
                ':rg'           => $dep['rg'] ?? null,
                ':cpf'          => $dep['cpf'] ?? null,
                ':data_nascimento' => $dep['data_nascimento'] ?? null,
                ':idade'        => $dep['idade'] ?? null,
                ':genero'       => $dep['genero'] ?? null,
                ':dependencia_cliente'   => $dep['dependencia_cliente'] ?? null
            ]);
        }

        $sql->query("COMMIT");

        echo json_encode([
            'success' => true,
            'message' => 'Dependentes salvos com sucesso'
        ]);

        exit;
    } catch (Exception $e) {

        if (isset($sql)) {
            $sql->query("ROLLBACK");
        }

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);

        exit;
    }
});

// Retorna dependentes de um titular em JSON
$app->get("/admin/dependentes/ajax/:id", function ($id) {

    $sql = new Sql();

    $dependentes = $sql->select("
        SELECT 
            nome,
            dependencia_cliente,
            TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) AS idade,
            genero
        FROM tb_dependentes
        WHERE id_titular = :id
        ORDER BY nome
    ", [
        ":id" => $id
    ]);

    header('Content-Type: application/json');
    echo json_encode($dependentes);
    exit;
});

