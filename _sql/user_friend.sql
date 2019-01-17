# MySQL-Front 5.0  (Build 1.96)

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;


# Host: localhost    Database: tangzy
# ------------------------------------------------------
# Server version 5.7.17-log

#
# Table structure for table tangzy_friend
#

DROP TABLE IF EXISTS `tangzy_friend`;
CREATE TABLE `tangzy_friend` (
  `friend_id` int(11) NOT NULL AUTO_INCREMENT,
  `friend_userid` int(11) DEFAULT NULL COMMENT '用户id',
  `friend_touserid` int(11) DEFAULT NULL COMMENT '被关联id',
  `friend_time` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`friend_id`),
  KEY `friend_userid` (`friend_userid`),
  KEY `friend_touserid` (`friend_touserid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='朋友表';

#
# Dumping data for table tangzy_friend
#
LOCK TABLES `tangzy_friend` WRITE;
/*!40000 ALTER TABLE `tangzy_friend` DISABLE KEYS */;

/*!40000 ALTER TABLE `tangzy_friend` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table tangzy_user
#

DROP TABLE IF EXISTS `tangzy_user`;
CREATE TABLE `tangzy_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `user_name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `user_email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `user_sex` tinyint(1) DEFAULT NULL COMMENT '0未知，1男，2女',
  `user_province` varchar(50) DEFAULT NULL COMMENT '省',
  `user_city` varchar(50) DEFAULT NULL COMMENT '市',
  `user_area` varchar(50) DEFAULT NULL COMMENT '区',
  `user_longitude` double(6,6) DEFAULT NULL COMMENT '经度',
  `user_latitude` double(6,6) DEFAULT NULL COMMENT '维度',
  `user_attribute` tinyint(2) DEFAULT NULL COMMENT '1、公开坐标，2、保密坐标',
  `user_openid` varchar(50) DEFAULT NULL COMMENT '三方帐号',
  `user_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Dumping data for table tangzy_user
#
LOCK TABLES `tangzy_user` WRITE;
/*!40000 ALTER TABLE `tangzy_user` DISABLE KEYS */;

/*!40000 ALTER TABLE `tangzy_user` ENABLE KEYS */;
UNLOCK TABLES;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
