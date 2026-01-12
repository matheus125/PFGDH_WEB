<?php

namespace Hcode\Backup;

use CURLFile;
use Exception;

class UploadService
{
    private string $uploadUrl = 'https://ms-tecnologia.app.br/backups/backup-upload.php';

    private string $token = 'PRATO_CHEIO_2026_SEGURANCA';

    public function send(string $filePath): void
    {
        $sentFlag = $filePath . '.sent';

        if (file_exists($sentFlag)) {
            return;
        }

        $curl = curl_init($this->uploadUrl);

        $file = new \CURLFile($filePath, 'application/sql', basename($filePath));

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'backup' => $file,
                'token'  => $this->token
            ],
        ]);

        $response = trim(curl_exec($curl));
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpCode !== 200 || $response !== 'UPLOAD_OK') {
            throw new \Exception("Upload falhou: {$response}");
        }

        file_put_contents($sentFlag, 'OK');
    }
}
