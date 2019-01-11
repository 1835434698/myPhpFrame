<?php
/**
 * 数据库备份
 *
 * @createtime		2018/4/19
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV   = "system";
$FLAG_LEFTMENU = 'sys_sec_info';

$POWERID       = '7003';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

set_time_limit(600);

//$content  = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\n";
//$content .= "SET time_zone = \"+00:00\";\r\n\r\n\r\n";
//$content .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n"
//$content .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n"
//$content .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n"
//$content .= "/*!40101 SET NAMES utf8 */;\r\n";

$content  = "-- Server: ".gethostbyname($_SERVER['SERVER_NAME'])." \r\n";
$content .= "-- Database: ".$DB_name." \r\n";
$content .= "-- Backup Time: ".date('Y-m-d H:i:s')." \r\n";
$content .= "-- Generated by: TangzyPHP \r\n";
$content .= "\r\n\r\n\r\n";

$tables = array();
$tables = $mypdo->sqlQuery('SHOW TABLES');

foreach($tables as $val){
	$table = $val[0];//表名
	if(empty($table)){
		continue;
	}
	
	//表结构
	$content .= "-- 表结构 `$table` \r\n";
	$table_struct = $mypdo->sqlQuery('SHOW CREATE TABLE `'.$table.'`');
	//print_r($table_struct);
	$content .= "\r\n".$table_struct[0]['Create Table'].";\r\n\r\n";

	//表字段
	$COLUMNS = array();
	$rs = $mypdo->sqlQuery('SHOW COLUMNS FROM `'.$table.'`');
	foreach($rs as $k => $v){
		$COLUMNS[] = $v['Field'];
	}
	$COLUMNS_count = count($COLUMNS);
	$COLUMNS_str = implode(', ', $COLUMNS);
	//echo $str_COLUMNS;

	//TODO 创建索引

	//表数据
	$content .= "-- 表数据 `$table` \r\n";
	$p = 100; //定义每$p行用一个insert into
	$q = 0;

	$rs = $mypdo->sqlQuery('SELECT * FROM `'.$table.'`');
	$rs_count = count($rs);
	foreach($rs as $k => $v){
		$q ++;
		if($q % $p == 1){
			$content .= "\r\nINSERT INTO `".$table."`(".$COLUMNS_str.") VALUES";
		}
		$content .= "\r\n(";  
		for($x = 0; $x < $COLUMNS_count; $x++){
			$v[$x] = str_replace("\n", "\\n", addslashes($v[$x]) ); 
			if (isset($v[$x])){
				$content .= "'".$v[$x]."'" ;
			}else{
				$content .= "''";
			}
			if($x < ($COLUMNS_count - 1)){
				$content .= ',';
			}
		}
		if($q % $p == 0 || $q == $rs_count){
			$content .= ");";
		}else{
			$content .= "),";
		}
	}

	$content .= "\r\n\r\n\r\n\r\n";
}

//$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n";
//$content .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n";
//$content .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

//记录管理员日志
$msg = '备份数据库';
Adminlog::add($msg);

try{
	//更新备份日志时间
	$mylog->filelog('OK', $LOG_PATH.'backupdb.log', true);
}catch(MyException $e){
	$e_msg = json_decode($e->jsonMsg());
	echo $e_msg->msg;
	exit();
}

//输出文件
$backup_name = $DB_name."_".date('YmdHis')."_".rand(1000, 9999).".sql";
ob_get_clean(); 
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$backup_name."\"");
echo $content;
exit;
?>