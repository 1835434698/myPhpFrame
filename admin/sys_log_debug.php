<?php
/**
 * 系统调试日志
 *
 * @createtime		2018/4/17
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV   = "system";
$FLAG_LEFTMENU = 'sys_log';

$POWERID       = '7003';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>系统调试日志 - 系统信息 - 管理系统 </title>
		<script type="text/javascript">
			$(function(){
				//清空日志
				$("#btn_clear").click(function(){
					layer.confirm('确认清空调试日志吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									type : 'debug'
								},
                                dataType : 'json',
								url : 'sys_log_do.php?act=clear',
								success : function(data){
												layer.close(index);
                                                
												var code = data.code;
    											var msg  = data.msg;
    											switch(code){
    												case 1:
    													layer.alert(msg, {icon: 6}, function(index){
    														location.reload();
    													});
    													break;
    												default:
    													layer.alert(msg, {icon: 5});
    											}
                                            }
							});
			            }, function(){}
			        );
				});
			});
		</script>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
			<?php include('admin_menu.inc.php');?>
			<div id="maincontent">
				<div class="zm_tab">
					<ul>
						<li class="first"><a href="sys_log_common.php">系统日志</a></li>
						<li class="active"><a href="sys_log_debug.php">调试日志</a></li>
					</ul>
					<div class="handle"><input type="button" class="btn-handle btn-red" id="btn_clear" value="清空调试日志"/></div>
				</div>
				<div class="textcontent">
					<?php
						try {
							$r = $mylog->read('debug');
							echo json_decode($r)->msg;
						}catch (MyException $e){
							echo json_decode($e->jsonMsg())->msg;
						}
					?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>