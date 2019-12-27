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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'technology'),(2,'cinema'),(3,'art'),(4,'politics'),(5,'history'),(6,'science'),(7,'book'),(8,'music');
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
INSERT INTO `comments` VALUES (229,105,'There is no doubt that Chrome is the domination the browser marker.','3',NULL,1,NULL),(230,103,'All of them are master piece.','3',NULL,1,NULL),(231,102,'Laravel is my favorite.','3',NULL,1,NULL),(234,100,'&quot;of&quot; missing in the title','4',NULL,1,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=223 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dislikes`
--

LOCK TABLES `dislikes` WRITE;
/*!40000 ALTER TABLE `dislikes` DISABLE KEYS */;
INSERT INTO `dislikes` VALUES (39,73,3,'2019-11-13 12:56:04'),(40,52,3,'2019-11-13 12:56:04'),(41,27,3,'2019-11-13 12:56:05'),(52,89,7,'2019-11-15 06:44:40'),(53,91,7,'2019-11-15 06:44:43'),(54,100,7,'2019-11-15 06:44:45'),(55,89,6,'2019-11-15 06:45:04'),(185,90,7,'2019-11-17 16:45:25'),(209,102,6,'2019-11-26 11:59:20'),(210,103,6,'2019-11-26 11:59:21'),(212,105,6,'2019-11-26 11:59:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hidden_polls`
--

LOCK TABLES `hidden_polls` WRITE;
/*!40000 ALTER TABLE `hidden_polls` DISABLE KEYS */;
INSERT INTO `hidden_polls` VALUES (13,102,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (356,107,3,'2019-11-26 11:10:04'),(370,100,3,'2019-11-26 11:13:01'),(372,91,3,'2019-11-26 11:38:32'),(373,102,3,'2019-11-26 11:38:36'),(374,105,3,'2019-11-26 11:38:40'),(377,100,6,'2019-11-26 11:59:18'),(381,91,6,'2019-11-26 12:36:07'),(383,90,6,'2019-11-26 12:42:28'),(384,90,3,'2019-12-03 14:26:31'),(385,103,3,'2019-12-17 14:05:29'),(388,103,4,'2019-12-26 02:13:29'),(389,102,4,'2019-12-26 02:13:42'),(390,105,4,'2019-12-26 02:41:03'),(391,90,4,'2019-12-26 02:45:18'),(393,100,4,'2019-12-26 16:52:21'),(394,91,4,'2019-12-26 16:52:31');
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
) ENGINE=InnoDB AUTO_INCREMENT=814 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (718,6,'newOptionRequest',100,100,'2019-11-26 13:03:12'),(719,3,'newComment',103,213,'2019-11-26 13:18:02'),(720,3,'newComment',91,214,'2019-11-26 13:18:23'),(721,3,'newComment',91,215,'2019-11-26 13:18:36'),(722,3,'newComment',91,216,'2019-11-26 13:18:37'),(728,3,'newVote',91,1,'2019-11-26 13:53:54'),(729,3,'newVote',91,1,'2019-11-26 13:53:56'),(730,3,'newVote',90,1,'2019-11-29 17:31:32'),(731,3,'newVote',102,1,'2019-11-30 12:19:52'),(732,3,'newVote',102,1,'2019-11-30 12:19:58'),(733,3,'newOptionRequest',90,101,'2019-12-03 12:58:23'),(734,3,'newVote',90,1,'2019-12-03 13:22:41'),(735,3,'newVote',90,1,'2019-12-03 13:22:45'),(736,3,'newVote',91,1,'2019-12-03 13:22:52'),(737,3,'newVote',90,1,'2019-12-03 13:22:56'),(738,3,'newVote',103,1,'2019-12-03 14:23:05'),(741,3,'newLike',90,384,'2019-12-03 14:26:31'),(742,3,'newVote',90,1,'2019-12-16 19:17:06'),(743,3,'newComment',90,223,'2019-12-16 19:17:10'),(746,3,'newVote',90,1,'2019-12-17 14:05:58'),(747,4,'newVote',91,1,'2019-12-17 19:10:56'),(748,3,'newComment',90,224,'2019-12-17 19:11:33'),(750,4,'newComment',102,226,'2019-12-17 19:15:45'),(751,4,'newVote',102,1,'2019-12-17 19:15:59'),(752,4,'newVote',91,1,'2019-12-17 19:17:13'),(753,4,'newComment',90,228,'2019-12-19 20:53:56'),(754,4,'newVote',91,1,'2019-12-19 21:31:18'),(755,4,'newVote',91,1,'2019-12-19 21:31:25'),(756,4,'newVote',102,1,'2019-12-19 21:35:46'),(757,4,'newOptionRequest',90,102,'2019-12-19 21:45:32'),(758,4,'newOptionRequest',91,103,'2019-12-19 21:45:42'),(759,4,'newOptionRequest',90,104,'2019-12-19 21:45:58'),(760,4,'newOptionRequest',90,105,'2019-12-19 21:48:37'),(761,4,'newOptionRequest',90,106,'2019-12-19 21:49:07'),(762,4,'newOptionRequest',90,107,'2019-12-19 21:50:35'),(763,4,'newOptionRequest',90,108,'2019-12-19 21:50:51'),(764,4,'newOptionRequest',90,109,'2019-12-19 21:52:36'),(765,4,'newOptionRequest',90,110,'2019-12-19 21:52:53'),(766,4,'newOptionRequest',90,111,'2019-12-19 21:53:18'),(767,3,'newVote',105,1,'2019-12-19 21:54:01'),(771,4,'newVote',90,1,'2019-12-19 22:03:17'),(772,4,'newVote',90,1,'2019-12-20 19:58:21'),(773,4,'newVote',90,1,'2019-12-20 19:58:28'),(775,4,'newOptionRequest',100,112,'2019-12-20 20:15:12'),(776,3,'newComment',105,233,'2019-12-20 21:09:40'),(777,3,'newOptionRequest',102,113,'2019-12-21 18:39:05'),(778,3,'newOptionRequest',103,114,'2019-12-21 18:39:11'),(779,3,'newOptionRequest',105,115,'2019-12-21 18:39:16'),(780,4,'newVote',109,1,'2019-12-21 19:59:12'),(781,4,'newVote',102,1,'2019-12-21 20:11:08'),(782,4,'newVote',102,1,'2019-12-21 20:11:12'),(783,4,'newVote',105,1,'2019-12-23 22:30:51'),(784,4,'newVote',105,1,'2019-12-23 22:31:00'),(785,4,'newVote',90,1,'2019-12-23 23:24:13'),(786,4,'newVote',90,1,'2019-12-23 23:24:22'),(787,4,'newVote',90,1,'2019-12-23 23:24:36'),(788,4,'newVote',90,1,'2019-12-23 23:24:38'),(789,4,'newComment',100,234,'2019-12-24 01:14:59'),(790,4,'newVote',100,1,'2019-12-24 01:15:12'),(791,6,'newOptionRequest',91,116,'2019-12-24 01:19:17'),(792,4,'newOptionRequest',110,117,'2019-12-24 01:20:04'),(793,4,'newComment',90,235,'2019-12-24 01:22:22'),(794,4,'newComment',90,236,'2019-12-24 01:23:40'),(795,4,'newComment',90,237,'2019-12-24 01:23:45'),(799,4,'newLike',103,388,'2019-12-26 02:13:29'),(801,4,'newLike',102,389,'2019-12-26 02:13:42'),(802,4,'newLike',105,390,'2019-12-26 02:41:03'),(804,4,'newLike',90,391,'2019-12-26 02:45:18'),(805,4,'newVote',90,1,'2019-12-26 04:48:59'),(806,4,'newVote',90,1,'2019-12-26 04:49:03'),(807,4,'newComment',90,238,'2019-12-26 04:49:15'),(808,4,'newComment',90,239,'2019-12-26 04:49:53'),(809,4,'newVote',90,1,'2019-12-26 16:46:14'),(811,4,'newLike',100,393,'2019-12-26 16:52:21'),(813,4,'newLike',91,394,'2019-12-26 16:52:31');
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
) ENGINE=InnoDB AUTO_INCREMENT=534 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES (6,'3 Idiots',2,4,'2019-11-01 23:28:04',0,'active'),(7,'Lagaan',2,4,'2019-11-01 23:28:04',0,'active'),(8,'Kal Ho Na Ho',2,4,'2019-11-01 23:28:04',0,'active'),(9,'Chak De Basanti',2,4,'2019-11-01 23:28:04',0,'active'),(10,'Lage Raho Munna Bhai',2,4,'2019-11-01 23:28:04',0,'active'),(11,'Think and Grow Rich by Nepolion Hill',3,4,'2019-11-01 23:31:48',0,'active'),(12,'7 Habits of Highly Effective People by Seteven Cuvy',3,4,'2019-11-01 23:31:48',0,'active'),(13,'How to Win Friends and Influence People by Dale Carnige',3,4,'2019-11-01 23:31:48',0,'active'),(14,'The Power Of Now by Eckhart Tolle',3,4,'2019-11-01 23:31:48',0,'active'),(44,'Swadesh',16,4,'2019-11-02 19:05:56',0,'active'),(45,'Lage Raho Munna Bhai',16,4,'2019-11-02 19:05:56',0,'active'),(46,'3 idiots',16,4,'2019-11-02 19:05:56',0,'active'),(47,'PK',16,4,'2019-11-02 19:05:56',0,'active'),(48,'Dhoom 2',16,4,'2019-11-02 19:05:56',0,'active'),(49,'Tare Zameen Paar',16,4,'2019-11-02 19:05:56',0,'active'),(50,'Eckhart Tolle',17,4,'2019-11-02 19:19:02',0,'active'),(51,'Steven Cuvy',17,4,'2019-11-02 19:19:02',0,'active'),(52,'Robert Greene',17,4,'2019-11-02 19:19:02',0,'active'),(56,'Think and Grow Rich by Nepolion Hill',19,4,'2019-11-02 20:56:27',0,'active'),(57,'7 Habits of Highly Effective People by Seteven Cuvy',19,4,'2019-11-02 20:56:27',0,'active'),(58,'How to Win Friends and Influence People by Dale Carnige',19,4,'2019-11-02 20:56:27',0,'active'),(59,'The Power Of Now by Eckhart Tolle',19,4,'2019-11-02 20:56:27',0,'active'),(373,'iPhone XI',91,3,'2019-11-15 00:44:49',0,'active'),(374,'Samsung Galaxy S10',91,3,'2019-11-15 00:44:49',0,'active'),(375,'OPPO Reno 2',91,3,'2019-11-15 00:44:49',0,'active'),(376,'Xiomi Redmi 5',91,3,'2019-11-15 00:44:49',0,'active'),(397,'The Dark Knight',100,3,'2019-11-15 02:08:37',0,'active'),(398,'Interstellar',100,3,'2019-11-15 02:08:37',0,'active'),(399,'Inception',100,3,'2019-11-15 02:08:37',0,'active'),(400,'Memento',100,3,'2019-11-15 02:08:37',0,'active'),(401,'The Batman',100,3,'2019-11-15 02:08:37',0,'active'),(432,'Laravel',102,4,'2019-11-15 02:47:10',0,'active'),(433,'Ruby On Rails',102,4,'2019-11-15 02:47:10',0,'active'),(434,'Java Spring',102,4,'2019-11-15 02:47:10',0,'active'),(435,'ASP.NET',102,4,'2019-11-15 02:47:10',0,'active'),(436,'Django',102,4,'2019-11-15 02:47:10',0,'active'),(437,'Flask',102,4,'2019-11-15 02:47:10',0,'active'),(438,'GodFather',103,4,'2019-11-15 06:47:30',0,'active'),(439,'GodFather II',103,4,'2019-11-15 06:47:30',0,'active'),(440,'The Dark Knight',103,4,'2019-11-15 06:47:30',0,'active'),(441,'Lord of the Rings the Return of the King',103,4,'2019-11-15 06:47:30',0,'active'),(448,'Google Chrome',105,4,'2019-11-17 16:44:33',0,'active'),(449,'Mozilla Firefox',105,4,'2019-11-17 16:44:33',0,'active'),(450,'Opera',105,4,'2019-11-17 16:44:33',0,'active'),(451,'Safari',105,4,'2019-11-17 16:44:33',0,'active'),(516,'Torch Browser',105,3,'2019-11-22 06:46:43',0,NULL),(520,'Ali Zafar',90,3,'2019-11-29 11:50:14',0,'active'),(521,'Nusrat Fateh Ali Khan',90,3,'2019-11-29 11:50:14',0,'active'),(522,'Atif Aslam',90,3,'2019-11-29 11:50:14',0,'active'),(523,'Rahat Fateh Ali Khan',90,3,'2019-11-29 11:50:14',0,'active'),(524,'Quratul Blouch',90,3,'2019-11-29 11:50:14',0,'active'),(528,'Fransh',110,6,'2019-12-24 01:18:42',0,'active'),(529,'China',110,6,'2019-12-24 01:18:42',0,'active'),(530,'Australia',110,6,'2019-12-24 01:18:42',0,'active'),(531,'Srilanka',110,6,'2019-12-24 01:18:42',0,'active');
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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
INSERT INTO `polls` VALUES (90,'music',3,'Best Pakistani Singer','2019-12-17 14:22:31',0,0,0,1),(91,'technology',3,'Best Phone of 2019','2019-11-15 02:06:14',0,0,0,1),(100,'cinema',3,'Best Film Christopher Nonal','2019-11-26 11:09:11',0,0,0,1),(102,'technology',4,'Top Backend Frameworks','2019-12-26 16:45:11',0,0,0,1),(103,'cinema',4,'Best Movie Of All Time','2019-12-26 02:06:02',0,0,0,1),(105,'technology',4,'Best Web Browser','2019-12-19 22:00:29',0,0,0,1),(110,'art',6,'Best Country For Spending Winter','2019-12-24 01:18:42',0,0,0,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposed_option`
--

LOCK TABLES `proposed_option` WRITE;
/*!40000 ALTER TABLE `proposed_option` DISABLE KEYS */;
INSERT INTO `proposed_option` VALUES (116,'&quot;Nokia 10',91,6,'2019-12-24 01:19:17'),(117,'&quot;Egypt',110,4,'2019-12-24 01:20:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saved_polls`
--

LOCK TABLES `saved_polls` WRITE;
/*!40000 ALTER TABLE `saved_polls` DISABLE KEYS */;
INSERT INTO `saved_polls` VALUES (90,4,70),(91,4,71);
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
) ENGINE=InnoDB AUTO_INCREMENT=648 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES (559,7,376,91),(560,7,433,102),(561,7,399,100),(563,6,397,100),(566,6,373,91),(567,6,433,102),(584,7,449,105),(586,6,450,105),(587,6,441,103),(593,3,400,100),(611,3,436,102),(614,3,376,91),(616,3,439,103),(618,3,521,90),(624,4,373,91),(626,3,448,105),(633,4,432,102),(635,4,448,105),(640,4,397,100),(641,6,523,90),(642,6,528,110),(645,4,440,103),(647,4,522,90);
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

-- Dump completed on 2019-12-27  6:23:24
