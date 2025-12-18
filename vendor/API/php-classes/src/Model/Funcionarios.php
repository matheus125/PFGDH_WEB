<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Funcionarios extends Model
{

	const SESSION = "User";
	const SECRET = "HcodePhp7_Secret";
	const SECRET_IV = "HcodePhp7_Secret_IV";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSucesss";

	public static function getFromSession()
	{

		$funcionarios = new Funcionarios();

		if (isset($_SESSION[Funcionarios::SESSION]) && (int)$_SESSION[Funcionarios::SESSION]['id_usuario'] > 0) {

			$funcionarios->setData($_SESSION[Funcionarios::SESSION]);
		}

		return $funcionarios;
	}

	public static function checkLogin($perfilPermitido = null)
	{
		if (
			!isset($_SESSION[self::SESSION]) ||
			!isset($_SESSION[self::SESSION]['id_usuario']) ||
			(int)$_SESSION[self::SESSION]['id_usuario'] <= 0
		) {
			// Não está logado
			return false;
		}

		// Se não exigir perfil específico, apenas estar logado já basta
		if ($perfilPermitido === null) {
			return true;
		}

		// Perfil do usuário logado
		$perfilUsuario = $_SESSION[self::SESSION]['perfil'] ?? null;

		if ($perfilUsuario === null) {
			return false;
		}

		// Permite um perfil ou vários
		if (is_array($perfilPermitido)) {
			return in_array($perfilUsuario, $perfilPermitido);
		}

		return $perfilUsuario === $perfilPermitido;
	}


	public static function login($cpf, $senha)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuario a INNER JOIN tb_funcionario b ON a.id_pessoa = b.id_pessoa WHERE a.cpf = :CPF", array(
			":CPF" => $cpf
		));

		if (count($results) === 0) {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}

		$data = $results[0];

		if (password_verify($senha, $data["senha"]) === true) {

			$user = new Funcionarios();

			$data['nome_funcionario'] = utf8_encode($data['nome_funcionario']);

			$user->setData($data);

			$_SESSION[Funcionarios::SESSION] = $user->getValues();

			return $user;
		} else {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}
	}

	public static function registerAccess(Funcionarios $funcionarios, string $acao)
	{
		try {
			$sql = new Sql();

			$sql->query(
				"INSERT INTO tb_userlogs
            (id_usuario, cpf_usuario, nome_funcionario, acao, ip, user_agent, created_at)
            VALUES
            (:id_usuario, :cpf_usuario, :nome_funcionario, :acao, :ip, :user_agent, NOW())",
				[
					":id_usuario"       => $funcionarios->getid_usuario(),
					":cpf_usuario"      => $funcionarios->getcpf(),
					":nome_funcionario" => $funcionarios->getnome_funcionario(),
					":acao"             => $acao, // ✅ CAMPO OBRIGATÓRIO
					":ip"               => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
					":user_agent"       => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN'
				]
			);
		} catch (\Exception $e) {
			var_dump($e->getMessage());
			exit;
		}
	}


	public static function verifyLogin($perfilPermitido = null)
	{
		if (!self::checkLogin($perfilPermitido)) {

			// Se não estiver logado
			if (!isset($_SESSION[self::SESSION]['id_usuario'])) {
				header("Location: /login");
				exit;
			}

			// Está logado, mas não tem permissão
			header("Location: /acesso-negado");
			exit;
		}
	}


	public static function logout()
	{

		$_SESSION[Funcionarios::SESSION] = NULL;
		session_destroy();
	}

	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuario a INNER JOIN tb_funcionario b USING(id_pessoa) ORDER BY b.nome_funcionario");
	}


	public function save()
	{
		$sql = new Sql();

		// Chama a procedure passando todos os parâmetros necessários
		$results = $sql->select(
			"CALL sp_funcionario_usuario_save(
        :nome_funcionario,
        :email,
        :nrphone,
        :cpf,
        :senha,
        :perfil
    )",
			[
				":nome_funcionario" => utf8_decode($this->getnome_funcionario()),
				":email"            => $this->getemail(),
				":nrphone"          => $this->getnrphone(),
				":cpf"              => $this->getcpf(),
				":senha"            => Funcionarios::getPasswordHash(trim($this->getsenha())),
				":perfil"           => $this->getperfil()
			]
		);


		// die(var_dump($this->getnome_funcionario(), $this->getlogin(), $this->getpassword(), $this->getemail(), $this->getnrphone(), $this->getinadmin()));
		// exit();

		// Verifica se há resultados e define os dados
		if (isset($results[0])) {
			$this->setData($results[0]);
		}
	}

	public static function validaCPF($cpf)
	{
		// Remove caracteres não numéricos
		$cpf = preg_replace('/[^0-9]/', '', $cpf);

		// Verifica tamanho
		if (strlen($cpf) != 11) {
			return false;
		}

		// Elimina CPFs inválidos conhecidos
		if (preg_match('/(\d)\1{10}/', $cpf)) {
			return false;
		}

		// Valida dígitos verificadores
		for ($t = 9; $t < 11; $t++) {
			$d = 0;
			for ($c = 0; $c < $t; $c++) {
				$d += $cpf[$c] * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf[$c] != $d) {
				return false;
			}
		}

		return true;
	}

	public static function validaTelefone($telefone)
	{
		// Remove tudo que não for número
		$telefone = preg_replace('/\D/', '', $telefone);

		// Telefones no Brasil: DDD (2 dígitos) + número (8 ou 9 dígitos)
		if (strlen($telefone) < 10 || strlen($telefone) > 11) {
			return false;
		}

		// Opcional: validar se DDD está entre 11 e 99
		$ddd = substr($telefone, 0, 2);
		if ($ddd < 11 || $ddd > 99) {
			return false;
		}

		// Opcional: validar se o número não é só dígitos repetidos
		if (preg_match('/(\d)\1{7,10}/', $telefone)) {
			return false;
		}

		return true;
	}

	public function get($id_usuario)
	{
		$sql = new Sql();

		$results = $sql->select(
			"SELECT *
         FROM tb_usuario a
         INNER JOIN tb_funcionario b USING(id_pessoa)
         WHERE a.id_usuario = :id_usuario",
			[
				":id_usuario" => $id_usuario
			]
		);

		if (count($results) === 0) {
			throw new \Exception("Funcionário não encontrado para este usuário.");
		}

		$data = $results[0];

		$data['nome_funcionario'] = utf8_encode($data['nome_funcionario']);

		$this->setData($data);
	}


	public function update()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_update(:id_usuario, :nome_funcionario, :cpf, :senha, :email, :nrphone, :inadmin)", array(
			":id_usuario" => $this->getid_usuario(),
			":nome_funcionario" => utf8_decode($this->getnome_funcionario()),
			":cpf" => $this->getcpf(),
			":senha" => Funcionarios::getPasswordHash($this->getsenha()),
			":email" => $this->getemail(),
			":nrphone" => $this->getnrphone(),
			":inadmin" => $this->getinadmin()
		));

		$this->setData($results[0]);
	}

	public function delete()
	{

		$sql = new Sql();

		$sql->query("CALL sp_users_delete(:id_usuario)", array(
			":id_usuario" => $this->getid_usuario()
		));
	}

	public function setPassword($senha)
	{

		$sql = new Sql();

		$sql->query("UPDATE tb_usuario SET senha = :senha WHERE id_usuario = :id_usuario", array(
			":senha" => $senha,
			":id_usuario" => $this->getid_usuario()
		));
	}

	public static function getPasswordHash($senha)
	{

		return password_hash($senha, PASSWORD_DEFAULT, [
			'cost' => 12
		]);
	}
public static function setError($msg)
	{

		$_SESSION[Funcionarios::ERROR] = $msg;
	}

	public static function getError()
	{

		$msg = (isset($_SESSION[Funcionarios::ERROR]) && $_SESSION[Funcionarios::ERROR]) ? $_SESSION[Funcionarios::ERROR] : '';

		Funcionarios::clearError();

		return $msg;
	}

	public static function clearError()
	{

		$_SESSION[Funcionarios::ERROR] = NULL;
	}

	public static function setSuccess($msg)
	{

		$_SESSION[Funcionarios::SUCCESS] = $msg;
	}

	public static function getSuccess()
	{

		$msg = (isset($_SESSION[Funcionarios::SUCCESS]) && $_SESSION[Funcionarios::SUCCESS]) ? $_SESSION[Funcionarios::SUCCESS] : '';

		Funcionarios::clearSuccess();

		return $msg;
	}

	public static function clearSuccess()
	{

		$_SESSION[Funcionarios::SUCCESS] = NULL;
	}

	public static function setErrorRegister($msg)
	{

		$_SESSION[Funcionarios::ERROR_REGISTER] = $msg;
	}

	public static function getErrorRegister()
	{

		$msg = (isset($_SESSION[Funcionarios::ERROR_REGISTER]) && $_SESSION[Funcionarios::ERROR_REGISTER]) ? $_SESSION[Funcionarios::ERROR_REGISTER] : '';

		Funcionarios::clearErrorRegister();

		return $msg;
	}

	public static function clearErrorRegister()
	{

		$_SESSION[Funcionarios::ERROR_REGISTER] = NULL;
	}

	public static function checkLoginExist($cpf)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuario WHERE cpf = :cpf", [
			':cpf' => $cpf
		]);

		return (count($results) > 0);
	}

	public static function checkCpfExists($cpf)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_usuario WHERE cpf = :cpf", [
			":cpf" => $cpf
		]);
		return (count($results) > 0);
	}

	public static function checkEmailExists($email)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_funcionario WHERE email = :email", [
			":email" => $email
		]);
		return (count($results) > 0);
	}

	public static function checkPhoneExists($nrphone)
	{
		// Remove caracteres não numéricos para padronizar
		$telefone = preg_replace('/\D/', '', $nrphone);

		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_funcionario WHERE nrphone = :nrphone", [
			":nrphone" => $telefone
		]);
		return (count($results) > 0);
	}

}


