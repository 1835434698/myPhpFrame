<?php
/**
 * 系统日志处理文件
 *
 * @createtime		2018/4/17
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$POWERID = '7003';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

$act = safeCheck($_GET['act'], 0);
switch($act){
	case 'clear'://清空日志
		$type  =  safeCheck($_POST['type'], 0);
		
		try {
			$rs = $mylog->clear($type);
			echo $rs;
		}catch (MyException $e){
			echo $e->jsonMsg();
		}
		break;
}
?>