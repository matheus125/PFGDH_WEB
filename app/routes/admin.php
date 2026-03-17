<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Clientes;
use \Hcode\Model\Funcionarios;
use \Hcode\DB\Sql;
use Hcode\Model\Notification;
use Hcode\Model\Permissions;

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
		'error' => Funcionarios::getError(),
		'attempts' => Funcionarios::getLoginAttempts()
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

	try {

		if (!isset($_POST["cpf"]) || trim($_POST["cpf"]) === '') {
			throw new Exception("Informe o CPF.");
		}

		if (!isset($_POST["senha"]) || trim($_POST["senha"]) === '') {
			throw new Exception("Informe a senha.");
		}

		if (Funcionarios::getLoginAttempts() >= 5) {
			throw new Exception("Muitas tentativas inválidas. Aguarde um momento antes de tentar novamente.");
		}

		$funcionarios = Funcionarios::login($_POST["cpf"], $_POST["senha"]);
		$_SESSION[Funcionarios::SESSION] = $funcionarios->getValues();

		Funcionarios::clearError();
		Funcionarios::clearLoginAttempts();
		Funcionarios::registerAccess($funcionarios, 'LOGIN');

		if (function_exists('backupAutomatico')) {
			backupAutomatico();
		}

		header("Location: /admin");
		exit;
	} catch (Exception $e) {

		Funcionarios::addLoginAttempt();
		$tentativas = Funcionarios::getLoginAttempts();
		$msg = $e->getMessage();

		if (
			$tentativas < 5 &&
			$msg !== "Muitas tentativas inválidas. Aguarde um momento antes de tentar novamente."
		) {
			$msg .= " Tentativa " . $tentativas . " de 5.";
		}

		Funcionarios::setError($msg);
		header("Location: /admin/login");
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
| FORGOT PASSWORD
|--------------------------------------------------------------------------
*/

$app->get('/admin/forgot', function () {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot", [
		'error' => Funcionarios::getError(),
		'success' => Funcionarios::getSuccess()
	]);
});

$app->post('/admin/forgot', function () {

	try {

		if (!isset($_POST["cpf"]) || trim($_POST["cpf"]) === '') {
			throw new Exception("Informe o CPF.");
		}

		$cpf = preg_replace('/\D/', '', $_POST["cpf"]);

		if (!Funcionarios::validaCPF($cpf)) {
			throw new Exception("Informe um CPF válido.");
		}

		Funcionarios::getForgot($cpf);

		Funcionarios::setSuccess("Se o CPF estiver cadastrado e possuir e-mail válido, você receberá um link de recuperação.");
		header("Location: /admin/forgot");
		exit;
	} catch (Exception $e) {
		Funcionarios::setError($e->getMessage());
		header("Location: /admin/forgot");
		exit;
	}
});

$app->get('/admin/forgot/reset', function () {

	try {

		if (!isset($_GET["code"]) || trim($_GET["code"]) === '') {
			throw new Exception("Código inválido.");
		}

		Funcionarios::validForgotDecrypt($_GET["code"]);

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);

		$page->setTpl("forgot-reset", [
			"code" => $_GET["code"],
			"error" => Funcionarios::getError(),
			"success" => Funcionarios::getSuccess()
		]);
	} catch (Exception $e) {
		Funcionarios::setError($e->getMessage());
		header("Location: /admin/forgot");
		exit;
	}
});

$app->post('/admin/forgot/reset', function () {

	try {

		if (!isset($_POST["code"]) || trim($_POST["code"]) === '') {
			throw new Exception("Código inválido.");
		}

		if (!isset($_POST["password"]) || trim($_POST["password"]) === '') {
			throw new Exception("Informe a nova senha.");
		}

		if (!isset($_POST["password_confirm"]) || trim($_POST["password_confirm"]) === '') {
			throw new Exception("Confirme a nova senha.");
		}

		if (strlen(trim($_POST["password"])) < 6) {
			throw new Exception("A senha deve ter pelo menos 6 caracteres.");
		}

		if ($_POST["password"] !== $_POST["password_confirm"]) {
			throw new Exception("As senhas não coincidem.");
		}

		$recovery = Funcionarios::validForgotDecrypt($_POST["code"]);

		$funcionario = new Funcionarios();
		$funcionario->get((int)$recovery["id_usuario"]);
		$funcionario->setPassword($_POST["password"]);

		Funcionarios::setForgotUsed((int)$recovery["id_recovery"]);

		Funcionarios::audit(
			'PASSWORD_RECOVERY_SUCCESS',
			'AUTH',
			(int)$recovery["id_usuario"],
			'Senha redefinida via token de recuperação'
		);

		Funcionarios::setSuccess("Senha alterada com sucesso.");
		header("Location: /admin/login");
		exit;
	} catch (Exception $e) {
		Funcionarios::setError($e->getMessage());
		header("Location: /admin/forgot/reset?code=" . urlencode($_POST["code"] ?? ""));
		exit;
	}
});



$app->get('/admin/test-email', function () {

	try {

		\Hcode\Mailer::quickSend(
			"mmota350@gmail.com", // <-- coloque seu email aqui
			"Teste",
			"Teste SMTP Prato Cheio",
			"<h1>🔥 Funcionando!</h1><p>Seu sistema de e-mail está OK.</p>"
		);

		echo "✅ E-mail enviado com sucesso!";
		exit;
	} catch (Exception $e) {

		echo "❌ Erro: " . $e->getMessage();
		exit;
	}
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

	Funcionarios::audit(
		'BACKUP_RUN',
		'BACKUP',
		null,
		'Backup executado pela rota de teste'
	);

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

	Funcionarios::checkPermission('BACKUP_RUN');

	if (function_exists('backupAutomatico')) {
		backupAutomatico();
	}

	Funcionarios::audit(
		'BACKUP_RUN',
		'BACKUP',
		null,
		'Backup manual executado'
	);

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

	Funcionarios::audit(
		'NOTIFICACOES_CLEAR',
		'NOTIFICACOES',
		null,
		'Todas as notificações foram removidas'
	);

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

	Funcionarios::audit(
		'NOTIFICACOES_CLEAR',
		'NOTIFICACOES',
		null,
		'Todas as notificações foram removidas via AJAX'
	);

	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(['success' => true]);
	exit;
});

$app->get('/admin/notificacoes/add-teste', function () {

	Funcionarios::checkPermission('NOTIFICACOES_CLEAR');

	Notification::add("Notificação de teste criada com sucesso.");

	Funcionarios::audit(
		'NOTIFICACAO_TESTE_CREATE',
		'NOTIFICACOES',
		null,
		'Notificação de teste criada manualmente'
	);

	header("Location: /admin");
	exit;
});

$app->get('/admin/api/notificacoes', function () {

	header('Content-Type: application/json; charset=utf-8');

	$user = Funcionarios::getFromSession();

	if (!$user || !$user->getid_usuario()) {
		http_response_code(401);
		echo json_encode([
			'success' => false,
			'mensagem' => 'Usuário não autenticado.'
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	$permissions = $_SESSION['User']['permissions'] ?? [];

	if (!in_array('NOTIFICACOES_VIEW', $permissions)) {
		http_response_code(403);
		echo json_encode([
			'success' => false,
			'mensagem' => 'Acesso negado.'
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	try {
		$notificacoesSessao = Notification::getAll();
		$notificacoesBackup = function_exists('getBackupNotifications') ? getBackupNotifications(50) : [];

		$notificacoes = array_merge($notificacoesSessao, $notificacoesBackup);

		echo json_encode([
			'success' => true,
			'total' => count($notificacoes),
			'notificacoes' => $notificacoes
		], JSON_UNESCAPED_UNICODE);
	} catch (Throwable $e) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'mensagem' => $e->getMessage()
		], JSON_UNESCAPED_UNICODE);
	}

	exit;
});

$app->get('/admin/seguranca/permissoes', function () {
	Funcionarios::checkPermission('ACL_PROFILES_MANAGE');

	$page = new PageAdmin();
	$all = Permissions::listAll();

	$page->setTpl('seguranca-permissoes', [
		'permissions' => $all,
		'admin_permissions' => Permissions::listByProfile('ADMIN'),
		'supervisor_permissions' => Permissions::listByProfile('SUPERVISOR'),
		'assessor_permissions' => Permissions::listByProfile('ASSESSOR')
	]);
});

$app->post('/admin/seguranca/permissoes', function () {
	Funcionarios::checkPermission('ACL_PROFILES_MANAGE');

	foreach (['ADMIN', 'SUPERVISOR', 'ASSESSOR'] as $perfil) {
		Permissions::saveProfilePermissions($perfil, $_POST['permissions'][$perfil] ?? []);
	}

	Permissions::clearSessionCache();

	Funcionarios::audit(
		'ACL_PERMISSION_UPDATE',
		'SEGURANCA',
		null,
		'Permissões por perfil foram atualizadas'
	);

	header('Location: /admin/seguranca/permissoes');
	exit;
});

$app->get('/admin/seguranca/acessos-negados', function () {
	Funcionarios::checkPermission('ACL_DENIED_VIEW');

	$sql = new Sql();
	$rows = $sql->select("SELECT * FROM tb_access_denied ORDER BY created_at DESC LIMIT 300");

	$page = new PageAdmin();
	$page->setTpl('seguranca-acessos-negados', ['rows' => $rows]);
});

$app->get('/admin/usuarios/seguranca', function () {
	Funcionarios::checkPermission('USUARIOS_SECURITY_MANAGE');

	$page = new PageAdmin();
	$page->setTpl('usuarios-seguranca', ['usuarios' => Funcionarios::listAllSecurity()]);
});

$app->post('/admin/usuarios/:id_usuario/status', function ($id_usuario) {
	Funcionarios::checkPermission('USUARIOS_SECURITY_MANAGE');

	Funcionarios::setUserActive((int)$id_usuario, (int)($_POST['ativo'] ?? 0));

	header('Location: /admin/usuarios/seguranca');
	exit;
});

$app->post('/admin/funcionarios/:id_pessoa/status-funcionario', function ($id_pessoa) {
	Funcionarios::checkPermission('USUARIOS_SECURITY_MANAGE');

	Funcionarios::setFuncionarioActive((int)$id_pessoa, (int)($_POST['ativo'] ?? 0));

	header('Location: /admin/usuarios/seguranca');
	exit;
});

$app->get('/admin/debug-permissoes', function () {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($_SESSION['User']['permissions'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	exit;
});

$app->get('/admin/seguranca/auditoria', function () {

	Funcionarios::checkPermission('ACL_DENIED_VIEW');

	$sql = new Sql();

	$logs = $sql->select("
        SELECT *
        FROM tb_userlogs
        ORDER BY created_at DESC
        LIMIT 300
    ");

	$page = new PageAdmin();

	$page->setTpl('seguranca-auditoria', [
		'logs' => $logs
	]);
});
