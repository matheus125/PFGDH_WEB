<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Clientes extends Model
{
    const SESSION = "User";
    const ERROR = "UserError";
    const ERROR_REGISTER = "UserErrorRegister";
    const SUCCESS = "UserSucesss";

    public static function getFromSession()
    {

        $clientes = new Clientes();

        if (isset($_SESSION[Clientes::SESSION]) && (int)$_SESSION[Clientes::SESSION]['iduser'] > 0) {

            $clientes->setData($_SESSION[Clientes::SESSION]);
        }

        return $clientes;
    }

    public static function checkLogin($inadmin = true)
    {

        if (
            !isset($_SESSION[Clientes::SESSION])
            ||
            !$_SESSION[Clientes::SESSION]
            ||
            !(int)$_SESSION[Clientes::SESSION]["iduser"] > 0
        ) {
            //Não está logado
            return false;
        } else {

            if ($inadmin === true && (bool)$_SESSION[Clientes::SESSION]['inadmin'] === true) {

                return true;
            } else if ($inadmin === false) {

                return true;
            } else {

                return false;
            }
        }
    }
    public static function lista_titulares()
    {

        $sql = new Sql();

        return $sql->select("select c.id, c.nome_completo, c.nome_social, c.cor_cliente, c.nome_mae, c.telefone, 
               	c.data_nascimento, c.idade_cliente, c.genero_cliente, c.estado_civil, c.rg, c.cpf, c.nis,c.status_cliente,
                	e.id, e.cep, e.bairro, e.rua, e.numero, e.referencia, e.nacionalidade, e.naturalidade, e.cidade, e.tempo_moradia_cliente
                	from tb_titular c inner join tb_endereco e on c.id_endereco = e.id order by nome_completo;");
    }

    public static function verifyLogin($inadmin = true)
    {

        if (!Clientes::checkLogin($inadmin)) {

            if ($inadmin) {
                header("Location: /admin/login");
            } else {
                header("Location: /login");
            }
            exit;
        }
    }



    public function salvar_cliente_titular()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_salvar_titular(
        :nome_completo,
        :nome_social,
        :cor_cliente,
        :nome_mae,
        :telefone,
        :data_nascimento,
        :idade_cliente,
        :genero_cliente,
        :estado_civil,
        :rg,
        :cpf,
        :nis,
        :status_cliente,
        :cep,
        :bairro,
        :rua,
        :numero,
        :referencia,
        :nacionalidade,
        :naturalidade,
        :cidade,
        :tempo_moradia_cliente
    )", array(
            ":nome_completo"          => $this->getnome_completo(),
            ":nome_social"            => $this->getnome_social(),
            ":cor_cliente"            => $this->getcor_cliente(),
            ":nome_mae"               => $this->getnome_mae(),
            ":telefone"               => $this->gettelefone(),
            ":data_nascimento"        => $this->getdata_nascimento(),
            ":idade_cliente"          => $this->getidade_cliente(),
            ":genero_cliente"         => $this->getgenero_cliente(),
            ":estado_civil"           => $this->getestado_civil(),
            ":rg"                     => $this->getrg(),
            ":cpf"                    => $this->getcpf(),
            ":nis"                    => $this->getnis(),
            ":status_cliente"         => $this->getstatus_cliente(),
            ":cep"                    => $this->getcep(),
            ":bairro"                 => $this->getbairro(),
            ":rua"                    => $this->getrua(),
            ":numero"                 => $this->getnumero(),
            ":referencia"             => $this->getreferencia(),
            ":nacionalidade"          => $this->getnacionalidade(),
            ":naturalidade"           => $this->getnaturalidade(),
            ":cidade"                 => $this->getcidade(),
            ":tempo_moradia_cliente"  => $this->gettempo_moradia_cliente()
        ));

        if (isset($results[0])) {
            $this->setData($results[0]);
        }
    }

    public function get($iduser)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_usuario a INNER JOIN tb_funcionario b USING(idperson) WHERE a.iduser = :iduser", array(
            ":iduser" => $iduser
        ));

        $data = $results[0];

        $data['person'] = utf8_encode($data['person']);


        $this->setData($data);
    }

    public static function setError($msg)
    {

        $_SESSION[Clientes::ERROR] = $msg;
    }

    public static function getError()
    {

        $msg = (isset($_SESSION[Clientes::ERROR]) && $_SESSION[Clientes::ERROR]) ? $_SESSION[Clientes::ERROR] : '';

        Clientes::clearError();

        return $msg;
    }

    public static function clearError()
    {

        $_SESSION[Clientes::ERROR] = NULL;
    }

    public static function setSuccess($msg)
    {

        $_SESSION[Clientes::SUCCESS] = $msg;
    }

    public static function getSuccess()
    {

        $msg = (isset($_SESSION[Clientes::SUCCESS]) && $_SESSION[Clientes::SUCCESS]) ? $_SESSION[Clientes::SUCCESS] : '';

        Clientes::clearSuccess();

        return $msg;
    }

    public static function clearSuccess()
    {

        $_SESSION[Clientes::SUCCESS] = NULL;
    }

    public static function setErrorRegister($msg)
    {

        $_SESSION[Clientes::ERROR_REGISTER] = $msg;
    }

    public static function getErrorRegister()
    {

        $msg = (isset($_SESSION[Clientes::ERROR_REGISTER]) && $_SESSION[Clientes::ERROR_REGISTER]) ? $_SESSION[Clientes::ERROR_REGISTER] : '';

        Clientes::clearErrorRegister();

        return $msg;
    }

    public static function clearErrorRegister()
    {

        $_SESSION[Clientes::ERROR_REGISTER] = NULL;
    }

    public static function getTotalTitulares()
    {
        $sql = new Sql();

        $result = $sql->select("SELECT COUNT(*) AS total FROM tb_titular");

        // se vier vazio, retorna 0
        return (count($result) > 0) ? (int)$result[0]['total'] : 0;

        
    }
}
