-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: WL
-- ------------------------------------------------------
-- Server version	5.5.38-0+wheezy1

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
-- Table structure for table `hits`
--

DROP TABLE IF EXISTS `hits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hits` (
  `hit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `php_date` varchar(8) NOT NULL,
  `php_time` varchar(8) NOT NULL,
  `php_remote_addr` varchar(20) NOT NULL,
  `php_http_referer` varchar(2048) NOT NULL,
  `php_http_user_agent` varchar(2048) DEFAULT NULL,
  `ua_os_family` varchar(20) DEFAULT NULL,
  `ua_os_version` varchar(20) DEFAULT NULL,
  `ua_os_platform` varchar(20) DEFAULT NULL,
  `ua_browser_wow64` varchar(20) DEFAULT NULL,
  `ua_browser_name` varchar(20) DEFAULT NULL,
  `ua_browser_version` varchar(20) DEFAULT NULL,
  `pd_os` varchar(20) DEFAULT NULL,
  `pd_br` varchar(40) DEFAULT NULL,
  `pd_br_ver` varchar(20) DEFAULT NULL,
  `pd_br_ver_full` varchar(40) DEFAULT NULL,
  `me_mshtml_build` varchar(20) DEFAULT NULL,
  `be_office` varchar(20) DEFAULT NULL,
  `pd_reader` varchar(20) DEFAULT NULL,
  `pd_flash` varchar(20) DEFAULT NULL,
  `pd_java` varchar(20) DEFAULT NULL,
  `pd_qt` varchar(20) DEFAULT NULL,
  `pd_rp` varchar(20) DEFAULT NULL,
  `pd_shock` varchar(20) DEFAULT NULL,
  `pd_silver` varchar(20) DEFAULT NULL,
  `pd_wmp` varchar(20) DEFAULT NULL,
  `pd_vlc` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`hit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invites` (
  `invite_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invite` varchar(32) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`invite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loads`
--

DROP TABLE IF EXISTS `loads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loads` (
  `load_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `throw_id` int(10) unsigned DEFAULT NULL,
  `php_date` varchar(8) DEFAULT NULL,
  `php_time` varchar(8) DEFAULT NULL,
  `php_remote_addr` varchar(20) DEFAULT NULL,
  `php_http_referer` varchar(2048) DEFAULT NULL,
  `php_http_user_agent` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`load_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `taskings`
--

DROP TABLE IF EXISTS `taskings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taskings` (
  `tasking_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `date` varchar(8) NOT NULL,
  `time` varchar(8) NOT NULL,
  `random_string` varchar(200) DEFAULT NULL,
  `throw_count` varchar(8) DEFAULT NULL,
  `frontend_url` varchar(2048) DEFAULT NULL,
  `backend_url` varchar(2048) DEFAULT NULL,
  `iframe_flag` varchar(200) DEFAULT NULL,
  `iframe_url` varchar(2048) DEFAULT NULL,
  `iframe_title` varchar(2048) DEFAULT NULL,
  `iframe_icon_url` varchar(2048) DEFAULT NULL,
  `debug_flag` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`tasking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `throws`
--

DROP TABLE IF EXISTS `throws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `throws` (
  `throw_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hit_id` int(10) unsigned DEFAULT NULL,
  `php_date` varchar(8) DEFAULT NULL,
  `php_time` varchar(8) DEFAULT NULL,
  `msf_exploit_full_path` varchar(2048) DEFAULT NULL,
  `msf_target` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`throw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `pass` char(40) NOT NULL,
  `user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `login` (`pass`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_invites`
--

DROP TABLE IF EXISTS `users_invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_invites` (
  `user_invite_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `invite_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_invite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-26 14:20:39
