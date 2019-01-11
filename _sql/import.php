<?php

/**
 * 导入初始化数据库
 *
 * 执行本文件前请先配置config.inc.php中的数据库连接参数
 *
 * @createtime		2018/12/13
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

require('../init.php');

$sqlarr = array();

$sqlarr[] = "CREATE TABLE IF NOT EXISTS `".$mypdo->prefix."admin` (
  `admin_id` int(4) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(20) DEFAULT NULL,
  `admin_account` varchar(50) DEFAULT NULL,
  `admin_password` varchar(50) DEFAULT NULL,
  `admin_salt` varchar(20) DEFAULT NULL,
  `admin_group` tinyint(1) DEFAULT NULL,
  `admin_lastloginip` varchar(100) DEFAULT NULL,
  `admin_lastlogintime` int(11) DEFAULT NULL,
  `admin_logincount` int(10) DEFAULT '0',
  `admin_addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
//admin  admin123456
$sqlarr[] = "INSERT INTO `".$mypdo->prefix."admin`(admin_id, admin_name, admin_account, admin_password, admin_salt, admin_group, admin_lastloginip, admin_lastlogintime, admin_logincount, admin_addtime) VALUES('1','','admin','93776df5ecc5fc375499ec9d7f0750f6','2uMsX6bt6o','1','127.0.0.1','1544681332','6','1544681332');";

$sqlarr[] = "CREATE TABLE IF NOT EXISTS `".$mypdo->prefix."admingroup` (
  `admingroup_id` int(4) NOT NULL AUTO_INCREMENT,
  `admingroup_name` varchar(50) NOT NULL,
  `admingroup_auth` varchar(600) DEFAULT NULL,
  `admingroup_type` tinyint(1) NOT NULL DEFAULT '0',
  `admingroup_order` int(4) NOT NULL DEFAULT '99',
  PRIMARY KEY (`admingroup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

$sqlarr[] = "INSERT INTO `".$mypdo->prefix."admingroup` (`admingroup_id`, `admingroup_name`, `admingroup_auth`, `admingroup_type`, `admingroup_order`) VALUES(1, '超级管理员', '7005|7004|7003|7002|7001', 9, 1);";

$sqlarr[] = "CREATE TABLE IF NOT EXISTS `".$mypdo->prefix."adminlog` (
  `adminlog_id` int(10) NOT NULL AUTO_INCREMENT,
  `adminlog_admin` int(4) DEFAULT NULL,
  `adminlog_time` int(11) DEFAULT NULL,
  `adminlog_log` varchar(600) DEFAULT NULL,
  `adminlog_ip` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`adminlog_id`),
  KEY `adminlog_admin` (`adminlog_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

$sqlarr[] = "CREATE TABLE IF NOT EXISTS `".$mypdo->prefix."apilog` (
  `apilog_id` int(20) NOT NULL AUTO_INCREMENT,
  `apilog_api` varchar(100) DEFAULT NULL,
  `apilog_uid` int(11) DEFAULT NULL,
  `apilog_request` text COMMENT '请求数据',
  `apilog_response` text COMMENT '返回数据',
  `apilog_time` int(11) DEFAULT NULL,
  `apilog_ip` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`apilog_id`),
  KEY `my_apilog` (`apilog_uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

$count = count($sqlarr);

for($i=0; $i<$count; $i++){
	$mypdo->pdo->exec($sqlarr[$i]);
}

echo '执行完毕！<br/>'.'执行时间：'.getRunTime().'秒';

?>