<?php

function getConnectionHostgator()
{
    $config = require __DIR__ . '/../config/db_hostgator.php';

    try {
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['user'],
            $config['pass'],
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            )
        );

        return $pdo;
    } catch (Exception $e) {
        error_log("Erro conexão HostGator: " . $e->getMessage());
        return null;
    }
}

function registrarHistoricoRelatorioHostgator(array $dados)
{
    try {
        $pdo = getConnectionHostgator();

        if (!$pdo) {
            throw new Exception("Sem conexão com HostGator");
        }

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
                :data_geracao,
                :data_upload
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':data_relatorio'   => $dados['data_relatorio'],
            ':nome_arquivo'     => $dados['nome_arquivo'],
            ':url_publica'      => $dados['url_publica'] ?? null,
            ':caminho_remoto'   => $dados['caminho_remoto'] ?? null,
            ':status_upload'    => $dados['status_upload'] ?? 'SUCESSO',
            ':mensagem_erro'    => $dados['mensagem_erro'] ?? null,
            ':responsavel'      => $dados['responsavel'] ?? null,
            ':cpf_responsavel'  => $dados['cpf_responsavel'] ?? null,
            ':data_geracao'     => $dados['data_geracao'],
            ':data_upload'      => $dados['data_upload'] ?? null
        ));

        return true;
    } catch (Exception $e) {
        error_log("Erro ao salvar histórico remoto: " . $e->getMessage());
        return false;
    }
}
