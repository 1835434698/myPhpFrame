<?php
/**
 * 编辑管理员组权限
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$POWERID = '7001';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

$id = safeCheck($_GET['id']);
$group = Admingroup::getInfoById($id);
if($group){
	$groupId   = $group['groupid'];
	$groupName = $group['name'];
	$groupType = $group['type'];
	$groupAuth = explode('|', $group['auth']);
}else{
	die('数据不存在或已被删除');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>管理员权限修改 - 管理系统 </title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php
				$FLAG_TOPNAV   =  "system";//主菜单焦点
				include('nav.inc.php');
			?>
		</div>
		<div id="container">
			<?php
				$FLAG_LEFTMENU =  'admingroup_list';//左侧子菜单焦点
				include('admin_menu.inc.php');
			?>
			<div id="maincontent">
				<div class="tablelist">
					<h3>编辑“<?php echo $groupName?>”的权限</h3>
					<table>
						<tr>
							<th width="5%">选择</th>
							<th width="10%">权限编号</th>
							<th width="30%">功能模块</th>
							<th>说明</th>
						</tr>
						<tr>
							<td><input type="checkbox" class="checkbox-input" value="7001" <?php if(in_array(7001, $groupAuth)) echo 'checked';?> /></td>
							<td>7001</td>
							<td>管理员组</td>
							<td>管理员组的增、删、改、权限设置、列表</td>
						</tr>
						<tr>
							<td><input type="checkbox" class="checkbox-input" value="7002" <?php if(in_array(7002, $groupAuth)) echo 'checked';?> /></td>
							<td>7002</td>
							<td>管理员</td>
							<td>管理员的增、删、改、列表</td>
						</tr>
						<tr>
							<td><input type="checkbox" class="checkbox-input" value="7003" <?php if(in_array(7003, $groupAuth)) echo 'checked';?> /></td>
							<td>7003</td>
							<td>管理员日志</td>
							<td>查看管理员日志</td>
						</tr>
					</table>
				</div>
				<div class="zm_form_wrap">
                    <p class="center">
        				<input type="submit" id="btn_submit" class="btn_submit" value="保存" />
						<input type="button" id="btn_back" class="btn btn_back" value="返回"/>
                        <input type="hidden" id="gid" value="<?php echo $groupId;?>" />
        			</p>
        		</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
		<script type="text/javascript">
			$(function(){
				// 返回
				$('#btn_back').click(function(){
					window.location.href = 'admingroup_list.php';
				});

				$('#btn_submit').click(function(){
				    var powernum =  $('.tablelist td > .checkbox-input').length;
                    var powerlist = '';
                    var id = $("#gid").val();
                    for(i=0; i<powernum; i++){
                        if($('.tablelist td > .checkbox-input').eq(i).prop('checked')){
                            powerlist = $('.tablelist td > .checkbox-input').eq(i).val() + '|' + powerlist;
                        }
                    }
                    $.getJSON('admingroup_do.php?act=updateauth',{id : id, auth : powerlist},function(data){
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
                    });
				});

			});
				
		</script>
	</body>
</html>

