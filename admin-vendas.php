<?php

use \Hcode\PageAdmin;
use \Hcode\DB\Sql;

/**
 * Tela de Vendas
 */
$app->get("/admin/vendas", function () {
    $page = new PageAdmin();
    $page->setTpl("admin/vendas", array(
        "vendas" => []
    ));
});

/**
 * ✅ LISTA TITULARES (JSON)
 */
$app->get("/admin/api/titulares", function () {

    $sql = new Sql();

    $result = $sql->select("
        SELECT 
            id,
            nome_completo AS nome,
            cpf,
            idade,
            genero
        FROM tb_titular
        ORDER BY nome_completo
    ");

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($result);
    exit;
});

/**
 * ✅ LISTA DEPENDENTES DO TITULAR (JSON)
 */
$app->get("/admin/titulares/:id/dependentes", function ($id) {

    $sql = new Sql();

    $result = $sql->select("
        SELECT 
            id,
            nome,
            idade,
            genero,
            dependencia_cliente
        FROM tb_dependentes
        WHERE id_titular = :id
        ORDER BY nome
    ", [
        ":id" => (int)$id
    ]);

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($result);
    exit;
});

/**
 * ✅ CONTAGEM DE SENHAS VENDIDAS NO DIA (baseada no banco)
 * GET /admin/api/senhas/contagem?data=YYYY-MM-DD
 */
$app->get("/admin/api/senhas/contagem", function () use ($app) {

    $req = $app->request(); // Slim 2
    $data = $req->get("data");

    if (!$data) $data = date("Y-m-d");

    $sql = new Sql();

    $row = $sql->select("
        SELECT COUNT(*) AS total
        FROM tb_senhas
        WHERE data_refeicao = :data_refeicao
    ", [
        ":data_refeicao" => $data
    ]);

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["ok" => true, "total" => (int)$row[0]["total"]]);
    exit;
});

/**
 * ✅ SALVAR SENHAS (titular + dependentes OU genérica)
 *
 * Body JSON:
 * {
 *   "tipoSenha": "NORMAL"|"GENERICA",
 *   "status_cliente": "...",
 *   "data_refeicao": "YYYY-MM-DD",
 *   "itens": [
 *     {"cliente":"...", "cpf":"...", "idade":"...", "genero":"...", "deficiente":"..."},
 *     ...
 *   ]
 * }
 *
 * Regras de validação (recompra no mesmo dia):
 * - Para tipoSenha = "GENERICA" (ou cpf vazio): NÃO valida duplicidade (permite várias, mas limite diário controla).
 * - Para tipoSenha = "NORMAL": bloqueia se já existir registro no mesmo dia para a mesma combinação (cpf + cliente).
 *   => Isso resolve o problema de N pessoas com o mesmo nome: quem manda é o CPF.
 *   => Para dependentes, como normalmente não há CPF próprio, o frontend envia o CPF do titular + nome do dependente,
 *      e a checagem vira (cpf do titular + nome do dependente) - bloqueia cada dependente individualmente.
 */
$app->post("/admin/api/senhas", function () use ($app) {

    $req  = $app->request(); // Slim 2
    $body = json_decode($req->getBody(), true);

    if (!$body) {
        $app->response()->status(400);
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => false, "error" => "JSON inválido"]);
        exit;
    }

    $tipoSenha      = isset($body["tipoSenha"]) ? $body["tipoSenha"] : "NORMAL";
    $status_cliente = isset($body["status_cliente"]) ? $body["status_cliente"] : "";
    $data_refeicao  = isset($body["data_refeicao"]) ? $body["data_refeicao"] : date("Y-m-d");
    $itens          = isset($body["itens"]) ? $body["itens"] : [];

    if (!is_array($itens) || count($itens) === 0) {
        $app->response()->status(400);
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => false, "error" => "Nenhum item para salvar"]);
        exit;
    }

    $sql = new Sql();

    // 1) Validação de duplicidade (ANTES da transação)
//    Regras:
//      - TITULAR: bloqueia recompra no mesmo dia por CPF (cpf != "")
//      - DEPENDENTE: bloqueia recompra no mesmo dia por ID do dependente (id_dependente)
//      - GENERICA: não valida duplicidade (não é pessoa)
if (strtoupper($tipoSenha) !== "GENERICA") {

    $duplicados = [];

    foreach ($itens as $item) {

        $cliente = isset($item["cliente"]) ? trim($item["cliente"]) : "";

        $idDependente = isset($item["id_dependente"]) && $item["id_dependente"] !== "" ? (int)$item["id_dependente"] : 0;

        if ($idDependente > 0) {
            $row = $sql->select("
                SELECT id
                FROM tb_senhas
                WHERE data_refeicao = :data_refeicao
                  AND id_dependente = :id_dependente
                LIMIT 1
            ", [
                ":data_refeicao"  => $data_refeicao,
                ":id_dependente" => $idDependente
            ]);

            if (count($row) > 0) {
                $duplicados[] = $cliente . " (ID " . $idDependente . ")";
            }
            continue;
        }

        // TITULAR: valida por CPF (somente dígitos)
        $cpfRaw = isset($item["cpf"]) ? (string)$item["cpf"] : "";
        $cpf    = preg_replace("/\D+/", "", $cpfRaw);

        if ($cpf === "") continue;

        $row = $sql->select("
            SELECT id
            FROM tb_senhas
            WHERE data_refeicao = :data_refeicao
              AND cpf = :cpf
              AND (id_dependente IS NULL OR id_dependente = 0)
            LIMIT 1
        ", [
            ":data_refeicao" => $data_refeicao,
            ":cpf"           => $cpf
        ]);

        if (count($row) > 0) {
            $duplicados[] = $cliente . " (CPF " . $cpf . ")";
        }
    }

    if (count($duplicados) > 0) {
        $app->response()->status(409);
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode([
            "ok" => false,
            "error" => "Cliente já realizou uma compra hoje e não pode comprar novamente.",
            "duplicados" => array_values(array_unique($duplicados))
        ]);
        exit;
    }
}

// 2) Insert (transação)
    $sql->query("START TRANSACTION");

    try {

        $ids = [];

        foreach ($itens as $item) {

            $cliente    = isset($item["cliente"]) ? trim($item["cliente"]) : "";
            $cpfRaw     = isset($item["cpf"]) ? (string)$item["cpf"] : "";
            $idade      = isset($item["idade"]) ? $item["idade"] : "";
            $genero     = isset($item["genero"]) ? $item["genero"] : "";
            $deficiente = isset($item["deficiente"]) ? $item["deficiente"] : "";

            // CPF só com dígitos para padronizar
            $cpf = preg_replace("/\D+/", "", $cpfRaw);

            $idTitular    = isset($item["id_titular"]) && $item["id_titular"] !== "" ? (int)$item["id_titular"] : null;
            $idDependente = isset($item["id_dependente"]) && $item["id_dependente"] !== "" ? (int)$item["id_dependente"] : null;


            $sql->query("
                INSERT INTO tb_senhas
                (cliente, cpf, Idade, Genero, Deficiente, tipoSenha, status_cliente, data_refeicao, id_titular, id_dependente, registration_date, registration_date_update)
                VALUES
                (:cliente, :cpf, :idade, :genero, :deficiente, :tipoSenha, :status_cliente, :data_refeicao, :id_titular, :id_dependente, NOW(), NOW())
            ", [
                ":cliente"        => $cliente,
                ":cpf"            => $cpf,
                ":idade"          => $idade,
                ":genero"         => $genero,
                ":deficiente"     => $deficiente,
                ":tipoSenha"      => $tipoSenha,
                ":status_cliente" => $status_cliente,
                ":data_refeicao" => $data_refeicao,
                ":id_titular" => $idTitular,
                ":id_dependente" => $idDependente
            ]);


            $row = $sql->select("SELECT LAST_INSERT_ID() AS id");
            $ids[] = (int)$row[0]["id"];
        }

        $sql->query("COMMIT");

        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => true, "ids" => $ids]);
        exit;

    } catch (\Exception $e) {

        $sql->query("ROLLBACK");

        $app->response()->status(500);
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => false, "error" => "Erro ao salvar senhas", "details" => $e->getMessage()]);
        exit;
    }
});
