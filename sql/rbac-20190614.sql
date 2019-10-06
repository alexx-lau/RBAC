-- MySQL dump 10.13  Distrib 8.0.11, for el7 (x86_64)
--
-- Host: localhost    Database: rbac
-- ------------------------------------------------------
-- Server version	8.0.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `r_name` varchar(100) NOT NULL,
  `c_time` int(10) unsigned NOT NULL,
  `e_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r_name` (`r_name`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'管理员',1557200373,1557200373),(3,'初级用户',1557200393,1559041585),(4,'中级用户',1557200403,1557200403),(5,'高级用户',1557200412,1559698957),(6,'访客',1557200423,1557404554),(92,'版主',1559614479,1559632656),(95,'Svip用户',1560394994,1560394994),(101,'a1',1560409971,1560477069);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_url`
--

DROP TABLE IF EXISTS `role_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `role_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `r_id` int(10) unsigned NOT NULL,
  `url_id` varchar(200) NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `r_id` (`r_id`),
  KEY `url_id` (`url_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_url`
--

LOCK TABLES `role_url` WRITE;
/*!40000 ALTER TABLE `role_url` DISABLE KEYS */;
INSERT INTO `role_url` VALUES (1,4,'3,4,8',1558512854),(2,3,'3,8,24,25',1559048607),(3,2,'3,4,5,8',1558058700),(4,5,'3,4,5,8',1558512861),(5,6,'8,24,25',1559033078),(8,75,'3,4,5,8',1558512884);
/*!40000 ALTER TABLE `role_url` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `urls`
--

DROP TABLE IF EXISTS `urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urls`
--

LOCK TABLES `urls` WRITE;
/*!40000 ALTER TABLE `urls` DISABLE KEYS */;
INSERT INTO `urls` VALUES (26,'195.16.5/ppppp'),(7,'admin/main/index'),(24,'index/website/aboutus'),(25,'index/website/contactus'),(8,'index/website/index'),(3,'index/website/innerpage1'),(4,'index/website/innerpage2'),(5,'index/website/innerpage3');
/*!40000 ALTER TABLE `urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `reg_time` int(10) unsigned NOT NULL,
  `log_time` int(10) unsigned DEFAULT NULL,
  `type` enum('1','2') DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','31cd67f2079ecab94338f34e3727bc8c',1556327933,1560498749,'2'),(2,'abc','e10adc3949ba59abbe56e057f20f883e',1556328517,1560499001,'2'),(3,'123','e10adc3949ba59abbe56e057f20f883e',1557369435,1560476816,'1'),(4,'lee','e10adc3949ba59abbe56e057f20f883e',1558670895,1559046258,'1'),(5,'mark','e10adc3949ba59abbe56e057f20f883e',1559032412,1559032412,'1'),(6,'123456','e10adc3949ba59abbe56e057f20f883e',1559033154,1560138413,'1'),(7,'梅叶雨','e10adc3949ba59abbe56e057f20f883e',1559033504,1559033519,'1'),(8,'123123','4297f44b13955235245b2497399d7a93',1559033674,1559033674,'1'),(9,'lisi','a8698009bce6d1b8c2128eddefc25aad',1559034127,1559034394,'1'),(10,'梁梁梁','e10adc3949ba59abbe56e057f20f883e',1559041407,1559041407,'1'),(11,'9999','e10adc3949ba59abbe56e057f20f883e',1559041563,1559042104,'1'),(12,'刘竟程','e1b587c62b1daff00b42845650f32aa6',1559045411,1560165819,'1'),(13,'李小龙','4297f44b13955235245b2497399d7a93',1559046086,1559046103,'1'),(14,'aabbcc','e10adc3949ba59abbe56e057f20f883e',1559046101,1559046118,'1'),(15,'李','e10adc3949ba59abbe56e057f20f883e',1559046163,1559046179,'1'),(24,'ass','e10adc3949ba59abbe56e057f20f883e',1559047889,1559047889,'1'),(33,'asd','e10adc3949ba59abbe56e057f20f883e',1559048154,1559048154,'1'),(34,'bbc','e10adc3949ba59abbe56e057f20f883e',1559048462,1559048462,'1'),(35,'林永豪','e10adc3949ba59abbe56e057f20f883e',1559561786,1560168389,'1'),(36,'321','e10adc3949ba59abbe56e057f20f883e',1559611523,1559635855,'1'),(37,'7777','f63f4fbc9f8c85d409f2f59f2b9e12d5',1559613872,1559613872,'1'),(38,'qwe','efe6398127928f1b2e9ef3207fb82663',1559613898,1559632912,'1'),(39,'fz','e10adc3949ba59abbe56e057f20f883e',1559613924,1560303415,'1'),(40,'qzy','e10adc3949ba59abbe56e057f20f883e',1559613939,1559613939,'1'),(41,'aa','e10adc3949ba59abbe56e057f20f883e',1559613959,1559613959,'1'),(42,'xuwentao','de50eff678e04fce733ae91d6ba24de5',1559614018,1559614018,'1'),(43,'789','21b95a0f90138767b0fd324e6be3457b',1559614426,1559614426,'1'),(44,'zjy','e10adc3949ba59abbe56e057f20f883e',1559615426,1559615426,'1'),(45,'lt','da369cdbec3168450ff83d76f257c68e',1559615553,1560165608,'1'),(46,'龚克','4297f44b13955235245b2497399d7a93',1559616594,1560165696,'1'),(47,'<font color=red>123</font>','e10adc3949ba59abbe56e057f20f883e',1559633601,1559633601,'1'),(48,'<font color=red>123','e10adc3949ba59abbe56e057f20f883e',1559633667,1559633731,'1'),(49,'<font size=7 color=red>1','e10adc3949ba59abbe56e057f20f883e',1559633801,1559633801,'1'),(50,'admin1','9b359fb925f46bdd8de2f8fdedfac65d',1559634648,1559634648,'1'),(51,'admin2','f8c9d4f293079790a618bf1e4f48188e',1559634800,1559634800,'1'),(52,'1111','96e79218965eb72c92a549dd5a330112',1560412283,1560412283,'1'),(53,'<b>hello</b>','e10adc3949ba59abbe56e057f20f883e',1560475882,1560475882,'1'),(54,'<font color=\'red\'>李四</font>','05a3699379b0a4f50cc0bb28e888ba52',1560475932,1560475932,'1'),(55,'<script type=\'text\'>','e10adc3949ba59abbe56e057f20f883e',1560476298,1560476298,'1'),(56,'<h2>world</h2>','e10adc3949ba59abbe56e057f20f883e',1560476378,1560476378,'1'),(57,'aileen gu','8b7b5ae2cd16baa2e8a7ddd2cd58255d',1560499035,1560499035,'1');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `u_id` int(10) unsigned NOT NULL,
  `r_id` varchar(200) NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `r_id` (`r_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (3,3,'3,4,5',1560481330),(4,2,'3,4',1558488036),(5,4,'3',1559033606),(6,6,'3,4,5,6',1559034821),(7,7,'3',1559033701),(9,33,'3',1559048154),(10,5,'3',1559048389),(11,8,'3',1559048400),(12,9,'3',1559048405),(13,10,'3',1559048410),(14,11,'3',1559048414),(15,12,'3',1559048420),(16,13,'3',1559048425),(17,14,'3',1559048430),(18,15,'3',1559048434),(19,24,'3',1559048439),(20,34,'3',1559048462),(21,35,'3',1559561788),(22,36,'3',1559611523),(23,37,'3',1559613872),(24,38,'3,5',1559614388),(25,39,'3',1559613924),(26,40,'3',1559613939),(27,41,'3',1559613959),(28,42,'3',1559614018),(29,43,'3',1559614427),(30,44,'3',1559615426),(31,45,'3',1559615554),(32,46,'3,4,5',1559616813),(33,47,'3',1559698243),(34,48,'3',1559633667),(35,49,'3',1559633801),(36,50,'3',1559634648),(37,51,'3',1559634800),(38,52,'3',1560412283),(39,53,'3',1560475882),(40,54,'3',1560475932),(41,55,'3',1560476298),(42,56,'3',1560476378);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-14 15:57:38
