-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: prato_cheio
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_access_denied` (
  `id` int NOT NULL AUTO_INCREMENT,
  `perfil` varchar(20) DEFAULT NULL,
  `rota` varchar(255) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_access_denied`
--

LOCK TABLES `tb_access_denied` WRITE;
/*!40000 ALTER TABLE `tb_access_denied` DISABLE KEYS */;
INSERT INTO `tb_access_denied` VALUES (1,'ASSESSOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:01'),(2,'ASSESSOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:03'),(3,'ASSESSOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:04'),(4,'ASSESSOR','/admin/clientes','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:05'),(5,'ASSESSOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:10'),(6,'ASSESSOR','/admin/clientes','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:12'),(7,'ASSESSOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:40:13'),(8,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:33'),(9,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:38'),(10,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:40'),(11,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:43'),(12,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:49'),(13,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:51'),(14,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:54'),(15,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:51:54'),(16,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:05'),(17,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:05'),(18,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:05'),(19,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:06'),(20,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:06'),(21,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:07'),(22,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:07'),(23,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:52:08'),(24,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:53:22'),(25,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 10:18:40'),(26,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 11:33:16'),(27,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 12:11:02'),(28,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 12:11:07'),(29,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-19 23:45:41'),(30,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-19 23:45:48'),(31,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 02:08:32'),(32,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 15:41:48'),(33,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 16:32:00'),(34,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 16:45:15'),(35,'SUPERVISOR','/admin/funcionarios','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 16:45:22');
/*!40000 ALTER TABLE `tb_access_denied` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_dependentes`
--

DROP TABLE IF EXISTS `tb_dependentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_dependentes`
--

LOCK TABLES `tb_dependentes` WRITE;
/*!40000 ALTER TABLE `tb_dependentes` DISABLE KEYS */;
INSERT INTO `tb_dependentes` VALUES (73,331,NULL,'YASMIN SANTOS MOTA',NULL,NULL,NULL,28,NULL,'Cônjuge','2025-12-19 01:11:29','2025-12-19 01:11:29'),(74,331,NULL,'BIANCA MOTA','','','2019-07-29',6,'F','Filho(a)','2025-12-19 01:21:03','2025-12-19 01:21:03'),(75,331,NULL,'YZIS HELENA','','','2022-07-29',4,'F','Filho(a)','2025-12-19 01:21:03','2025-12-19 01:21:03'),(76,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:27:45','2025-12-20 03:44:52'),(77,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:27:45','2025-12-20 03:44:52'),(78,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:28:23','2025-12-20 03:44:52'),(79,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:28:23','2025-12-20 03:44:52'),(80,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:28:33','2025-12-20 03:44:52'),(81,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:28:33','2025-12-20 03:44:52'),(82,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:28:44','2025-12-20 03:44:52'),(83,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:28:44','2025-12-20 03:44:52'),(84,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:28:56','2025-12-20 03:44:52'),(85,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:28:56','2025-12-20 03:44:52'),(86,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:29:08','2025-12-20 03:44:52'),(87,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:29:08','2025-12-20 03:44:52'),(88,331,NULL,'teste','','','1311-03-12',714,'Outro','Filho(a)','2025-12-19 01:29:19','2025-12-20 03:44:52'),(89,331,NULL,'asdasd','','','3111-12-12',-1085,'Outro','Pai/Mãe','2025-12-19 01:29:19','2025-12-20 03:44:52'),(90,331,NULL,'asdasdasd','','','1231-03-12',794,'M','','2025-12-19 01:30:41','2025-12-20 03:44:52'),(91,331,NULL,'aasdadadadasdaasd','','','2311-11-13',-285,'F','','2025-12-19 01:31:12','2025-12-20 03:44:52'),(92,332,NULL,'BIANCA MOTA','','','3221-12-13',-1195,'F','Filho(a)','2025-12-20 00:18:48','2025-12-20 03:44:52'),(93,332,NULL,'YASMIN SANTOS MOTA','','','1996-03-13',29,'F','Filho(a)','2025-12-20 00:19:48','2025-12-20 03:44:52');
/*!40000 ALTER TABLE `tb_dependentes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_dependente_idade_update` BEFORE UPDATE ON `tb_dependentes` FOR EACH ROW BEGIN
  IF NEW.data_nascimento IS NOT NULL THEN
    SET NEW.idade = TIMESTAMPDIFF(YEAR, NEW.data_nascimento, CURDATE());
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `tb_endereco`
--

DROP TABLE IF EXISTS `tb_endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_endereco`
--

LOCK TABLES `tb_endereco` WRITE;
/*!40000 ALTER TABLE `tb_endereco` DISABLE KEYS */;
INSERT INTO `tb_endereco` VALUES (315,'69047-090','Planalto','Rua Waldir Normando','170','PARQUE MOSAICO','Brasil','Manaus','Manaus',2,'2025-12-18 15:10:59','2025-12-18 15:10:59'),(316,'69047-090','Planalto','Rua Waldir Normando','170','PARQUE MOSAICO','Brasil','Manaus','Manaus',2,'2025-12-18 15:14:33','2025-12-18 15:14:33'),(317,'69047-090','Planalto','Rua Waldir Normando','170','PARQUE MOSAICO','Brasil','Manaus','Manaus',2,'2025-12-18 15:18:35','2025-12-18 15:18:35'),(318,'69044-236','REDENÇÃO','MANTIQUEIRA','208','','Brasil','Manaus','Manaus',2,'2025-12-20 00:18:19','2025-12-20 00:18:19');
/*!40000 ALTER TABLE `tb_endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_familia`
--

DROP TABLE IF EXISTS `tb_familia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_familia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_familia` varchar(100) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_familia`
--

LOCK TABLES `tb_familia` WRITE;
/*!40000 ALTER TABLE `tb_familia` DISABLE KEYS */;
INSERT INTO `tb_familia` VALUES (1,'Família MOTA','2025-12-18 15:14:33','2025-12-18 15:14:33'),(3,'Família Santos','2025-12-20 00:18:19','2025-12-20 00:18:19');
/*!40000 ALTER TABLE `tb_familia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_funcionario`
--

DROP TABLE IF EXISTS `tb_funcionario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_funcionario` (
  `id_pessoa` int NOT NULL AUTO_INCREMENT,
  `nome_funcionario` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `nrphone` varchar(20) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pessoa`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_funcionario`
--

LOCK TABLES `tb_funcionario` WRITE;
/*!40000 ALTER TABLE `tb_funcionario` DISABLE KEYS */;
INSERT INTO `tb_funcionario` VALUES (1,'Matheus Mota da Silva','mmota350@gmail.com','(92) 98167-8846','2025-12-08 17:54:33'),(5,'marcos mota pinto','marcos@mota.com','92982525129','2025-12-17 22:49:41'),(7,'mariana silva','teste@gmail.com','(92) 98167-8846','2025-12-17 22:52:59');
/*!40000 ALTER TABLE `tb_funcionario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_titular`
--

DROP TABLE IF EXISTS `tb_titular`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `tb_titular` VALUES (331,317,1,'MATHEUS MOTA DA SILVA','MATHEUS MOTA DA SILVA','NEGRO','MARIA ELIETH MUNIZ DA MOTA','(92) 98252-5129','1996-03-13',29,'Outro','Solteiro(a)','022.903.862-00','022.903.862-00','02290386200','ATIVO','2025-12-18 15:18:35','2025-12-20 00:19:17'),(332,318,3,'Yasmin Santos Mota','Yasmin Santos Mota','PRETA','Yasmin Santos Mota','(92) 98252-5129','1996-08-29',NULL,'M','Solteiro(a)','124.422.222-22','123.123.123-12','123.12312.31-2','ATIVO','2025-12-20 00:18:19','2025-12-20 00:18:19');
/*!40000 ALTER TABLE `tb_titular` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_titular_idade_update` BEFORE UPDATE ON `tb_titular` FOR EACH ROW BEGIN
  IF NEW.data_nascimento IS NOT NULL THEN
    SET NEW.idade = TIMESTAMPDIFF(YEAR, NEW.data_nascimento, CURDATE());
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `tb_userlogs`
--

DROP TABLE IF EXISTS `tb_userlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userlogs`
--

LOCK TABLES `tb_userlogs` WRITE;
/*!40000 ALTER TABLE `tb_userlogs` DISABLE KEYS */;
INSERT INTO `tb_userlogs` VALUES (1,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-08 19:14:17'),(2,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-08 19:23:56'),(3,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-08 19:30:52'),(4,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-08 22:51:20'),(5,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-08 22:51:22'),(6,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-08 23:03:49'),(7,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-17 22:24:11'),(8,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-17 23:37:37'),(9,4,'565.761.962-53','asdasdasd','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-17 23:37:40'),(10,4,'565.761.962-53','asdasdasd','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:37:50'),(11,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:38:02'),(12,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:56:23'),(13,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 00:56:27'),(14,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 01:11:12'),(15,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 01:11:18'),(16,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 01:35:15'),(17,2,'335.140.702-53','marcos mota','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 01:35:18'),(18,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 02:09:49'),(19,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 02:11:56'),(20,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 02:12:13'),(21,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 02:13:27'),(22,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 02:15:23'),(23,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 02:15:40'),(24,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:01'),(25,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:03'),(26,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:04'),(27,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:05'),(28,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:10'),(29,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:12'),(30,2,'335.140.702-53','marcos mota','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:13'),(31,2,'335.140.702-53','marcos mota','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:40:57'),(32,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:41:06'),(33,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:50:34'),(34,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:07'),(35,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:29'),(36,4,'565.761.962-53','mariana silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:32'),(37,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:33'),(38,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:38'),(39,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:40'),(40,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:43'),(41,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:49'),(42,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:51'),(43,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:54'),(44,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:51:54'),(45,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:05'),(46,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:05'),(47,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:05'),(48,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:06'),(49,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:06'),(50,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:07'),(51,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:07'),(52,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:08'),(53,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:52:26'),(54,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 04:53:22'),(55,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 14:18:40'),(56,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 15:33:16'),(57,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 16:11:02'),(58,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 16:11:07'),(59,4,'565.761.962-53','mariana silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 18:26:17'),(60,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 18:26:25'),(61,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 18:28:09'),(62,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 18:28:27'),(63,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 18:49:49'),(64,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-18 23:20:06'),(65,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-19 15:44:21'),(66,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-19 19:34:12'),(67,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-19 19:34:19'),(68,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 03:45:20'),(69,4,'565.761.962-53','mariana silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 03:45:39'),(70,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 03:45:41'),(71,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 03:45:48'),(72,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 06:08:32'),(73,4,'565.761.962-53','mariana silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 22:01:52'),(74,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-20 22:01:56'),(75,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 16:05:46'),(76,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 19:39:41'),(77,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 19:39:45'),(78,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 19:41:42'),(79,2,'335.140.702-53','marcos mota pinto','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 19:41:46'),(80,2,'335.140.702-53','marcos mota pinto','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 19:41:48'),(81,2,'335.140.702-53','marcos mota pinto','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 20:32:00'),(82,2,'335.140.702-53','marcos mota pinto','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 20:34:03'),(83,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-22 20:34:06'),(84,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:44:39'),(85,1,'022.903.862-00','Matheus Mota da Silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:45:10'),(86,4,'565.761.962-53','mariana silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:45:13'),(87,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:45:15'),(88,4,'565.761.962-53','mariana silva','ACESSO_NEGADO','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:45:22'),(89,4,'565.761.962-53','mariana silva','LOGOUT','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:45:25'),(90,1,'022.903.862-00','Matheus Mota da Silva','LOGIN','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-08 20:45:28');
/*!40000 ALTER TABLE `tb_userlogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `perfil` enum('ADMIN','SUPERVISOR','ASSESSOR') NOT NULL DEFAULT 'ASSESSOR',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `uq_usuario_cpf` (`cpf`),
  UNIQUE KEY `uq_usuario_pessoa` (`id_pessoa`),
  CONSTRAINT `fk_usuario_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `tb_funcionario` (`id_pessoa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuario`
--

LOCK TABLES `tb_usuario` WRITE;
/*!40000 ALTER TABLE `tb_usuario` DISABLE KEYS */;
INSERT INTO `tb_usuario` VALUES (1,1,'022.903.862-00','$2y$12$sB9mFuCim.sDfS7Bbgqo7eD7hKQFTn5I2jzmQ3DQNvQzB/hcFnNBK','2025-12-08 17:54:53','ADMIN'),(2,5,'335.140.702-53','$2y$12$.TiJR1OZCrE20b0gLVNvV.hoFpe9C3scGDrOwyX.1RbTbjmJgPGZa','2025-12-17 22:49:41','SUPERVISOR'),(4,7,'565.761.962-53','$2y$12$dE2jRPta0eJ1a9TAqaeFtOBYAYgJMncTtNYO1NUNNyDE7LVfVcIAS','2025-12-17 22:52:59','SUPERVISOR');
/*!40000 ALTER TABLE `tb_usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-12 16:07:56
