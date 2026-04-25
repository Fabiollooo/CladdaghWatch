
/* DEPRICIATED !!!!!!!!!! */






CREATE DATABASE  IF NOT EXISTS `cwp_roster` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `cwp_roster`;
-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: cwp_roster
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `cw_county`
--

DROP TABLE IF EXISTS `cw_county`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cw_county` (
  `idcounty` int(11) NOT NULL AUTO_INCREMENT,
  `countyName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcounty`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cw_county`
--

LOCK TABLES `cw_county` WRITE;
/*!40000 ALTER TABLE `cw_county` DISABLE KEYS */;
INSERT INTO `cw_county` VALUES (1,'Antrim'),(2,'Armagh'),(3,'Carlow'),(4,'Cavan'),(5,'Clare'),(6,'Cork'),(7,'Donegal'),(8,'Down'),(9,'Dublin'),(10,'DunLaoghaire-Rathdown'),(11,'Fermanagh'),(12,'Fingal'),(13,'Galway'),(14,'Kerry'),(15,'Kildare'),(16,'Kilkenny'),(17,'Laois'),(18,'Leitrim'),(19,'Limerick'),(20,'Londonderry'),(21,'Longford'),(22,'Louth'),(23,'Mayo'),(24,'Meath'),(25,'Monaghan'),(26,'North Tipperary'),(27,'Offaly'),(28,'Roscommon'),(29,'Sligo'),(30,'South Dublin'),(31,'South Tipperary'),(32,'Tipperary'),(33,'Tyrone'),(34,'Waterford'),(35,'Westmeath'),(36,'Wexford'),(37,'Wicklow'),(99,'Unknown County');
/*!40000 ALTER TABLE `cw_county` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cw_patrol_roster`
--

DROP TABLE IF EXISTS `cw_patrol_roster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cw_patrol_roster` (
  `volunteer_ID_Nr` int(11) NOT NULL,
  `patrolNr` int(11) NOT NULL,
  PRIMARY KEY (`volunteer_ID_Nr`,`patrolNr`),
  KEY `fk_patrol_schedule_has_user_user1_idx` (`volunteer_ID_Nr`),
  KEY `fk_cw_patrol_roster_cw_patrol_schedule1_idx` (`patrolNr`),
  CONSTRAINT `fk_cw_patrol_roster_cw_patrol_schedule1` FOREIGN KEY (`patrolNr`) REFERENCES `cw_patrol_schedule` (`patrolNr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_patrol_schedule_has_user_user1` FOREIGN KEY (`volunteer_ID_Nr`) REFERENCES `cw_user` (`UserNr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cw_patrol_roster`
--

LOCK TABLES `cw_patrol_roster` WRITE;
/*!40000 ALTER TABLE `cw_patrol_roster` DISABLE KEYS */;
INSERT INTO `cw_patrol_roster` VALUES (2,0),(5,0),(6,0);
/*!40000 ALTER TABLE `cw_patrol_roster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cw_patrol_schedule`
--

DROP TABLE IF EXISTS `cw_patrol_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cw_patrol_schedule` (
  `patrolNr` int(11) NOT NULL AUTO_INCREMENT,
  `patrolDate` date DEFAULT NULL,
  `patrolDescription` varchar(45) DEFAULT 'Regular Scheduled Patrol',
  `SuperUserNr` int(11) DEFAULT NULL,
  `patrol_status` int(11) NOT NULL,
  PRIMARY KEY (`patrolNr`),
  KEY `fk_patrol_schedule_user1_idx` (`SuperUserNr`),
  KEY `fk_cw_patrol_schedule_cw_patrol_status1_idx` (`patrol_status`),
  CONSTRAINT `fk_cw_patrol_schedule_cw_patrol_status1` FOREIGN KEY (`patrol_status`) REFERENCES `cw_patrol_status` (`patrol_status_nr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_patrol_schedule_user1` FOREIGN KEY (`SuperUserNr`) REFERENCES `cw_user` (`UserNr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cw_patrol_schedule`
--

LOCK TABLES `cw_patrol_schedule` WRITE;
/*!40000 ALTER TABLE `cw_patrol_schedule` DISABLE KEYS */;
INSERT INTO `cw_patrol_schedule` VALUES (1,'2025-11-23','Regular Scheduled Patrol',42,0),(2,'2025-12-24','Regular Scheduled Patrol',44,0),(3,'2026-01-30','Regular Scheduled Patrol',42,0),(4,'2026-01-31','Regular Scheduled Patrol',44,0),(5,'2026-02-06','Regular Scheduled Patrol',42,0),(6,'2026-02-07','Regular Scheduled Patrol',46,0),(7,'2026-02-13','Regular Scheduled Patrol',46,0),(8,'2026-02-14','Regular Scheduled Patrol',46,0),(9,'2026-02-20','Regular Scheduled Patrol',46,0),(10,'2026-02-21','Regular Scheduled Patrol',46,0),(11,'2026-02-27','Regular Scheduled Patrol',46,0),(12,'2026-02-28','Regular Scheduled Patrol',46,0),(13,'2026-03-06','Regular Scheduled Patrol',46,0),(14,'2026-03-07','Regular Scheduled Patrol',46,0),(15,'2026-03-13','Regular Scheduled Patrol',46,0),(16,'2026-03-14','Regular Scheduled Patrol',46,0),(17,'2026-03-20','Regular Scheduled Patrol',46,0),(18,'2026-03-21','Regular Scheduled Patrol',46,0),(19,'2026-03-27','Regular Scheduled Patrol',46,0),(20,'2026-03-28','Regular Scheduled Patrol',46,0),(21,'2026-04-03','Regular Scheduled Patrol',46,0),(22,'2026-04-04','Regular Scheduled Patrol',46,0),(23,'2026-04-10','Regular Scheduled Patrol',46,0),(24,'2026-04-11','Regular Scheduled Patrol',46,0),(25,'2026-04-17','Regular Scheduled Patrol',46,0);
/*!40000 ALTER TABLE `cw_patrol_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cw_patrol_status`
--

DROP TABLE IF EXISTS `cw_patrol_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cw_patrol_status` (
  `patrol_status_nr` int(11) NOT NULL,
  `patrol_status_description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`patrol_status_nr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cw_patrol_status`
--

LOCK TABLES `cw_patrol_status` WRITE;
/*!40000 ALTER TABLE `cw_patrol_status` DISABLE KEYS */;
INSERT INTO `cw_patrol_status` VALUES (0,'Not Released'),(1,'Released for Rostering'),(2,'Suspended'),(3,'Postponed'),(4,'Roster Finalised');
/*!40000 ALTER TABLE `cw_patrol_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cw_user`
--

DROP TABLE IF EXISTS `cw_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cw_user` (
  `UserNr` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `PassWord` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `userID` varchar(45) DEFAULT NULL,
  `userEnabled` tinyint(4) DEFAULT 1,
  `userTypeNr` int(11) NOT NULL DEFAULT 99,
  `idcounty` int(11) DEFAULT NULL,
  PRIMARY KEY (`UserNr`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `userID_UNIQUE` (`userID`),
  KEY `fk_cw_user_cw_usertype1_idx` (`userTypeNr`),
  KEY `fk_cw_user_cw_county1_idx` (`idcounty`),
  CONSTRAINT `fk_cw_user_cw_county1` FOREIGN KEY (`idcounty`) REFERENCES `cw_county` (`idcounty`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cw_user_cw_usertype1` FOREIGN KEY (`userTypeNr`) REFERENCES `cw_usertype` (`userTypeNr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cw_user`
--

LOCK TABLES `cw_user` WRITE;
/*!40000 ALTER TABLE `cw_user` DISABLE KEYS */;
INSERT INTO `cw_user` VALUES (1,'Gerry','Guinane','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','gerryguinane@claddaghwatch.ie','0875869745','gerryguinane@claddaghwatch.ie',1,4,NULL),(2,'Jane','Murphy','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','janeh@mail.com','0871234567','janeh@mail.com',1,3,NULL),(3,'Harry','Boland','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','harry@lit.ie','01234567','harry@lit.ie',1,3,NULL),(4,'Deborah','Carr','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','deborahcarr@claddaghwatch.ie','0875426987','deborahcarr@claddaghwatch.ie',1,3,NULL),(5,'James','Murphy','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','james@framework.com','0862356897','james@framework.com',1,3,NULL),(6,'Jack','McKeown','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','jack@lit.ie','0875458745','jack@lit.ie',1,3,NULL),(25,'elvis','presley','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','presley@tus.ie','0865478745','presley@tus.ie',1,3,NULL),(42,'Arthur','Carr','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','arthurcarr@claddaghwatch.ie','085457854','arthurcarr@claddaghwatch.ie',1,3,NULL),(44,'Jimmy','O','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','jimmyo@claddaghwatch.ie','0854789654','jimmyo@claddaghwatch.ie',1,3,NULL),(45,'John','Customer2','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','cust2@cust.com','0587458745','cust2@cust.com',1,3,NULL),(46,'Not','Assigned','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','notassigned@claddaghwatch.ie','000000','notassigned@claddaghwatch.ie',0,3,NULL);
/*!40000 ALTER TABLE `cw_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cw_usertype`
--

DROP TABLE IF EXISTS `cw_usertype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cw_usertype` (
  `userTypeNr` int(11) NOT NULL,
  `userTypeDescr` varchar(45) NOT NULL DEFAULT 'UNKNOWN',
  PRIMARY KEY (`userTypeNr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cw_usertype`
--

LOCK TABLES `cw_usertype` WRITE;
/*!40000 ALTER TABLE `cw_usertype` DISABLE KEYS */;
INSERT INTO `cw_usertype` VALUES (1,'ADMIN'),(2,'MANAGER'),(3,'VOLUNTEER'),(4,'SUPER'),(99,'UNKNOWN');
/*!40000 ALTER TABLE `cw_usertype` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-26 15:27:21