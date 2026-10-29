-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sigsm
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Current Database: `sigsm`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sigsm` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;

USE `sigsm`;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (30,'Anestesiología'),(2,'Cardiología'),(10,'Centro Cardiovascular'),(12,'Cirugía Cardíaca'),(31,'Cirugía Plástica y Quemados'),(32,'Cirugía Vascular Periférica'),(13,'Clínica Médica A'),(14,'Clínica Médica B'),(15,'Clínica Médica C'),(29,'Departamento Clínico de Cirugía'),(9,'Departamento Clínico de Medicina'),(16,'Dermatología'),(7,'Emergencia'),(17,'Endocrinología'),(4,'Estudios imagenológicos'),(18,'Gastroenterología'),(20,'Geriatría'),(33,'Ginecotocología B'),(21,'Hematología'),(11,'Hemodinamia'),(45,'Hemoterapia'),(46,'Imagenología'),(22,'Infectología'),(23,'Medicina Física, Rehabilitación y Medicina del Deporte'),(44,'Medicina Nuclear'),(1,'Nefrología'),(8,'Neonatología'),(34,'Neurocirugía'),(24,'Neurología'),(35,'Odontología'),(36,'Oftalmología'),(25,'Oncología'),(37,'Otorrinolaringología'),(5,'Pacientes ostomizados'),(26,'Psiquiatría'),(38,'Quirúrgica A'),(39,'Quirúrgica B'),(40,'Quirúrgica F'),(43,'Radioterapia'),(3,'Trasplantes'),(42,'Traumatología y Ortopedia de Adultos'),(6,'Unidad de Cuidados Paliativos'),(27,'Unidad de Enfermedades Autoinmunes Sistémicas'),(19,'Unidad de Ostomías'),(28,'Unidad de Tabaquismo'),(41,'Urología');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_subida` date NOT NULL DEFAULT curdate(),
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `codigo_qr` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_documento`),
  KEY `fk_documento_categoria` (`id_categoria`),
  KEY `fk_documento_usuario` (`id_usuario`),
  CONSTRAINT `fk_documento_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `fk_documento_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
INSERT INTO `documento` VALUES (1,'Indicaciones para pacientes en tratamiento con warfarina','Recomendaciones de cuidado y controles para pacientes anticoagulados. jejejeej',2,1,'2026-08-10','documentos/70816d5a8d6636dd3c7d1866a296e956.pdf','QR-DOC-001',1),(3,'Indicaciones ecocardiograma con dobutamina','Preparación previa al estudio de ecocardiograma con dobutamina.',2,2,'2026-08-08','documentos/7a594da82ffa465cafc117f28b6801f8.pdf','QR-DOC-003',1),(4,'Indicaciones de enfermería para usuarios trasplantados','Cuidados post trasplante indicados por el equipo de enfermería.',3,2,'2026-08-01','documentos/6ecb4560b1248fca1ba6156b0ac3800d.pdf','QR-DOC-004',1),(5,'Preparación para estudios imagenológicos','Requisitos y ayuno previo a estudios de imagen.',46,1,'2026-07-28','documentos/9c21507323f635be3fe072f8facb75ea.pdf','QR-DOC-005',1),(6,'Pauta para pacientes ostomizados','Cuidado de la ostomía en el hogar.',19,2,'2026-07-20','documentos/1de0dbfc6dc50f0ad008c24a9e26de80.pdf','QR-DOC-006',1),(15,'67777777','',5,1,'2026-10-27','documentos/6fc0d12a2d2209aa09bcfb5a.pdf',NULL,0),(16,'1111','sadsa',2,1,'2026-10-27','documentos/8c032c8c91563cf9418e2de60e13152f.pdf',NULL,0),(17,'fefef','g',2,1,'2026-10-27','documentos/e9180f5a43ef5098a3665cfafc679cab.pdf',NULL,0),(18,'12321','44444',2,1,'2026-10-27','',NULL,0),(19,'prubaa sql 67','hola',5,1,'2026-10-28','',NULL,0);
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `encuesta`
--

DROP TABLE IF EXISTS `encuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encuesta` (
  `id_encuesta` int(11) NOT NULL AUTO_INCREMENT,
  `id_documento` int(11) NOT NULL,
  `calificacion` tinyint(4) NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id_encuesta`),
  KEY `fk_encuesta_documento` (`id_documento`),
  CONSTRAINT `fk_encuesta_documento` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`),
  CONSTRAINT `chk_calificacion` CHECK (`calificacion` between 1 and 5)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuesta`
--

LOCK TABLES `encuesta` WRITE;
/*!40000 ALTER TABLE `encuesta` DISABLE KEYS */;
INSERT INTO `encuesta` VALUES (1,1,5,'Muy claro y fácil de entender.','2026-08-11'),(2,1,4,'Buena información, un poco larga.','2026-08-12'),(3,3,5,'Me ayudó mucho antes del estudio.','2026-08-09'),(4,4,3,'Faltaría más detalle sobre la dieta.','2026-08-02'),(7,15,5,'ggggggggg','2026-10-27'),(8,16,3,'3232','2026-10-27'),(9,18,4,'hhgffffcfggg','2026-10-27'),(10,3,4,'muchas gracias','2026-10-28'),(11,4,1,'no me gusto','2026-10-28');
/*!40000 ALTER TABLE `encuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'medico',
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Luis Fagúndez','luis.fagundez@gmail.com','$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66','administrativo',1),(2,'Gabriel Barboza','gabriel.barboza@gmail.com','$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66','medico',1),(3,'María Rodríguez','maria.rodriguez@sigsm.uy','$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66','medico',1),(4,'Carlos Pérez','carlos.perez@sigsm.uy','$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66','medico',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vista_documentos_activos`
--

DROP TABLE IF EXISTS `vista_documentos_activos`;
/*!50001 DROP VIEW IF EXISTS `vista_documentos_activos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_documentos_activos` AS SELECT
 1 AS `id_documento`,
  1 AS `titulo`,
  1 AS `descripcion`,
  1 AS `categoria`,
  1 AS `fecha_subida` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vista_resultados_encuestas`
--

DROP TABLE IF EXISTS `vista_resultados_encuestas`;
/*!50001 DROP VIEW IF EXISTS `vista_resultados_encuestas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_resultados_encuestas` AS SELECT
 1 AS `id_documento`,
  1 AS `titulo`,
  1 AS `categoria`,
  1 AS `activo`,
  1 AS `total_respuestas`,
  1 AS `promedio` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'sigsm'
--

--
-- Dumping routines for database 'sigsm'
--

--
-- Current Database: `sigsm`
--

USE `sigsm`;

--
-- Final view structure for view `vista_documentos_activos`
--

/*!50001 DROP VIEW IF EXISTS `vista_documentos_activos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_documentos_activos` AS select `d`.`id_documento` AS `id_documento`,`d`.`titulo` AS `titulo`,`d`.`descripcion` AS `descripcion`,`c`.`nombre` AS `categoria`,`d`.`fecha_subida` AS `fecha_subida` from (`documento` `d` join `categoria` `c` on(`d`.`id_categoria` = `c`.`id_categoria`)) where `d`.`activo` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_resultados_encuestas`
--

/*!50001 DROP VIEW IF EXISTS `vista_resultados_encuestas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_resultados_encuestas` AS select `d`.`id_documento` AS `id_documento`,`d`.`titulo` AS `titulo`,`c`.`nombre` AS `categoria`,`d`.`activo` AS `activo`,count(`e`.`id_encuesta`) AS `total_respuestas`,avg(`e`.`calificacion`) AS `promedio` from ((`documento` `d` join `categoria` `c` on(`d`.`id_categoria` = `c`.`id_categoria`)) left join `encuesta` `e` on(`d`.`id_documento` = `e`.`id_documento`)) group by `d`.`id_documento`,`d`.`titulo`,`c`.`nombre`,`d`.`activo` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-29 11:31:17
