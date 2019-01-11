<?php
/**
 * 管理员处理
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
require_once('admincheck.php');

$POWERID = '7002';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

$act = safeCheck($_GET['act'], 0);
switch($act){
	case 'add'://添加
		$account   =  safeCheck($_POST['account'], 0);
		$password  =  safeCheck($_POST['password'], 0);
		$group     =  safeCheck($_POST['group']);
		
		try {
			$rs = Admin::add($account, $password, $group);
			echo $rs;
		}catch (MyException $e){
			echo $e->jsonMsg();
		}
		break;
		
	case 'edit'://编辑
		$id      = safeCheck($_POST['id']);
		$account = safeCheck($_POST['account'], 0);
		$group   = safeCheck($_POST['group']);

		try {
			$rs = Admin::edit($id, $account, $group);
			echo $rs;
		}catch (MyException $e){
			echo $e->jsonMsg();
		}
		break;
		
	case 'del'://删除
		$id = safeCheck($_POST['id']);
		
		try {
			$rs = Admin::del($id);
			echo $rs;
		}catch (MyException $e){
			echo $e->jsonMsg();
		}
		break;
		
	case 'reset'://重置密码
		$id = safeCheck($_POST['id']);
		$newpass = 'admin123456';
		
		try{
			$r = Admin::resetPwd($id, $newpass);
			echo $r;
		}catch(MyException $e){
			echo $e->jsonMsg();
		}
		break;
		
}
?>