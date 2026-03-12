<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Clientes;
use \Hcode\Model\Funcionarios;
use \Hcode\DB\Sql;
use Hcode\Model\Notification;

/*
|--------------------------------------------------------------------------
| ROOT -> manda pro login
|--------------------------------------------------------------------------
*/

$app->get('/', function () {
	header("Location: /admin/login");
	exit;
});

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN (/admin)
|--------------------------------------------------------------------------
*/
$app->get('/admin', function () {

	Funcionarios::checkPermission('DASHBOARD_VIEW');

	$notificacoesSessao = Notification::getAll();
	$notificacoesBackup = function_exists('getBackupNotifications') ? getBackupNotifications(10) : [];

	$notificacoes = array_merge($notificacoesSessao, $notificacoesBackup);
	$total = count($notificacoes);

	$ultimoBackup = function_exists('getUltimoBackup') ? getUltimoBackup() : null;

	$page = new PageAdmin([
		"data" => [
			"notificacoes" => $notificacoes,
			"total" => $total,
			"ultimoBackup" => $ultimoBackup
		]
	]);

	$page->setTpl("index");
});

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
$app->get("/api/total-titulares", function () {
	$total = Clientes::total_usuarios();
	echo json_encode(["total" => $total]);
});

$app->get('/api/usuarios-titulares', function () {

	header('Content-Type: application/json; charset=utf-8');

	try {
		$sql = new Sql();

		$dados = $sql->select("
            SELECT 
                id,
                nome_completo,
                telefone
            FROM tb_titular
            ORDER BY nome_completo
        ");

		echo json_encode($dados, JSON_UNESCAPED_UNICODE);
	} catch (Throwable $e) {
		http_response_code(500);
		echo json_encode([
			'erro' => true,
			'mensagem' => $e->getMessage()
		]);
	}
});

$app->get("/api/total-dependentes", function () {
	$total = Clientes::total_dependentes();
	echo json_encode(["total" => $total]);
});

$app->get("/api/total-familias", function () {
	$total = Clientes::total_familias();
	echo json_encode(["total" => $total]);
});

$app->get("/api/grafico-titulares", function () {

	header('Content-Type: application/json; charset=utf-8');

	try {
		$sql = new Sql();

		$rows = $sql->select("
            SELECT data_nascimento, genero FROM tb_titular
            UNION ALL
            SELECT data_nascimento, genero FROM tb_dependentes
        ");

		$faixas = [
			'3-17' => 0,
			'18-59' => 0,
			'60+' => 0
		];

		foreach ($rows as $row) {

			if (empty($row['data_nascimento'])) continue;

			$idade = (new DateTime($row['data_nascimento']))->diff(new DateTime())->y;

			if ($idade >= 3 && $idade <= 17) {
				$faixas['3-17']++;
			} elseif ($idade >= 18 && $idade <= 59) {
				$faixas['18-59']++;
			} else {
				$faixas['60+']++;
			}
		}

		echo json_encode($faixas, JSON_UNESCAPED_UNICODE);
	} catch (Throwable $e) {
		http_response_code(500);
		echo json_encode([
			'erro' => true,
			'mensagem' => $e->getMessage()
		]);
	}
});

/*
|--------------------------------------------------------------------------
| LOGIN (GET)
|--------------------------------------------------------------------------
*/
$app->get('/admin/login', function () {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("login", [
		'error' => Funcionarios::getError()
	]);
});

/*
|--------------------------------------------------------------------------
| TESTE ESTRUTURA TABELA (APENAS ADMIN)
|--------------------------------------------------------------------------
*/
$app->get('/admin/test-tb-usuario', function () {

	Funcionarios::checkPermission('SISTEMA_DEBUG');

	header('Content-Type: application/json; charset=utf-8');

	try {
		$sql = new \Hcode\DB\Sql();
		$results = $sql->select("DESCRIBE tb_usuario");

		echo json_encode([
			"success"   => true,
			"structure" => $results
		]);
	} catch (Exception $e) {
		echo json_encode([
			"success" => false,
			"error"   => $e->getMessage()
		]);
	}

	exit;
});

/*
|--------------------------------------------------------------------------
| LOGIN (POST)
|--------------------------------------------------------------------------
*/
$app->post('/admin/login', function () {

	header('Content-Type: application/json; charset=utf-8');

	try {

		$funcionarios = Funcionarios::login($_POST["cpf"], $_POST["senha"]);
		$_SESSION[Funcionarios::SESSION] = $funcionarios->getValues();
		Funcionarios::registerAccess($funcionarios, 'LOGIN');

		// 1) Responde o login imediatamente
		echo json_encode(["success" => true]);

		// 2) Fecha a resposta pro navegador
		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		} else {
			@ob_end_flush();
			@ob_flush();
			@flush();
		}

		// 3) Backup silencioso (se existir)
		if (function_exists('backupAutomatico')) {
			backupAutomatico();
		}

		exit;
	} catch (Exception $e) {

		echo json_encode([
			"success" => false,
			"error"   => $e->getMessage()
		]);
		exit;
	}
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
$app->get('/admin/logout', function () {

	$funcionarios = Funcionarios::getFromSession();

	if ($funcionarios && $funcionarios->getid_usuario() > 0) {
		Funcionarios::registerAccess($funcionarios, 'LOGOUT');
	}

	Funcionarios::logout();

	header("Location: /admin/login");
	exit;
});

/*
|--------------------------------------------------------------------------
| Compatibilidade
|--------------------------------------------------------------------------
*/
$app->get('/admin/index', function () {
	header("Location: /admin");
	exit;
});

/*
|--------------------------------------------------------------------------
| Rotas de teste (mantidas)
|--------------------------------------------------------------------------
*/
$app->get('/admin/teste-backup', function () {

	Funcionarios::checkPermission('BACKUP_RUN');

	if (function_exists('backupAutomatico')) {
		backupAutomatico();
	}

	Notification::add("Backup executado (teste).");

	$notificacoesSessao = Notification::getAll();
	$notificacoesBackup = function_exists('getBackupNotifications') ? getBackupNotifications(10) : [];

	$notificacoes = array_merge($notificacoesSessao, $notificacoesBackup);
	$total = count($notificacoes);

	$ultimoBackup = function_exists('getUltimoBackup') ? getUltimoBackup() : null;

	$page = new PageAdmin([
		"data" => [
			"notificacoes" => $notificacoes,
			"total" => $total,
			"ultimoBackup" => $ultimoBackup
		]
	]);

	$page->setTpl("index", [
		"msg" => "Backup executado (teste)."
	]);
});

$app->get('/admin/teste-notifs', function () {

	header('Content-Type: application/json; charset=utf-8');

	echo json_encode(
		function_exists('getBackupNotifications') ? getBackupNotifications(5) : [],
		JSON_UNESCAPED_UNICODE
	);

	exit;
});

$app->get('/admin/debug-logpath', function () {

	header('Content-Type: application/json; charset=utf-8');

	$arquivo = defined('BACKUP_DIR') ? (BACKUP_DIR . '/backup_notifications.log') : (__DIR__ . '/backup/backup_notifications.log');

	echo json_encode([
		"admin_php_dir" => __DIR__,
		"log_path" => $arquivo,
		"exists" => file_exists($arquivo),
		"size" => file_exists($arquivo) ? filesize($arquivo) : 0,
		"realpath" => file_exists($arquivo) ? realpath($arquivo) : null,
	], JSON_UNESCAPED_UNICODE);

	exit;
});

$app->get('/admin/backup/run', function () {

	echo "ENTROU NA ROTA /admin/backup/run<br>";

	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	if (function_exists('backupAutomatico')) {
		backupAutomatico();
	}

	echo "CHAMOU backupAutomatico()";
	exit;
});

$app->get('/admin/ping', function () {
	echo "OK PING";
	exit;
});


$app->get('/admin/notificacoes', function () {

	Funcionarios::checkPermission('NOTIFICACOES_VIEW');

	$notificacoesSessao = Notification::getAll();
	$notificacoesBackup = function_exists('getBackupNotifications') ? getBackupNotifications(50) : [];

	$notificacoes = array_merge($notificacoesSessao, $notificacoesBackup);
	$total = count($notificacoes);

	$page = new PageAdmin();

	$page->setTpl("notificacoes", [
		"notificacoes" => $notificacoes,
		"total" => $total
	]);
});

$app->get('/admin/notificacoes/limpar', function () {

	Funcionarios::checkPermission('NOTIFICACOES_CLEAR');

	Notification::clear();

	if (function_exists('backupLogFile')) {
		$arquivo = backupLogFile();
		if (file_exists($arquivo)) {
			file_put_contents($arquivo, '');
		}
	}

	header("Location: /admin/notificacoes");
	exit;
});



$app->post('/admin/notificacoes/limpar', function () {

	Funcionarios::checkPermission('NOTIFICACOES_CLEAR');

	Notification::clear();

	if (function_exists('backupLogFile')) {
		$arquivo = backupLogFile();
		if (file_exists($arquivo)) {
			file_put_contents($arquivo, '');
		}
	}

	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(['success' => true]);
	exit;
});

$app->get('/admin/notificacoes/add-teste', function () {

	Funcionarios::checkPermission('NOTIFICACOES_CLEAR');

	Notification::add("Notificação de teste criada com sucesso.");

	header("Location: /admin");
	exit;
});
