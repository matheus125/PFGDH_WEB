<?php

namespace Hcode\Backup;

use CURLFile;
use Exception;

class UploadService
{
    private string $uploadUrl = 'https://ms-tecnologia.app.br/backups/backup-upload.php';
    private string $token = 'PRATO_CHEIO_2026_SEGURANCA';
    private int $timeoutSeconds = 60;
    private int $connectTimeoutSeconds = 15;

    public function send(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("Arquivo de backup não encontrado: {$filePath}");
        }

        $sentFlag = $filePath . '.sent';
        $lockFlag = $filePath . '.uploading';

        if (file_exists($sentFlag)) {
            return;
        }

        if (file_exists($lockFlag)) {
            $lockTime = (int) @file_get_contents($lockFlag);
            if ($lockTime > 0 && (time() - $lockTime) < 600) {
                return;
            }
        }

        @file_put_contents($lockFlag, (string) time());

        $ch = curl_init($this->uploadUrl);
        if ($ch === false) {
            @unlink($lockFlag);
            throw new Exception("Falha ao inicializar cURL.");
        }

        $post = [
            "backup" => new CURLFile($filePath, "application/sql", basename($filePath)),
            "token"  => $this->token
        ];

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->connectTimeoutSeconds,
            CURLOPT_TIMEOUT => $this->timeoutSeconds,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => ['Expect:'],
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $errNo = curl_errno($ch);
            $errMsg = curl_error($ch);
            curl_close($ch);
            @unlink($lockFlag);
            throw new Exception("Erro cURL ({$errNo}): {$errMsg}");
        }

        curl_close($ch);
        @unlink($lockFlag);

        $responseTrim = trim((string) $response);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new Exception("Upload falhou. HTTP={$httpCode}. Resposta='{$responseTrim}'");
        }

        if ($responseTrim !== 'UPLOAD_OK') {
            throw new Exception("Upload falhou. Resposta inesperada: '{$responseTrim}'");
        }

        file_put_contents($sentFlag, 'OK');
    }
}
