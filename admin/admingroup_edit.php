<?php
/**
 * 编辑管理员组【弹出窗口】
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
require_once('admincheck.php');

$POWERID       = '7001';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

$id = safeCheck($_GET['id']);
$group = Admingroup::getInfoById($id);
if($group){
	$groupid   = $group['groupid'];
	$groupname = $group['name'];
	$grouptype = $group['type'];
}else{
	die('数据不存在或已被删除');
}
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
					<input type="text" class="text-input input-length-30" name="group_name" value="<?php echo $groupname;?>" />
					<span class="warn-inline">* </span>
				</p>
				<p>
					<label>组类型</label>
					<select name="group_type" class="select-option">
						<?php
							//获取组类型
							foreach($ARRAY_admin_type as $key => $value){
								echo '<option value="'.$key.'"';
								if($grouptype == $key){
									echo 'selected>'.$value.'</option>';
								}else{
									echo '>'.$value.'</option>';
								}
							}
						?>
					</select>
					<span class="warn-inline">* </span>
				</p>
				<p>
					<label>　　</label>
					<input type="hidden" name="group_id" value="<?php echo $groupid;?>" />
					<input type="submit" id="btn_sumit" class="btn_submit" value="修　改" />
				</p>
			</div>
		</div>
		<script type="text/javascript">
			$(function(){
				$('#btn_sumit').click(function(){

					var group_name = $('input[name="group_name"]').val();
					var group_type = $('select[name="group_type"]').val();
					var group_id   = <?php echo $groupid;?>;

					if(group_name == ''){
						layer.msg('组名不能为空');
						return false;
					}
					if(group_type == ''){
						layer.msg('组类型不能为空');
						return false;
					}

					$.ajax({
						type        : 'POST',
						data        : {
							group_name  : group_name,
							group_type  : parseInt(group_type),
							id          : group_id
						},
						dataType :  'json',
						url :       'admingroup_do.php?act=edit',
						success :   function(data){
							var code = data.code;
							var msg  = data.msg;
							switch(code){
								case 1:
									layer.alert(msg, {icon: 6 ,shade: false}, function(index){
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