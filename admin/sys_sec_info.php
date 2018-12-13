<?php
/**
 * 系统安全信息
 *
 * @createtime		2018/4/14
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV   = "system";
$FLAG_LEFTMENU = 'sys_sec_info';

$POWERID       = '7003';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

$ZHIMA_SEC_STATUS = array(
	'ok'  => '<span class="status green">OK</span>',
	'bad' => '<span class="status red">BAD</span>',
	'unknown'  => '<span class="status orange">CHECK</span>'
);

$ZHIMA_SEC_INFO = array();

//
//$ZHIMA_SEC_INFO['100']['req']    = '';
//$ZHIMA_SEC_INFO['100']['status'] = $status100;
//$ZHIMA_SEC_INFO['100']['desc']   = '';

//201
//读取上次备份日期
try{
	$r = $mylog->read($LOG_PATH.'backupdb.log');
	$r_msg = json_decode($r)->msg;
	$backuptime = substr($r_msg, 0, 19);
	if(time() - strtotime($backuptime) > 3600 * 24 * 30){
		$status201 = 'bad';
	}else{
		$status201 = 'ok';
	}
	
}catch( MyException $e ){
	$backuptime = '未知';
	$status201 = 'unknown';
}

$ZHIMA_SEC_INFO['201']['req']    = '数据库备份';
$ZHIMA_SEC_INFO['201']['status'] = $status201;
$ZHIMA_SEC_INFO['201']['desc']   = '有服务器操作权限的应设置数据库自动备份，否则应定期进行手动备份。上次备份时间：<strong>'.$backuptime.'</strong>。<a href="sys_backup_db.php" target="_blank">【立即备份】</a>';

//101
$ini_er = ini_get('error_reporting');
if($ini_er == 0){
	$status101 = 'ok';
}else{
	$status101 = 'bad';
}
$ZHIMA_SEC_INFO['101']['req']    = '系统上线须关闭PHP报错';
$ZHIMA_SEC_INFO['101']['status'] = $status101;
$ZHIMA_SEC_INFO['101']['desc']   = '如果有服务器权限，请设置php.ini中的display_errors=off。在根目录下config.inc.php中打开error_reporting(0)。';

//102
$debug = $mypdo->debug;
if($debug === false){
	$status102 = 'ok';
}else{
	$status102 = 'bad';
}
$ZHIMA_SEC_INFO['102']['req']    = '系统上线须关闭数据库调试';
$ZHIMA_SEC_INFO['102']['status'] = $status102;
$ZHIMA_SEC_INFO['102']['desc']   = '在根目录下init.php中关闭$mypdo->debug。';

//103
if(PROJECTCODE == 'ZhimaPHP'){
	$status103 = 'bad';
}else{
	$status103 = 'ok';
}
$ZHIMA_SEC_INFO['103']['req']    = '修改项目编号';
$ZHIMA_SEC_INFO['103']['status'] = $status103;
$ZHIMA_SEC_INFO['103']['desc']   = '在根目录下config.inc.php中修改常量PROJECTCODE的值。';

//104
if(empty($HTTP_PATH) || (substr($HTTP_PATH, 0, 7) != 'http://' && substr($HTTP_PATH, 0, 8) != 'https://') || substr($HTTP_PATH, -1, 1) != '/' || substr($HTTP_PATH, -2, 1) == '/'){
	$status104 = 'bad';
}else{
	$status104 = 'ok';
}
$ZHIMA_SEC_INFO['104']['req']    = '正确设置访问路径';
$ZHIMA_SEC_INFO['104']['status'] = $status104;
$ZHIMA_SEC_INFO['104']['desc']   = '在根目录下的config.inc.php中设置$HTTP_PATH的值。访问路径应该以http://或https://开头，并以“/”结束。';

//105 //TODO 改进判断子目录
if(is_writable($FILE_PATH.'userfiles')){
	$status105 = 'unknown';
}else{
	$status105 = 'bad';
}
$ZHIMA_SEC_INFO['105']['req']    = '设置根目录下userfiles及其子目录的写权限为777';
$ZHIMA_SEC_INFO['105']['status'] = $status105;
$ZHIMA_SEC_INFO['105']['desc']   = '';

//106
if(is_writable($LOG_PATH.'common.log') && is_writable($LOG_PATH.'debug.log') && is_writable($LOG_PATH.'backupdb.log')){
	$status106 = 'ok';
}else{
	$status106 = 'bad';
}
$ZHIMA_SEC_INFO['106']['req']    = '设置日志文件的写权限';
$ZHIMA_SEC_INFO['106']['status'] = $status106;
$ZHIMA_SEC_INFO['106']['desc']   = '设置根目录下logs中日志文件的写权限';

//107 //TODO 改进判断更多临时文件夹
if(file_exists($FILE_PATH.'_sql') || file_exists($FILE_PATH.'_doc')){
	$status107 = 'bad';
}else{
	$status107 = 'unknown';
}
$ZHIMA_SEC_INFO['107']['req']    = '系统上线删除开发文档和临时文件';
$ZHIMA_SEC_INFO['107']['status'] = $status107;
$ZHIMA_SEC_INFO['107']['desc']   = '删除各个目录中下划线开头的文件和文件夹，特别是根目录下的_sql和_doc等。';

//108 //TODO 改进判断放在其他位置而未重命名的
if(file_exists($FILE_PATH.'phpMyAdmin') || file_exists($FILE_PATH.'web/phpMyAdmin')){
	$status108 = 'bad';
}else{
	$status108 = 'unknown';
}
$ZHIMA_SEC_INFO['108']['req']    = '系统根目录下不得存在phpMyAdmin文件夹';
$ZHIMA_SEC_INFO['108']['status'] = $status108;
$ZHIMA_SEC_INFO['108']['desc']   = '放在系统中的phpMyAdmin必须重命名';

//109 //TODO 改进判断代码里面
if(file_exists($FILE_PATH.'phpinfo.php') || file_exists($FILE_PATH.'web/phpinfo.php') || file_exists($FILE_PATH.'web/info.php')){
	$status109 = 'bad';
}else{
	$status109 = 'unknown';
}
$ZHIMA_SEC_INFO['109']['req']    = '系统上线后不得存在输出phpinfo的页面';
$ZHIMA_SEC_INFO['109']['status'] = $status109;
$ZHIMA_SEC_INFO['109']['desc']   = '删除系统中输出phpinfo的页面或代码';

//110
if(file_exists($FILE_PATH.'admin')){
	$status110 = 'bad';
}else{
	$status110 = 'ok';
}
$ZHIMA_SEC_INFO['110']['req']    = '系统上线须对管理后台admin重命名';
$ZHIMA_SEC_INFO['110']['status'] = $status110;
$ZHIMA_SEC_INFO['110']['desc']   = '';

//202
/** 2018/10/17放弃
if(file_exists($FILE_PATH.'logs')){
	$status202 = 'bad';
}else{
	$status202 = 'ok';
}
$ZHIMA_SEC_INFO['202']['req']    = '系统上线须对根目录下logs进行重命名';
$ZHIMA_SEC_INFO['202']['status'] = $status202;
$ZHIMA_SEC_INFO['202']['desc']   = 'logs重命名后，须对应修改根目录下config.inc.php中的定义。';
**/

//TODO 检查所有程序的变量是否过滤
//TODO 检查是否有没写在Table层的SQL语句
//TODO 单个文件过大
//TODO userfiles目录下是否存在可执行文件

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>安全信息 - 系统信息 - 管理系统 </title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
			<?php include('admin_menu.inc.php');?>
			<div id="maincontent">
				<div class="tips">
					提示：<span class="status green">OK</span>表示正常状态。<span class="status red">BAD</span>表示不正常状态。<span class="status orange">CHECK</span>表示需要技术人员检查确认。
				</div>
				<div class="tablelist">
					<table>
						<tr>
							<th width="10%">编号</th>
                            <th width="30%">安全项</th>
                            <th width="15%">状态</th>
							<th width="45%">操作说明</th>
						</tr>
						<?php 
							foreach($ZHIMA_SEC_INFO as $k => $v){
								echo '<tr>';
								echo '<td class="center">'.$k.'</td>';
								echo '<td>'.$ZHIMA_SEC_INFO[$k]['req'].'</td>';
								echo '<td class="center">'.$ZHIMA_SEC_STATUS[$ZHIMA_SEC_INFO[$k]['status']].'</td>';
								echo '<td>'.$ZHIMA_SEC_INFO[$k]['desc'].'</td>';
								echo '</tr>';
							}
						?>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>