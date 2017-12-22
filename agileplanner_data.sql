-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: agileplan
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `developers`
--

DROP TABLE IF EXISTS `developers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `developers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `imageurl` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `developers`
--

LOCK TABLES `developers` WRITE;
/*!40000 ALTER TABLE `developers` DISABLE KEYS */;
INSERT INTO `developers` VALUES (2,'Alex','Stennett','bhjbjh','globe',''),(4,'Richard Mark<br>','Oelmann','s2114508','linux',NULL),(5,'Corina','Payne','ABC','map',NULL),(16,'Rich','Hill','s999999','book',NULL);
/*!40000 ALTER TABLE `developers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `effort`
--

DROP TABLE IF EXISTS `effort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `effort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskid` int(11) DEFAULT NULL,
  `developerid` int(11) DEFAULT NULL,
  `weekid` int(11) DEFAULT NULL,
  `effort` int(11) DEFAULT NULL,
  `committed` char(1) DEFAULT NULL,
  `effortcol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `effort`
--

LOCK TABLES `effort` WRITE;
/*!40000 ALTER TABLE `effort` DISABLE KEYS */;
/*!40000 ALTER TABLE `effort` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `epic`
--

DROP TABLE IF EXISTS `epic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `epic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systemid` int(11) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` longtext,
  `deadline` date DEFAULT NULL,
  `notes` longtext,
  `icon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `epic`
--

LOCK TABLES `epic` WRITE;
/*!40000 ALTER TABLE `epic` DISABLE KEYS */;
INSERT INTO `epic` VALUES (1,1,'EMA','Create bridge between Moode and SITS','2018-09-01','Due Dates, Feedback dates, grades','check'),(2,1,'Module Guides','Automate online Module guides','2018-09-01','Vaildate and other details','book'),(3,2,'new Mahara theme','create a new theme to bring Mahara into line with Moodle and UoG branding','2018-09-01','','paintbrush');
/*!40000 ALTER TABLE `epic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system`
--

DROP TABLE IF EXISTS `system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system` varchar(45) DEFAULT NULL,
  `productowner` varchar(45) DEFAULT NULL,
  `customercontact` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system`
--

LOCK TABLES `system` WRITE;
/*!40000 ALTER TABLE `system` DISABLE KEYS */;
INSERT INTO `system` VALUES (1,'Moodle','Richard Oelmann','ADU'),(2,'Mahara','Richard Oelmann','ADU'),(3,'Data Warehouse','Alex Stennett','Various'),(4,'Planet eStream','Richard Oelmann<br>','ADU');
/*!40000 ALTER TABLE `system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('UserStory','Task','SubTask') DEFAULT NULL,
  `epicid` int(11) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` blob,
  `deadline` datetime DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `notes` blob,
  `MoSCoW` int(11) DEFAULT NULL,
  `Releasability` int(11) DEFAULT NULL,
  `Risk` int(11) DEFAULT NULL,
  `DependenciesUpstream` int(11) DEFAULT NULL,
  `DependenciesDownstream` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `velocity`
--

DROP TABLE IF EXISTS `velocity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `velocity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `developerid` int(11) DEFAULT NULL,
  `weekid` int(11) DEFAULT NULL,
  `plannedvelocity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `velocity`
--

LOCK TABLES `velocity` WRITE;
/*!40000 ALTER TABLE `velocity` DISABLE KEYS */;
/*!40000 ALTER TABLE `velocity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weeks`
--

DROP TABLE IF EXISTS `weeks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weeks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weekcommencing` date DEFAULT NULL,
  `sprint` int(11) DEFAULT NULL,
  `events` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weeks`
--

LOCK TABLES `weeks` WRITE;
/*!40000 ALTER TABLE `weeks` DISABLE KEYS */;
INSERT INTO `weeks` VALUES (1,'2017-01-01',13,'New Year<br>'),(2,'2017-01-08',13,'Exam week, Semester2 starts<br>'),(3,'2017-01-15',13,'<br>'),(4,'2017-01-22',14,'<br>'),(5,'2017-01-29',14,NULL),(6,'2017-02-05',14,'<br>'),(7,'2017-02-12',15,'<br>'),(8,'2017-02-19',15,'<br>'),(9,'2017-02-26',15,NULL),(10,'2017-03-05',16,'<br>'),(11,'2017-03-12',16,'<br>'),(12,'2017-03-19',16,'<br>'),(13,'2017-03-26',17,'RO MoodleMoot<br>'),(14,'2017-04-02',17,NULL),(15,'2017-04-09',17,NULL),(16,'2017-04-16',18,NULL),(17,'2017-04-23',18,NULL),(18,'2017-04-30',18,NULL),(19,'2017-05-07',19,NULL),(20,'2017-05-14',19,NULL),(21,'2017-05-21',19,'<br>'),(22,'2017-05-28',20,NULL),(23,'2017-06-04',20,NULL),(24,'2017-06-11',20,NULL),(25,'2017-06-18',21,NULL),(26,'2017-06-25',21,NULL),(27,'2017-07-02',21,NULL),(28,'2017-07-09',22,NULL),(29,'2017-07-16',22,NULL),(30,'2017-07-23',22,NULL),(31,'2017-07-30',23,NULL),(32,'2017-08-06',23,NULL),(33,'2017-08-13',23,NULL),(34,'2017-08-20',24,NULL),(35,'2017-08-27',24,NULL),(36,'2017-09-03',24,'<br>'),(37,'2017-09-10',0,NULL),(38,'2017-09-17',0,NULL),(39,'2017-09-24',0,NULL),(40,'2017-10-01',0,NULL),(41,'2017-10-08',0,NULL),(42,'2017-10-15',0,NULL),(43,'2017-10-22',0,NULL),(44,'2017-10-29',0,NULL),(45,'2017-11-05',0,NULL),(46,'2017-11-12',0,NULL),(47,'2017-11-19',0,NULL),(48,'2017-11-26',0,NULL),(49,'2017-12-03',0,NULL),(50,'2017-12-10',0,NULL),(51,'2017-12-17',0,NULL),(52,'2017-12-24',0,NULL),(53,'2017-12-31',0,NULL),(54,'2018-01-07',0,NULL),(55,'2018-01-14',0,NULL),(56,'2018-01-21',0,NULL),(57,'2018-01-28',0,NULL),(58,'2018-02-04',0,NULL),(59,'2018-02-11',0,NULL),(60,'2018-02-18',0,NULL),(61,'2018-02-25',0,NULL),(62,'2018-03-04',0,NULL),(63,'2018-03-11',0,NULL),(64,'2018-03-18',0,NULL),(65,'2018-03-25',0,NULL),(66,'2018-04-01',0,NULL),(67,'2018-04-08',0,NULL),(68,'2018-04-15',0,NULL),(69,'2018-04-22',0,NULL),(70,'2018-04-29',0,NULL),(71,'2018-05-06',0,NULL),(72,'2018-05-13',0,NULL),(73,'2018-05-20',0,NULL),(74,'2018-05-27',0,NULL),(75,'2018-06-03',0,NULL),(76,'2018-06-10',0,NULL),(77,'2018-06-17',0,NULL),(78,'2018-06-24',0,NULL),(79,'2018-07-01',0,NULL),(80,'2018-07-08',0,NULL),(81,'2018-07-15',0,NULL),(82,'2018-07-22',0,NULL),(83,'2018-07-29',0,NULL),(84,'2018-08-05',0,NULL),(85,'2018-08-12',0,NULL),(86,'2018-08-19',0,NULL),(87,'2018-08-26',0,NULL),(88,'2018-09-02',0,NULL),(89,'2018-09-09',0,NULL),(90,'2018-09-16',0,NULL),(91,'2018-09-23',0,NULL),(92,'2018-09-30',0,NULL),(93,'2018-10-07',0,NULL),(94,'2018-10-14',0,NULL),(95,'2018-10-21',0,NULL),(96,'2018-10-28',0,NULL),(97,'2018-11-04',0,NULL),(98,'2018-11-11',0,NULL),(99,'2018-11-18',0,NULL),(100,'2018-11-25',0,NULL),(101,'2018-12-02',0,NULL),(102,'2018-12-09',0,NULL),(103,'2018-12-16',0,NULL),(104,'2018-12-23',0,NULL),(105,'2018-12-30',0,NULL),(106,'2019-01-06',0,NULL),(107,'2019-01-13',0,NULL),(108,'2019-01-20',0,NULL),(109,'2019-01-27',0,NULL),(110,'2019-02-03',0,NULL),(111,'2019-02-10',0,NULL),(112,'2019-02-17',0,NULL),(113,'2019-02-24',0,NULL),(114,'2019-03-03',0,NULL),(115,'2019-03-10',0,NULL),(116,'2019-03-17',0,NULL),(117,'2019-03-24',0,NULL),(118,'2019-03-31',0,NULL),(119,'2019-04-07',0,NULL),(120,'2019-04-14',0,NULL),(121,'2019-04-21',0,NULL),(122,'2019-04-28',0,NULL),(123,'2019-05-05',0,NULL),(124,'2019-05-12',0,NULL),(125,'2019-05-19',0,NULL),(126,'2019-05-26',0,NULL),(127,'2019-06-02',0,NULL),(128,'2019-06-09',0,NULL),(129,'2019-06-16',0,NULL),(130,'2019-06-23',0,NULL),(131,'2019-06-30',0,NULL),(132,'2019-07-07',0,NULL),(133,'2019-07-14',0,NULL),(134,'2019-07-21',0,NULL),(135,'2019-07-28',0,NULL),(136,'2019-08-04',0,NULL),(137,'2019-08-11',0,NULL),(138,'2019-08-18',0,NULL),(139,'2019-08-25',0,NULL),(140,'2019-09-01',0,NULL),(141,'2019-09-08',0,NULL),(142,'2019-09-15',0,NULL),(143,'2019-09-22',0,NULL),(144,'2019-09-29',0,NULL),(145,'2019-10-06',0,NULL),(146,'2019-10-13',0,NULL),(147,'2019-10-20',0,NULL),(148,'2019-10-27',0,NULL),(149,'2019-11-03',0,NULL),(150,'2019-11-10',0,NULL),(151,'2019-11-17',0,NULL),(152,'2019-11-24',0,NULL),(153,'2019-12-01',0,NULL),(154,'2019-12-08',0,NULL),(155,'2019-12-15',0,NULL),(156,'2019-12-22',0,NULL),(157,'2019-12-29',0,NULL),(158,'2020-01-05',0,NULL),(159,'2020-01-12',0,NULL),(160,'2020-01-19',0,NULL),(161,'2020-01-26',0,NULL),(162,'2020-02-02',0,NULL),(163,'2020-02-09',0,NULL),(164,'2020-02-16',0,NULL),(165,'2020-02-23',0,NULL),(166,'2020-03-01',0,NULL),(167,'2020-03-08',0,NULL),(168,'2020-03-15',0,NULL),(169,'2020-03-22',0,NULL),(170,'2020-03-29',0,NULL),(171,'2020-04-05',0,NULL),(172,'2020-04-12',0,NULL),(173,'2020-04-19',0,NULL),(174,'2020-04-26',0,NULL),(175,'2020-05-03',0,NULL),(176,'2020-05-10',0,NULL),(177,'2020-05-17',0,NULL),(178,'2020-05-24',0,NULL),(179,'2020-05-31',0,NULL),(180,'2020-06-07',0,NULL),(181,'2020-06-14',0,NULL),(182,'2020-06-21',0,NULL),(183,'2020-06-28',0,NULL),(184,'2020-07-05',0,NULL),(185,'2020-07-12',0,NULL),(186,'2020-07-19',0,NULL),(187,'2020-07-26',0,NULL),(188,'2020-08-02',0,NULL),(189,'2020-08-09',0,NULL),(190,'2020-08-16',0,NULL),(191,'2020-08-23',0,NULL),(192,'2020-08-30',0,NULL),(193,'2020-09-06',0,NULL),(194,'2020-09-13',0,NULL),(195,'2020-09-20',0,NULL),(196,'2020-09-27',0,NULL),(197,'2020-10-04',0,NULL),(198,'2020-10-11',0,NULL),(199,'2020-10-18',0,NULL),(200,'2020-10-25',0,NULL),(201,'2020-11-01',0,NULL),(202,'2020-11-08',0,NULL),(203,'2020-11-15',0,NULL),(204,'2020-11-22',0,NULL),(205,'2020-11-29',0,NULL),(206,'2020-12-06',0,NULL),(207,'2020-12-13',0,NULL),(208,'2020-12-20',0,NULL),(209,'2020-12-27',0,NULL),(210,'2021-01-03',0,NULL),(211,'2021-01-10',0,NULL),(212,'2021-01-17',0,NULL),(213,'2021-01-24',0,NULL),(214,'2021-01-31',0,NULL),(215,'2021-02-07',0,NULL),(216,'2021-02-14',0,NULL),(217,'2021-02-21',0,NULL),(218,'2021-02-28',0,NULL),(219,'2021-03-07',0,NULL),(220,'2021-03-14',0,NULL),(221,'2021-03-21',0,NULL),(222,'2021-03-28',0,NULL),(223,'2021-04-04',0,NULL),(224,'2021-04-11',0,NULL),(225,'2021-04-18',0,NULL),(226,'2021-04-25',0,NULL),(227,'2021-05-02',0,NULL),(228,'2021-05-09',0,NULL),(229,'2021-05-16',0,NULL),(230,'2021-05-23',0,NULL),(231,'2021-05-30',0,NULL),(232,'2021-06-06',0,NULL),(233,'2021-06-13',0,NULL),(234,'2021-06-20',0,NULL),(235,'2021-06-27',0,NULL),(236,'2021-07-04',0,NULL),(237,'2021-07-11',0,NULL),(238,'2021-07-18',0,NULL),(239,'2021-07-25',0,NULL),(240,'2021-08-01',0,NULL),(241,'2021-08-08',0,NULL),(242,'2021-08-15',0,NULL),(243,'2021-08-22',0,NULL),(244,'2021-08-29',0,NULL),(245,'2021-09-05',0,NULL),(246,'2021-09-12',0,NULL),(247,'2021-09-19',0,NULL),(248,'2021-09-26',0,NULL),(249,'2021-10-03',0,NULL),(250,'2021-10-10',0,NULL),(251,'2021-10-17',0,NULL);
/*!40000 ALTER TABLE `weeks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-22 21:28:47
