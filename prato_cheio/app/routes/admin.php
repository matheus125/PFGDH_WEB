<?php

use \Hcode\DB\Sql;
use \Hcode\Model\Funcionarios;
use \Hcode\PageAdmin;
use Dompdf\Dompdf;


date_default_timezone_set('America/Manaus');

function getLimiteSenhasDiaRelatorio()
{
    if (defined('LIMITE_SENHAS_DIA')) {
        return max(0, (int)LIMITE_SENHAS_DIA);
    }

    return 600;
}

function contarSenhasVendidasRelatorio($sql, $dataRef)
{
    $res = $sql->select("
        SELECT COUNT(*) AS total
        FROM tb_senhas
        WHERE data_refeicao = :data_ref
    ", array(
        ':data_ref' => $dataRef
    ));

    return isset($res[0]['total']) ? (int)$res[0]['total'] : 0;
}


$app->get('/admin/api/relatorio/fechamento-info', function () {

    header('Content-Type: application/json; charset=utf-8');

    try {
        $sql = new Sql();

        $data = isset($_GET['data']) && trim($_GET['data']) !== ''
            ? trim($_GET['data'])
            : date('Y-m-d');

        $result = $sql->select("
            SELECT
                data,
                qtd_refeicoes_servidas,
                ocorrencias,
                cardapio,
                nome_banco,
                refeicoes_ofertadas,
                sobra_refeicoes,
                sobra_senhas,
                fechado
            FROM tb_relatorios
            WHERE data = :data
            LIMIT 1
        ", array(
            ':data' => $data
        ));

        if (!isset($result[0])) {
            echo json_encode(array(
                'ok' => true,
                'dados' => array(
                    'data' => $data,
                    'qtd_refeicoes_servidas' => null,
                    'ocorrencias' => 'NÃO HOUVE NENHUMA OCORRÊNCIA.',
                    'cardapio' => '',
                    'nome_banco' => '',
                    'refeicoes_ofertadas' => getLimiteSenhasDiaRelatorio(),
                    'sobra_refeicoes' => null,
                    'sobra_senhas' => null,
                    'fechado' => 0
                )
            ));
            exit;
        }

        echo json_encode(array(
            'ok' => true,
            'dados' => $result[0]
        ));
        exit;
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            'ok' => false,
            'error' => $e->getMessage()
        ));
        exit;
    }
});

$app->post('/admin/api/relatorio/fechamento-info', function () {

    header('Content-Type: application/json; charset=utf-8');

    try {
        $sql = new Sql();

        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);

        if (!is_array($input)) {
            throw new Exception('JSON inválido.');
        }

        $dataHoje = isset($input['data']) && trim($input['data']) !== ''
            ? trim($input['data'])
            : date('Y-m-d');

        $qtdRefeicoes = isset($input['qtd_refeicoes_servidas']) ? (int)$input['qtd_refeicoes_servidas'] : 0;
        $ocorrencias = isset($input['ocorrencias']) ? trim($input['ocorrencias']) : '';
        $cardapio = isset($input['cardapio']) ? trim($input['cardapio']) : '';

        if ($ocorrencias === '') {
            $ocorrencias = 'NÃO HOUVE NENHUMA OCORRÊNCIA.';
        }

        $dbInfo = $sql->select("SELECT DATABASE() AS nome_banco");
        $nomeBanco = isset($dbInfo[0]['nome_banco']) ? (string)$dbInfo[0]['nome_banco'] : '';

        $relatorioAtual = $sql->select("
            SELECT
                id,
                Total_pessoas_atendidas,
                fechado
            FROM tb_relatorios
            WHERE data = :data
            LIMIT 1
        ", array(
            ':data' => $dataHoje
        ));

        $totalPessoasAtendidas = 0;
        $fechado = 0;

        if (isset($relatorioAtual[0])) {
            $totalPessoasAtendidas = isset($relatorioAtual[0]['Total_pessoas_atendidas'])
                ? (int)$relatorioAtual[0]['Total_pessoas_atendidas']
                : 0;
            $fechado = isset($relatorioAtual[0]['fechado']) ? (int)$relatorioAtual[0]['fechado'] : 0;
        }

        if ($fechado === 1) {
            throw new Exception('Relatório já fechado. Não é possível alterar.');
        }

        $LIMITE_SENHAS_DIA = getLimiteSenhasDiaRelatorio();

        if ($qtdRefeicoes < 0) {
            $qtdRefeicoes = 0;
        }
        if ($qtdRefeicoes > $LIMITE_SENHAS_DIA) {
            $qtdRefeicoes = $LIMITE_SENHAS_DIA;
        }

        $totalPessoasAtendidas = contarSenhasVendidasRelatorio($sql, $dataHoje);
        if ($totalPessoasAtendidas > $LIMITE_SENHAS_DIA) {
            $totalPessoasAtendidas = $LIMITE_SENHAS_DIA;
        }

        $refeicoesOfertadas = $LIMITE_SENHAS_DIA;
        $sobraRefeicoes = max(0, $refeicoesOfertadas - $qtdRefeicoes);
        $sobraSenhas = max(0, $refeicoesOfertadas - $totalPessoasAtendidas);

        $sql->query("
            INSERT INTO tb_relatorios (
                data,
                Total_pessoas_atendidas,
                qtd_refeicoes_servidas,
                ocorrencias,
                cardapio,
                nome_banco,
                refeicoes_ofertadas,
                sobra_refeicoes,
                sobra_senhas,
                fechado
            ) VALUES (
                :data,
                :total_pessoas_atendidas,
                :qtd,
                :ocorrencias,
                :cardapio,
                :nome_banco,
                :refeicoes_ofertadas,
                :sobra_refeicoes,
                :sobra_senhas,
                0
            )
            ON DUPLICATE KEY UPDATE
                Total_pessoas_atendidas = VALUES(Total_pessoas_atendidas),
                qtd_refeicoes_servidas = VALUES(qtd_refeicoes_servidas),
                ocorrencias = VALUES(ocorrencias),
                cardapio = VALUES(cardapio),
                nome_banco = VALUES(nome_banco),
                refeicoes_ofertadas = VALUES(refeicoes_ofertadas),
                sobra_refeicoes = VALUES(sobra_refeicoes),
                sobra_senhas = VALUES(sobra_senhas)
        ", array(
            ':data' => $dataHoje,
            ':total_pessoas_atendidas' => $totalPessoasAtendidas,
            ':qtd' => $qtdRefeicoes,
            ':ocorrencias' => $ocorrencias,
            ':cardapio' => $cardapio,
            ':nome_banco' => $nomeBanco,
            ':refeicoes_ofertadas' => $refeicoesOfertadas,
            ':sobra_refeicoes' => $sobraRefeicoes,
            ':sobra_senhas' => $sobraSenhas
        ));

        echo json_encode(array(
            'ok' => true,
            'dados' => array(
                'data' => $dataHoje,
                'senhas_vendidas' => $totalPessoasAtendidas,
                'qtd_refeicoes_servidas' => $qtdRefeicoes,
                'ocorrencias' => $ocorrencias,
                'cardapio' => $cardapio,
                'nome_banco' => $nomeBanco,
                'refeicoes_ofertadas' => $refeicoesOfertadas,
                'sobra_refeicoes' => $sobraRefeicoes,
                'sobra_senhas' => $sobraSenhas,
                'fechado' => 0
            )
        ));
        exit;
    } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode(array(
            'ok' => false,
            'error' => $e->getMessage()
        ));
        exit;
    }
});

$app->post('/admin/api/relatorio/fechar', function () {

    header('Content-Type: application/json; charset=utf-8');

    try {
        $sql = new Sql();

        $data = date('Y-m-d');

        $sql->query("
            UPDATE tb_relatorios
            SET fechado = 1
            WHERE data = :data
        ", array(
            ':data' => $data
        ));

        echo json_encode(array(
            'ok' => true
        ));
        exit;
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            'ok' => false,
            'error' => $e->getMessage()
        ));
        exit;
    }
});



$app->get('/admin/api/relatorio/pdf', function () {

    $sql = new Sql();
    $config = getRelatorioUploadConfig();

    if (isset($_GET['debug'])) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    if (isset($_GET['teste_rota'])) {
        limparBufferSaida();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'success' => true,
            'arquivo' => __FILE__,
            'upload' => isset($_GET['upload']) ? $_GET['upload'] : null
        ));
        exit;
    }

    $data = isset($_GET['data']) && $_GET['data'] != ''
        ? trim($_GET['data'])
        : date('Y-m-d');

    $upload = isset($_GET['upload']) ? (int)$_GET['upload'] : 0;

    if (strpos($data, '/') !== false) {
        $dt = DateTime::createFromFormat('d/m/Y', $data);
        if (!$dt) {
            responderJson(array(
                'success' => false,
                'message' => 'Data inválida.'
            ));
        }
        $dataSql = $dt->format('Y-m-d');
    } else {
        $dataSql = $data;
    }

    $qtdRefeicoesServidas = isset($_GET['qtd_refeicoes_servidas']) && $_GET['qtd_refeicoes_servidas'] !== ''
        ? (int)$_GET['qtd_refeicoes_servidas']
        : null;

    $ocorrencias = isset($_GET['ocorrencias']) && $_GET['ocorrencias'] != ''
        ? trim($_GET['ocorrencias'])
        : null;

    $cardapio = isset($_GET['cardapio']) && $_GET['cardapio'] != ''
        ? trim($_GET['cardapio'])
        : null;

    $nomeBanco = isset($_GET['nome_banco']) && $_GET['nome_banco'] != ''
        ? trim($_GET['nome_banco'])
        : null;

    $ruaMasculinoExtra = isset($_GET['rua_masculino']) && $_GET['rua_masculino'] !== ''
        ? (int)$_GET['rua_masculino']
        : 0;

    $ruaFemininoExtra = isset($_GET['rua_feminino']) && $_GET['rua_feminino'] !== ''
        ? (int)$_GET['rua_feminino']
        : 0;

    $pcdExtra = isset($_GET['pcd_extra']) && $_GET['pcd_extra'] !== ''
        ? (int)$_GET['pcd_extra']
        : 0;


    $result = $sql->select("
        SELECT
            Idade_3a17Masculino,
            Idade_3a17Masculino_PCD,
            Idade_3a17Feminino,
            Idade_3a17Feminino_PCD,
            Idade_18a59Masculino,
            Idade_18a59Masculino_PCD,
            Idade_17a59Feminino,
            Idade_17a59Feminino_PCD,
            Idade_60Masculino,
            Idade_60Masculino_PCD,
            Idade_60Feminino,
            Idade_60Feminino_PCD,
            Situacao_risco_masculino,
            Situacao_risco_Feminino,
            Deficientes,
            senhas_genericas,
            Total_pessoas_atendidas,
            qtd_refeicoes_servidas,
            ocorrencias,
            cardapio,
            nome_banco,
            refeicoes_ofertadas,
            sobra_refeicoes,
            sobra_senhas,
            data
        FROM tb_relatorios
        WHERE data = :data
        LIMIT 1
    ", array(
        ':data' => $dataSql
    ));

    if (!isset($result[0])) {
        responderJson(array(
            'success' => false,
            'message' => 'Nenhum fechamento encontrado para a data ' . $data
        ));
    }

    $rel = $result[0];

    if ($qtdRefeicoesServidas === null) {
        $qtdRefeicoesServidas = isset($rel['qtd_refeicoes_servidas']) && $rel['qtd_refeicoes_servidas'] !== null
            ? (int)$rel['qtd_refeicoes_servidas']
            : 0;
    }

    if ($ocorrencias === null || trim($ocorrencias) === '') {
        $ocorrencias = isset($rel['ocorrencias']) ? trim((string)$rel['ocorrencias']) : '';
    }

    if ($cardapio === null) {
        $cardapio = isset($rel['cardapio']) ? trim((string)$rel['cardapio']) : '';
    }

    if ($nomeBanco === null || trim($nomeBanco) === '') {
        $nomeBanco = isset($rel['nome_banco']) ? trim((string)$rel['nome_banco']) : '';
    }

    if ($nomeBanco === '') {
        try {
            $dbInfo = $sql->select("SELECT DATABASE() AS nome_banco");
            if ($dbInfo && isset($dbInfo[0]['nome_banco'])) {
                $nomeBanco = (string)$dbInfo[0]['nome_banco'];
            }
        } catch (\Exception $e) {
            $nomeBanco = '';
        }
    }

    if ($ocorrencias === null || trim($ocorrencias) === '') {
        $ocorrencias = 'NÃO HOUVE NENHUMA OCORRÊNCIA.';
    }

    $LIMITE_SENHAS_DIA = getLimiteSenhasDiaRelatorio();

    $situacaoRiscoMasculino = (int)$rel['Situacao_risco_masculino'] + $ruaMasculinoExtra;
    $situacaoRiscoFeminino = (int)$rel['Situacao_risco_Feminino'] + $ruaFemininoExtra;
    $deficientesTotal = (int)$rel['Deficientes'] + $pcdExtra;
    $totalPessoasAtendidas = isset($rel['Total_pessoas_atendidas']) && $rel['Total_pessoas_atendidas'] !== null
        ? (int)$rel['Total_pessoas_atendidas']
        : contarSenhasVendidasRelatorio($sql, $dataSql);
    $refeicoesOfertadas = isset($rel['refeicoes_ofertadas']) && $rel['refeicoes_ofertadas'] !== null
        ? (int)$rel['refeicoes_ofertadas']
        : $LIMITE_SENHAS_DIA;
    $sobraRefeicoes = isset($rel['sobra_refeicoes']) && $rel['sobra_refeicoes'] !== null
        ? max(0, (int)$rel['sobra_refeicoes'])
        : max(0, $refeicoesOfertadas - $qtdRefeicoesServidas);
    $sobraSenhas = isset($rel['sobra_senhas']) && $rel['sobra_senhas'] !== null
        ? max(0, (int)$rel['sobra_senhas'])
        : max(0, $refeicoesOfertadas - $totalPessoasAtendidas);

    $percentualUtilizacao = 0;
    if ($totalPessoasAtendidas > 0) {
        $percentualUtilizacao = ($qtdRefeicoesServidas / $totalPessoasAtendidas) * 100;
    }
    $percentualUtilizacaoFormatado = number_format($percentualUtilizacao, 1, ',', '.');

    $observacaoIndicadores = array();

    $totalSituacaoRua = $situacaoRiscoMasculino + $situacaoRiscoFeminino;
    $totalPcd = $deficientesTotal;

    if ($totalSituacaoRua > 0) {
        $observacaoIndicadores[] = 'Pessoas em situação de rua (' . $totalSituacaoRua . ') já estão incluídas nas faixas etárias acima.';
    }

    if ($totalPcd > 0) {
        $observacaoIndicadores[] = 'Pessoas com deficiência (PCD) (' . $totalPcd . ') já estão contabilizadas nas faixas etárias.';
    }

    $responsavelFechamento = 'Não identificado';
    $cpfResponsavel = '';

    try {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $dadosSessao = isset($_SESSION[Funcionarios::SESSION]) && is_array($_SESSION[Funcionarios::SESSION])
            ? $_SESSION[Funcionarios::SESSION]
            : array();

        if (!empty($dadosSessao)) {
            if (!empty($dadosSessao['nome_funcionario'])) {
                $responsavelFechamento = trim($dadosSessao['nome_funcionario']);
            } elseif (!empty($dadosSessao['nome'])) {
                $responsavelFechamento = trim($dadosSessao['nome']);
            }

            if (!empty($dadosSessao['cpf'])) {
                $cpfResponsavel = trim($dadosSessao['cpf']);
            }
        }

        if ($responsavelFechamento === '' || $responsavelFechamento === null) {
            $responsavelFechamento = 'Não identificado';
        }
    } catch (\Exception $e) {
        $responsavelFechamento = 'Não identificado';
    }

    $dataHoraEmissao = new DateTime('now', new DateTimeZone('America/Manaus'));

    $logoPath = __DIR__ . '/../views/assets/img/logo-prato-cheio.png';

    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $imgData = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode($imgData);
    }

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Relatório de Fechamento</title>
        <style>
            @page {
                margin: 32px 28px 32px 28px;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                color: #1f2937;
                margin: 0;
                padding: 0;
            }

            .watermark {
                position: fixed;
                top: 180px;
                left: 110px;
                width: 360px;
                opacity: 0.06;
                z-index: -1;
            }

            .header-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 18px;
                border-bottom: 2px solid #d9e2ec;
                padding-bottom: 10px;
            }

            .header-table td {
                border: none;
                vertical-align: middle;
            }

            .logo-topo {
                width: 85px;
            }

            .header-title {
                text-align: center;
            }

            .header-title h1 {
                margin: 0 0 6px 0;
                font-size: 20px;
                color: #1f3b57;
            }

            .header-title p {
                margin: 2px 0;
                font-size: 11px;
                color: #4b5563;
            }

            .bloco-info {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 16px;
            }

            .bloco-info td {
                border: 1px solid #d1d5db;
                background: #f8fafc;
                padding: 8px 10px;
                font-size: 11px;
            }

            .duas-colunas {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 16px;
            }

            .duas-colunas td {
                vertical-align: top;
                border: none;
            }

            .col-esq {
                width: 49%;
                padding-right: 1%;
            }

            .col-dir {
                width: 49%;
                padding-left: 1%;
            }

            .card {
                border: 1px solid #d1d5db;
                margin-bottom: 14px;
            }

            .card-title {
                background: #1f3b57;
                color: #ffffff;
                font-size: 12px;
                font-weight: bold;
                padding: 9px 10px;
            }

            table.report {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            table.report th,
            table.report td {
                border: 1px solid #d1d5db;
                padding: 7px;
                font-size: 10.5px;
            }

            table.report th {
                background: #eef2f7;
                color: #374151;
                text-align: left;
            }

            table.report td.qtd,
            table.report th.qtd {
                width: 85px;
                text-align: center;
            }

            .card table.report tr:nth-child(even) td {
                background: #fafafa;
            }

            .totais-grid {
                width: 100%;
                border-collapse: collapse;
                margin-top: 8px;
                margin-bottom: 12px;
            }

            .totais-grid td {
                width: 25%;
                border: 2px solid #2e7d32;
                background: #f0f9f1;
                text-align: center;
                padding: 14px 10px;
                vertical-align: top;
            }

            .totais-grid .label {
                font-size: 12px;
                color: #2e7d32;
                margin-bottom: 4px;
            }

            .totais-grid .valor {
                font-size: 24px;
                font-weight: bold;
                color: #1b5e20;
            }

            .totais-grid .valor.sobra {
                color: #c62828;
            }

            .observacao-alerta {
                border: 1px solid #f59e0b;
                background: #fff8e1;
                margin-top: 12px;
                margin-bottom: 14px;
            }

            .observacao-alerta .titulo {
                background: #fef3c7;
                color: #92400e;
                font-size: 12px;
                font-weight: bold;
                padding: 9px 10px;
                border-bottom: 1px solid #f59e0b;
            }

            .observacao-alerta .conteudo {
                padding: 10px 12px;
                font-size: 10.8px;
                color: #78350f;
                line-height: 1.5;
            }

            .observacao-alerta ul {
                margin: 6px 0 0 18px;
                padding: 0;
            }

            .observacao-alerta li {
                margin-bottom: 4px;
            }

            .ocorrencias-box {
                border: 1px solid #d1d5db;
                margin-top: 10px;
                margin-bottom: 12px;
            }

            .ocorrencias-box .titulo {
                background: #1f3b57;
                color: #fff;
                font-size: 12px;
                font-weight: bold;
                padding: 9px 10px;
            }

            .ocorrencias-box .conteudo {
                min-height: 60px;
                padding: 10px;
                font-size: 11px;
                background: #fafafa;
                white-space: pre-wrap;
            }

            .assinatura-box {
                border: 1px solid #d1d5db;
                margin-top: 14px;
                padding: 16px 12px 10px 12px;
                text-align: center;
                background: #fcfcfc;
            }

            .assinatura-nome {
                font-size: 12px;
                font-weight: bold;
                color: #1f2937;
            }

            .assinatura-cargo {
                font-size: 10px;
                color: #6b7280;
            }

            .footer {
                text-align: center;
                font-size: 10px;
                color: #6b7280;
                margin-top: 10px;
            }
        </style>
    </head>

    <body>

        <?php if ($logoBase64 != '') { ?>
            <img src="<?php echo $logoBase64; ?>" class="watermark" alt="Logo">
        <?php } ?>

        <table class="header-table">
            <tr>
                <td style="width: 90px;">
                    <?php if ($logoBase64 != '') { ?>
                        <img src="<?php echo $logoBase64; ?>" class="logo-topo" alt="Logo">
                    <?php } ?>
                </td>
                <td class="header-title">
                    <h1>RELATÓRIO DE FECHAMENTO DIÁRIO</h1>
                    <p>Resumo consolidado dos atendimentos realizados</p>
                    <p><strong>Emitido em:</strong> <?php echo $dataHoraEmissao->format('d/m/Y H:i'); ?></p>
                </td>
                <td style="width: 90px;"></td>
            </tr>
        </table>

        <table class="bloco-info">
            <tr>
                <td><strong>Data do fechamento:</strong> <?php echo htmlspecialchars($rel['data']); ?></td>
                <td><strong>Total registrado:</strong> <?php echo $totalPessoasAtendidas; ?> atendimentos</td>
            </tr>
            <tr>
                <td><strong>Prato Cheio:</strong> <?php echo htmlspecialchars($nomeBanco !== '' ? $nomeBanco : '-'); ?></td>
                <td><strong>Cardápio:</strong> <?php echo nl2br(htmlspecialchars($cardapio !== '' ? $cardapio : '-')); ?></td>
            </tr>
        </table>

        <table class="duas-colunas">
            <tr>
                <td class="col-esq">
                    <div class="card">
                        <div class="card-title">Faixa etária - Masculino</div>
                        <table class="report">
                            <tr>
                                <th>Categoria</th>
                                <th class="qtd">Qtd.</th>
                            </tr>
                            <tr>
                                <td>3 a 17 anos</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_3a17Masculino']; ?></td>
                            </tr>
                            <tr>
                                <td>3 a 17 anos PCD</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_3a17Masculino_PCD']; ?></td>
                            </tr>
                            <tr>
                                <td>18 a 59 anos</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_18a59Masculino']; ?></td>
                            </tr>
                            <tr>
                                <td>18 a 59 anos PCD</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_18a59Masculino_PCD']; ?></td>
                            </tr>
                            <tr>
                                <td>60+ anos</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_60Masculino']; ?></td>
                            </tr>
                            <tr>
                                <td>60+ anos PCD</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_60Masculino_PCD']; ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="col-dir">
                    <div class="card">
                        <div class="card-title">Faixa etária - Feminino</div>
                        <table class="report">
                            <tr>
                                <th>Categoria</th>
                                <th class="qtd">Qtd.</th>
                            </tr>
                            <tr>
                                <td>3 a 17 anos</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_3a17Feminino']; ?></td>
                            </tr>
                            <tr>
                                <td>3 a 17 anos PCD</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_3a17Feminino_PCD']; ?></td>
                            </tr>
                            <tr>
                                <td>17 a 59 anos</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_17a59Feminino']; ?></td>
                            </tr>
                            <tr>
                                <td>17 a 59 anos PCD</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_17a59Feminino_PCD']; ?></td>
                            </tr>
                            <tr>
                                <td>60+ anos</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_60Feminino']; ?></td>
                            </tr>
                            <tr>
                                <td>60+ anos PCD</td>
                                <td class="qtd"><?php echo (int)$rel['Idade_60Feminino_PCD']; ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <div class="card">
            <div class="card-title">Outros indicadores</div>
            <table class="report">
                <tr>
                    <th>Indicador</th>
                    <th class="qtd">Qtd.</th>
                </tr>
                <tr>
                    <td>Situação de risco masculino</td>
                    <td class="qtd"><?php echo $situacaoRiscoMasculino; ?></td>
                </tr>
                <tr>
                    <td>Situação de risco feminino</td>
                    <td class="qtd"><?php echo $situacaoRiscoFeminino; ?></td>
                </tr>
                <tr>
                    <td>Deficientes</td>
                    <td class="qtd"><?php echo $deficientesTotal; ?></td>
                </tr>
                <tr>
                    <td>Senhas genéricas</td>
                    <td class="qtd"><?php echo (int)$rel['senhas_genericas']; ?></td>
                </tr>
            </table>
        </div>

        <?php if (!empty($observacaoIndicadores)) { ?>
            <div class="observacao-alerta">
                <div class="titulo">Observações importantes</div>
                <div class="conteudo">
                    <ul>
                        <?php foreach ($observacaoIndicadores as $obs) { ?>
                            <li><?php echo htmlspecialchars($obs); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>

        <table class="totais-grid">
            <tr>
                <td>
                    <div class="label">Total de pessoas atendidas</div>
                    <div class="valor"><?php echo $totalPessoasAtendidas; ?></div>
                </td>
                <td>
                    <div class="label">Total de refeições servidas</div>
                    <div class="valor"><?php echo $qtdRefeicoesServidas; ?></div>
                </td>
                <td>
                    <div class="label">Sobra de refeições</div>
                    <div class="valor <?php echo ($sobraRefeicoes > 0) ? 'sobra' : ''; ?>"><?php echo $sobraRefeicoes; ?></div>
                </td>
                <td>
                    <div class="label">% de utilização</div>
                    <div class="valor"><?php echo $percentualUtilizacaoFormatado; ?>%</div>
                </td>
            </tr>
        </table>

        <div class="ocorrencias-box">
            <div class="titulo">Ocorrências</div>
            <div class="conteudo"><?php echo nl2br(htmlspecialchars($ocorrencias)); ?></div>
        </div>

        <div class="assinatura-box">
            <div class="assinatura-nome">
                <?php echo htmlspecialchars($responsavelFechamento); ?>
                <?php if (!empty($cpfResponsavel)) { ?>
                    <br><span style="font-size:10px;">CPF: <?php echo htmlspecialchars($cpfResponsavel); ?></span>
                <?php } ?>
            </div>
            <div class="assinatura-cargo">Responsável pelo fechamento</div>
        </div>

        <div class="footer">
            Relatório gerado automaticamente pelo sistema
        </div>

    </body>

    </html>
<?php

    $html = ob_get_clean();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $nomeArquivo = 'relatorio_fechamento_' . str_replace('/', '-', $dataSql) . '_' . date('H-i-s') . '.pdf';

    if ($upload === 1) {

        $dirTemp = getRelatorioTempDir($config);

        if (!is_dir($dirTemp)) {
            if (!mkdir($dirTemp, 0775, true) && !is_dir($dirTemp)) {
                $dadosHistorico = array(
                    'data_relatorio' => $dataSql,
                    'nome_arquivo' => $nomeArquivo,
                    'url_publica' => null,
                    'caminho_remoto' => null,
                    'status_upload' => 'ERRO',
                    'mensagem_erro' => 'Não foi possível criar a pasta temporária.',
                    'responsavel' => $responsavelFechamento,
                    'cpf_responsavel' => $cpfResponsavel
                );

                $resultadoHistorico = registrarHistoricoRelatorioEmAmbosBancos($sql, $dadosHistorico, $config);
                $resultadoHistorico = garantirHistoricoRelatorioRemoto($resultadoHistorico, $dadosHistorico, $config);

                responderJson(array(
                    'success' => false,
                    'message' => 'Não foi possível criar a pasta temporária.'
                ));
            }
        }

        $caminhoLocal = $dirTemp . DIRECTORY_SEPARATOR . $nomeArquivo;

        if (file_put_contents($caminhoLocal, $pdfOutput) === false) {
            $dadosHistorico = array(
                'data_relatorio' => $dataSql,
                'nome_arquivo' => $nomeArquivo,
                'url_publica' => null,
                'caminho_remoto' => null,
                'status_upload' => 'ERRO',
                'mensagem_erro' => 'Não foi possível salvar o PDF temporariamente.',
                'responsavel' => $responsavelFechamento,
                'cpf_responsavel' => $cpfResponsavel
            );

            $resultadoHistorico = registrarHistoricoRelatorioEmAmbosBancos($sql, $dadosHistorico, $config);
            $resultadoHistorico = garantirHistoricoRelatorioRemoto($resultadoHistorico, $dadosHistorico, $config);

            responderJson(array(
                'success' => false,
                'message' => 'Não foi possível salvar o PDF temporariamente.'
            ));
        }

        try {
            $uploadInfo = uploadRelatorioRemoto($config, $caminhoLocal, $nomeArquivo);

            $dadosHistorico = array(
                'data_relatorio' => $dataSql,
                'nome_arquivo' => $nomeArquivo,
                'url_publica' => $uploadInfo['url_publica'],
                'caminho_remoto' => $uploadInfo['remote_file'],
                'status_upload' => 'SUCESSO',
                'mensagem_erro' => null,
                'responsavel' => $responsavelFechamento,
                'cpf_responsavel' => $cpfResponsavel
            );

            $resultadoHistorico = registrarHistoricoRelatorioEmAmbosBancos($sql, $dadosHistorico, $config);
            $resultadoHistorico = garantirHistoricoRelatorioRemoto($resultadoHistorico, $dadosHistorico, $config);

            $idHistorico = isset($resultadoHistorico['local_id']) ? (int)$resultadoHistorico['local_id'] : 0;

            escreverLogRelatorio($config, 'SUCESSO upload PDF: ' . $nomeArquivo . ' | URL: ' . $uploadInfo['url_publica']);

            responderJson(array(
                'success' => true,
                'message' => 'PDF gerado e enviado com sucesso.',
                'arquivo' => $nomeArquivo,
                'url_publica' => $uploadInfo['url_publica'],
                'id_historico' => $idHistorico,
                'id_historico_remoto' => isset($resultadoHistorico['remoto_id']) ? (int)$resultadoHistorico['remoto_id'] : 0,
                'historico_remoto_ok' => !empty($resultadoHistorico['remoto_ok']),
                'erro_historico_remoto' => isset($resultadoHistorico['erro_remoto']) ? $resultadoHistorico['erro_remoto'] : null
            ));
        } catch (\Exception $e) {
            escreverLogRelatorio($config, 'ERRO upload PDF: ' . $nomeArquivo . ' | ' . $e->getMessage());

            $dadosHistorico = array(
                'data_relatorio' => $dataSql,
                'nome_arquivo' => $nomeArquivo,
                'url_publica' => null,
                'caminho_remoto' => null,
                'status_upload' => 'ERRO',
                'mensagem_erro' => $e->getMessage(),
                'responsavel' => $responsavelFechamento,
                'cpf_responsavel' => $cpfResponsavel
            );

            $resultadoHistorico = registrarHistoricoRelatorioEmAmbosBancos($sql, $dadosHistorico, $config);
            $resultadoHistorico = garantirHistoricoRelatorioRemoto($resultadoHistorico, $dadosHistorico, $config);

            $idHistorico = isset($resultadoHistorico['local_id']) ? (int)$resultadoHistorico['local_id'] : 0;

            responderJson(array(
                'success' => false,
                'message' => $e->getMessage(),
                'id_historico' => $idHistorico,
                'id_historico_remoto' => isset($resultadoHistorico['remoto_id']) ? (int)$resultadoHistorico['remoto_id'] : 0,
                'historico_remoto_ok' => !empty($resultadoHistorico['remoto_ok']),
                'erro_historico_remoto' => isset($resultadoHistorico['erro_remoto']) ? $resultadoHistorico['erro_remoto'] : null
            ));
        }
    }

    limparBufferSaida();
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $nomeArquivo . '"');
    header('Content-Length: ' . strlen($pdfOutput));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    header('Expires: 0');
    header('X-Content-Type-Options: nosniff');
    echo $pdfOutput;
    exit;
});

function getRelatorioUploadConfig()
{
    $configPath = __DIR__ . '/../config/relatorio-upload.php';

    if (!file_exists($configPath)) {
        throw new Exception('Arquivo de configuração do upload não encontrado: ' . $configPath);
    }

    $config = require $configPath;

    if (!is_array($config)) {
        throw new Exception('Configuração de upload inválida.');
    }

    return $config;
}

function getRelatorioDbRemotoConfig()
{
    $configPath = __DIR__ . '/../config/relatorio-db-remoto.php';

    if (!file_exists($configPath)) {
        return array(
            'enabled' => false
        );
    }

    $config = require $configPath;

    if (!is_array($config)) {
        throw new Exception('Configuração do banco remoto inválida.');
    }

    return $config;
}

function getPdoRelatorioRemoto()
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = getRelatorioDbRemotoConfig();

    if (empty($config['enabled'])) {
        return null;
    }

    $required = array('host', 'dbname', 'user', 'pass');
    foreach ($required as $campo) {
        if (!array_key_exists($campo, $config)) {
            throw new Exception('Campo ausente na configuração do banco remoto: ' . $campo);
        }
    }

    $charset = !empty($config['charset']) ? $config['charset'] : 'utf8mb4';
    $port = !empty($config['port']) ? ';port=' . (int)$config['port'] : '';

    $pdo = new PDO(
        'mysql:host=' . $config['host'] . $port . ';dbname=' . $config['dbname'] . ';charset=' . $charset,
        $config['user'],
        $config['pass'],
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        )
    );

    return $pdo;
}

function getRelatorioTempDir(array $config)
{
    $tempDirName = !empty($config['temp_dir_name']) ? $config['temp_dir_name'] : 'tmp_relatorios';
    return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $tempDirName;
}

function limparBufferSaida()
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
}

function responderJson(array $payload)
{
    limparBufferSaida();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

function escreverLogRelatorio(array $config, $mensagem)
{
    $logFile = !empty($config['log_file']) ? $config['log_file'] : 'relatorio_upload.log';
    $logDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'logs';

    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }

    $logPath = $logDir . DIRECTORY_SEPARATOR . $logFile;
    $linha = '[' . date('Y-m-d H:i:s') . '] ' . $mensagem . PHP_EOL;

    @file_put_contents($logPath, $linha, FILE_APPEND);
}

function registrarHistoricoRelatorio(Sql $sql, array $dados)
{
    $sql->query("
        INSERT INTO tb_relatorios_pdf (
            data_relatorio,
            nome_arquivo,
            url_publica,
            caminho_remoto,
            status_upload,
            mensagem_erro,
            responsavel,
            cpf_responsavel,
            data_geracao,
            data_upload
        ) VALUES (
            :data_relatorio,
            :nome_arquivo,
            :url_publica,
            :caminho_remoto,
            :status_upload,
            :mensagem_erro,
            :responsavel,
            :cpf_responsavel,
            NOW(),
            " . ($dados['status_upload'] === 'SUCESSO' ? 'NOW()' : 'NULL') . "
        )
    ", array(
        ':data_relatorio' => $dados['data_relatorio'],
        ':nome_arquivo' => $dados['nome_arquivo'],
        ':url_publica' => $dados['url_publica'],
        ':caminho_remoto' => $dados['caminho_remoto'],
        ':status_upload' => $dados['status_upload'],
        ':mensagem_erro' => $dados['mensagem_erro'],
        ':responsavel' => $dados['responsavel'],
        ':cpf_responsavel' => $dados['cpf_responsavel']
    ));

    $ret = $sql->select('SELECT LAST_INSERT_ID() AS id');
    return isset($ret[0]['id']) ? (int)$ret[0]['id'] : 0;
}

function registrarHistoricoRelatorioRemoto(PDO $pdo, array $dados)
{
    $sql = "
        INSERT INTO tb_relatorios_pdf (
            data_relatorio,
            nome_arquivo,
            url_publica,
            caminho_remoto,
            status_upload,
            mensagem_erro,
            responsavel,
            cpf_responsavel,
            data_geracao,
            data_upload
        ) VALUES (
            :data_relatorio,
            :nome_arquivo,
            :url_publica,
            :caminho_remoto,
            :status_upload,
            :mensagem_erro,
            :responsavel,
            :cpf_responsavel,
            NOW(),
            " . ($dados['status_upload'] === 'SUCESSO' ? 'NOW()' : 'NULL') . "
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':data_relatorio' => $dados['data_relatorio'],
        ':nome_arquivo' => $dados['nome_arquivo'],
        ':url_publica' => $dados['url_publica'],
        ':caminho_remoto' => $dados['caminho_remoto'],
        ':status_upload' => $dados['status_upload'],
        ':mensagem_erro' => $dados['mensagem_erro'],
        ':responsavel' => $dados['responsavel'],
        ':cpf_responsavel' => $dados['cpf_responsavel']
    ));

    return (int)$pdo->lastInsertId();
}

function registrarHistoricoRelatorioEmAmbosBancos(Sql $sqlLocal, array $dados, array $configUpload = array())
{
    $resultado = array(
        'local_id' => 0,
        'remoto_id' => 0,
        'remoto_ok' => false,
        'erro_remoto' => null
    );

    $resultado['local_id'] = registrarHistoricoRelatorio($sqlLocal, $dados);

    try {
        $pdoRemoto = getPdoRelatorioRemoto();

        if ($pdoRemoto instanceof PDO) {
            $resultado['remoto_id'] = registrarHistoricoRelatorioRemoto($pdoRemoto, $dados);
            $resultado['remoto_ok'] = true;
        }
    } catch (\Exception $e) {
        $resultado['erro_remoto'] = $e->getMessage();

        if (!empty($configUpload)) {
            escreverLogRelatorio($configUpload, 'ERRO ao salvar histórico no banco remoto: ' . $e->getMessage());
        } else {
            error_log('ERRO ao salvar histórico no banco remoto: ' . $e->getMessage());
        }
    }

    return $resultado;
}



function garantirHistoricoRelatorioRemoto(array $resultado, array $dados, array $configUpload = array())
{
    if (!isset($resultado['remoto_ok'])) {
        $resultado['remoto_ok'] = false;
    }

    if (!isset($resultado['remoto_id'])) {
        $resultado['remoto_id'] = 0;
    }

    if (!isset($resultado['erro_remoto'])) {
        $resultado['erro_remoto'] = null;
    }

    if (!empty($resultado['remoto_ok'])) {
        return $resultado;
    }

    try {
        $pdoRemoto = getPdoRelatorioRemoto();

        if ($pdoRemoto instanceof PDO) {
            $resultado['remoto_id'] = registrarHistoricoRelatorioRemoto($pdoRemoto, $dados);
            $resultado['remoto_ok'] = true;
            $resultado['erro_remoto'] = null;

            if (!empty($configUpload)) {
                escreverLogRelatorio($configUpload, 'Histórico garantido no banco remoto para o arquivo: ' . $dados['nome_arquivo']);
            }
        }
    } catch (\Exception $e) {
        $resultado['erro_remoto'] = $e->getMessage();

        if (!empty($configUpload)) {
            escreverLogRelatorio($configUpload, 'Falha ao garantir histórico no banco remoto: ' . $e->getMessage());
        } else {
            error_log('Falha ao garantir histórico no banco remoto: ' . $e->getMessage());
        }
    }

    return $resultado;
}

function uploadRelatorioRemoto(array $config, $caminhoLocal, $nomeArquivo)
{
    $ftpHost = $config['host'];
    $ftpUser = $config['user'];
    $ftpPass = $config['pass'];
    $remoteDir = $config['remote_dir'];
    $publicBaseUrl = rtrim($config['public_base_url'], '/') . '/';

    $remoteFile = rtrim($remoteDir, '/') . '/' . $nomeArquivo;

    $fp = fopen($caminhoLocal, 'r');
    if (!$fp) {
        throw new Exception('Erro ao abrir arquivo local: ' . $caminhoLocal);
    }

    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_URL => 'ftp://' . $ftpHost . $remoteFile,
        CURLOPT_USERPWD => $ftpUser . ':' . $ftpPass,
        CURLOPT_UPLOAD => true,
        CURLOPT_INFILE => $fp,
        CURLOPT_INFILESIZE => filesize($caminhoLocal),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 180,
        CURLOPT_FTP_USE_EPSV => true,
        CURLOPT_FTP_CREATE_MISSING_DIRS => true,
        CURLOPT_VERBOSE => false
    ));

    $result = curl_exec($ch);
    $error = curl_error($ch);
    $errno = curl_errno($ch);

    curl_close($ch);
    fclose($fp);

    if ($errno) {
        throw new Exception('Erro FTP (' . $errno . '): ' . $error);
    }

    if ($result === false) {
        throw new Exception('Falha no upload FTP sem retorno válido.');
    }

    return array(
        'success' => true,
        'remote_file' => $remoteFile,
        'url_publica' => $publicBaseUrl . rawurlencode($nomeArquivo)
    );
}

$app->get('/admin/relatorio/pdf/historico', function () {

    $page = new PageAdmin();
    $page->setTpl("admin/relatorio-pdf-historico");
});


$app->get('/admin/api/relatorio/pdf/historico', function () {

    $sql = new Sql();

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $pageSize = isset($_GET['pageSize']) ? (int)$_GET['pageSize'] : 20;
    $data = isset($_GET['data']) ? trim($_GET['data']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';

    if ($page < 1) $page = 1;
    if ($pageSize < 1) $pageSize = 20;
    if ($pageSize > 100) $pageSize = 100;

    $offset = ($page - 1) * $pageSize;

    $where = array();
    $params = array();

    if ($data !== '') {
        if (strpos($data, '/') !== false) {
            $dt = DateTime::createFromFormat('d/m/Y', $data);
            if ($dt) {
                $data = $dt->format('Y-m-d');
            }
        }
        $where[] = "DATE(data_relatorio) = :data";
        $params[':data'] = $data;
    }

    if ($status !== '') {
        $where[] = "status_upload = :status";
        $params[':status'] = strtoupper($status);
    }

    $whereSql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $totalResult = $sql->select("
        SELECT COUNT(*) AS total
        FROM tb_relatorios_pdf
        {$whereSql}
    ", $params);

    $total = isset($totalResult[0]['total']) ? (int)$totalResult[0]['total'] : 0;

    $items = $sql->select("
        SELECT
            id,
            data_relatorio,
            nome_arquivo,
            url_publica,
            caminho_remoto,
            status_upload,
            mensagem_erro,
            responsavel,
            cpf_responsavel,
            data_geracao,
            data_upload
        FROM tb_relatorios_pdf
        {$whereSql}
        ORDER BY id DESC
        LIMIT {$offset}, {$pageSize}
    ", $params);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array(
        'success' => true,
        'page' => $page,
        'pageSize' => $pageSize,
        'total' => $total,
        'pages' => $pageSize > 0 ? ceil($total / $pageSize) : 1,
        'items' => $items
    ));
    exit;
});
