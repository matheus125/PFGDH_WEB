<?php

use \Hcode\Model\Funcionarios;
use \Hcode\Model\Cart;

require_once __DIR__ . '/vendor/autoload.php';

function formatPrice($vlprice)
{

    if (!$vlprice > 0) $vlprice = 0;

    return number_format($vlprice, 2, ",", ".");
}

function formatDate($date)
{

    return date('d/m/Y', strtotime($date));
}

function checkLogin($inadmin = true)
{

    return Funcionarios::checkLogin($inadmin);
}

function getUserName()
{

    $funcionarios = Funcionarios::getFromSession();

    return $funcionarios->getnome_funcionario();
}



function backupAutomatico(): void
{
    try {
        $database = 'prato_cheio';

        $backupService = new Hcode\Backup\BackupService();
        $uploadService = new Hcode\Backup\UploadService();

        $file = $backupService->createBackup($database);
        backupLog("Backup OK: " . basename($file));

        $uploadService->send($file);
        backupLog("Upload OK");

        // 🧹 Limpeza automática
        limparBackupsAntigos(7);
    
    } catch (Exception $e) {
        backupLog("ERRO: " . $e->getMessage());
    }
}


function backupLog(string $msg): void
{
    $dir = __DIR__ . '/backup';

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $file = $dir . '/log.txt';
    $date = date('[Y-m-d H:i:s]');

    file_put_contents($file, "$date $msg\n", FILE_APPEND);
}


function limparBackupsAntigos(int $dias = 7): void
{
    $dir = __DIR__ . '/backup';
    $limite = time() - ($dias * 86400);

    foreach (glob("$dir/*.sql") as $file) {
        if (filemtime($file) < $limite) {
            @unlink($file);
            @unlink($file . '.sent');
            backupLog("Backup removido: " . basename($file));
        }
    }
}
