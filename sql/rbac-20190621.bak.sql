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
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'管理员',1557200373,1557200373),(182,'初级用户',1561082219,1561082219),(183,'中级用户',1561082224,1561082224),(184,'高级用户',1561082230,1561082230),(185,'特级用户',1561082235,1561082235),(186,'超级用户',1561082240,1561082240),(187,'版主',1561082245,1561082245);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_url`
--

LOCK TABLES `role_url` WRITE;
/*!40000 ALTER TABLE `role_url` DISABLE KEYS */;
INSERT INTO `role_url` VALUES (4,182,'3,4,6,7',1561086555),(5,183,'41,42,44,45',1561082448),(6,184,'41,42,43,44,45',1561082456),(7,185,'41,42,43,44,45,46',1561082560),(8,186,'41,42,43,44,45,47',1561082552),(9,187,'41,42,43,44,45,48',1561082546),(10,182,'3,4,6,7',1561086555);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urls`
--

LOCK TABLES `urls` WRITE;
/*!40000 ALTER TABLE `urls` DISABLE KEYS */;
INSERT INTO `urls` VALUES (2,'index/user/changepassword'),(1,'index/user/personalcenter'),(6,'index/website/aboutus'),(7,'index/website/contactus'),(3,'index/website/innerpage1'),(4,'index/website/innerpage2'),(5,'index/website/innerpage3');
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
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','31cd67f2079ecab94338f34e3727bc8c',1556327933,1561085061,'2'),(2,'abc','e10adc3949ba59abbe56e057f20f883e',1556328517,1561084820,'2'),(3,'123','e10adc3949ba59abbe56e057f20f883e',1557369435,1561086349,'1'),(4,'lee','e10adc3949ba59abbe56e057f20f883e',1558670895,1559046258,'1'),(5,'mark','e10adc3949ba59abbe56e057f20f883e',1559032412,1559032412,'1'),(6,'123456','e10adc3949ba59abbe56e057f20f883e',1559033154,1560738127,'1'),(7,'梅叶雨','e10adc3949ba59abbe56e057f20f883e',1559033504,1559033519,'1'),(8,'123123','4297f44b13955235245b2497399d7a93',1559033674,1559033674,'1'),(9,'lisi','a8698009bce6d1b8c2128eddefc25aad',1559034127,1559034394,'1'),(10,'梁梁梁','e10adc3949ba59abbe56e057f20f883e',1559041407,1559041407,'1'),(11,'9999','e10adc3949ba59abbe56e057f20f883e',1559041563,1559042104,'1'),(12,'刘竟程','e1b587c62b1daff00b42845650f32aa6',1559045411,1560165819,'1'),(13,'李小龙','4297f44b13955235245b2497399d7a93',1559046086,1559046103,'1'),(14,'aabbcc','e10adc3949ba59abbe56e057f20f883e',1559046101,1559046118,'1'),(15,'李','e10adc3949ba59abbe56e057f20f883e',1559046163,1559046179,'1'),(24,'ass','e10adc3949ba59abbe56e057f20f883e',1559047889,1559047889,'1'),(33,'asd','e10adc3949ba59abbe56e057f20f883e',1559048154,1559048154,'1'),(34,'bbc','e10adc3949ba59abbe56e057f20f883e',1559048462,1559048462,'1'),(35,'林永豪','e10adc3949ba59abbe56e057f20f883e',1559561786,1560168389,'1'),(36,'321','e10adc3949ba59abbe56e057f20f883e',1559611523,1559635855,'1'),(37,'7777','f63f4fbc9f8c85d409f2f59f2b9e12d5',1559613872,1559613872,'1'),(38,'qwe','efe6398127928f1b2e9ef3207fb82663',1559613898,1560860099,'1'),(39,'fz','e10adc3949ba59abbe56e057f20f883e',1559613924,1560303415,'1'),(40,'qzy','e10adc3949ba59abbe56e057f20f883e',1559613939,1559613939,'1'),(41,'aa','e10adc3949ba59abbe56e057f20f883e',1559613959,1559613959,'1'),(42,'xuwentao','de50eff678e04fce733ae91d6ba24de5',1559614018,1559614018,'1'),(43,'789','21b95a0f90138767b0fd324e6be3457b',1559614426,1559614426,'1'),(44,'zjy','e10adc3949ba59abbe56e057f20f883e',1559615426,1559615426,'1'),(45,'lt','da369cdbec3168450ff83d76f257c68e',1559615553,1560820793,'1'),(46,'龚克','4297f44b13955235245b2497399d7a93',1559616594,1560165696,'1'),(47,'<font color=red>123</font>','e10adc3949ba59abbe56e057f20f883e',1559633601,1559633601,'1'),(48,'<font color=red>123','e10adc3949ba59abbe56e057f20f883e',1559633667,1559633731,'1'),(49,'<font size=7 color=red>1','e10adc3949ba59abbe56e057f20f883e',1559633801,1559633801,'1'),(50,'admin1','9b359fb925f46bdd8de2f8fdedfac65d',1559634648,1559634648,'1'),(51,'admin2','f8c9d4f293079790a618bf1e4f48188e',1559634800,1559634800,'1'),(52,'1111','96e79218965eb72c92a549dd5a330112',1560412283,1560412283,'1'),(53,'<b>hello</b>','e10adc3949ba59abbe56e057f20f883e',1560475882,1560475882,'1'),(54,'<font color=\'red\'>李四</font>','05a3699379b0a4f50cc0bb28e888ba52',1560475932,1560475932,'1'),(55,'<script type=\'text\'>','e10adc3949ba59abbe56e057f20f883e',1560476298,1560476298,'1'),(56,'<h2>world</h2>','e10adc3949ba59abbe56e057f20f883e',1560476378,1560476378,'1'),(57,'aileen gu','8b7b5ae2cd16baa2e8a7ddd2cd58255d',1560499035,1560499035,'1'),(58,'www','e10adc3949ba59abbe56e057f20f883e',1560754853,1560754853,'1'),(59,'王五','8b7b5ae2cd16baa2e8a7ddd2cd58255d',1560755086,1560755086,'1'),(60,'admins','e10adc3949ba59abbe56e057f20f883e',1560755419,1560755419,'1'),(61,'<font>ddd</font>','e10adc3949ba59abbe56e057f20f883e',1560770757,1560770757,'1'),(62,'gfa','e10adc3949ba59abbe56e057f20f883e',1560770788,1560770788,'1'),(63,'<a href=\"#\">world</a>','e10adc3949ba59abbe56e057f20f883e',1560830215,1560830215,'1'),(64,'qqi','e10adc3949ba59abbe56e057f20f883e',1560903293,1560903293,'1'),(65,'sds','e10adc3949ba59abbe56e057f20f883e',1560927703,1560927703,'1'),(66,'王俊力','e10adc3949ba59abbe56e057f20f883e',1560944604,1560944747,'1'),(67,'4564654','e10adc3949ba59abbe56e057f20f883e',1560944725,1560944725,'1'),(68,'1231111','e10adc3949ba59abbe56e057f20f883e',1560944768,1560944768,'1'),(69,'qwe456','e10adc3949ba59abbe56e057f20f883e',1560944994,1560944994,'1'),(70,'dsdadsasadasddas','e10adc3949ba59abbe56e057f20f883e',1560946163,1560946163,'1'),(71,'12311111','e10adc3949ba59abbe56e057f20f883e',1560946342,1560946342,'1'),(72,'sadlsadjlasdjasdojdas','e10adc3949ba59abbe56e057f20f883e',1561086056,1561086056,'1');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (10,2,'182,183,184,185,186,187',1561082613),(11,3,'182',1561084993),(12,4,'182,183',1561082287),(13,5,'184',1561082291),(14,7,'186',1561082296),(15,13,'185',1561082304),(16,15,'184',1561082630),(17,35,'182,183,184,185',1561082639);
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

-- Dump completed on 2019-06-21 11:11:12
