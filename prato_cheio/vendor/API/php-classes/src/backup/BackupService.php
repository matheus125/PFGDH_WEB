<?php

namespace Hcode\Backup;

use Hcode\DB\Sql;
use PDO;
use Throwable;

class BackupService
{
    private string $backupDir;

    public function __construct()
    {
        if (defined('BACKUP_DIR') && BACKUP_DIR) {
            $this->backupDir = BACKUP_DIR;
        } else {
            $this->backupDir = dirname(__DIR__, 4) . '/storage/backup';
        }

        if (!is_dir($this->backupDir)) {
            @mkdir($this->backupDir, 0777, true);
        }
    }

    public function createBackup(string $database): string
    {
        $date = date('Y-m-d');
        $file = $this->backupDir . '/' . $database . '_' . $date . '.sql';

        if (file_exists($file) && filesize($file) > 0) {
            return $file;
        }

        $ultimaExcecao = null;

        try {
            if ($this->createBackupWithMysqlDump($database, $file)) {
                return $file;
            }
        } catch (Throwable $e) {
            $ultimaExcecao = $e;
        }

        try {
            $this->createBackupWithPdo($database, $file);
            if (file_exists($file) && filesize($file) > 0) {
                return $file;
            }
        } catch (Throwable $e) {
            $ultimaExcecao = $e;
        }

        if (file_exists($file) && filesize($file) === 0) {
            @unlink($file);
        }

        $mensagem = 'Falha ao gerar backup';
        if ($ultimaExcecao) {
            $mensagem .= ': ' . $ultimaExcecao->getMessage();
        }

        throw new \Exception($mensagem);
    }

    private function createBackupWithMysqlDump(string $database, string $file): bool
    {
        $mysqldump = $this->findMysqldumpBinary();
        if ($mysqldump === null) {
            return false;
        }

        $dbHost = Sql::HOSTNAME;
        $dbUser = Sql::USERNAME;
        $dbPass = Sql::PASSWORD;

        $command = sprintf(
            '"%s" --default-character-set=utf8mb4 --skip-set-charset --single-transaction --routines --triggers -h %s -u %s -p%s %s > "%s" 2>&1',
            $mysqldump,
            $this->escapeShellToken($dbHost),
            $this->escapeShellToken($dbUser),
            $this->escapePasswordForShell($dbPass),
            $this->escapeShellToken($database),
            $file
        );

        $output = [];
        $return = 0;
        @exec($command, $output, $return);

        if ($return !== 0) {
            if (file_exists($file) && filesize($file) === 0) {
                @unlink($file);
            }
            throw new \Exception('mysqldump retornou código ' . $return . (!empty($output) ? ' (' . implode(' | ', $output) . ')' : ''));
        }

        return file_exists($file) && filesize($file) > 0;
    }

    private function createBackupWithPdo(string $database, string $file): void
    {
        $pdo = new PDO(
            'mysql:host=' . Sql::HOSTNAME . ';dbname=' . $database . ';charset=utf8mb4',
            Sql::USERNAME,
            Sql::PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
            ]
        );

        $tables = $pdo->query('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"')->fetchAll(PDO::FETCH_NUM);

        $fp = @fopen($file, 'wb');
        if (!$fp) {
            throw new \Exception('Não foi possível criar o arquivo de backup em ' . $file);
        }

        fwrite($fp, "-- Backup gerado em " . date('Y-m-d H:i:s') . PHP_EOL);
        fwrite($fp, "SET FOREIGN_KEY_CHECKS=0;" . PHP_EOL . PHP_EOL);

        foreach ($tables as $row) {
            $table = $row[0] ?? null;
            if (!$table) {
                continue;
            }

            $createStmt = $pdo->query('SHOW CREATE TABLE `' . str_replace('`', '``', $table) . '`')->fetch(PDO::FETCH_ASSOC);
            $createSql = $createStmt['Create Table'] ?? array_values($createStmt)[1] ?? null;

            if (!$createSql) {
                continue;
            }

            fwrite($fp, "-- Estrutura da tabela `{$table}`" . PHP_EOL);
            fwrite($fp, 'DROP TABLE IF EXISTS `' . $table . '`;' . PHP_EOL);
            fwrite($fp, $createSql . ';' . PHP_EOL . PHP_EOL);

            $stmt = $pdo->query('SELECT * FROM `' . str_replace('`', '``', $table) . '`');
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                fwrite($fp, "-- Dados da tabela `{$table}`" . PHP_EOL);
                foreach ($rows as $data) {
                    $columns = array_map(static fn($c) => '`' . str_replace('`', '``', $c) . '`', array_keys($data));
                    $values = [];

                    foreach ($data as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = $pdo->quote((string) $value);
                        }
                    }

                    $insertSql = 'INSERT INTO `' . $table . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';
                    fwrite($fp, $insertSql . PHP_EOL);
                }
                fwrite($fp, PHP_EOL);
            }
        }

        fwrite($fp, 'SET FOREIGN_KEY_CHECKS=1;' . PHP_EOL);
        fclose($fp);
    }

    private function findMysqldumpBinary(): ?string
    {
        $candidates = [
            getenv('MYSQLDUMP_PATH') ?: null,
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.4\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump',
        ];

        foreach ($candidates as $candidate) {
            if (!empty($candidate) && @is_file($candidate)) {
                return $candidate;
            }
        }

        $whichCommand = stripos(PHP_OS_FAMILY, 'Windows') !== false ? 'where mysqldump' : 'which mysqldump';
        $found = @shell_exec($whichCommand . ' 2>NUL');
        if (is_string($found)) {
            $lines = preg_split('/\r\n|\r|\n/', trim($found));
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '' && @is_file($line)) {
                    return $line;
                }
            }
        }

        return null;
    }

    private function escapeShellToken(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $value);
    }

    private function escapePasswordForShell(string $password): string
    {
        if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
            return str_replace(['^', '&', '|', '<', '>', '%', '"'], ['^^', '^&', '^|', '^<', '^>', '%%', '\\"'], $password);
        }

        return str_replace("'", "'\\''", $password);
    }
}
