CREATE DATABASE  IF NOT EXISTS `luispuli_dab` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `luispuli_dab`;
-- MySQL dump 10.13  Distrib 5.5.16, for osx10.5 (i386)
--
-- Host: localhost    Database: luispuli_dab
-- ------------------------------------------------------
-- Server version	5.5.21

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
-- Table structure for table `Status`
--

DROP TABLE IF EXISTS `Status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Status` (
  `StatusId` int(11) NOT NULL AUTO_INCREMENT,
  `GameId` int(11) NOT NULL,
  `StatusBoard` varchar(800) NOT NULL,
  PRIMARY KEY (`StatusId`,`GameId`),
  KEY `fk_Status_Games1` (`GameId`),
  CONSTRAINT `fk_Status_Games1` FOREIGN KEY (`GameId`) REFERENCES `Games` (`GameId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Status`
--

LOCK TABLES `Status` WRITE;
/*!40000 ALTER TABLE `Status` DISABLE KEYS */;
/*!40000 ALTER TABLE `Status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'luispu','$2a$08$o.bTbti2LvdOeqYYKdFW8utiLnYwkrMtr77csCNIBpCGLr9eRSXQi','pulidoman@gmail.com',1,0,NULL,NULL,NULL,NULL,NULL,'127.0.0.1','2012-05-05 10:56:56','2012-04-19 11:41:56',NULL),(2,'luison','$2a$08$sF.GoS1KheKo4JxqjsR1VuMNtoA9eIkfAmIOXPVemypFC9Jk.o6X6','luis@luispulido.com',1,0,NULL,NULL,NULL,NULL,NULL,'127.0.0.1',NULL,'2012-04-19 12:32:28',NULL),(3,'luis0','$2a$08$eCQvMyP7.ZNr.s7DrYDTfeCEpEHb7Na77nIJCMas3h56d0OKQT7lG','alsdkfj@plumit.org',1,0,NULL,NULL,NULL,NULL,NULL,'127.0.0.1','2012-04-19 12:42:18','2012-04-19 12:41:59',NULL),(4,'Homie','$2a$08$1Rb296vZkUyJGX7VlI8CT.XkqkCLCSCng56ymo.E6ZWS8vQy.d61.','luis@plumit.org',1,0,NULL,NULL,NULL,NULL,NULL,'127.0.0.1','2012-05-03 18:08:46','2012-05-02 10:14:03',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sessions`
--

DROP TABLE IF EXISTS `Sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sessions` (
  `SessionKey` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `SessionUserAgent` varchar(150) DEFAULT NULL,
  `SessionLastIp` varchar(45) DEFAULT NULL,
  `SessionLastLogin` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`SessionKey`),
  KEY `fk_Sessions_Users1` (`user_id`),
  CONSTRAINT `fk_Sessions_Users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sessions`
--

LOCK TABLES `Sessions` WRITE;
/*!40000 ALTER TABLE `Sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `Sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Scores`
--

DROP TABLE IF EXISTS `Scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scores` (
  `UserId` int(11) NOT NULL,
  `GameId` int(11) NOT NULL,
  `ScoreBox` int(11) NOT NULL,
  PRIMARY KEY (`UserId`,`GameId`),
  KEY `fk_Scores_Games1` (`GameId`),
  CONSTRAINT `fk_Scores_Games1` FOREIGN KEY (`GameId`) REFERENCES `Games` (`GameId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Scores_Users1` FOREIGN KEY (`UserId`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Scores`
--

LOCK TABLES `Scores` WRITE;
/*!40000 ALTER TABLE `Scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `Scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EarlyBirds`
--

DROP TABLE IF EXISTS `EarlyBirds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EarlyBirds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EarlyBirds`
--

LOCK TABLES `EarlyBirds` WRITE;
/*!40000 ALTER TABLE `EarlyBirds` DISABLE KEYS */;
/*!40000 ALTER TABLE `EarlyBirds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Websites`
--

DROP TABLE IF EXISTS `Websites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Websites` (
  `WebsiteId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `WebsiteUrl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`WebsiteId`),
  KEY `fk_Websites_Users1` (`UserId`),
  CONSTRAINT `fk_Websites_Users1` FOREIGN KEY (`UserId`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Websites`
--

LOCK TABLES `Websites` WRITE;
/*!40000 ALTER TABLE `Websites` DISABLE KEYS */;
/*!40000 ALTER TABLE `Websites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GamePlayers`
--

DROP TABLE IF EXISTS `GamePlayers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GamePlayers` (
  `Game_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Left` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Score` int(11) NOT NULL DEFAULT '0',
  `Metadata` text NOT NULL,
  `HexColor` varchar(6) NOT NULL DEFAULT '000',
  KEY `Game_id` (`Game_id`),
  KEY `User_id` (`User_id`),
  CONSTRAINT `gameplayers_ibfk_1` FOREIGN KEY (`Game_id`) REFERENCES `Games` (`GameId`),
  CONSTRAINT `gameplayers_ibfk_2` FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GamePlayers`
--

LOCK TABLES `GamePlayers` WRITE;
/*!40000 ALTER TABLE `GamePlayers` DISABLE KEYS */;
INSERT INTO `GamePlayers` VALUES (3,1,'2012-05-02 17:00:36','0000-00-00 00:00:00',0,'joined','000'),(4,1,'2012-05-02 17:07:23','0000-00-00 00:00:00',0,'joined','000'),(5,1,'2012-05-02 17:12:03','0000-00-00 00:00:00',0,'joined','000'),(5,4,'2012-05-02 17:14:24','0000-00-00 00:00:00',0,'joined','000'),(7,4,'2012-05-02 17:24:24','0000-00-00 00:00:00',0,'created, joined','000'),(7,1,'2012-05-02 17:24:43','0000-00-00 00:00:00',0,'joined','000'),(8,1,'2012-05-02 23:20:34','0000-00-00 00:00:00',0,'created, joined','000'),(9,1,'2012-05-03 05:02:10','0000-00-00 00:00:00',0,'created, joined','000'),(9,4,'2012-05-03 05:02:45','0000-00-00 00:00:00',0,'joined','000'),(10,1,'2012-05-03 08:01:14','0000-00-00 00:00:00',0,'created, joined','000'),(10,4,'2012-05-03 08:04:36','0000-00-00 00:00:00',0,'joined','000'),(11,1,'2012-05-03 19:29:58','0000-00-00 00:00:00',0,'created, joined','000'),(12,1,'2012-05-03 19:31:41','0000-00-00 00:00:00',0,'created, joined','000'),(13,1,'2012-05-03 19:46:17','0000-00-00 00:00:00',0,'created, joined','000'),(14,1,'2012-05-03 22:26:56','0000-00-00 00:00:00',0,'created, joined','000'),(14,4,'2012-05-03 22:27:22','0000-00-00 00:00:00',0,'joined','000'),(15,1,'2012-05-04 01:08:21','0000-00-00 00:00:00',0,'created, joined','000'),(15,4,'2012-05-04 01:08:46','0000-00-00 00:00:00',0,'joined','000'),(16,1,'2012-05-05 19:07:42','0000-00-00 00:00:00',0,'created, joined','000');
/*!40000 ALTER TABLE `GamePlayers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Games`
--

DROP TABLE IF EXISTS `Games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Games` (
  `GameId` int(11) NOT NULL AUTO_INCREMENT,
  `GameStart` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `GameEnd` timestamp NULL DEFAULT NULL,
  `Slug` varchar(64) NOT NULL DEFAULT '0',
  `User_id` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`GameId`),
  KEY `User_id` (`User_id`),
  CONSTRAINT `games_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Games`
--

LOCK TABLES `Games` WRITE;
/*!40000 ALTER TABLE `Games` DISABLE KEYS */;
INSERT INTO `Games` VALUES (1,'2012-05-02 16:52:09',NULL,'f6GQWf77xz2gdY3R',1,0),(2,'2012-05-02 16:55:25',NULL,'x7FZejVLEjt7bPMv',1,0),(3,'2012-05-02 16:56:36',NULL,'oJOA4XO4OWXwwby3',1,0),(4,'2012-05-02 17:07:23',NULL,'HK24HrlNLRgmb1oM',1,0),(5,'2012-05-02 17:12:02',NULL,'cc6IxXleJSqKv7f9',1,0),(7,'2012-05-02 17:24:24',NULL,'etb94giemU1AMuv6',4,0),(8,'2012-05-02 23:20:34',NULL,'Mf9m1FtqN8pt93yk',1,0),(9,'2012-05-03 05:02:10',NULL,'fcWUzYwvAvHDGToj',1,0),(10,'2012-05-03 08:01:14',NULL,'Wg8Od6p2YYWgYNxe',1,0),(11,'2012-05-03 19:29:58',NULL,'ZB5Nhpy0b2fb7yI2',1,0),(12,'2012-05-03 19:31:41',NULL,'AhZeFxLjIKrjPjZq',1,0),(13,'2012-05-03 19:46:17',NULL,'wThYMJ7U1OJGBKgh',1,0),(14,'2012-05-03 22:26:56',NULL,'IhZOyQ1hhfV0OQWc',1,0),(15,'2012-05-04 01:08:21',NULL,'wiL9jjfKiKrQG9QG',1,0),(16,'2012-05-05 19:07:42',NULL,'oELYRmvhB9mHpDYU',1,0);
/*!40000 ALTER TABLE `Games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GameRecords`
--

DROP TABLE IF EXISTS `GameRecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GameRecords` (
  `GameRecordId` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `RecordScore` int(11) NOT NULL,
  PRIMARY KEY (`GameRecordId`),
  KEY `fk_GameRecords_Users1` (`user_id`),
  CONSTRAINT `fk_GameRecords_Users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GameRecords`
--

LOCK TABLES `GameRecords` WRITE;
/*!40000 ALTER TABLE `GameRecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `GameRecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_autologin`
--

DROP TABLE IF EXISTS `user_autologin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  KEY `fk_user_autologin_Users1` (`user_id`),
  CONSTRAINT `fk_user_autologin_Users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_autologin`
--

LOCK TABLES `user_autologin` WRITE;
/*!40000 ALTER TABLE `user_autologin` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_autologin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ProfileName` varchar(200) NOT NULL,
  `ProfileLastName` varchar(300) NOT NULL,
  `ProfileBirthday` date DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_Profiles_Users1` (`user_id`),
  CONSTRAINT `fk_Profiles_Users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profiles`
--

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;
INSERT INTO `user_profiles` VALUES (1,1,'','',NULL),(2,2,'','',NULL),(3,3,'','',NULL),(4,4,'','',NULL);
/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-05-05 16:08:59
