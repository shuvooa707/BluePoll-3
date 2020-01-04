-- MySQL dump 10.17  Distrib 10.3.16-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bluepoll
-- ------------------------------------------------------
-- Server version	10.3.16-MariaDB

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(1000) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'technology'),(2,'cinema'),(3,'art'),(4,'politics'),(5,'history'),(6,'science'),(7,'book'),(8,'music'),(9,'other');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_poll_id` int(255) NOT NULL,
  `comment_content` longtext NOT NULL,
  `comment_user_id` varchar(255) NOT NULL,
  `comment_birthdate` datetime DEFAULT NULL,
  `comment_order` int(10) unsigned DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dislikes`
--

DROP TABLE IF EXISTS `dislikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dislikes` (
  `dislike_id` int(11) NOT NULL AUTO_INCREMENT,
  `dislike_poll_id` int(11) NOT NULL,
  `dislike_disliker_id` int(11) NOT NULL,
  `dislike_disliked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`dislike_id`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dislikes`
--

LOCK TABLES `dislikes` WRITE;
/*!40000 ALTER TABLE `dislikes` DISABLE KEYS */;
INSERT INTO `dislikes` VALUES (39,73,3,'2019-11-13 12:56:04'),(40,52,3,'2019-11-13 12:56:04'),(41,27,3,'2019-11-13 12:56:05'),(52,89,7,'2019-11-15 06:44:40'),(53,91,7,'2019-11-15 06:44:43'),(54,100,7,'2019-11-15 06:44:45'),(55,89,6,'2019-11-15 06:45:04'),(185,90,7,'2019-11-17 16:45:25'),(209,102,6,'2019-11-26 11:59:20'),(210,103,6,'2019-11-26 11:59:21'),(212,105,6,'2019-11-26 11:59:33'),(243,123,7,'2020-01-04 09:38:21');
/*!40000 ALTER TABLE `dislikes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `remove_like` BEFORE INSERT ON `dislikes` FOR EACH ROW BEGIN

	DELETE FROM likes 

    	WHERE likes.like_poll_id = new.dislike_poll_id AND 

        	  likes.like_liker_id = new.dislike_disliker_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `remove_notification_for_dislike` AFTER DELETE ON `dislikes` FOR EACH ROW BEGIN

	DELETE FROM 

    	notifications 

    WHERE

    	notifications.notification_object_id = old.dislike_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `hidden_polls`
--

DROP TABLE IF EXISTS `hidden_polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hidden_polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hidden_polls`
--

LOCK TABLES `hidden_polls` WRITE;
/*!40000 ALTER TABLE `hidden_polls` DISABLE KEYS */;
INSERT INTO `hidden_polls` VALUES (66,90,6),(67,102,6),(68,100,6),(69,91,6),(70,103,6),(71,112,6),(72,114,6),(84,110,6),(85,115,6),(86,116,6);
/*!40000 ALTER TABLE `hidden_polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `like_poll_id` int(11) NOT NULL,
  `like_liker_id` int(11) NOT NULL,
  `like_liked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`like_id`)
) ENGINE=InnoDB AUTO_INCREMENT=450 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (356,107,3,'2019-11-26 11:10:04'),(370,100,3,'2019-11-26 11:13:01'),(372,91,3,'2019-11-26 11:38:32'),(373,102,3,'2019-11-26 11:38:36'),(374,105,3,'2019-11-26 11:38:40'),(377,100,6,'2019-11-26 11:59:18'),(381,91,6,'2019-11-26 12:36:07'),(383,90,6,'2019-11-26 12:42:28'),(384,90,3,'2019-12-03 14:26:31'),(385,103,3,'2019-12-17 14:05:29'),(389,102,4,'2019-12-26 02:13:42'),(390,105,4,'2019-12-26 02:41:03'),(393,100,4,'2019-12-26 16:52:21'),(394,91,4,'2019-12-26 16:52:31'),(396,110,3,'2020-01-04 01:32:59'),(398,103,4,'2020-01-04 04:13:32'),(400,90,4,'2020-01-04 06:32:52'),(402,110,4,'2020-01-04 09:00:20'),(449,123,6,'2020-01-04 09:55:06');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `remove_dislike` BEFORE INSERT ON `likes` FOR EACH ROW BEGIN

	DELETE FROM dislikes 

    	WHERE dislikes.dislike_poll_id = new.like_poll_id AND 

        	  dislikes.dislike_disliker_id = new.like_liker_id;



END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `remove_notification_for_like` AFTER DELETE ON `likes` FOR EACH ROW BEGIN

	DELETE FROM 

    	notifications 

    WHERE

    	notifications.notification_object_id = old.like_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_actor_id` int(11) NOT NULL,
  `notification_action` varchar(1000) NOT NULL,
  `notification_poll_id` int(11) NOT NULL,
  `notification_object_id` int(11) DEFAULT NULL,
  `notification_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1117 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (719,3,'newComment',103,213,'2019-11-26 13:18:02'),(720,3,'newComment',91,214,'2019-11-26 13:18:23'),(721,3,'newComment',91,215,'2019-11-26 13:18:36'),(722,3,'newComment',91,216,'2019-11-26 13:18:37'),(728,3,'newVote',91,1,'2019-11-26 13:53:54'),(729,3,'newVote',91,1,'2019-11-26 13:53:56'),(730,3,'newVote',90,1,'2019-11-29 17:31:32'),(731,3,'newVote',102,1,'2019-11-30 12:19:52'),(732,3,'newVote',102,1,'2019-11-30 12:19:58'),(734,3,'newVote',90,1,'2019-12-03 13:22:41'),(735,3,'newVote',90,1,'2019-12-03 13:22:45'),(736,3,'newVote',91,1,'2019-12-03 13:22:52'),(737,3,'newVote',90,1,'2019-12-03 13:22:56'),(738,3,'newVote',103,1,'2019-12-03 14:23:05'),(741,3,'newLike',90,384,'2019-12-03 14:26:31'),(742,3,'newVote',90,1,'2019-12-16 19:17:06'),(746,3,'newVote',90,1,'2019-12-17 14:05:58'),(747,4,'newVote',91,1,'2019-12-17 19:10:56'),(751,4,'newVote',102,1,'2019-12-17 19:15:59'),(752,4,'newVote',91,1,'2019-12-17 19:17:13'),(754,4,'newVote',91,1,'2019-12-19 21:31:18'),(755,4,'newVote',91,1,'2019-12-19 21:31:25'),(756,4,'newVote',102,1,'2019-12-19 21:35:46'),(767,3,'newVote',105,1,'2019-12-19 21:54:01'),(771,4,'newVote',90,1,'2019-12-19 22:03:17'),(772,4,'newVote',90,1,'2019-12-20 19:58:21'),(773,4,'newVote',90,1,'2019-12-20 19:58:28'),(780,4,'newVote',109,1,'2019-12-21 19:59:12'),(781,4,'newVote',102,1,'2019-12-21 20:11:08'),(782,4,'newVote',102,1,'2019-12-21 20:11:12'),(783,4,'newVote',105,1,'2019-12-23 22:30:51'),(784,4,'newVote',105,1,'2019-12-23 22:31:00'),(785,4,'newVote',90,1,'2019-12-23 23:24:13'),(786,4,'newVote',90,1,'2019-12-23 23:24:22'),(787,4,'newVote',90,1,'2019-12-23 23:24:36'),(788,4,'newVote',90,1,'2019-12-23 23:24:38'),(790,4,'newVote',100,1,'2019-12-24 01:15:12'),(801,4,'newLike',102,389,'2019-12-26 02:13:42'),(802,4,'newLike',105,390,'2019-12-26 02:41:03'),(805,4,'newVote',90,1,'2019-12-26 04:48:59'),(806,4,'newVote',90,1,'2019-12-26 04:49:03'),(809,4,'newVote',90,1,'2019-12-26 16:46:14'),(815,4,'newVote',112,649,'2020-01-02 19:52:19'),(816,4,'newVote',112,1,'2020-01-02 19:52:23'),(817,4,'newVote',112,650,'2020-01-02 19:52:25'),(818,4,'newVote',113,651,'2020-01-02 19:57:22'),(819,4,'newVote',113,652,'2020-01-02 19:57:39'),(820,4,'newVote',114,653,'2020-01-02 19:58:56'),(822,4,'newVote',103,654,'2020-01-02 20:04:46'),(829,6,'newVote',115,660,'2020-01-03 02:38:09'),(830,6,'newVote',116,661,'2020-01-03 02:40:53'),(834,6,'newVote',115,1,'2020-01-03 02:41:18'),(835,6,'newVote',115,665,'2020-01-03 02:41:20'),(837,6,'newVote',110,1,'2020-01-03 02:52:07'),(838,6,'newVote',110,667,'2020-01-03 02:52:09'),(839,4,'newOptionRequest',100,120,'2020-01-03 03:55:35'),(842,4,'newOptionRequest',100,121,'2020-01-03 04:22:55'),(845,4,'newVote',120,668,'2020-01-03 04:40:33'),(846,4,'newOptionRequest',91,124,'2020-01-03 07:35:00'),(847,4,'newOptionRequest',100,125,'2020-01-03 23:52:25'),(848,4,'newOptionRequest',100,126,'2020-01-03 23:56:01'),(849,4,'newOptionRequest',90,127,'2020-01-03 23:58:06'),(850,4,'newVote',103,669,'2020-01-04 00:15:11'),(851,4,'newOptionRequest',100,128,'2020-01-04 00:15:33'),(852,4,'newOptionRequest',91,129,'2020-01-04 00:16:05'),(854,4,'newOptionRequest',91,131,'2020-01-04 00:30:24'),(855,4,'newOptionRequest',91,132,'2020-01-04 00:30:29'),(856,4,'newVote',102,670,'2020-01-04 00:47:28'),(857,4,'newOptionRequest',91,133,'2020-01-04 00:48:13'),(859,3,'newVote',103,672,'2020-01-04 00:49:37'),(860,3,'newVote',91,1,'2020-01-04 00:51:36'),(861,3,'newVote',91,673,'2020-01-04 00:51:38'),(862,3,'newVote',91,1,'2020-01-04 00:53:04'),(863,3,'newVote',91,674,'2020-01-04 00:53:06'),(864,3,'newVote',91,1,'2020-01-04 00:53:39'),(865,3,'newVote',91,675,'2020-01-04 00:53:41'),(873,4,'newOptionRequest',91,141,'2020-01-04 01:24:51'),(874,4,'newOptionRequest',91,142,'2020-01-04 01:24:53'),(875,4,'newOptionRequest',91,143,'2020-01-04 01:24:55'),(876,4,'newOptionRequest',91,144,'2020-01-04 01:24:57'),(877,4,'newOptionRequest',91,145,'2020-01-04 01:25:01'),(878,4,'newOptionRequest',91,146,'2020-01-04 01:25:03'),(879,4,'newOptionRequest',91,147,'2020-01-04 01:25:05'),(881,4,'newOptionRequest',91,149,'2020-01-04 01:29:52'),(882,4,'newOptionRequest',91,150,'2020-01-04 01:29:54'),(883,4,'newOptionRequest',91,151,'2020-01-04 01:29:56'),(884,4,'newOptionRequest',91,152,'2020-01-04 01:29:58'),(885,4,'newOptionRequest',91,153,'2020-01-04 01:30:00'),(886,3,'newVote',91,676,'2020-01-04 01:30:56'),(887,3,'newVote',103,1,'2020-01-04 01:31:03'),(888,3,'newVote',103,677,'2020-01-04 01:31:05'),(889,3,'newVote',103,1,'2020-01-04 01:31:13'),(891,3,'newVote',116,679,'2020-01-04 01:31:56'),(894,3,'newVote',115,682,'2020-01-04 01:32:02'),(895,3,'newLike',110,396,'2020-01-04 01:32:59'),(896,3,'newVote',91,1,'2020-01-04 01:34:57'),(897,3,'newVote',91,683,'2020-01-04 01:34:59'),(898,3,'newVote',100,1,'2020-01-04 01:35:48'),(899,3,'newVote',100,684,'2020-01-04 01:35:50'),(900,3,'newVote',100,685,'2020-01-04 01:36:11'),(901,3,'newVote',100,1,'2020-01-04 01:36:13'),(902,3,'newVote',100,1,'2020-01-04 01:36:15'),(903,3,'newVote',100,686,'2020-01-04 01:36:18'),(904,3,'newVote',100,687,'2020-01-04 01:38:49'),(905,3,'newVote',100,1,'2020-01-04 01:38:51'),(910,4,'newOptionRequest',100,154,'2020-01-04 01:54:14'),(911,4,'newVote',102,689,'2020-01-04 02:40:29'),(912,4,'newOptionRequest',91,155,'2020-01-04 02:56:26'),(913,4,'newOptionRequest',91,156,'2020-01-04 02:56:29'),(914,4,'newOptionRequest',91,157,'2020-01-04 02:56:36'),(915,4,'newLike',103,398,'2020-01-04 04:13:32'),(916,4,'newVote',103,690,'2020-01-04 06:12:40'),(917,4,'newVote',90,691,'2020-01-04 06:21:47'),(918,4,'newVote',90,1,'2020-01-04 06:23:27'),(919,4,'newVote',90,692,'2020-01-04 06:23:29'),(920,4,'newVote',90,1,'2020-01-04 06:24:16'),(921,4,'newVote',90,693,'2020-01-04 06:24:18'),(922,4,'newVote',90,1,'2020-01-04 06:24:48'),(923,4,'newVote',90,694,'2020-01-04 06:24:50'),(924,4,'newVote',90,695,'2020-01-04 06:24:55'),(925,4,'newVote',90,1,'2020-01-04 06:24:57'),(926,4,'newVote',90,1,'2020-01-04 06:27:46'),(927,4,'newVote',90,696,'2020-01-04 06:27:48'),(928,4,'newVote',90,1,'2020-01-04 06:28:28'),(929,4,'newVote',90,697,'2020-01-04 06:28:30'),(930,4,'newVote',90,698,'2020-01-04 06:28:35'),(931,4,'newVote',90,1,'2020-01-04 06:28:37'),(932,4,'newVote',90,1,'2020-01-04 06:29:49'),(933,4,'newVote',90,699,'2020-01-04 06:29:51'),(934,4,'newVote',90,1,'2020-01-04 06:30:28'),(935,4,'newVote',90,700,'2020-01-04 06:30:30'),(936,4,'newVote',90,1,'2020-01-04 06:31:19'),(942,4,'newOptionRequest',100,158,'2020-01-04 06:38:31'),(943,4,'newOptionRequest',91,159,'2020-01-04 06:41:45'),(948,4,'newOptionRequest',91,163,'2020-01-04 06:57:36'),(949,4,'newVote',102,703,'2020-01-04 07:19:45'),(950,4,'newVote',112,704,'2020-01-04 07:19:54'),(951,4,'newVote',115,705,'2020-01-04 07:20:00'),(952,4,'newOptionRequest',116,164,'2020-01-04 07:20:18'),(953,4,'newOptionRequest',116,165,'2020-01-04 07:20:43'),(954,4,'newOptionRequest',116,166,'2020-01-04 07:21:09'),(955,4,'newOptionRequest',116,167,'2020-01-04 07:21:24'),(956,4,'newOptionRequest',116,168,'2020-01-04 07:21:42'),(957,4,'newOptionRequest',116,169,'2020-01-04 07:21:51'),(958,4,'newOptionRequest',116,170,'2020-01-04 07:22:14'),(962,3,'newVote',100,709,'2020-01-04 07:22:51'),(963,3,'newOptionRequest',110,171,'2020-01-04 07:23:58'),(964,3,'newOptionRequest',110,172,'2020-01-04 07:24:03'),(968,6,'newVote',116,713,'2020-01-04 07:25:42'),(974,7,'newVote',110,719,'2020-01-04 07:26:25'),(976,7,'newVote',115,721,'2020-01-04 07:26:33'),(979,7,'newVote',110,724,'2020-01-04 07:26:48'),(980,7,'newVote',116,725,'2020-01-04 07:27:25'),(981,7,'newVote',116,1,'2020-01-04 07:27:27'),(982,6,'newVote',110,726,'2020-01-04 07:28:03'),(985,3,'newVote',91,729,'2020-01-04 07:29:06'),(986,3,'newVote',110,730,'2020-01-04 07:29:13'),(987,3,'newVote',110,731,'2020-01-04 07:29:19'),(988,3,'newVote',116,1,'2020-01-04 07:30:29'),(989,3,'newVote',116,732,'2020-01-04 07:30:31'),(991,3,'newVote',114,1,'2020-01-04 07:30:35'),(992,3,'newVote',115,1,'2020-01-04 07:30:39'),(993,3,'newVote',115,734,'2020-01-04 07:30:41'),(994,3,'newVote',90,735,'2020-01-04 07:36:31'),(995,3,'newVote',90,1,'2020-01-04 07:36:39'),(996,3,'newVote',90,736,'2020-01-04 07:36:41'),(997,4,'newVote',116,737,'2020-01-04 07:37:11'),(999,4,'newVote',110,739,'2020-01-04 07:37:36'),(1004,6,'newVote',110,741,'2020-01-04 08:21:01'),(1005,6,'newVote',110,742,'2020-01-04 08:31:18'),(1006,6,'newVote',116,743,'2020-01-04 08:31:35'),(1007,6,'newVote',115,744,'2020-01-04 08:31:37'),(1009,7,'newVote',110,746,'2020-01-04 08:32:19'),(1010,7,'newVote',110,747,'2020-01-04 08:32:26'),(1011,7,'newVote',115,748,'2020-01-04 08:32:28'),(1012,4,'newVote',110,749,'2020-01-04 08:32:44'),(1013,4,'newVote',110,750,'2020-01-04 08:32:49'),(1014,4,'newVote',115,751,'2020-01-04 08:32:54'),(1015,3,'newVote',110,752,'2020-01-04 08:33:33'),(1016,3,'newVote',110,753,'2020-01-04 08:33:38'),(1017,3,'newVote',115,754,'2020-01-04 08:33:43'),(1018,4,'newLike',110,402,'2020-01-04 09:00:20'),(1046,7,'newDislike',123,243,'2020-01-04 09:38:21'),(1116,6,'newLike',123,449,'2020-01-04 09:55:06');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(10000) NOT NULL,
  `option_belongsto` int(11) NOT NULL,
  `option_addedby_id` int(11) NOT NULL,
  `option_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `option_votes` int(11) NOT NULL DEFAULT 0,
  `option_status` varchar(20) DEFAULT 'active',
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=845 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES (6,'3 Idiots',2,4,'2019-11-01 23:28:04',0,'active'),(7,'Lagaan',2,4,'2019-11-01 23:28:04',0,'active'),(8,'Kal Ho Na Ho',2,4,'2019-11-01 23:28:04',0,'active'),(9,'Chak De Basanti',2,4,'2019-11-01 23:28:04',0,'active'),(10,'Lage Raho Munna Bhai',2,4,'2019-11-01 23:28:04',0,'active'),(11,'Think and Grow Rich by Nepolion Hill',3,4,'2019-11-01 23:31:48',0,'active'),(12,'7 Habits of Highly Effective People by Seteven Cuvy',3,4,'2019-11-01 23:31:48',0,'active'),(13,'How to Win Friends and Influence People by Dale Carnige',3,4,'2019-11-01 23:31:48',0,'active'),(14,'The Power Of Now by Eckhart Tolle',3,4,'2019-11-01 23:31:48',0,'active'),(44,'Swadesh',16,4,'2019-11-02 19:05:56',0,'active'),(45,'Lage Raho Munna Bhai',16,4,'2019-11-02 19:05:56',0,'active'),(46,'3 idiots',16,4,'2019-11-02 19:05:56',0,'active'),(47,'PK',16,4,'2019-11-02 19:05:56',0,'active'),(48,'Dhoom 2',16,4,'2019-11-02 19:05:56',0,'active'),(49,'Tare Zameen Paar',16,4,'2019-11-02 19:05:56',0,'active'),(50,'Eckhart Tolle',17,4,'2019-11-02 19:19:02',0,'active'),(51,'Steven Cuvy',17,4,'2019-11-02 19:19:02',0,'active'),(52,'Robert Greene',17,4,'2019-11-02 19:19:02',0,'active'),(56,'Think and Grow Rich by Nepolion Hill',19,4,'2019-11-02 20:56:27',0,'active'),(57,'7 Habits of Highly Effective People by Seteven Cuvy',19,4,'2019-11-02 20:56:27',0,'active'),(58,'How to Win Friends and Influence People by Dale Carnige',19,4,'2019-11-02 20:56:27',0,'active'),(59,'The Power Of Now by Eckhart Tolle',19,4,'2019-11-02 20:56:27',0,'active'),(448,'Google Chrome',105,4,'2019-11-17 16:44:33',0,'active'),(449,'Mozilla Firefox',105,4,'2019-11-17 16:44:33',0,'active'),(450,'Opera',105,4,'2019-11-17 16:44:33',0,'active'),(451,'Safari',105,4,'2019-11-17 16:44:33',0,'active'),(516,'Torch Browser',105,3,'2019-11-22 06:46:43',0,NULL),(546,'Tom Hanks',113,4,'2020-01-02 19:57:35',0,'active'),(547,'Morgan Freeman',113,4,'2020-01-02 19:57:35',0,'active'),(548,'Robert DeNero',113,4,'2020-01-02 19:57:35',0,'active'),(549,'Al Pachino',113,4,'2020-01-02 19:57:35',0,'active'),(550,'Jhonny Depp',113,4,'2020-01-02 19:57:35',0,'active'),(551,'Marlon Brando',113,4,'2020-01-02 19:57:35',0,'active'),(552,'Leonardo Decaprio',113,4,'2020-01-02 19:57:35',0,'active'),(553,'Robin Williams',113,4,'2020-01-02 19:57:35',0,'active'),(554,'Jack Nicolson',113,4,'2020-01-02 19:57:35',0,'active'),(555,'Heath Ledger',113,4,'2020-01-02 19:57:35',0,'active'),(556,'Salvestor Stalon',114,4,'2020-01-02 19:58:51',0,'active'),(557,'Arnold Swashenegar',114,4,'2020-01-02 19:58:51',0,'active'),(741,'The Dark Knight (2008)',103,4,'2020-01-04 06:12:34',0,'active'),(742,'Lord of the Rings the Return of the King  (2003)',103,4,'2020-01-04 06:12:34',0,'active'),(743,'GodFather (1973)',103,4,'2020-01-04 06:12:34',0,'active'),(744,'GodFather II (1974)',103,4,'2020-01-04 06:12:34',0,'active'),(745,'Casablanca (1942)',103,4,'2020-01-04 06:12:34',0,'active'),(746,'Goodfellas (1990)',103,4,'2020-01-04 06:12:34',0,'active'),(747,'Back to the Future (1985)',103,4,'2020-01-04 06:12:34',0,'active'),(748,'Once Upon a Time in America (1984)',103,4,'2020-01-04 06:12:34',0,'active'),(749,'Fight Club (1999)',103,4,'2020-01-04 06:12:35',0,'active'),(750,'12 Angry Men (1957)',103,4,'2020-01-04 06:12:35',0,'active'),(751,'Inception (2009)',103,4,'2020-01-04 06:12:35',0,'active'),(757,'The Dark Knight',100,3,'2020-01-04 06:46:31',0,'active'),(758,'Inception',100,3,'2020-01-04 06:46:31',0,'active'),(759,'Interstellar',100,3,'2020-01-04 06:46:31',0,'active'),(760,'Memento',100,3,'2020-01-04 06:46:31',0,'active'),(761,'The Batman',100,3,'2020-01-04 06:46:31',0,'active'),(764,'Laravel',102,4,'2020-01-04 07:19:13',0,'active'),(765,'Ruby On Rails',102,4,'2020-01-04 07:19:13',0,'active'),(766,'Django',102,4,'2020-01-04 07:19:13',0,'active'),(767,'Java Spring',102,4,'2020-01-04 07:19:13',0,'active'),(768,'ASP.NET',102,4,'2020-01-04 07:19:13',0,'active'),(769,'Flask',102,4,'2020-01-04 07:19:13',0,'active'),(770,'Kim Kardesian',112,4,'2020-01-04 07:19:27',0,'active'),(771,'Justin Bebar',112,4,'2020-01-04 07:19:27',0,'active'),(778,'Xiomi Redmi 5',91,3,'2020-01-04 07:23:29',0,'active'),(779,'iPhone XI',91,3,'2020-01-04 07:23:29',0,'active'),(780,'Nokia 10',91,3,'2020-01-04 07:23:29',0,'active'),(781,'Samsung Galaxy S10',91,3,'2020-01-04 07:23:29',0,'active'),(782,'OPPO Reno 2',91,3,'2020-01-04 07:23:29',0,'active'),(807,'Nusrat Fateh Ali Khan',90,3,'2020-01-04 07:36:26',0,'active'),(808,'Atif Aslam',90,3,'2020-01-04 07:36:26',0,'active'),(809,'Rahat Fateh Ali Khan',90,3,'2020-01-04 07:36:26',0,'active'),(810,'Quratul Blouch',90,3,'2020-01-04 07:36:26',0,'active'),(811,'Ali Zafar',90,3,'2020-01-04 07:36:26',0,'active'),(825,'Egypt',110,6,'2020-01-04 08:31:14',0,'active'),(826,'China',110,6,'2020-01-04 08:31:14',0,'active'),(827,'France',110,6,'2020-01-04 08:31:14',0,'active'),(828,'India',110,6,'2020-01-04 08:31:14',0,'active'),(829,'Srilanka',110,6,'2020-01-04 08:31:14',0,'active'),(830,'Russia',110,6,'2020-01-04 08:31:14',0,'active'),(831,'Australia',110,6,'2020-01-04 08:31:14',0,'active'),(832,'Plato',115,6,'2020-01-04 08:31:23',0,'active'),(833,'Socrates',115,6,'2020-01-04 08:31:23',0,'active'),(834,'Marcus Auralius',115,6,'2020-01-04 08:31:23',0,'active'),(835,'48 Laws of Power',116,6,'2020-01-04 08:31:30',0,'active'),(836,'Think And Grow Rich',116,6,'2020-01-04 08:31:30',0,'active'),(837,'7 Habit of Highly Effective People',116,6,'2020-01-04 08:31:30',0,'active'),(838,'Deep Work',116,6,'2020-01-04 08:31:30',0,'active'),(839,'Power of Habit',116,6,'2020-01-04 08:31:30',0,'active'),(840,'How To Win Friends',116,6,'2020-01-04 08:31:30',0,'active'),(841,'Outlier',116,6,'2020-01-04 08:31:30',0,'active'),(842,'Emotional Intelligence',116,6,'2020-01-04 08:31:30',0,'active');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_delete_option` BEFORE DELETE ON `options` FOR EACH ROW BEGIN

	DELETE FROM votes WHERE votes.vote_option_id = old.option_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `poll_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `poll_category` varchar(100) DEFAULT NULL,
  `poll_user_id` int(255) unsigned NOT NULL,
  `poll_name` longtext NOT NULL,
  `poll_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `poll_likes` int(10) unsigned DEFAULT 0,
  `poll_dislikes` int(10) unsigned DEFAULT 0,
  `poll_view` int(10) unsigned DEFAULT 0,
  `poll_status` int(11) DEFAULT 1,
  PRIMARY KEY (`poll_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
INSERT INTO `polls` VALUES (90,'music',3,'Best Pakistani Singer','2020-01-04 07:36:26',0,0,0,1),(91,'technology',3,'Best Phone of 2019','2020-01-04 07:23:29',0,0,0,1),(100,'cinema',3,'Best Film Christopher Nonal','2020-01-04 06:46:31',0,0,0,1),(102,'technology',4,'Top Backend Frameworks','2020-01-04 07:19:13',0,0,0,1),(103,'cinema',4,'Best Movie Of All Time','2020-01-04 06:12:34',0,0,0,1),(105,'technology',4,'Best Web Browser','2020-01-03 02:16:11',0,0,0,0),(110,'art',6,'Best Country For Spending Winter','2020-01-04 08:31:14',0,0,0,1),(112,'other',4,'Most Overrated Celebrity','2020-01-04 07:19:27',0,0,0,1),(113,'cinema',4,'Best Actor of All Time','2020-01-02 19:57:35',0,0,0,1),(114,'technology',4,'Best Action Hero of All Time','2020-01-02 19:58:51',0,0,0,1),(115,'other',6,'Best Philosophers of All Time','2020-01-04 08:31:23',0,0,0,1),(116,'book',6,'Must Read Self Help Books','2020-01-04 08:31:30',0,0,0,1);
/*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_delete_poll` BEFORE DELETE ON `polls` FOR EACH ROW BEGIN

	DELETE FROM options WHERE options.option_belongsto = old.poll_id;

	DELETE FROM comments WHERE comments.comment_poll_id = old.poll_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `proposed_option`
--

DROP TABLE IF EXISTS `proposed_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposed_option` (
  `proposed_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `proposed_option_name` varchar(100) NOT NULL,
  `proposed_option_poll_id` int(11) NOT NULL,
  `proposed_option_poll_added_by` int(11) NOT NULL,
  `proposed_option_poll_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`proposed_option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposed_option`
--

LOCK TABLES `proposed_option` WRITE;
/*!40000 ALTER TABLE `proposed_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposed_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saved_polls`
--

DROP TABLE IF EXISTS `saved_polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saved_polls` (
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saved_polls`
--

LOCK TABLES `saved_polls` WRITE;
/*!40000 ALTER TABLE `saved_polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `saved_polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(100) NOT NULL,
  `user_lastname` varchar(100) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_password` mediumtext NOT NULL,
  `user_email` varchar(1000) NOT NULL,
  `user_phone` int(15) NOT NULL,
  `user_age` int(10) unsigned NOT NULL,
  `user_gender` varchar(50) NOT NULL,
  `user_bloodgroup` varchar(50) NOT NULL,
  `user_education` varchar(100) NOT NULL,
  `user_profession` varchar(1000) NOT NULL,
  `user_current_city` varchar(100) NOT NULL,
  `user_from` varchar(100) NOT NULL,
  `user_avater` varchar(100) DEFAULT 'ano.jpg',
  `user_roll` varchar(100) DEFAULT 'user',
  `user_nationality` varchar(100) DEFAULT NULL,
  `user_ethnicity` varchar(100) DEFAULT NULL,
  `user_website` longtext DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Rakesh','Patel','rakesh','$2y$10$7KL1xQtIL8mFrsiVFn0T9.NhTzd5k4jfo85OGqbB4VuFr2A6pDPaa','rakesh@gmail.com',2344234,72,'Male','O+','Bcom','Film Maker','Mumbai','Mumbai','sticky Notes.PNG','user',NULL,NULL,NULL),(4,'Shuvo','Sarker','shuvo','$2y$10$lvXSAaRict2XJlCM8.6fFet6RuOooKvX7wJSIIUYK6iBaA6toSWc2','shuvooa707@gmail.com',1290129012,25,'Male','Ab+','BSc in CSE','Web Dev','Dhaka','Dhaka','sticky Notes.PNG','user',NULL,NULL,NULL),(6,'Andrew','Simon','simon','$2y$10$6X3jQX3FaordlUgrAamyUOFUnwVX1eMbXPa9RDkaxC288w1PGL9EW','simon@gmail.com',2147483647,40,'Male','O+','Journalism','Writter','New York','New York','post_5_thumpic.jpg','user',NULL,NULL,NULL),(7,'Aslam','Atif','atif','$2y$10$cz6tTNL50zuh.CKuXsAuXesDWTG4RfaSlMAMsWRkh1rSkqb7jJfgC','atif@gmail.com',23920112,40,'Male','O+','BCom','BCom','Karachi','Karachi','post_5_thumpic.jpg','user',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `views`
--

DROP TABLE IF EXISTS `views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `views` (
  `view_id` int(11) NOT NULL AUTO_INCREMENT,
  `view_viewed_by` int(11) DEFAULT NULL,
  `view_poll_id` int(11) NOT NULL,
  `view_ip` varchar(20) DEFAULT NULL,
  `view_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`view_id`),
  FULLTEXT KEY `ip_check` (`view_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `views`
--

LOCK TABLES `views` WRITE;
/*!40000 ALTER TABLE `views` DISABLE KEYS */;
/*!40000 ALTER TABLE `views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `vote_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vote_given_by` int(10) unsigned NOT NULL,
  `vote_option_id` int(10) unsigned NOT NULL,
  `vote_poll_id` int(11) NOT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB AUTO_INCREMENT=755 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES (584,7,449,105),(586,6,450,105),(626,3,448,105),(635,4,448,105),(652,4,546,113),(653,4,557,114),(657,3,555,113),(664,6,548,113),(666,6,556,114),(690,4,742,103),(702,4,759,100),(703,4,764,102),(704,4,771,112),(706,3,768,102),(707,3,741,103),(708,3,770,112),(709,3,757,100),(710,6,766,102),(711,6,743,103),(712,6,771,112),(715,7,781,91),(716,7,758,100),(717,7,749,103),(718,7,765,102),(720,7,556,114),(722,7,546,113),(723,7,771,112),(727,6,779,91),(728,6,760,100),(729,3,778,91),(733,3,557,114),(736,3,807,90),(738,4,780,91),(740,4,809,90),(742,6,825,110),(743,6,836,116),(744,6,833,115),(745,7,808,90),(746,7,826,110),(747,7,837,110),(748,7,832,115),(749,4,827,110),(750,4,835,110),(751,4,834,115),(752,3,828,110),(753,3,839,110),(754,3,833,115);
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-04 16:05:15
