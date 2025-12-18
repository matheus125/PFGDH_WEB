<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \PDOException;


class Passageiros extends Model
{
    const SESSION = "Passageiros";
    const ERROR = "UserError";
    const ERROR_REGISTER = "UserErrorRegister";
    const SUCCESS = "UserSucesss";


    public static function getFromSession()
    {

        $user = new User();

        if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0) {

            $user->setData($_SESSION[User::SESSION]);
        }

        return $user;
    }

    public static function lista_passageiros()
    {

        $sql = new Sql();

        return $sql->select("CALL sp_listar_passageiros();");
    }

    public function get($id_passageiros)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_passageiros WHERE id_passageiros = :id", array(
            ":id" => $id_passageiros
        ));

        if (count($results) > 0) {
            $data = $results[0];
            $data['nome_passageiros'] = utf8_encode($data['nome_passageiros']);
            $this->setData($data);
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

    public static function checkLogin($inadmin = true)
    {

        if (
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0
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

    public function salvar_orçamentos()
    {
        if (!filter_var($this->getemail(), FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email inválido. Por favor, informe um endereço válido.");
        }

        try {
            $sql = new Sql();

            // Se o ID do passageiro já existe, vai fazer UPDATE
            $id_passageiro = $this->getid_passageiros();
            if (empty($id_passageiro) || $id_passageiro == 0) {
                $id_passageiro = null; // força INSERT
            }

            // Garante que o orcamento_id seja NULL se estiver vazio
            $orcamento_id = $this->getorcamento_id();
            if (empty($orcamento_id)) {
                $orcamento_id = null;
            }

            $results = $sql->select("CALL sp_salvar_passageiro(
            :id_passageiros,
            :orcamento_id,
            :nome_passageiros,
            :assento,
            :valor,
            :telefone,
            :email
        )", array(
                ":id_passageiros"   => $id_passageiro,
                ":orcamento_id"     => $orcamento_id,
                ":nome_passageiros" => $this->getnome_passageiros(),
                ":assento"          => $this->getassento(),
                ":valor"            => $this->getvalor(),
                ":telefone"         => $this->gettelefone(),
                ":email"            => $this->getemail()
            ));

            if (count($results) > 0) {
                $this->setData($results[0]);
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000 || $e->getCode() == '45000') {
                throw new \Exception("Telefone ou Email já cadastrados. Por favor, use outro.");
            } else {
                throw new \Exception("Erro ao salvar no banco: " . $e->getMessage());
            }
        }
    }

    public static function setError($msg)
    {

        $_SESSION[Colaboradores::ERROR] = $msg;
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
}
