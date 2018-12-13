<?php
/**
 * 管理员退出登录
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
Admin::logout();
header("Location: adminlogin.html");
exit();

?>