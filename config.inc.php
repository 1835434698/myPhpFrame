<?php

/**
 * config.inc.php 基础配置文件
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

//基础设置=================================================
//error_reporting(0);                          //网站开发时，务必关闭此项；网站上线时，务必打开此项。
define("TANGZYPHP_VERSION", '5.0');          //TangzyPHP版本号（）
define("TANGZYPHP_UPDATE",  '20181018');        //TangzyPHP更新日期
header("content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');              //时区设置，服务器放置在国外的需要打开此项
session_start();
//ob_start();
define("PROJECTCODE",    'TangzyPHP');          //项目编号，建议修改，每个项目应该不同
$HTTP_PATH = 'http://localhost/';     //网站访问路径，根据实际情况修改，务必以“/”结尾。

//数据库连接参数设置=======================================
$DB_host   = 'localhost';                      //数据库地址
$DB_user   = 'root';                           //数据库用户
$DB_pass   = '123456';                           //数据库用户密码
$DB_name   = 'tangzy';                       //数据库名称
$DB_prefix = 'tangzy_';                      //表前缀，可以为空


/*********以下配置一般不用修改***********/
//路径定义=================================================
$FILE_PATH = str_replace('\\','/',dirname(__FILE__)).'/'; //网站根目录路径
$LIB_PATH        = $FILE_PATH.'lib/';
$LIB_COMMON_PATH = $LIB_PATH.'common/';
$LIB_TABLE_PATH  = $LIB_PATH.'table/';


//日志文件路径==============================================
//请给以下日志文件设置写权限
$LOG_PATH   = $FILE_PATH.'logs/';
$LOG_config = array(
	'common'      => $LOG_PATH.'common.log',
	'debug'       => $LOG_PATH.'debug.log'
);

//管理员Cookie 和 Session===================================
$cookie_ADMINID      = PROJECTCODE.'ACID';
$cookie_ADMINCODE    = PROJECTCODE.'ACCODE';
$session_ADMINID     = PROJECTCODE.'ASID';

?>