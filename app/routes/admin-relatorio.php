<?php

use \Hcode\DB\Sql;
use \Hcode\Model\Funcionarios;
use \Hcode\PageAdmin;
use Dompdf\Dompdf;

if (!function_exists('getLimiteSenhasDiaRelatorio')) {
    function getLimiteSenhasDiaRelatorio()
    {
        if (defined('LIMITE_SENHAS_DIA')) {
            return max(0, (int)LIMITE_SENHAS_DIA);
        }

        return 600;
    }
}

if (!function_exists('contarSenhasVendidasRelatorio')) {
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

if (!function_exists('getRelatorioUploadConfig')) {
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
}

if (!function_exists('getRelatorioDbRemotoConfig')) {
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
}

if (!function_exists('getPdoRelatorioRemoto')) {
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
}

if (!function_exists('getRelatorioTempDir')) {
    function getRelatorioTempDir(array $config)
    {
        $tempDirName = !empty($config['temp_dir_name']) ? $config['temp_dir_name'] : 'tmp_relatorios';
        return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $tempDirName;
    }
}

if (!function_exists('limparBufferSaida')) {
    function limparBufferSaida()
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }
}

if (!function_exists('responderJson')) {
    function responderJson(array $payload)
    {
        limparBufferSaida();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }
}

if (!function_exists('escreverLogRelatorio')) {
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
}

if (!function_exists('registrarHistoricoRelatorio')) {
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
}

if (!function_exists('registrarHistoricoRelatorioRemoto')) {
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
}

if (!function_exists('registrarHistoricoRelatorioEmAmbosBancos')) {
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
}

if (!function_exists('garantirHistoricoRelatorioRemoto')) {
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
}

if (!function_exists('uploadRelatorioRemoto')) {
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
}

$app->get('/admin/api/relatorio/pdf', function () {
    responderJson(array(
        'success' => false,
        'message' => 'Arquivo separado gerado. Cole ou use a versão completa recebida antes para o bloco integral do PDF.'
    ));
});

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
