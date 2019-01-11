<?php
/**
 * 管理员登陆处理
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');

//获取值
$account   = safeCheck($_POST['account'], 0);
$password  = safeCheck($_POST['pass'], 0);

$vercode   = safeCheck($_POST['vercode'], 0);
$remember  = safeCheck($_POST['remember']);//是否记住cookie

//校验验证码
if($vercode != $_SESSION['TangzyPHP_imgcode']){
	echo action_msg('验证码错误', -4);
	exit();
}else{
	try {
		$admin = new Admin();
		$r = $admin->login($account, $password, $remember);
		echo $r;
	}catch(MyException $e){
		echo $e->jsonMsg();
	}
}
?>