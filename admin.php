<?php

use \Hcode\PageAdmin;
use \Hcode\Model\Clientes;
use Hcode\Model\Funcionarios;
use \Hcode\DB\Sql;
/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

$app->get('/', function () {

	// Apenas ADMIN e SUPERVISOR
	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	// ✅ Notificações para o header
	$notificacoes = getBackupNotifications(10);
	$total = count($notificacoes);

	// ✅ Passa no construtor (header é renderizado aqui!)
	$page = new PageAdmin([
		"notificacoes" => $notificacoes,
		"total" => $total
	]);

	$page->setTpl("index");
});

$app->get("/api/total-titulares", function () {
	$total = Clientes::total_usuarios(); // esse método deve retornar um número
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
	$total = Clientes::total_dependentes(); // esse método deve retornar um número
	echo json_encode(["total" => $total]);
});

$app->get("/api/total-familias", function () {
	$total = Clientes::total_familias(); // esse método deve retornar um número
	echo json_encode(["total" => $total]);
});

$app->get("/api/grafico-titulares", function () {

	header('Content-Type: application/json; charset=utf-8');
	require __DIR__ . "/vendor/autoload.php";

	try {
		$sql = new Sql();

		$rows = $sql->select("
            SELECT data_nascimento, genero FROM tb_titular
            UNION ALL
            SELECT data_nascimento, genero FROM tb_dependentes
        ");

		// Totais por faixa (SIMPLES, direto pro gráfico)
		$faixas = [
			'3-17' => 0,
			'18-59' => 0,
			'60+' => 0
		];

		foreach ($rows as $row) {

			if (empty($row['data_nascimento'])) continue;

			$idade = (new DateTime($row['data_nascimento']))
				->diff(new DateTime())->y;

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

// Rota GET -> exibe o formulário de login (renderiza login.tpl)
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

	Funcionarios::verifyLogin('ADMIN');

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

        // ✅ 1) Responde o login IMEDIATAMENTE
        echo json_encode(["success" => true]);
        
        // ✅ 2) Fecha a resposta pro navegador (não deixa o usuário esperando)
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            // fallback (ajuda em alguns ambientes)
            @ob_end_flush();
            @ob_flush();
            @flush();
        }

        // ✅ 3) Agora roda o backup "silencioso"
        // (O próprio backupAutomatico() já tem cooldown/lock)
        backupAutomatico();

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
| DASHBOARD /admin/index
|--------------------------------------------------------------------------
*/
$app->get('/admin/index', function () {

	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	$notificacoes = getBackupNotifications(10);
	$total = count($notificacoes);

	$page = new PageAdmin([
		"data" => [
			"notificacoes" => $notificacoes,
			"total" => $total
		]
	]);

	$page->setTpl("index");
});


$app->get('/admin/teste-backup', function () {

	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	// roda o backup/upload (se você quiser)
	backupAutomatico();

	// Notificações pro header
	$notificacoes = getBackupNotifications(10);
	$total = count($notificacoes);

	$page = new PageAdmin([
		"data" => [
			"notificacoes" => $notificacoes,
			"total" => $total
		]
	]);

	$page->setTpl("index", [
		"msg" => "Backup executado (teste)."
	]);
});

// ==============================
// TESTE NOTIFICAÇÕES BACKUP
// ==============================
$app->get('/admin/teste-notifs', function () {

	header('Content-Type: application/json; charset=utf-8');

	echo json_encode(
		getBackupNotifications(5),
		JSON_UNESCAPED_UNICODE
	);

	exit;
});

$app->get('/admin/debug-logpath', function () {

	header('Content-Type: application/json; charset=utf-8');

	$arquivo = __DIR__ . '/backup/backup_notifications.log'; // <- coloque aqui o mesmo caminho usado no getBackupNotifications()

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

	// opcional: comentar verifyLogin só pra teste rápido
	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	backupAutomatico();

	echo "CHAMOU backupAutomatico()";
	exit;
});


$app->get('/admin/ping', function () {
	echo "OK PING";
	exit;
});


$app->get('/admin/index', function () {

	Funcionarios::verifyLogin(['ADMIN', 'SUPERVISOR']);

	$notificacoes = getBackupNotifications(10);
	$total = count($notificacoes);
	$ultimoBackup = getUltimoBackup();

	$page = new PageAdmin([
		"data" => [
			"notificacoes" => $notificacoes,
			"total" => $total,
			"ultimoBackup" => $ultimoBackup
		]
	]);

	$page->setTpl("index");
});
