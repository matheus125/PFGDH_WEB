<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Usuarios extends Model
{
    const SESSION = "Usuarios";
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

    public static function listar_usuarios()
    {
        $sql = new Sql();

        try {
            // Chama a procedure que retorna os titulares + dados agregados
            return $sql->select("CALL sp_consultar_ficha_cadastro()");
        } catch (\Exception $e) {
            // Log de erro e retorno seguro
            error_log("Erro ao listar usuários: " . $e->getMessage());
            return [];
        }
    }

    public static function total_usuarios()
    {
        $sql = new Sql();
        $result = $sql->select("SELECT COUNT(*) AS total FROM tb_titular");
        return $result[0]['total'];
    }

    public static function total_dependentes()
    {
        $sql = new Sql();
        $result = $sql->select("SELECT COUNT(*) AS total FROM tb_dependentes");
        return $result[0]['total'];
    }

    public static function listar_usuarios_dependentes()
    {
        $sql = new Sql();

        try {
            // Chama a procedure que retorna os titulares + dados agregados
            return $sql->select("CALL sp_consultar_ficha_cadastro_dependentes()");
        } catch (\Exception $e) {
            // Log de erro e retorno seguro
            error_log("Erro ao listar usuários: " . $e->getMessage());
            return [];
        }
    }

    public static function buscar_dependente($id)
    {
        $sql = new Sql();
        $dep = $sql->select("SELECT * FROM tb_dependentes WHERE id_dependente = :id", [
            ":id" => $id
        ]);
    }

    // =====================================================================
    // BUSCAR UM USUÁRIO PELO ID
    // =====================================================================
    public static function getById($id)
    {
        $sql = new Sql();

        try {
            // Corrigido: o campo na tabela é "id", não "id_titular"
            $result = $sql->select("
                SELECT 
                    id,
                    nome ,
                    telefone,
                    email,
                    rg,
                    cpf,
                    data_nascimento,
                    estado_civil,
                    grau_escolaridade,
                    cidade,
                    bairro,
                    endereco,
                    numero,
                    trabalha_atualmente
                FROM tb_titular 
                WHERE id = :id
            ", [
                ":id" => $id
            ]);

            if (count($result) === 0) {
                return null;
            }

            $titular = $result[0];

            // Dependentes
            $titular["dependentes"] = $sql->select("
                SELECT * FROM tb_dependentes WHERE id_titular = :id
            ", [":id" => $id]);

            return $titular;
        } catch (\Exception $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return ["erro_sql" => $e->getMessage()];
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


    public function saveFichaCadastro()
    {
        $sql = new Sql();

        if (empty($this->getNome())) {
            throw new \Exception("O campo 'nome' é obrigatório e não pode estar vazio. ERRO: TITULAR");
        }

        // Converte checkboxes/múltiplos valores em JSON

        $tipoBeneficio = json_encode($this->getTipoBeneficio() ?: []);

        $results = $sql->select("CALL sp_salvar_ficha_cadastro(
        :nome, :endereco, :numero, :complemento, :bairro, :cidade, :cep, :uf,
        :telefone, :email, :rg, :cpf, :data_nascimento, :estado_civil, :outros_estado_civil,
        :grau_escolaridade, :estuda_atualmente, :profissao, :trabalha_atualmente,
        :tipo_registro, :recebe_beneficio_governo, :tipo_beneficio, :outros_beneficio
    )", array(
            ":nome" => $this->getNome(),
            ":endereco" => $this->getEndereco(),
            ":numero" => $this->getNumero(),
            ":complemento" => $this->getComplemento(),
            ":bairro" => $this->getBairro(),
            ":cidade" => $this->getCidade(),
            ":cep" => $this->getCep(),
            ":uf" => $this->getUf(),
            ":telefone" => $this->getTelefone(),
            ":email" => $this->getEmail(),
            ":rg" => $this->getRg(),
            ":cpf" => $this->getCpf(),
            ":data_nascimento" => $this->getDataNascimento(),
            ":estado_civil" => $this->getEstadoCivil() ?: 'Outros',
            ":outros_estado_civil" => $this->getOutrosEstadoCivil(),
            ":grau_escolaridade" => $this->getGrauEscolaridade(),
            ":estuda_atualmente" => $this->getEstudaAtualmente() ?: 'Não',
            ":profissao" => $this->getProfissao(),
            ":trabalha_atualmente" => $this->getTrabalhaAtualmente() ?: 'Não',
            ":tipo_registro" => $this->getTipoRegistro() ?: 'Sem Registro',
            ":recebe_beneficio_governo" => $this->getRecebeBeneficioGoverno() ?: 'Não',
            ":tipo_beneficio" => json_encode($this->getTipoBeneficio(), JSON_UNESCAPED_UNICODE),
            ":outros_beneficio" => $this->getOutrosBeneficio()
        ));

        if (isset($results[0]['id'])) {
            $this->setId($results[0]['id']);
        }

        return array(
            "status" => "success",
            "message" => "Ficha salva com sucesso!",
            "id" => $this->getId()
        );
    }

    public function setDataDependente($data)
    {
        $this->setIdDependente($data['id_dependente'] ?? 0);

        // obrigatório
        $this->setIdTitular($data['id_titular']);

        // campos do front
        $this->setNomeDependente($data['nome_dependente'] ?? null);
        $this->setParentesco($data['parentesco'] ?? null);
        $this->setIdade($data['idade'] ?? null);
        $this->setEscolaridade($data['escolaridade'] ?? null);

        // novos campos
        $this->setPossuiDeficiencia($data['possui_deficiencia'] ?? 'Não');
        $this->setTipoDeficiencia($data['tipo_deficiencia'] ?? null);
    }




    public function saveDependente()
    {
        $sql = new Sql();

        // Validação simples
        if (empty($this->getNomeDependente())) {
            throw new \Exception("O campo 'nome' é obrigatório e não pode estar vazio. ERRO: DEPENDENTE");
        }

        // Chama a procedure
        $results = $sql->select("CALL sp_salvar_dependente(
        :id_dependente,
        :id_titular,
        :nome_dependente,
        :parentesco,
        :idade,
        :escolaridade,
        :estuda,
        :trabalha,
        :ocupacao,
        :deficiencia,
        :tipo_deficiencia,
        :outros
    )", array(
            ":id_dependente" => $this->getIdDependente() ?: 0,
            ":id_titular" => $this->getIdTitular(),
            ":nome_dependente" => $this->getNomeDependente(),
            ":parentesco" => $this->getParentesco(),
            ":idade" => $this->getIdade(),
            ":escolaridade" => $this->getEscolaridade(),
            ":estuda" => $this->getEstuda() ?: 'Não',
            ":trabalha" => $this->getTrabalha() ?: 'Não',
            ":ocupacao" => $this->getOcupacao(),
            ":deficiencia" => $this->getDeficiencia() ?: 'Não',
            ":tipo_deficiencia" => $this->getTipoDeficiencia(),
            ":outros" => $this->getOutros()
        ));

        // Se a procedure retornar um ID (padrão de insert)
        if (isset($results[0]['id_dependente'])) {
            $this->setIdDependente($results[0]['id_dependente']);
        }

        return array(
            "status" => "success",
            "message" => "Dependente salvo com sucesso!",
            "id_dependente" => $this->getIdDependente()
        );
    }


    public function saveDadosSocioeconomicos()
    {
        $sql = new Sql();

        // Validação simples
        if (empty($this->getIdTitular())) {
            throw new \Exception("O campo 'id_titular' é obrigatório. ERRO: DADOS SOCIOECONÔMICOS");
        }

        // Chamada da procedure atualizada
        $results = $sql->select("CALL sp_insert_dados_socioeconomicos(
        :id_titular,
        :renda_fixa_70,
        :renda_acima_70,
        :renda_1_salario,
        :renda_2_salarios,
        :renda_3_salarios,
        :renda_outro_valor,
        :principal_fonte_renda,
        :trabalhador_formal,
        :trabalhador_informal,
        :autonomo,
        :outros_quais_renda,
        :recebimento_agua_tratada,
        :recebimento_agua_filtrada,
        :recebimento_agua_poco,
        :possui_fossa,
        :lixo_coleta,
        :lixo_queimado,
        :lixo_jogado_rua,
        :lixo_outros,
        :casa_propria,
        :casa_cedida,
        :casa_alugada,
        :casa_madeira,
        :casa_alvenaria,
        :casa_palafita,
        :casa_enchimento,
        :casa_estrutura_outros,
        :participa_comunidade,
        :comunidade_qual,
        :associacoes,
        :cooperativas,
        :centro_comunitario,
        :outras_participacoes,
        :saude,
        :alimentacao,
        :documentacao,
        :moradia,
        :escola_votacao,
        :outras_informacoes
    )", array(
            ":id_titular" => $this->getIdTitular(),
            ":renda_fixa_70" => $this->getRendaFixa70(),
            ":renda_acima_70" => $this->getRendaAcima70(),
            ":renda_1_salario" => $this->getRenda1Salario(),
            ":renda_2_salarios" => $this->getRenda2Salarios(),
            ":renda_3_salarios" => $this->getRenda3Salarios(),
            ":renda_outro_valor" => $this->getRendaOutroValor(),
            ":principal_fonte_renda" => $this->getPrincipalFonteRenda(),
            ":trabalhador_formal" => $this->getTrabalhadorFormal(),
            ":trabalhador_informal" => $this->getTrabalhadorInformal(),
            ":autonomo" => $this->getAutonomo(),
            ":outros_quais_renda" => $this->getOutrosQuaisRenda(),
            ":recebimento_agua_tratada" => $this->getAguaTratada(),
            ":recebimento_agua_filtrada" => $this->getAguaFiltrada(),
            ":recebimento_agua_poco" => $this->getAguaPoco(),
            ":possui_fossa" => $this->getPossuiFossa(),
            ":lixo_coleta" => $this->getLixoColeta(),
            ":lixo_queimado" => $this->getLixoQueimado(),
            ":lixo_jogado_rua" => $this->getLixoJogadoRua(),
            ":lixo_outros" => $this->getLixoOutros(),
            ":casa_propria" => $this->getCasaPropria(),
            ":casa_cedida" => $this->getCasaCedida(),
            ":casa_alugada" => $this->getCasaAlugada(),
            ":casa_madeira" => $this->getCasaMadeira(),
            ":casa_alvenaria" => $this->getCasaAlvenaria(),
            ":casa_palafita" => $this->getCasaPalafita(),
            ":casa_enchimento" => $this->getCasaEnchimento(),
            ":casa_estrutura_outros" => $this->getCasaEstruturaOutros(),
            ":participa_comunidade" => $this->getParticipaComunidade(),
            ":comunidade_qual" => $this->getComunidadeQual(),
            ":associacoes" => $this->getAssociacoes(),
            ":cooperativas" => $this->getCooperativas(),
            ":centro_comunitario" => $this->getCentroComunitario(),
            ":outras_participacoes" => $this->getOutrasParticipacoes(),
            ":saude" => $this->getSaude(),
            ":alimentacao" => $this->getAlimentacao(),
            ":documentacao" => $this->getDocumentacao(),
            ":moradia" => $this->getMoradia(),
            ":escola_votacao" => $this->getEscolaVotacao(),
            ":outras_informacoes" => $this->getOutrasInformacoes()
        ));

        return array(
            "status" => "success",
            "message" => "Dados socioeconômicos salvos com sucesso!"
        );
    }




    public function upload()
    {
        if (!isset($_POST['id_titular'])) {
            throw new Exception("ID do titular não informado.");
        }

        $id_titular = intval($_POST['id_titular']);

        if (!isset($_FILES['documentos'])) {
            throw new Exception("Nenhum arquivo enviado.");
        }

        $uploadDir = "uploads/documentos/$id_titular/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $files = $_FILES['documentos'];

        $resultados = [];

        for ($i = 0; $i < count($files['name']); $i++) {

            $nome = $files['name'][$i];
            $tmp = $files['tmp_name'][$i];
            $tipo = $files['type'][$i];
            $tamanho = $files['size'][$i];

            $novoNome = uniqid() . "_" . $nome;
            $caminho = $uploadDir . $novoNome;

            if (move_uploaded_file($tmp, $caminho)) {
                $this->registrarDocumento($id_titular, $nome, $caminho, $tipo, $tamanho);
                $resultados[] = $nome;
            }
        }

        return [
            "status" => "success",
            "message" => "Arquivos enviados.",
            "arquivos" => $resultados
        ];
    }

    private function registrarDocumento($id, $nome, $caminho, $tipo, $tamanho)
    {
        $sql = new Sql();
        $sql->query("
            INSERT INTO tb_documentos_titular
            (id_titular, nome_arquivo, caminho_arquivo, tipo_arquivo, tamanho_arquivo)
            VALUES (:id, :nome, :caminho, :tipo, :tamanho)
        ", [
            ":id" => $id,
            ":nome" => $nome,
            ":caminho" => $caminho,
            ":tipo" => $tipo,
            ":tamanho" => $tamanho
        ]);
    }



    public static function setError($msg)
    {

        $_SESSION[Usuarios::ERROR] = $msg;
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
