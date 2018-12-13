<?php
/**
 * 添加管理员组【弹窗页面】
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
require_once('admincheck.php');

$POWERID       = '7001';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
	</head>
	<body>
		<div id="maincontent">
			<div class="zm_form_wrap">
				<p>
					<label>组名称</label>
					<input type="text" class="text-input input-length-30" name="admingroup_name" />
					<span class="warn-inline">* </span>
				</p>
				<p>
					<label>组类型</label>
					<select name="admingroup_type" id="admingroup_type" class="select-option">
						<?php
							foreach($ARRAY_admin_type as $key => $value){
								echo '<option value="'.$key.'">'.$value.'</option>';
							}
						?>
					</select>
					<span class="warn-inline">* </span>
				</p>
				<p>
					<label>　　</label>
					<input type="submit" id="btn_submit" class="btn_submit" value="提交" />
				</p>
			</div>
		</div>
		<script type="text/javascript">
			$(function(){
				$('#btn_submit').click(function(){

					var admingroup_name = $('input[name="admingroup_name"]').val();
					var admingroup_type = $('#admingroup_type').val();
					
					if(admingroup_name == ''){
						layer.msg('组名不能为空');
						return false;
					}
					if(admingroup_type == ''){
						layer.msg('组类型不能为空');
						return false;
					}
					$.ajax({
						type        : 'POST',
						data        : {
								admingroup_name  : admingroup_name,
								admingroup_type  : parseInt(admingroup_type)
						},
						dataType:     'json',
						url :         'admingroup_do.php?act=add',
						success :     function(data){
							var code = data.code;
							var msg  = data.msg;
							switch(code){
								case 1:
									layer.alert(msg, {icon: 6,shade: false}, function(index){
										parent.location.reload();
									});
									break;
								default:
									layer.alert(msg, {icon: 5});
							}
						}
					});
				});
			});
		</script>
	</body>
</html>