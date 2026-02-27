CREATE DATABASE  IF NOT EXISTS `prato_cheio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `prato_cheio`;
-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: prato_cheio
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_access_denied`
--

DROP TABLE IF EXISTS `tb_access_denied`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_access_denied` (
  `id_access_denied` int NOT NULL AUTO_INCREMENT,
  `perfil` varchar(20) NOT NULL,
  `rota` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_access_denied`),
  KEY `idx_perfil` (`perfil`),
  KEY `idx_rota` (`rota`),
  KEY `idx_ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_access_denied`
--

LOCK TABLES `tb_access_denied` WRITE;
/*!40000 ALTER TABLE `tb_access_denied` DISABLE KEYS */;
INSERT INTO `tb_access_denied` VALUES (1,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:04'),(2,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:17'),(3,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:19'),(4,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:21'),(5,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:22'),(6,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:23');
/*!40000 ALTER TABLE `tb_access_denied` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_dependentes`
--

DROP TABLE IF EXISTS `tb_dependentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_dependentes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_titular` int DEFAULT NULL,
  `id_familia` int DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `idade` int DEFAULT NULL,
  `genero` enum('M','F','Outro') DEFAULT NULL,
  `dependencia_cliente` varchar(50) DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_dependente_titular` (`id_titular`),
  KEY `fk_dependente_familia` (`id_familia`),
  CONSTRAINT `fk_dependente_familia` FOREIGN KEY (`id_familia`) REFERENCES `tb_familia` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_dependente_titular` FOREIGN KEY (`id_titular`) REFERENCES `tb_titular` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_dependentes`
--

LOCK TABLES `tb_dependentes` WRITE;
/*!40000 ALTER TABLE `tb_dependentes` DISABLE KEYS */;
INSERT INTO `tb_dependentes` VALUES (73,331,NULL,'teste','','','2021-06-29',4,'F','Filho(a)','2026-01-19 20:23:30','2026-01-19 20:23:30'),(74,331,NULL,'BIANCA MOTA','','','2020-05-13',5,'F','Filho(a)','2026-01-19 20:25:29','2026-01-19 20:25:29'),(75,331,NULL,'aasdadadadasdaasd','','','2018-03-13',7,'F','Filho(a)','2026-01-19 20:26:25','2026-01-19 20:26:25'),(76,331,NULL,'fernado delas','','','1996-03-31',29,'M','Outro','2026-01-19 20:27:43','2026-01-19 20:27:43'),(77,331,NULL,'vasdadas','','','1231-03-12',794,'M','Filho(a)','2026-01-19 20:29:46','2026-01-19 20:29:46'),(78,331,NULL,'rara','','','1996-03-13',29,'M','Filho(a)','2026-01-19 20:30:24','2026-01-19 20:30:24'),(79,332,NULL,'Fernanda Filha','','','2021-03-31',4,'F','Filho(a)','2026-01-20 17:57:43','2026-01-20 17:57:43');
/*!40000 ALTER TABLE `tb_dependentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_endereco`
--

DROP TABLE IF EXISTS `tb_endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_endereco` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cep` varchar(20) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `nacionalidade` varchar(50) DEFAULT NULL,
  `naturalidade` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `tempo_moradia_anos` int DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=317 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_endereco`
--

LOCK TABLES `tb_endereco` WRITE;
/*!40000 ALTER TABLE `tb_endereco` DISABLE KEYS */;
INSERT INTO `tb_endereco` VALUES (315,'69044-236','Planalto','Rua Waldir Normando','170','','Brasil','Manaus','Manaus',2,'2026-01-19 20:19:15','2026-01-19 20:19:15'),(316,'69047-761','Redenção','Rua Waldir Normando','170','ASDASDASD','Brasil','Manaus','Manaus',2,'2026-01-20 17:56:54','2026-01-20 17:56:54');
/*!40000 ALTER TABLE `tb_endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_familia`
--

DROP TABLE IF EXISTS `tb_familia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_familia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_familia` varchar(100) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_familia`
--

LOCK TABLES `tb_familia` WRITE;
/*!40000 ALTER TABLE `tb_familia` DISABLE KEYS */;
INSERT INTO `tb_familia` VALUES (1,'Família MOTA','2026-01-19 20:19:15','2026-01-19 20:19:15'),(2,'Família BEIRA','2026-01-20 17:56:54','2026-01-20 17:56:54');
/*!40000 ALTER TABLE `tb_familia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_funcionario`
--

DROP TABLE IF EXISTS `tb_funcionario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_funcionario` (
  `id_pessoa` int NOT NULL AUTO_INCREMENT,
  `nome_funcionario` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `nrphone` varchar(20) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_pessoa`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nrphone` (`nrphone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_funcionario`
--

LOCK TABLES `tb_funcionario` WRITE;
/*!40000 ALTER TABLE `tb_funcionario` DISABLE KEYS */;
INSERT INTO `tb_funcionario` VALUES (1,'Matheus Mota da Silva','mmota350@gmail.com','(92) 981678846','2026-01-19 18:55:53',1),(2,'Raufe DE TODAS','raufe@delas.com.br','(90) 99009-0911','2026-01-19 19:19:34',0);
/*!40000 ALTER TABLE `tb_funcionario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_titular`
--

DROP TABLE IF EXISTS `tb_titular`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_titular` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_endereco` int DEFAULT NULL,
  `id_familia` int DEFAULT NULL,
  `nome_completo` varchar(100) DEFAULT NULL,
  `nome_social` varchar(100) DEFAULT NULL,
  `cor_cliente` varchar(50) DEFAULT NULL,
  `nome_mae` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `idade` int DEFAULT NULL,
  `genero` enum('M','F','Outro') DEFAULT NULL,
  `estado_civil` varchar(50) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `status_cliente` varchar(30) DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_titular_endereco` (`id_endereco`),
  KEY `fk_titular_familia` (`id_familia`),
  CONSTRAINT `fk_titular_endereco` FOREIGN KEY (`id_endereco`) REFERENCES `tb_endereco` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_titular_familia` FOREIGN KEY (`id_familia`) REFERENCES `tb_familia` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_titular`
--

LOCK TABLES `tb_titular` WRITE;
/*!40000 ALTER TABLE `tb_titular` DISABLE KEYS */;
INSERT INTO `tb_titular` VALUES (331,315,1,'MATHEUS MOTA DA SILVA','MATHEUS MOTA DA SILVA','PRETA','MARIA ELIETH MUNIZ DA MOTA','(92) 98841-1136','1996-03-13',29,'M','Casado(a)','022.903.862-00','022.903.862-00','02290286200','ATIVO','2026-01-19 20:19:15','2026-01-19 20:19:15'),(332,316,2,'FERNANDINHO BEIRA ','','Branco','Fernandinha Beira MAr','(92) 99999-9999','2006-11-07',19,'Outro','Solteiro(a)','702.422.323-26','702.422.323-26','702.422.323-26','ATIVO','2026-01-20 17:56:54','2026-01-20 17:56:54');
/*!40000 ALTER TABLE `tb_titular` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_userlogs`
--

DROP TABLE IF EXISTS `tb_userlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_userlogs` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `cpf_usuario` varchar(14) NOT NULL,
  `nome_funcionario` varchar(255) NOT NULL,
  `acao` varchar(50) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_userlogs_usuario` (`id_usuario`),
  KEY `idx_userlogs_cpf` (`cpf_usuario`),
  KEY `idx_userlogs_acao` (`acao`),
  CONSTRAINT `fk_userlogs_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userlogs`
--

LOCK TABLES `tb_userlogs` WRITE;
/*!40000 ALTER TABLE `tb_userlogs` DISABLE KEYS */;
INSERT INTO `tb_userlogs` VALUES (1,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 18:57:00'),(2,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:20:08'),(3,2,'335.140.702-53','Raufe delas','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:20:12'),(4,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:04'),(5,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:14'),(6,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:17'),(7,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:19'),(8,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:21'),(9,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:22'),(10,2,'335.140.702-53','Raufe delas','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:23'),(11,2,'335.140.702-53','Raufe delas','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:26'),(12,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:25:30'),(13,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:57:23'),(14,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:57:29'),(15,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-19 19:57:29'),(16,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-20 17:53:07');
/*!40000 ALTER TABLE `tb_userlogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `inadmin` tinyint(1) NOT NULL DEFAULT '0',
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `perfil` enum('ADMIN','SUPERVISOR','ASSESSOR') NOT NULL DEFAULT 'ASSESSOR',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `uq_usuario_cpf` (`cpf`),
  UNIQUE KEY `uq_usuario_pessoa` (`id_pessoa`),
  CONSTRAINT `fk_usuario_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `tb_funcionario` (`id_pessoa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuario`
--

LOCK TABLES `tb_usuario` WRITE;
/*!40000 ALTER TABLE `tb_usuario` DISABLE KEYS */;
INSERT INTO `tb_usuario` VALUES (1,1,'022.903.862-00','$2y$12$W.qI4J0AtvW3ojZ1SquCt.poos5QtF3xbwvjdo3TRNJtwegxvU086',1,'2026-01-19 18:56:43','ADMIN',1),(2,2,'335.140.702-53','$2y$12$N5S5Qz/qDOUhEoCql.ECceVWXz2JxATV82hZ9cmnttA3twegSDg26',0,'2026-01-19 19:19:34','SUPERVISOR',0);
/*!40000 ALTER TABLE `tb_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'prato_cheio'
--

--
-- Dumping routines for database 'prato_cheio'
--
/*!50003 DROP PROCEDURE IF EXISTS `sp_cadastrar_dependente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastrar_dependente`(
    IN p_id_titular INT,
    IN p_nome VARCHAR(100),
    IN p_rg VARCHAR(20),
    IN p_cpf VARCHAR(15),
    IN p_data_nascimento DATE,
    IN p_genero ENUM('M','F','Outro'),
    IN p_dependencia_cliente VARCHAR(50)
)
BEGIN
    DECLARE v_id_familia INT;
    DECLARE v_idade INT;
    DECLARE v_id_dependente INT;

    -- Tratamento de erro
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro ao cadastrar dependente';
    END;

    START TRANSACTION;

    -- Busca a família vinculada ao titular
    SELECT id_familia
      INTO v_id_familia
      FROM tb_titular
     WHERE id = p_id_titular
     LIMIT 1;

    -- Validação
    IF v_id_familia IS NULL THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Titular nao encontrado';
    END IF;

    -- Calcula idade
    SET v_idade = TIMESTAMPDIFF(YEAR, p_data_nascimento, CURDATE());

    -- Insere dependente
    INSERT INTO tb_dependentes (
        id_titular,
        id_familia,
        nome,
        rg,
        cpf,
        data_nascimento,
        idade,
        genero,
        dependencia_cliente,
        registration_date
    ) VALUES (
        p_id_titular,
        v_id_familia,
        p_nome,
        p_rg,
        p_cpf,
        p_data_nascimento,
        v_idade,
        p_genero,
        p_dependencia_cliente,
        NOW()
    );

    SET v_id_dependente = LAST_INSERT_ID();

    COMMIT;

    -- Retorno para o PHP
    SELECT
        v_id_dependente AS id_dependente,
        p_id_titular    AS id_titular,
        v_id_familia    AS id_familia,
        'DEPENDENTE CADASTRADO COM SUCESSO' AS status;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_cadastrar_titular_familia_endereco` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastrar_titular_familia_endereco`(
    -- ENDEREÇO
    IN p_cep VARCHAR(20),
    IN p_bairro VARCHAR(50),
    IN p_rua VARCHAR(100),
    IN p_numero VARCHAR(20),
    IN p_referencia VARCHAR(100),
    IN p_nacionalidade VARCHAR(50),
    IN p_naturalidade VARCHAR(50),
    IN p_cidade VARCHAR(50),
    IN p_tempo_moradia_anos INT,

    -- FAMÍLIA
    IN p_nome_familia VARCHAR(100),

    -- TITULAR
    IN p_nome_completo VARCHAR(100),
    IN p_nome_social VARCHAR(100),
    IN p_cor_cliente VARCHAR(50),
    IN p_nome_mae VARCHAR(100),
    IN p_telefone VARCHAR(20),
    IN p_data_nascimento DATE,
    IN p_genero ENUM('M','F','Outro'),
    IN p_estado_civil VARCHAR(50),
    IN p_rg VARCHAR(20),
    IN p_cpf VARCHAR(15),
    IN p_nis VARCHAR(30),
    IN p_status_cliente VARCHAR(30)
)
BEGIN
    DECLARE v_id_endereco INT;
    DECLARE v_id_familia INT;
    DECLARE v_id_titular INT;
    DECLARE v_idade INT;

    -- Tratamento de erro
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro ao cadastrar titular, familia e endereco';
    END;

    START TRANSACTION;

    -- ======================
    -- ENDEREÇO
    -- ======================
    INSERT INTO tb_endereco (
        cep,
        bairro,
        rua,
        numero,
        referencia,
        nacionalidade,
        naturalidade,
        cidade,
        tempo_moradia_anos,
        registration_date
    ) VALUES (
        p_cep,
        p_bairro,
        p_rua,
        p_numero,
        p_referencia,
        p_nacionalidade,
        p_naturalidade,
        p_cidade,
        p_tempo_moradia_anos,
        NOW()
    );

    SET v_id_endereco = LAST_INSERT_ID();

    -- ======================
    -- FAMÍLIA
    -- ======================
    INSERT INTO tb_familia (
        nome_familia,
        registration_date
    ) VALUES (
        p_nome_familia,
        NOW()
    );

    SET v_id_familia = LAST_INSERT_ID();

    -- ======================
    -- IDADE
    -- ======================
    SET v_idade = TIMESTAMPDIFF(YEAR, p_data_nascimento, CURDATE());

    -- ======================
    -- TITULAR
    -- ======================
    INSERT INTO tb_titular (
        id_endereco,
        id_familia,
        nome_completo,
        nome_social,
        cor_cliente,
        nome_mae,
        telefone,
        data_nascimento,
        idade,
        genero,
        estado_civil,
        rg,
        cpf,
        nis,
        status_cliente,
        registration_date
    ) VALUES (
        v_id_endereco,
        v_id_familia,
        p_nome_completo,
        p_nome_social,
        p_cor_cliente,
        p_nome_mae,
        p_telefone,
        p_data_nascimento,
        v_idade,
        p_genero,
        p_estado_civil,
        p_rg,
        p_cpf,
        p_nis,
        p_status_cliente,
        NOW()
    );

    SET v_id_titular = LAST_INSERT_ID();

    COMMIT;

    -- Retorno para o PHP
    SELECT
        v_id_titular  AS id_titular,
        v_id_familia  AS id_familia,
        v_id_endereco AS id_endereco,
        'CADASTRO REALIZADO COM SUCESSO' AS status;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_funcionario_usuario_delete` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_funcionario_usuario_delete`(
    IN p_id_usuario INT
)
BEGIN
    -- DECLARES DEVEM VIR PRIMEIRO
    DECLARE v_id_pessoa INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Usuario possui vinculos e nao pode ser excluido';
    END;

    START TRANSACTION;

    -- Busca id_pessoa
    SELECT id_pessoa
      INTO v_id_pessoa
      FROM tb_usuario
     WHERE id_usuario = p_id_usuario
     LIMIT 1;

    -- Usuario nao encontrado
    IF v_id_pessoa IS NULL THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Usuario nao encontrado';
    END IF;

    -- Remove usuario
    DELETE FROM tb_usuario
     WHERE id_usuario = p_id_usuario;

    -- Remove funcionario
    DELETE FROM tb_funcionario
     WHERE id_pessoa = v_id_pessoa;

    COMMIT;

    -- Retorno
    SELECT 
        p_id_usuario AS id_usuario,
        v_id_pessoa  AS id_pessoa,
        'EXCLUIDO COM SUCESSO' AS status;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_funcionario_usuario_save` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_funcionario_usuario_save`(
    IN p_nome_funcionario VARCHAR(255),
    IN p_email            VARCHAR(150),
    IN p_nrphone          VARCHAR(20),
    IN p_cpf              VARCHAR(14),
    IN p_senha            VARCHAR(255),
    IN p_perfil           VARCHAR(20)
)
BEGIN
    -- DECLARE SEMPRE NO INÍCIO
    DECLARE v_id_pessoa INT;
    DECLARE v_inadmin TINYINT(1);

    -- Handler de erro
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro ao salvar funcionário e usuário';
    END;

    -- Define se é admin
    SET v_inadmin = IF(p_perfil = 'ADMIN', 1, 0);

    START TRANSACTION;

    -- Insere funcionário
    INSERT INTO tb_funcionario (
        nome_funcionario,
        email,
        nrphone,
        dtregister
    ) VALUES (
        p_nome_funcionario,
        p_email,
        p_nrphone,
        NOW()
    );

    SET v_id_pessoa = LAST_INSERT_ID();

    -- Insere usuário
    INSERT INTO tb_usuario (
        id_pessoa,
        cpf,
        senha,
        inadmin,
        dtregister,
        perfil
    ) VALUES (
        v_id_pessoa,
        p_cpf,
        p_senha,
        v_inadmin,
        NOW(),
        p_perfil
    );

    COMMIT;

    -- Retorno
    SELECT 
        f.id_pessoa,
        f.nome_funcionario,
        f.email,
        f.nrphone,
        u.id_usuario,
        u.cpf,
        u.perfil,
        u.inadmin
    FROM tb_funcionario f
    INNER JOIN tb_usuario u 
        ON u.id_pessoa = f.id_pessoa
    WHERE f.id_pessoa = v_id_pessoa;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_funcionario_usuario_soft_delete` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_funcionario_usuario_soft_delete`(
    IN p_id_usuario INT
)
BEGIN
    DECLARE v_id_pessoa INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro ao desativar usuario';
    END;

    START TRANSACTION;

    SELECT id_pessoa
      INTO v_id_pessoa
      FROM tb_usuario
     WHERE id_usuario = p_id_usuario
     LIMIT 1;

    IF v_id_pessoa IS NULL THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Usuario nao encontrado';
    END IF;

    UPDATE tb_usuario
       SET ativo = 0
     WHERE id_usuario = p_id_usuario;

    UPDATE tb_funcionario
       SET ativo = 0
     WHERE id_pessoa = v_id_pessoa;

    COMMIT;

    SELECT 'USUARIO DESATIVADO COM SUCESSO' AS status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_funcionario_usuario_update` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_funcionario_usuario_update`(
    IN p_id_usuario       INT,
    IN p_nome_funcionario VARCHAR(255),
    IN p_email            VARCHAR(150),
    IN p_nrphone          VARCHAR(20),
    IN p_cpf              VARCHAR(14),
    IN p_senha            VARCHAR(255),
    IN p_perfil           VARCHAR(20)
)
BEGIN
    -- DECLARES sempre no início
    DECLARE v_id_pessoa INT;
    DECLARE v_inadmin TINYINT(1);

    -- Handler de erro
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro ao atualizar funcionário e usuário';
    END;

    -- Define se é admin
    SET v_inadmin = IF(p_perfil = 'ADMIN', 1, 0);

    START TRANSACTION;

    -- Descobre o id_pessoa pelo id_usuario
    SELECT id_pessoa
      INTO v_id_pessoa
      FROM tb_usuario
     WHERE id_usuario = p_id_usuario
     LIMIT 1;

    -- Atualiza funcionário
    UPDATE tb_funcionario
       SET nome_funcionario = p_nome_funcionario,
           email            = p_email,
           nrphone          = p_nrphone
     WHERE id_pessoa = v_id_pessoa;

    -- Atualiza usuário (senha opcional)
    UPDATE tb_usuario
       SET cpf      = p_cpf,
           perfil   = p_perfil,
           inadmin  = v_inadmin,
           senha    = IF(p_senha IS NULL OR p_senha = '', senha, p_senha)
     WHERE id_usuario = p_id_usuario;

    COMMIT;

    -- Retorno final
    SELECT 
        f.id_pessoa,
        f.nome_funcionario,
        f.email,
        f.nrphone,
        u.id_usuario,
        u.cpf,
        u.perfil,
        u.inadmin
    FROM tb_funcionario f
    INNER JOIN tb_usuario u 
        ON u.id_pessoa = f.id_pessoa
    WHERE u.id_usuario = p_id_usuario;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-27  8:51:04
