-- MySQL dump 10.13  Distrib 8.0.44, for macos12.7 (arm64)
--
-- Host: localhost    Database: prode_mundial
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `team1` varchar(100) NOT NULL,
  `team2` varchar(100) NOT NULL,
  `team1_flag` varchar(255) DEFAULT NULL,
  `team2_flag` varchar(255) DEFAULT NULL,
  `stage_id` int DEFAULT NULL,
  `group_name` char(1) DEFAULT NULL,
  `matchday` int DEFAULT NULL,
  `stadium` varchar(255) DEFAULT NULL,
  `match_date` datetime DEFAULT NULL,
  `result1` int DEFAULT NULL,
  `result2` int DEFAULT NULL,
  `status` enum('pending','finished') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `stage_id` (`stage_id`),
  CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
INSERT INTO `matches` VALUES (1,'México','Sudáfrica',NULL,NULL,1,'A',1,'Estadio Azteca, CDMX','2026-06-11 20:00:00',NULL,NULL,'pending'),(2,'Corea del Sur','Chequia',NULL,NULL,1,'A',1,'Ciudad de México','2026-06-11 23:00:00',NULL,NULL,'pending'),(3,'México','Corea del Sur',NULL,NULL,1,'A',2,'Estadio BBVA, Monterrey','2026-06-16 18:00:00',NULL,NULL,'pending'),(4,'Chequia','Sudáfrica',NULL,NULL,1,'A',2,'Estadio Azteca, CDMX','2026-06-17 21:00:00',NULL,NULL,'pending'),(5,'México','Chequia',NULL,NULL,1,'A',3,'Estadio Azteca, CDMX','2026-06-24 16:00:00',NULL,NULL,'pending'),(6,'Sudáfrica','Corea del Sur',NULL,NULL,1,'A',3,'Estadio Akron, Guadalajara','2026-06-24 16:00:00',NULL,NULL,'pending'),(7,'Canadá','Bosnia y Herzegovina',NULL,NULL,1,'B',1,'BMO Field, Toronto','2026-06-12 18:00:00',NULL,NULL,'pending'),(8,'Catar','Suiza',NULL,NULL,1,'B',1,'BC Place, Vancouver','2026-06-13 14:00:00',NULL,NULL,'pending'),(9,'Canadá','Catar',NULL,NULL,1,'B',2,'BMO Field, Toronto','2026-06-18 19:00:00',NULL,NULL,'pending'),(10,'Suiza','Bosnia y Herzegovina',NULL,NULL,1,'B',2,'BC Place, Vancouver','2026-06-19 16:00:00',NULL,NULL,'pending'),(11,'Canadá','Suiza',NULL,NULL,1,'B',3,'BMO Field, Toronto','2026-06-24 19:00:00',NULL,NULL,'pending'),(12,'Bosnia y Herzegovina','Catar',NULL,NULL,1,'B',3,'BC Place, Vancouver','2026-06-24 19:00:00',NULL,NULL,'pending'),(13,'Brasil','Marruecos',NULL,NULL,1,'C',1,'Hard Rock Stadium, Miami','2026-06-13 19:00:00',NULL,NULL,'pending'),(14,'Haití','Escocia',NULL,NULL,1,'C',1,'Mercedes-Benz Stadium, Atlanta','2026-06-14 15:00:00',NULL,NULL,'pending'),(15,'Brasil','Haití',NULL,NULL,1,'C',2,'Hard Rock Stadium, Miami','2026-06-19 21:00:00',NULL,NULL,'pending'),(16,'Escocia','Marruecos',NULL,NULL,1,'C',2,'Mercedes-Benz Stadium, Atlanta','2026-06-20 18:00:00',NULL,NULL,'pending'),(17,'Brasil','Escocia',NULL,NULL,1,'C',3,'Hard Rock Stadium, Miami','2026-06-25 18:00:00',NULL,NULL,'pending'),(18,'Marruecos','Haití',NULL,NULL,1,'C',3,'Mercedes-Benz Stadium, Atlanta','2026-06-25 18:00:00',NULL,NULL,'pending'),(19,'Estados Unidos','Paraguay',NULL,NULL,1,'D',1,'SoFi Stadium, Los Angeles','2026-06-12 21:00:00',NULL,NULL,'pending'),(20,'Australia','Turquía',NULL,NULL,1,'D',1,'Levi\'s Stadium, San Francisco','2026-06-13 17:00:00',NULL,NULL,'pending'),(21,'Estados Unidos','Australia',NULL,NULL,1,'D',2,'Lumen Field, Seattle','2026-06-19 21:00:00',NULL,NULL,'pending'),(22,'Turquía','Paraguay',NULL,NULL,1,'D',2,'SoFi Stadium, Los Angeles','2026-06-20 18:00:00',NULL,NULL,'pending'),(23,'Estados Unidos','Turquía',NULL,NULL,1,'D',3,'SoFi Stadium, Los Angeles','2026-06-25 18:00:00',NULL,NULL,'pending'),(24,'Paraguay','Australia',NULL,NULL,1,'D',3,'Lumen Field, Seattle','2026-06-25 18:00:00',NULL,NULL,'pending'),(25,'Alemania','Curazao',NULL,NULL,1,'E',1,'MetLife Stadium, New Jersey','2026-06-14 16:00:00',NULL,NULL,'pending'),(26,'Costa de Marfil','Ecuador',NULL,NULL,1,'E',1,'Lincoln Financial Field, Philadelphia','2026-06-15 13:00:00',NULL,NULL,'pending'),(27,'Alemania','Costa de Marfil',NULL,NULL,1,'E',2,'Gillette Stadium, Boston','2026-06-20 19:00:00',NULL,NULL,'pending'),(28,'Ecuador','Curazao',NULL,NULL,1,'E',2,'MetLife Stadium, New Jersey','2026-06-21 16:00:00',NULL,NULL,'pending'),(29,'Alemania','Ecuador',NULL,NULL,1,'E',3,'Lincoln Financial Field, Philadelphia','2026-06-26 21:00:00',NULL,NULL,'pending'),(30,'Curazao','Costa de Marfil',NULL,NULL,1,'E',3,'Gillette Stadium, Boston','2026-06-26 21:00:00',NULL,NULL,'pending'),(31,'Países Bajos','Japón',NULL,NULL,1,'F',1,'NRG Stadium, Houston','2026-06-16 21:00:00',NULL,NULL,'pending'),(32,'Suecia','Túnez',NULL,NULL,1,'F',1,'AT&T Stadium, Dallas','2026-06-17 18:00:00',NULL,NULL,'pending'),(33,'Países Bajos','Suecia',NULL,NULL,1,'F',2,'NRG Stadium, Houston','2026-06-22 21:00:00',NULL,NULL,'pending'),(34,'Túnez','Japón',NULL,NULL,1,'F',2,'AT&T Stadium, Dallas','2026-06-23 18:00:00',NULL,NULL,'pending'),(35,'Países Bajos','Túnez',NULL,NULL,1,'F',3,'NRG Stadium, Houston','2026-06-28 15:00:00',NULL,NULL,'pending'),(36,'Japón','Suecia',NULL,NULL,1,'F',3,'AT&T Stadium, Dallas','2026-06-28 15:00:00',NULL,NULL,'pending'),(37,'Bélgica','Egipto',NULL,NULL,1,'G',1,'Arrowhead Stadium, Kansas City','2026-06-17 21:00:00',NULL,NULL,'pending'),(38,'Irán','Nueva Zelanda',NULL,NULL,1,'G',1,'SoFi Stadium, Los Angeles','2026-06-18 18:00:00',NULL,NULL,'pending'),(39,'Bélgica','Irán',NULL,NULL,1,'G',2,'Arrowhead Stadium, Kansas City','2026-06-23 21:00:00',NULL,NULL,'pending'),(40,'Nueva Zelanda','Egipto',NULL,NULL,1,'G',2,'SoFi Stadium, Los Angeles','2026-06-24 18:00:00',NULL,NULL,'pending'),(41,'Bélgica','Nueva Zelanda',NULL,NULL,1,'G',3,'Arrowhead Stadium, Kansas City','2026-06-29 20:00:00',NULL,NULL,'pending'),(42,'Egipto','Irán',NULL,NULL,1,'G',3,'SoFi Stadium, Los Angeles','2026-06-29 20:00:00',NULL,NULL,'pending'),(43,'España','Cabo Verde',NULL,NULL,1,'H',1,'MetLife Stadium, New Jersey','2026-06-18 21:00:00',NULL,NULL,'pending'),(44,'Arabia Saudita','Uruguay',NULL,NULL,1,'H',1,'Lincoln Financial Field, Philadelphia','2026-06-19 18:00:00',NULL,NULL,'pending'),(45,'España','Arabia Saudita',NULL,NULL,1,'H',2,'Gillette Stadium, Boston','2026-06-24 21:00:00',NULL,NULL,'pending'),(46,'Uruguay','Cabo Verde',NULL,NULL,1,'H',2,'MetLife Stadium, New Jersey','2026-06-25 18:00:00',NULL,NULL,'pending'),(47,'España','Uruguay',NULL,NULL,1,'H',3,'Lincoln Financial Field, Philadelphia','2026-06-30 19:00:00',NULL,NULL,'pending'),(48,'Cabo Verde','Arabia Saudita',NULL,NULL,1,'H',3,'Gillette Stadium, Boston','2026-06-30 19:00:00',NULL,NULL,'pending'),(49,'Francia','Senegal',NULL,NULL,1,'I',1,'AT&T Stadium, Dallas','2026-06-20 15:00:00',NULL,NULL,'pending'),(50,'Irak','Noruega',NULL,NULL,1,'I',1,'NRG Stadium, Houston','2026-06-20 18:00:00',NULL,NULL,'pending'),(51,'Francia','Irak',NULL,NULL,1,'I',2,'AT&T Stadium, Dallas','2026-06-25 15:00:00',NULL,NULL,'pending'),(52,'Noruega','Senegal',NULL,NULL,1,'I',2,'NRG Stadium, Houston','2026-06-25 18:00:00',NULL,NULL,'pending'),(53,'Francia','Noruega',NULL,NULL,1,'I',3,'AT&T Stadium, Dallas','2026-06-30 21:00:00',NULL,NULL,'pending'),(54,'Senegal','Irak',NULL,NULL,1,'I',3,'NRG Stadium, Houston','2026-06-30 21:00:00',NULL,NULL,'pending'),(55,'Argentina','Argelia',NULL,NULL,1,'J',1,'MetLife Stadium, New Jersey','2026-06-16 16:00:00',NULL,NULL,'pending'),(56,'Austria','Jordania',NULL,NULL,1,'J',1,'Hard Rock Stadium, Miami','2026-06-16 19:00:00',NULL,NULL,'pending'),(57,'Argentina','Austria',NULL,NULL,1,'J',2,'Mercedes-Benz Stadium, Atlanta','2026-06-21 21:00:00',NULL,NULL,'pending'),(58,'Jordania','Argelia',NULL,NULL,1,'J',2,'Hard Rock Stadium, Miami','2026-06-22 18:00:00',NULL,NULL,'pending'),(59,'Argentina','Jordania',NULL,NULL,1,'J',3,'Mercedes-Benz Stadium, Atlanta','2026-06-27 18:00:00',NULL,NULL,'pending'),(60,'Argelia','Austria',NULL,NULL,1,'J',3,'Hard Rock Stadium, Miami','2026-06-27 18:00:00',NULL,NULL,'pending'),(61,'Portugal','RD Congo',NULL,NULL,1,'K',1,'Arrowhead Stadium, Kansas City','2026-06-22 15:00:00',NULL,NULL,'pending'),(62,'Uzbekistán','Colombia',NULL,NULL,1,'K',1,'SoFi Stadium, Los Angeles','2026-06-22 18:00:00',NULL,NULL,'pending'),(63,'Portugal','Uzbekistán',NULL,NULL,1,'K',2,'Arrowhead Stadium, Kansas City','2026-06-27 15:00:00',NULL,NULL,'pending'),(64,'Colombia','RD Congo',NULL,NULL,1,'K',2,'SoFi Stadium, Los Angeles','2026-06-27 18:00:00',NULL,NULL,'pending'),(65,'Portugal','Colombia',NULL,NULL,1,'K',3,'Arrowhead Stadium, Kansas City','2026-07-02 18:00:00',NULL,NULL,'pending'),(66,'RD Congo','Uzbekistán',NULL,NULL,1,'K',3,'SoFi Stadium, Los Angeles','2026-07-02 18:00:00',NULL,NULL,'pending'),(67,'Inglaterra','Croacia',NULL,NULL,1,'L',1,'MetLife Stadium, New Jersey','2026-06-23 15:00:00',NULL,NULL,'pending'),(68,'Ghana','Panamá',NULL,NULL,1,'L',1,'Lincoln Financial Field, Philadelphia','2026-06-23 18:00:00',NULL,NULL,'pending'),(69,'Inglaterra','Ghana',NULL,NULL,1,'L',2,'Gillette Stadium, Boston','2026-06-28 15:00:00',NULL,NULL,'pending'),(70,'Panamá','Croacia',NULL,NULL,1,'L',2,'MetLife Stadium, New Jersey','2026-06-28 18:00:00',NULL,NULL,'pending'),(71,'Inglaterra','Panamá',NULL,NULL,1,'L',3,'Lincoln Financial Field, Philadelphia','2026-07-03 15:00:00',NULL,NULL,'pending'),(72,'Croacia','Ghana',NULL,NULL,1,'L',3,'Gillette Stadium, Boston','2026-07-03 15:00:00',NULL,NULL,'pending'),(73,'1º Grupo A','2º Grupo C',NULL,NULL,2,NULL,NULL,'Estadio Azteca, CDMX','2026-07-04 15:00:00',NULL,NULL,'pending'),(74,'1º Grupo B','2º Grupo D',NULL,NULL,2,NULL,NULL,'BMO Field, Toronto','2026-07-04 18:00:00',NULL,NULL,'pending'),(75,'1º Grupo E','3º Grupo G/H/I',NULL,NULL,2,NULL,NULL,'SoFi Stadium, Los Angeles','2026-07-05 15:00:00',NULL,NULL,'pending'),(76,'1º Grupo F','2º Grupo H',NULL,NULL,2,NULL,NULL,'MetLife Stadium, New Jersey','2026-07-05 18:00:00',NULL,NULL,'pending'),(77,'1º Grupo C','2º Grupo A',NULL,NULL,2,NULL,NULL,'Mercedes-Benz Stadium, Atlanta','2026-07-06 15:00:00',NULL,NULL,'pending'),(78,'1º Grupo D','2º Grupo B',NULL,NULL,2,NULL,NULL,'Hard Rock Stadium, Miami','2026-07-06 18:00:00',NULL,NULL,'pending'),(79,'1º Grupo G','3º Grupo I/J/K',NULL,NULL,2,NULL,NULL,'NRG Stadium, Houston','2026-07-07 15:00:00',NULL,NULL,'pending'),(80,'1º Grupo H','2º Grupo F',NULL,NULL,2,NULL,NULL,'Arrowhead Stadium, Kansas City','2026-07-07 18:00:00',NULL,NULL,'pending'),(81,'1º Grupo I','3º Grupo A/B/C',NULL,NULL,2,NULL,NULL,'Gillette Stadium, Boston','2026-07-08 15:00:00',NULL,NULL,'pending'),(82,'1º Grupo J','2º Grupo L',NULL,NULL,2,NULL,NULL,'BC Place, Vancouver','2026-07-08 18:00:00',NULL,NULL,'pending'),(83,'1º Grupo K','3º Grupo D/E/F',NULL,NULL,2,NULL,NULL,'Lumen Field, Seattle','2026-07-09 15:00:00',NULL,NULL,'pending'),(84,'1º Grupo L','2º Grupo J',NULL,NULL,2,NULL,NULL,'Levi\'s Stadium, San Francisco','2026-07-09 18:00:00',NULL,NULL,'pending'),(85,'2º Grupo E','2º Grupo G',NULL,NULL,2,NULL,NULL,'Lincoln Financial Field, Philadelphia','2026-07-10 15:00:00',NULL,NULL,'pending'),(86,'2º Grupo I','2º Grupo K',NULL,NULL,2,NULL,NULL,'AT&T Stadium, Dallas','2026-07-10 18:00:00',NULL,NULL,'pending'),(87,'3º Grupo B/E/F','1º Grupo M',NULL,NULL,2,NULL,NULL,'SoFi Stadium, Los Angeles','2026-07-11 15:00:00',NULL,NULL,'pending'),(88,'3º Grupo C/D/L','2º Grupo N',NULL,NULL,2,NULL,NULL,'Estadio Azteca, CDMX','2026-07-11 18:00:00',NULL,NULL,'pending'),(89,'Ganador 73','Ganador 74',NULL,NULL,3,NULL,NULL,'Estadio Azteca, CDMX','2026-07-13 20:00:00',NULL,NULL,'pending'),(90,'Ganador 75','Ganador 76',NULL,NULL,3,NULL,NULL,'MetLife Stadium, New Jersey','2026-07-13 20:00:00',NULL,NULL,'pending'),(91,'Ganador 77','Ganador 78',NULL,NULL,3,NULL,NULL,'SoFi Stadium, Los Angeles','2026-07-14 20:00:00',NULL,NULL,'pending'),(92,'Ganador 79','Ganador 80',NULL,NULL,3,NULL,NULL,'Hard Rock Stadium, Miami','2026-07-14 20:00:00',NULL,NULL,'pending'),(93,'Ganador 81','Ganador 82',NULL,NULL,3,NULL,NULL,'Mercedes-Benz Stadium, Atlanta','2026-07-15 20:00:00',NULL,NULL,'pending'),(94,'Ganador 83','Ganador 84',NULL,NULL,3,NULL,NULL,'NRG Stadium, Houston','2026-07-15 20:00:00',NULL,NULL,'pending'),(95,'Ganador 85','Ganador 86',NULL,NULL,3,NULL,NULL,'Arrowhead Stadium, Kansas City','2026-07-16 20:00:00',NULL,NULL,'pending'),(96,'Ganador 87','Ganador 88',NULL,NULL,3,NULL,NULL,'BC Place, Vancouver','2026-07-16 20:00:00',NULL,NULL,'pending'),(97,'Ganador Octavos 1','Ganador Octavos 2',NULL,NULL,4,NULL,NULL,'SoFi Stadium, Los Angeles','2026-07-18 20:00:00',NULL,NULL,'pending'),(98,'Ganador Octavos 3','Ganador Octavos 4',NULL,NULL,4,NULL,NULL,'Hard Rock Stadium, Miami','2026-07-18 20:00:00',NULL,NULL,'pending'),(99,'Ganador Octavos 5','Ganador Octavos 6',NULL,NULL,4,NULL,NULL,'Arrowhead Stadium, Kansas City','2026-07-19 20:00:00',NULL,NULL,'pending'),(100,'Ganador Octavos 7','Ganador Octavos 8',NULL,NULL,4,NULL,NULL,'Gillette Stadium, Boston','2026-07-19 20:00:00',NULL,NULL,'pending'),(101,'Ganador Cuartos 1','Ganador Cuartos 2',NULL,NULL,5,NULL,NULL,'AT&T Stadium, Dallas','2026-07-22 21:00:00',NULL,NULL,'pending'),(102,'Ganador Cuartos 3','Ganador Cuartos 4',NULL,NULL,5,NULL,NULL,'Mercedes-Benz Stadium, Atlanta','2026-07-23 21:00:00',NULL,NULL,'pending'),(103,'Perdedor Semi 1','Perdedor Semi 2',NULL,NULL,6,NULL,NULL,'Hard Rock Stadium, Miami','2026-07-25 20:00:00',NULL,NULL,'pending'),(104,'Ganador Semi 1','Ganador Semi 2',NULL,NULL,7,NULL,NULL,'MetLife Stadium, New Jersey','2026-07-26 16:00:00',NULL,NULL,'pending');
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `predictions`
--

DROP TABLE IF EXISTS `predictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `predictions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `match_id` int DEFAULT NULL,
  `score1` int DEFAULT NULL,
  `score2` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_match` (`user_id`,`match_id`),
  KEY `match_id` (`match_id`),
  CONSTRAINT `predictions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `predictions_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `predictions`
--

LOCK TABLES `predictions` WRITE;
/*!40000 ALTER TABLE `predictions` DISABLE KEYS */;
/*!40000 ALTER TABLE `predictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stages`
--

DROP TABLE IF EXISTS `stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_open` tinyint(1) DEFAULT '1',
  `display_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stages`
--

LOCK TABLES `stages` WRITE;
/*!40000 ALTER TABLE `stages` DISABLE KEYS */;
INSERT INTO `stages` VALUES (1,'Fase de Grupos',1,1),(2,'Dieciseisavos de Final',0,2),(3,'Octavos de Final',0,3),(4,'Cuartos de Final',0,4),(5,'Semifinales',0,5),(6,'Tercer Puesto',0,6),(7,'Final',0,7);
/*!40000 ALTER TABLE `stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `is_fan` tinyint(1) DEFAULT '0',
  `points` int DEFAULT '0',
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@fsinet.com.ar','$2y$12$khSSkaHmMrDkyaGynknOLOk2TDXokbUlBMrTMo2aVeqe2Dj3ww99i','Administrador',0,0,1,'2026-05-20 20:32:04');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-20 21:38:54
