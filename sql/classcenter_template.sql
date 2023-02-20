-- MySQL dump 10.13  Distrib 5.6.50, for Linux (x86_64)
--
-- Host: localhost    Database: classcenter_template
-- ------------------------------------------------------
-- Server version	5.6.50-log

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
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `mc` char(100) NOT NULL,
  `dengji` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  `people` char(100) NOT NULL,
  `people_num` char(100) NOT NULL DEFAULT '0',
  `author` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collect_list`
--

DROP TABLE IF EXISTS `collect_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collect_list` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `people` text NOT NULL,
  `people_num` char(100) NOT NULL,
  `file_rename` text NOT NULL,
  `ifrename` char(20) NOT NULL,
  `file_format` char(100) NOT NULL,
  `file_format_num` char(100) NOT NULL,
  `author` char(100) NOT NULL,
  `classid` char(100) NOT NULL,
  `bond` text NOT NULL,
  `time` char(100) NOT NULL,
  `notice` text NOT NULL,
  `time_frame` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20001 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collect_list`
--

LOCK TABLES `collect_list` WRITE;
/*!40000 ALTER TABLE `collect_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `collect_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc`
--

DROP TABLE IF EXISTS `doc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `r_time` char(100) NOT NULL,
  `dengji` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  `place` char(255) NOT NULL,
  `classid` char(100) NOT NULL,
  `author` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc`
--

LOCK TABLES `doc` WRITE;
/*!40000 ALTER TABLE `doc` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fee`
--

DROP TABLE IF EXISTS `fee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `fee` char(255) NOT NULL,
  `after_f` char(255) NOT NULL,
  `method` char(10) NOT NULL,
  `author` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fee`
--

LOCK TABLES `fee` WRITE;
/*!40000 ALTER TABLE `fee` DISABLE KEYS */;
/*!40000 ALTER TABLE `fee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `author` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notice`
--

LOCK TABLES `notice` WRITE;
/*!40000 ALTER TABLE `notice` DISABLE KEYS */;
/*!40000 ALTER TABLE `notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `mc` char(100) NOT NULL,
  `dengji` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  `classid` char(50) NOT NULL,
  `author` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `yt` text NOT NULL,
  `fee` char(255) NOT NULL,
  `payment` char(100) NOT NULL,
  `method` char(10) NOT NULL,
  `photo1` char(255) NOT NULL,
  `photo2` char(255) NOT NULL,
  `classid` char(100) NOT NULL,
  `author` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  `ps` text NOT NULL,
  `pf_author` char(100) NOT NULL,
  `pf_time` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `register_list`
--

DROP TABLE IF EXISTS `register_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `register_list` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `people` text NOT NULL,
  `people_num` char(100) NOT NULL,
  `author` char(100) NOT NULL,
  `classid` char(100) NOT NULL,
  `bond` text NOT NULL,
  `time` char(100) NOT NULL,
  `is_public` varchar(1) NOT NULL,
  `time_frame` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `register_list`
--

LOCK TABLES `register_list` WRITE;
/*!40000 ALTER TABLE `register_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `register_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `say`
--

DROP TABLE IF EXISTS `say`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `say` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banwei` char(100) NOT NULL,
  `title` char(200) NOT NULL,
  `content` text NOT NULL,
  `shiming` char(20) NOT NULL,
  `author` char(100) NOT NULL,
  `time` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `say`
--

LOCK TABLES `say` WRITE;
/*!40000 ALTER TABLE `say` DISABLE KEYS */;
/*!40000 ALTER TABLE `say` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_info`
--

DROP TABLE IF EXISTS `system_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_info` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `tag` char(100) NOT NULL,
  `content` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_info`
--

LOCK TABLES `system_info` WRITE;
/*!40000 ALTER TABLE `system_info` DISABLE KEYS */;
INSERT INTO `system_info` VALUES (1,'classmoney','0');
/*!40000 ALTER TABLE `system_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` bigint(10) NOT NULL DEFAULT '0',
  `classid` varchar(10) DEFAULT NULL,
  `name` varchar(3) DEFAULT NULL,
  `sex` varchar(4) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `xiaoqu` varchar(10) DEFAULT NULL,
  `xueyuan` varchar(10) DEFAULT NULL,
  `zhuanye` varchar(8) DEFAULT NULL,
  `class` varchar(11) DEFAULT NULL,
  `tel` bigint(11) DEFAULT NULL,
  `usergroup` varchar(4) DEFAULT NULL,
  `zhiwu` varchar(7) DEFAULT NULL,
  `sushe` varchar(7) DEFAULT NULL,
  `zzmm` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'classcenter_template'
--

--
-- Dumping routines for database 'classcenter_template'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-19 11:37:22
