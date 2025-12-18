<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class User extends Model
{

	const SESSION = "User";
	const SECRET = "HcodePhp7_Secret";
	const SECRET_IV = "HcodePhp7_Secret_IV";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSucesss";

	public static function getFromSession()
	{

		$user = new User();

		if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['id_usuario'] > 0) {

			$user->setData($_SESSION[User::SESSION]);
		}

		return $user;
	}

	public static function checkLogin($inadmin = true)
	{

		if (
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["id_usuario"] > 0
		) {
			//Não está logado
			return false;
		} else {

			if ($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true) {

				return true;
			} else if ($inadmin === false) {

				return true;
			} else {

				return false;
			}
		}
	}

	public static function login($login, $senha)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuario a INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.login = :LOGIN", array(
			":LOGIN" => $login
		));

		if (count($results) === 0) {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}

		$data = $results[0];

		if (password_verify($senha, $data["senha"]) === true) {

			$user = new User();

			$data['nome_pessoa'] = utf8_encode($data['nome_pessoa']);

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;
		} else {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}
	}

	public static function verifyLogin($inadmin = true)
	{

		if (!User::checkLogin($inadmin)) {

			if ($inadmin) {
				header("Location: /admin/login");
			} else {
				header("Location: /login");
			}
			exit;
		}
	}

	public static function logout()
	{

		$_SESSION[User::SESSION] = NULL;
	}

	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuario a INNER JOIN tb_pessoa b USING(id_pessoa) ORDER BY b.nome_pessoa");
	}

	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_user_save(:nome_pessoa, :login, :senha, :email, :nrphone, :inadmin)", array(
			":nome_pessoa" => utf8_decode($this->getnome_pessoa()),
			":login" => $this->getlogin(),
			":senha" => User::getPasswordHash($this->getsenha()),
			":email" => $this->getemail(),
			":nrphone" => $this->getnrphone(),
			":inadmin" => $this->getinadmin()
		));

		$this->setData($results[0]);
	}

	public function get($id_usuario)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuario a INNER JOIN tb_pessoa b USING(id_pessoa) WHERE a.id_usuario = :id_usuario", array(
			":id_usuario" => $id_usuario
		));

		$data = $results[0];

		$data['nome_pessoa'] = utf8_encode($data['nome_pessoa']);


		$this->setData($data);
	}

	public function update()
	{
		$sql = new Sql();

		$results = $sql->select(
			"
        CALL sp_user_update(
            :id_usuario,
            :nome_pessoa,
            :login,
            :senha,
            :email,
            :nrphone,
            :inadmin
        )",
			array(
				":id_usuario"  => $this->getid_usuario(),
				":nome_pessoa" => utf8_decode($this->getnome_pessoa()),
				":login"    => $this->getlogin(),
				":senha"       => User::getPasswordHash($this->getsenha()),
				":email"    => $this->getemail(),
				":nrphone"     => $this->getnrphone(),
				":inadmin"     => $this->getinadmin()
			)
		);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}
	}


	public function delete()
	{

		$sql = new Sql();

		$sql->query("CALL sp_user_delete(:id_usuario)", array(
			":id_usuario" => $this->getid_usuario()
		));
	}

	public static function getForgot($email, $inadmin = true)
	{

		$sql = new Sql();

		$results = $sql->select("
			SELECT *
			FROM tb_pessoa a
			INNER JOIN tb_usuario b USING(id_pessoa)
			WHERE a.desemail = :email;
		", array(
			":email" => $email
		));

		if (count($results) === 0) {

			throw new \Exception("Não foi possível recuperar a senha.");
		} else {

			$data = $results[0];

			$results2 = $sql->select("CALL sp_userssenhasrecoveries_create(:id_usuario, :desip)", array(
				":id_usuario" => $data['id_usuario'],
				":desip" => $_SERVER['REMOTE_ADDR']
			));

			if (count($results2) === 0) {

				throw new \Exception("Não foi possível recuperar a senha.");
			} else {

				$dataRecovery = $results2[0];

				$code = openssl_encrypt($dataRecovery['idrecovery'], 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

				$code = base64_encode($code);

				if ($inadmin === true) {

					$link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";
				} else {

					$link = "http://www.hcodecommerce.com.br/forgot/reset?code=$code";
				}

				$mailer = new Mailer($data['desemail'], $data['nome_pessoa'], "Redefinir senha da Hcode Store", "forgot", array(
					"name" => $data['nome_pessoa'],
					"link" => $link
				));

				$mailer->send();

				return $link;
			}
		}
	}

	public static function validForgotDecrypt($code)
	{

		$code = base64_decode($code);

		$idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

		$sql = new Sql();

		$results = $sql->select("
			SELECT *
			FROM tb_usuariosenhasrecoveries a
			INNER JOIN tb_usuario b USING(id_usuario)
			INNER JOIN tb_pessoa c USING(id_pessoa)
			WHERE
				a.idrecovery = :idrecovery
				AND
				a.dtrecovery IS NULL
				AND
				DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
		", array(
			":idrecovery" => $idrecovery
		));

		if (count($results) === 0) {
			throw new \Exception("Não foi possível recuperar a senha.");
		} else {

			return $results[0];
		}
	}

	public static function setFogotUsed($idrecovery)
	{

		$sql = new Sql();

		$sql->query("UPDATE tb_usuariosenhasrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
			":idrecovery" => $idrecovery
		));
	}

	public function setsenha($senha)
	{

		$sql = new Sql();

		$sql->query("UPDATE tb_usuario SET senha = :senha WHERE id_usuario = :id_usuario", array(
			":senha" => $senha,
			":id_usuario" => $this->getid_usuario()
		));
	}

	public static function setError($msg)
	{

		$_SESSION[User::ERROR] = $msg;
	}

	public static function getError()
	{

		$msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';

		User::clearError();

		return $msg;
	}

	public static function clearError()
	{

		$_SESSION[User::ERROR] = NULL;
	}

	public static function setSuccess($msg)
	{

		$_SESSION[User::SUCCESS] = $msg;
	}

	public static function getSuccess()
	{

		$msg = (isset($_SESSION[User::SUCCESS]) && $_SESSION[User::SUCCESS]) ? $_SESSION[User::SUCCESS] : '';

		User::clearSuccess();

		return $msg;
	}

	public static function clearSuccess()
	{

		$_SESSION[User::SUCCESS] = NULL;
	}

	public static function setErrorRegister($msg)
	{

		$_SESSION[User::ERROR_REGISTER] = $msg;
	}

	public static function getErrorRegister()
	{

		$msg = (isset($_SESSION[User::ERROR_REGISTER]) && $_SESSION[User::ERROR_REGISTER]) ? $_SESSION[User::ERROR_REGISTER] : '';

		User::clearErrorRegister();

		return $msg;
	}

	public static function clearErrorRegister()
	{

		$_SESSION[User::ERROR_REGISTER] = NULL;
	}

	public static function checkLoginExist($login)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuario WHERE deslogin = :deslogin", [
			':deslogin' => $login
		]);

		return (count($results) > 0);
	}

	public static function getPasswordHash($senha)
	{

		return password_hash($senha, PASSWORD_DEFAULT, [
			'cost' => 12
		]);
	}

	public function getOrders()
	{

		$sql = new Sql();

		$results = $sql->select("
			SELECT * 
			FROM tb_orders a 
			INNER JOIN tb_ordersstatus b USING(idstatus) 
			INNER JOIN tb_carts c USING(idcart)
			INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario
			INNER JOIN tb_addresses e USING(idaddress)
			INNER JOIN tb_pessoa f ON f.id_pessoa = d.id_pessoa
			WHERE a.id_usuario = :id_usuario
		", [
			':id_usuario' => $this->getid_usuario()
		]);

		return $results;
	}

	public static function getPage($page = 1, $itemsPerPage = 10)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_usuario a 
			INNER JOIN tb_pessoa b USING(id_pessoa) 
			ORDER BY b.nome_pessoa
			LIMIT $start, $itemsPerPage;
		");

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data' => $results,
			'total' => (int)$resultTotal[0]["nrtotal"],
			'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}

	public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_usuario a 
			INNER JOIN tb_pessoa b USING(id_pessoa)
			WHERE b.nome_pessoa LIKE :search OR b.desemail = :search OR a.deslogin LIKE :search
			ORDER BY b.nome_pessoa
			LIMIT $start, $itemsPerPage;
		", [
			':search' => '%' . $search . '%'
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data' => $results,
			'total' => (int)$resultTotal[0]["nrtotal"],
			'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}
}
