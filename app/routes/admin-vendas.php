<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Funcionarios;
use \Hcode\DB\Sql;


/**
 * Helper: responde JSON (Slim 2) e finaliza.
 */
function jsonResponse($app, $statusCode, $payload)
{
    $app->response()->status($statusCode);
    $app->response()->header("Content-Type", "application/json; charset=utf-8");
    echo json_encode($payload);
    exit;
}

/**
 * Log do relatório (grava dentro do projeto, evitando /tmp e open_basedir).
 * Arquivo: <esta_pasta>/logs/relatorio_errors.log
 */
function relatorioLog($msg)
{
    $dir = defined('LOG_DIR') ? LOG_DIR : (__DIR__ . DIRECTORY_SEPARATOR . "logs");
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }

    $file = $dir . DIRECTORY_SEPARATOR . "relatorio_errors.log";
    $line = "[" . date("Y-m-d H:i:s") . "] " . $msg . PHP_EOL;

    // tenta gravar no arquivo
    @file_put_contents($file, $line, FILE_APPEND);

    // fallback: também manda para o error_log do PHP
    error_log($line);
}

/**
 * Descobre automaticamente qual coluna de data existe em tb_relatorios.
 * (Evita erro "Unknown column data_refeicao".)
 */
function getColunaDataRelatorio($sql)
{
    // 1) tenta por nomes comuns (prioridade)
    $prefer = [
        'data_refeicao',
        'data_relatorio',
        'data',
        'data_ref',
        'dt_refeicao',
        'dt_relatorio',
        'dt_data',
        'dt',
        'created_at',
        'registration_date'
    ];

    $cols = $sql->select("
        SELECT COLUMN_NAME, DATA_TYPE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'tb_relatorios'
    ");

    if (!$cols || count($cols) === 0) {
        throw new \Exception("Tabela tb_relatorios não encontrada (ou sem colunas visíveis) no schema atual.");
    }

    $byName = [];
    foreach ($cols as $c) {
        $byName[strtolower($c['COLUMN_NAME'])] = $c;
    }

    foreach ($prefer as $p) {
        $k = strtolower($p);
        if (isset($byName[$k])) return $byName[$k]['COLUMN_NAME'];
    }

    // 2) fallback: primeira coluna do tipo date/datetime/timestamp
    foreach ($cols as $c) {
        $dt = strtolower($c['DATA_TYPE']);
        if (in_array($dt, ['date', 'datetime', 'timestamp'])) {
            return $c['COLUMN_NAME'];
        }
    }

    // 3) se nada, falha explícita
    $lista = array_map(function ($c) {
        return $c['COLUMN_NAME'];
    }, $cols);
    throw new \Exception("Não consegui identificar a coluna de data em tb_relatorios. Colunas: " . implode(", ", $lista));
}

/**
 * Gera/atualiza o relatório do dia em tb_relatorios chamando a procedure.
 * (A procedure deve fazer UPSERT por data.)
 *
 * Agora:
 * - loga tudo em /logs/relatorio_errors.log
 * - valida que a linha apareceu em tb_relatorios após o CALL
 * - lança Exception se falhar (não fica silencioso)
 */
function gerarRelatorioDia($sql, $dataRef)
{
    relatorioLog("Iniciando gerarRelatorioDia | data={$dataRef}");

    // Executa a procedure
    try {
        $sql->query("CALL sp_gerar_relatorio_dia(:data_ref)", [
            ":data_ref" => $dataRef
        ]);
        relatorioLog("Procedure sp_gerar_relatorio_dia executada | data={$dataRef}");
    } catch (\Exception $e) {
        relatorioLog("ERRO ao executar procedure sp_gerar_relatorio_dia | data={$dataRef} | msg=" . $e->getMessage());
        throw new \Exception("Falha na procedure sp_gerar_relatorio_dia: " . $e->getMessage());
    }

    // Verifica se criou/atualizou registro em tb_relatorios
    $colData = getColunaDataRelatorio($sql);

    try {
        $chk = $sql->select("
            SELECT COUNT(*) AS total
            FROM tb_relatorios
            WHERE DATE(`{$colData}`) = :data_ref
        ", [
            ":data_ref" => $dataRef
        ]);
        $total = ($chk && isset($chk[0]['total'])) ? (int)$chk[0]['total'] : 0;
    } catch (\Exception $e) {
        relatorioLog("ERRO ao verificar tb_relatorios | coluna={$colData} | data={$dataRef} | msg=" . $e->getMessage());
        throw new \Exception("Erro ao verificar tb_relatorios: " . $e->getMessage());
    }

    if ($total <= 0) {
        $msg = "Procedure executada, mas nenhum registro encontrado em tb_relatorios para a data {$dataRef} (coluna {$colData}).";
        relatorioLog("ERRO: " . $msg);
        throw new \Exception($msg);
    }

    relatorioLog("OK gerarRelatorioDia | data={$dataRef} | coluna={$colData} | total_encontrado={$total}");
    return true;
}

/**
 * Tela de Vendas
 */
$app->get("/admin/vendas", function () {
    Funcionarios::checkPermission('VENDAS_VIEW');
    $page = new PageAdmin();
    $page->setTpl("admin/vendas", array(
        "vendas" => []
    ));
});

/**
 * ✅ TESTE DE LOG (JSON)
 * GET /admin/api/relatorios/logtest
 */
$app->get("/admin/api/relatorios/logtest", function () use ($app) {
    relatorioLog("TESTE DE LOG: rota /admin/api/relatorios/logtest acionada.");
    jsonResponse($app, 200, ["ok" => true, "msg" => "Log de teste escrito (verifique /logs/relatorio_errors.log)"]);
});

/**
 * ✅ GERAR/ATUALIZAR RELATÓRIO DO DIA (tb_relatorios)
 * GET /admin/api/relatorios/gerar?data=YYYY-MM-DD
 */
$app->get("/admin/api/relatorios/gerar", function () use ($app) {

    $req  = $app->request(); // Slim 2
    $data = $req->get("data");
    if (!$data) $data = date("Y-m-d");

    $sql = new Sql();

    try {
        gerarRelatorioDia($sql, $data);
        jsonResponse($app, 200, ["ok" => true, "data" => $data]);
    } catch (\Exception $e) {
        relatorioLog("ERRO rota /admin/api/relatorios/gerar | data={$data} | msg=" . $e->getMessage());
        jsonResponse($app, 500, ["ok" => false, "error" => "Erro ao gerar relatório", "details" => $e->getMessage()]);
    }
});


/**
 * 📊 Tela de Relatório (Senhas)
 */
$app->get("/admin/relatorio/senhas", function () {
    Funcionarios::checkPermission('RELATORIOS_VIEW');
    $page = new PageAdmin();
    $page->setTpl("admin/relatorio-senhas", array(
        "relatorio" => []
    ));
});

/**
 * Helper: normaliza tipoSenha
 */
function _tipoSenhaFiltro()
{
    $t = isset($_GET['tipoSenha']) ? trim($_GET['tipoSenha']) : '';
    $t = strtoupper($t);
    if ($t === 'NORMAL' || $t === 'GENERICA') return $t;
    return '';
}

/**
 * ✅ RESUMO DO DIA (JSON)
 * /admin/api/relatorio/senhas/resumo?data=YYYY-MM-DD&tipoSenha=NORMAL|GENERICA
 */
$app->get("/admin/api/relatorio/senhas/resumo", function () use ($app) {

    $data = (isset($_GET["data"]) && $_GET["data"]) ? $_GET["data"] : date("Y-m-d");
    $tipoSenha = _tipoSenhaFiltro();

    $sql = new Sql();

    $whereTipo = $tipoSenha ? " AND tipoSenha = :tipoSenha" : "";

    // Observação: considera "linhas em tb_senhas" como refeições liberadas
    $res = $sql->select("
        SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN tipoSenha = 'NORMAL' THEN 1 ELSE 0 END) AS total_normal,
            SUM(CASE WHEN tipoSenha = 'GENERICA' THEN 1 ELSE 0 END) AS total_generica,
            /*
              Titulares do dia: conta o atendimento por titular mesmo quando só foram liberados dependentes.
              (no fluxo atual, ao selecionar dependentes, pode não existir linha do titular em tb_senhas)
            */
            COUNT(DISTINCT CASE
                WHEN tipoSenha = 'NORMAL' AND id_titular IS NOT NULL THEN id_titular
                ELSE NULL
            END) AS total_titular,
            SUM(CASE WHEN id_dependente IS NOT NULL THEN 1 ELSE 0 END) AS total_dependente,
            SUM(CASE WHEN UPPER(TRIM(Deficiente)) IN ('SIM','S','1','TRUE','YES') THEN 1 ELSE 0 END) AS total_deficiente
        FROM tb_senhas
        WHERE data_refeicao = :data
        {$whereTipo}
    ", $tipoSenha ? [":data" => $data, ":tipoSenha" => $tipoSenha] : [":data" => $data]);

    $row = isset($res[0]) ? $res[0] : [
        "total" => 0,
        "total_normal" => 0,
        "total_generica" => 0,
        "total_titular" => 0,
        "total_dependente" => 0,
        "total_deficiente" => 0
    ];

    $app->response()->header("Content-Type", "application/json; charset=utf-8");
    echo json_encode([
        "ok" => true,
        "data" => $data,
        "tipoSenha" => $tipoSenha ?: null,
        "resumo" => [
            "total" => (int)$row["total"],
            "normal" => (int)$row["total_normal"],
            "generica" => (int)$row["total_generica"],
            "titulares" => (int)$row["total_titular"],
            "dependentes" => (int)$row["total_dependente"],
            "deficientes" => (int)$row["total_deficiente"],
        ]
    ]);
    exit;
});

/**
 * ✅ LISTA DO DIA (JSON + paginação)
 * /admin/api/relatorio/senhas/lista?data=YYYY-MM-DD&page=1&pageSize=50&tipoSenha=NORMAL|GENERICA
 */
$app->get("/admin/api/relatorio/senhas/lista", function () use ($app) {

    $data = (isset($_GET["data"]) && $_GET["data"]) ? $_GET["data"] : date("Y-m-d");
    $tipoSenha = _tipoSenhaFiltro();

    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
    $pageSize = isset($_GET["pageSize"]) ? (int)$_GET["pageSize"] : 50;

    if ($page < 1) $page = 1;
    if ($pageSize < 10) $pageSize = 10;
    if ($pageSize > 200) $pageSize = 200;

    $offset = ($page - 1) * $pageSize;

    $sql = new Sql();

    $whereTipo = $tipoSenha ? " AND tipoSenha = :tipoSenha" : "";
    $params = $tipoSenha ? [":data" => $data, ":tipoSenha" => $tipoSenha] : [":data" => $data];

    $totalRes = $sql->select("
        SELECT COUNT(*) AS total
        FROM tb_senhas
        WHERE data_refeicao = :data
        {$whereTipo}
    ", $params);

    $total = isset($totalRes[0]["total"]) ? (int)$totalRes[0]["total"] : 0;

    // LIMIT com inteiros "injetados" (page/pageSize já sanitizados como int)
    $rows = $sql->select("
        SELECT
            id,
            cliente,
            cpf,
            Idade AS idade,
            Genero AS genero,
            Deficiente AS deficiente,
            tipoSenha,
            status_cliente,
            data_refeicao,
            id_titular,
            id_dependente,
            registration_date
        FROM tb_senhas
        WHERE data_refeicao = :data
        {$whereTipo}
        ORDER BY id DESC
        LIMIT {$offset}, {$pageSize}
    ", $params);

    $app->response()->header("Content-Type", "application/json; charset=utf-8");
    echo json_encode([
        "ok" => true,
        "data" => $data,
        "tipoSenha" => $tipoSenha ?: null,
        "page" => $page,
        "pageSize" => $pageSize,
        "total" => $total,
        "items" => $rows
    ]);
    exit;
});

/**
 * ✅ TOP 10 TITULARES (por frequência no período)
 * /admin/api/relatorio/senhas/top10?data=YYYY-MM-DD&period=DIA|SEMANA|MES|ANO
 *
 * Retorna:
 * - total_dias: em quantos dias diferentes o titular apareceu no período (frequência)
 * - total_refeicoes: total de linhas (refeições) no período
 */
$app->get("/admin/api/relatorio/senhas/top10", function () use ($app) {

    $refData = (isset($_GET["data"]) && $_GET["data"]) ? $_GET["data"] : date("Y-m-d");
    $periodo = isset($_GET["period"]) ? strtoupper(trim($_GET["period"])) : "DIA";
    $ordenar = isset($_GET["order"]) ? strtoupper(trim($_GET["order"])) : "FREQ"; // FREQ | REFEICOES

    // calcula range (YYYY-MM-DD)
    try {
        $ref = new DateTimeImmutable($refData);
    } catch (Exception $e) {
        $ref = new DateTimeImmutable(date("Y-m-d"));
        $refData = $ref->format("Y-m-d");
    }

    switch ($periodo) {
        case "SEMANA":
            // segunda a domingo
            $inicio = $ref->modify("monday this week")->format("Y-m-d");
            $fim    = $ref->modify("sunday this week")->format("Y-m-d");
            break;

        case "MES":
            $inicio = $ref->modify("first day of this month")->format("Y-m-d");
            $fim    = $ref->modify("last day of this month")->format("Y-m-d");
            break;

        case "ANO":
            $inicio = $ref->setDate((int)$ref->format("Y"), 1, 1)->format("Y-m-d");
            $fim    = $ref->setDate((int)$ref->format("Y"), 12, 31)->format("Y-m-d");
            break;

        case "DIA":
        default:
            $periodo = "DIA";
            $inicio  = $ref->format("Y-m-d");
            $fim     = $ref->format("Y-m-d");
            break;
    }

    $sql = new Sql();

    // Ordenação (whitelist)
    $orderBy = "total_dias DESC, total_refeicoes DESC";
    if ($ordenar === "REFEICOES" || $ordenar === "REFEICOE" || $ordenar === "REFEICAO") {
        $ordenar = "REFEICOES";
        $orderBy = "total_refeicoes DESC, total_dias DESC";
    } else {
        $ordenar = "FREQ";
        $orderBy = "total_dias DESC, total_refeicoes DESC";
    }

    $rows = $sql->select("
        SELECT
            s.id_titular,
            t.nome_completo AS titular_nome,
            t.cpf AS titular_cpf,
            COUNT(DISTINCT s.data_refeicao) AS total_dias,
            COUNT(*) AS total_refeicoes
        FROM tb_senhas s
        INNER JOIN tb_titular t ON t.id = s.id_titular
        WHERE s.id_titular IS NOT NULL
          AND s.tipoSenha = 'NORMAL'
          AND s.data_refeicao BETWEEN :inicio AND :fim
        GROUP BY s.id_titular, t.nome_completo, t.cpf
        ORDER BY {$orderBy}
        LIMIT 10
    ", [
        ":inicio" => $inicio,
        ":fim" => $fim
    ]);

    $app->response()->header("Content-Type", "application/json; charset=utf-8");
    echo json_encode([
        "ok" => true,
        "data_ref" => $refData,
        "periodo" => $periodo,
        "ordenar" => $ordenar,
        "inicio" => $inicio,
        "fim" => $fim,
        "items" => $rows
    ]);
    exit;
});

/**
 * ✅ RELATÓRIO MENSAL (por dia) p/ gráfico
 * /admin/api/relatorio/senhas/mensal?mes=YYYY-MM
 */
$app->get("/admin/api/relatorio/senhas/mensal", function () use ($app) {

    $mes = (isset($_GET["mes"]) && $_GET["mes"]) ? $_GET["mes"] : date("Y-m");

    // valida formato YYYY-MM
    if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => false, "error" => "Parâmetro mes inválido. Use YYYY-MM."]);
        exit;
    }

    $sql = new Sql();

    $rows = $sql->select("
        SELECT
            data_refeicao,
            COUNT(*) AS total,
            SUM(CASE WHEN tipoSenha = 'NORMAL' THEN 1 ELSE 0 END) AS normal,
            SUM(CASE WHEN tipoSenha = 'GENERICA' THEN 1 ELSE 0 END) AS generica,
            COUNT(DISTINCT CASE
                WHEN tipoSenha = 'NORMAL' AND id_titular IS NOT NULL THEN id_titular
                ELSE NULL
            END) AS titulares,
            SUM(CASE WHEN id_dependente IS NOT NULL THEN 1 ELSE 0 END) AS dependentes,
            SUM(CASE WHEN UPPER(TRIM(Deficiente)) IN ('SIM','S','1','TRUE','YES') THEN 1 ELSE 0 END) AS deficientes
        FROM tb_senhas
        WHERE data_refeicao LIKE :mes
        GROUP BY data_refeicao
        ORDER BY data_refeicao ASC
    ", [":mes" => $mes . "-%"]);

    $app->response()->header("Content-Type", "application/json; charset=utf-8");
    echo json_encode([
        "ok" => true,
        "mes" => $mes,
        "items" => $rows
    ]);
    exit;
});

/**
 * ✅ EXPORT CSV DO DIA
 * /admin/api/relatorio/senhas/export?data=YYYY-MM-DD&tipoSenha=NORMAL|GENERICA
 */
$app->get("/admin/api/relatorio/senhas/export", function () use ($app) {

    $data = (isset($_GET["data"]) && $_GET["data"]) ? $_GET["data"] : date("Y-m-d");
    $tipoSenha = _tipoSenhaFiltro();

    $sql = new Sql();

    $whereTipo = $tipoSenha ? " AND tipoSenha = :tipoSenha" : "";
    $params = $tipoSenha ? [":data" => $data, ":tipoSenha" => $tipoSenha] : [":data" => $data];

    $rows = $sql->select("
        SELECT
            id,
            cliente,
            cpf,
            Idade AS idade,
            Genero AS genero,
            Deficiente AS deficiente,
            tipoSenha,
            status_cliente,
            data_refeicao,
            id_titular,
            id_dependente,
            registration_date
        FROM tb_senhas
        WHERE data_refeicao = :data
        {$whereTipo}
        ORDER BY id ASC
    ", $params);

    $suffix = $tipoSenha ? "_" . strtolower($tipoSenha) : "";
    $filename = "relatorio_senhas_{$data}{$suffix}.csv";

    $app->response()->header("Content-Type", "text/csv; charset=utf-8");
    $app->response()->header("Content-Disposition", "attachment; filename={$filename}");

    // BOM UTF-8 para Excel
    echo "\xEF\xBB\xBF";

    $out = fopen("php://output", "w");

    fputcsv($out, [
        "ID",
        "CLIENTE",
        "CPF",
        "IDADE",
        "GENERO",
        "DEFICIENTE",
        "TIPO_SENHA",
        "STATUS",
        "DATA_REFEICAO",
        "ID_TITULAR",
        "ID_DEPENDENTE",
        "REGISTRATION_DATE"
    ], ";");

    foreach ($rows as $r) {
        fputcsv($out, [
            $r["id"] ?? "",
            $r["cliente"] ?? "",
            $r["cpf"] ?? "",
            $r["idade"] ?? "",
            $r["genero"] ?? "",
            $r["deficiente"] ?? "",
            $r["tipoSenha"] ?? "",
            $r["status_cliente"] ?? "",
            $r["data_refeicao"] ?? "",
            $r["id_titular"] ?? "",
            $r["id_dependente"] ?? "",
            $r["registration_date"] ?? ""
        ], ";");
    }

    fclose($out);
    exit;
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
 * ✅ VERIFICA SE TITULAR JÁ COMPROU NO DIA (JSON)
 * GET /admin/api/senhas/ja-comprou?data=YYYY-MM-DD&cpf=...
 */
/**
 * GET /admin/api/senhas/ja-comprou?data=YYYY-MM-DD&cpf=...
 */
$app->get("/admin/api/senhas/ja-comprou", function () use ($app) {

    try {

        $req  = $app->request();
        $data = trim((string)$req->get("data"));
        $cpf  = preg_replace("/\D+/", "", (string)$req->get("cpf"));

        if ($data === "") {
            $data = date("Y-m-d");
        }

        if ($cpf === "") {
            jsonResponse($app, 400, [
                "ok" => false,
                "error" => "CPF inválido."
            ]);
        }

        $sql = new Sql();

        $row = $sql->select("
            SELECT 1
            FROM tb_senhas
            WHERE data_refeicao = :data
            AND cpf = :cpf
            AND (id_dependente IS NULL OR id_dependente = 0)
            LIMIT 1
        ", [
            ":data" => $data,
            ":cpf"  => $cpf
        ]);

        $jaComprou = !empty($row);

        header("Content-Type: application/json; charset=utf-8");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo json_encode([
            "ok" => true,
            "ja_comprou" => $jaComprou
        ]);
        exit;
    } catch (Exception $e) {

        jsonResponse($app, 500, [
            "ok" => false,
            "error" => "Erro ao verificar compra do titular.",
            "details" => $e->getMessage()
        ]);
    }
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

    $titularJaComprou = false; // usado para permitir venda de dependentes mesmo se titular já comprou

    // 1) Validação de duplicidade (ANTES da transação)
    //    Regras:
    //      - TITULAR: bloqueia recompra no mesmo dia por CPF (cpf != "")
    //      - DEPENDENTE: bloqueia recompra no mesmo dia por ID do dependente (id_dependente)
    //      - GENERICA: não valida duplicidade (não é pessoa)
    //
    // Flexibilidade desejada:
    // - Se o titular JÁ comprou hoje, ainda pode comprar (imprimir) os dependentes.
    //   => Nesse caso, removemos o item do titular e seguimos com os dependentes.
    // - Para dependentes, o bloqueio continua: se algum dependente já comprou hoje, bloqueia.
    if (strtoupper($tipoSenha) !== "GENERICA") {

        $dupTitular = [];
        $dupDependentes = [];

        foreach ($itens as $item) {

            $cliente = isset($item["cliente"]) ? trim($item["cliente"]) : "";

            $idDependente = isset($item["id_dependente"]) && $item["id_dependente"] !== "" ? (int)$item["id_dependente"] : 0;

            // DEPENDENTE: valida por id_dependente
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
                    $dupDependentes[] = $cliente . " (ID " . $idDependente . ")";
                }

                continue;
            }

            // TITULAR: valida por id_titular primeiro; se não vier, usa CPF como fallback
            $idTitular = isset($item["id_titular"]) && $item["id_titular"] !== "" ? (int)$item["id_titular"] : 0;
            $cpfRaw = isset($item["cpf"]) ? (string)$item["cpf"] : "";
            $cpf    = preg_replace("/\D+/", "", $cpfRaw);

            if ($idTitular > 0) {
                $row = $sql->select("
                    SELECT id
                    FROM tb_senhas
                    WHERE data_refeicao = :data_refeicao
                      AND id_titular = :id_titular
                      AND (id_dependente IS NULL OR id_dependente = 0)
                    LIMIT 1
                ", [
                    ":data_refeicao" => $data_refeicao,
                    ":id_titular"    => $idTitular
                ]);

                if (count($row) > 0) {
                    $dupTitular[] = $cliente . " (titular #" . $idTitular . ")";
                }
                continue;
            }

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
                $dupTitular[] = $cliente . " (CPF " . $cpf . ")";
            }
        }

        // Se algum DEPENDENTE já comprou, bloqueia (regra mantém-se)
        if (count($dupDependentes) > 0) {
            $app->response()->status(409);
            $app->response()->header("Content-Type", "application/json; charset=utf-8");
            echo json_encode([
                "ok" => false,
                "error" => "Um ou mais dependentes já realizaram uma compra hoje e não podem comprar novamente.",
                "duplicados" => array_values(array_unique($dupDependentes))
            ]);
            exit;
        }

        // Se o TITULAR já comprou hoje, bloqueia apenas quando houver tentativa de vender
        // novamente para o titular. Dependentes continuam podendo comprar normalmente,
        // mas SEM remover ou alterar o payload no backend.
        if (count($dupTitular) > 0) {
            $haItemTitularNoPayload = false;
            foreach ($itens as $item) {
                $idDependente = isset($item["id_dependente"]) && $item["id_dependente"] !== "" ? (int)$item["id_dependente"] : 0;
                if ($idDependente <= 0) {
                    $haItemTitularNoPayload = true;
                    break;
                }
            }

            if ($haItemTitularNoPayload) {
                $app->response()->status(409);
                $app->response()->header("Content-Type", "application/json; charset=utf-8");
                echo json_encode([
                    "ok" => false,
                    "error" => "Titular já realizou uma compra hoje e não pode comprar novamente.",
                    "duplicados" => array_values(array_unique($dupTitular))
                ]);
                exit;
            }

            $titularJaComprou = true;
        }
    }
    $LIMITE_SENHAS_DIA = 5;
    $data_refeicao = isset($body["data_refeicao"]) ? $body["data_refeicao"] : date("Y-m-d");

    $sql->select("CALL sp_fechamento_atualizar(:data, :limite)", [
        ":data" => $data_refeicao,
        ":limite" => $LIMITE_SENHAS_DIA
    ]);

    $fech = $sql->select("SELECT fechado, total, limite FROM tb_fechamento_dia WHERE data_refeicao = :data", [
        ":data" => $data_refeicao
    ]);

    if ($fech && (int)$fech[0]["fechado"] === 1) {
        $app->response()->status(409);
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode([
            "ok" => false,
            "error" => "Dia fechado: limite diário atingido.",
            "total" => (int)$fech[0]["total"],
            "limite" => (int)$fech[0]["limite"]
        ]);
        exit;
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

        $sql->select("CALL sp_fechamento_atualizar(:data, :limite)", [
            ":data" => $data_refeicao,
            ":limite" => $LIMITE_SENHAS_DIA
        ]);


        // Se fechou agora, tenta gerar/atualizar o relatório do dia em tb_relatorios
        try {
            $fech2 = $sql->select("SELECT fechado, total, limite FROM tb_fechamento_dia WHERE data_refeicao = :data", [
                ":data" => $data_refeicao
            ]);

            if ($fech2 && (int)$fech2[0]["fechado"] === 1) {
                relatorioLog("Dia fechado após venda | data={$data_refeicao} | total=" . (int)$fech2[0]["total"] . " | limite=" . (int)$fech2[0]["limite"]);
                gerarRelatorioDia($sql, $data_refeicao);
            } else {
                relatorioLog("Dia ainda aberto após venda | data={$data_refeicao}");
            }
        } catch (\Exception $e) {
            // Não bloqueia a venda por falha no relatório, mas registra para depuração
            relatorioLog("Falha ao gerar/verificar relatório após venda | data={$data_refeicao} | msg=" . $e->getMessage());
        }

        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => true, "ids" => $ids, "titular_ja_comprou" => (bool)$titularJaComprou]);
        exit;
    } catch (\Exception $e) {

        $sql->query("ROLLBACK");

        $app->response()->status(500);
        $app->response()->header("Content-Type", "application/json; charset=utf-8");
        echo json_encode(["ok" => false, "error" => "Erro ao salvar senhas", "details" => $e->getMessage()]);
        exit;
    }
});
