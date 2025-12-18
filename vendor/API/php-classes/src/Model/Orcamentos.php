<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Model\Exception;
use \PDOException;


class Orcamentos extends Model
{
    const SESSION = "Orcamentos";
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


    public static function lista_orçamentos()
    {
        try {
            $sql = new Sql();
            $results = $sql->select("CALL sp_listar_orcamentos()");

            return $results ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }


    public function get($id)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM orcamentos WHERE id = :id", array(
            ":id" => $id
        ));

        if (count($results) > 0) {
            $data = $results[0];

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

    public function salvar()
    {
        try {
            $sql = new Sql(); // sua classe que já encapsula o PDO

            $results = $sql->select("CALL sp_salvar_orcamento(
            :id,
            :localizador,
            :status,
            :data_emissao,
            :origem,
            :destino,
            :duracao,
            :escalas,
            :bagagem,
            :classe,
            :tarifa,
            :taxa,
            :tx_embarque
        )", [
                ":id"           => $this->getid() ?: null,
                ":localizador"  => $this->getlocalizador(),
                ":status"       => $this->getstatus(),
                ":data_emissao" => $this->getdata_emissao(),
                ":origem"       => $this->getorigem(),
                ":destino"      => $this->getdestino(),
                ":duracao"      => $this->getduracao(),
                ":escalas"      => $this->getescalas(),
                ":bagagem"      => $this->getbagagem(),
                ":classe"       => $this->getclasse(),
                ":tarifa"       => $this->gettarifa() ?: 0,
                ":taxa"         => $this->gettaxa() ?: 0,
                ":tx_embarque"  => $this->gettx_embarque() ?: 0
            ]);

            if (count($results) > 0) {
                $this->setData($results[0]);

                return [
                    "success" => true,
                    "message" => "Orçamento salvo com sucesso!",
                    "data"    => $results[0]
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Nenhum dado retornado após salvar o orçamento."
                ];
            }
        } catch (\PDOException $e) {
            // Erro de conexão ou execução no banco
            return [
                "success" => false,
                "message" => "Erro de banco de dados ao salvar orçamento: " . $e->getMessage()
            ];
        } catch (\Exception $e) {
            // Qualquer outro erro inesperado
            return [
                "success" => false,
                "message" => "Ocorreu um erro inesperado: " . $e->getMessage()
            ];
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
