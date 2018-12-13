<?php
/**
 * 管理员登陆验证
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');

$check = Admin::checkLogin();
if(empty($check)) {
	header('Location: adminlogin.html');
	exit();//header()之后一定要加上程序终止
}else{
    $adminId = Admin::getSession();
	$ADMIN = new Admin($adminId);
	$ADMINGROUP = new Admingroup($ADMIN->getGroupID());
	$ADMINAUTH = $ADMINGROUP->getAuth();
}
	
?>