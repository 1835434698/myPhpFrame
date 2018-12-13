<?php
/**
 * 编辑管理员【弹窗页面】
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
require_once('admincheck.php');

$POWERID = '7002';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

$id = safeCheck($_GET['id']);
$admin = Admin::getInfoById($id);
if($admin){
	$adminId      = $admin['id'];
	$adminAccount = $admin['account'];
	$adminGroup   = $admin['group'];
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
		<div class="zm_form_wrap">
			<p>
				<label>帐号</label>
				<input type="text" class="text-input input-length-30" name="account" value="<?php echo $adminAccount;?>" />
				<span class="warn-inline">* </span>
			</p>
			<p>
				<label>管理员所属组</label>
				<select name="admin_group" class="select-option" id="group">
					<?php
						$group = Admingroup::getList();
                        
						foreach($group as $g){
							$gid   = $g['groupid'];
							$gname = $g['name'];
							echo '<option value="'.$gid.'"';
							if($adminGroup == $gid) {
								echo' selected="selected">'.$gname.'</option>';
							}else{
								echo' >'.$gname.'</option>';
							}
						}
					?>
				</select>
				<span class="warn-inline">* </span>
			</p>
			<p>
				<label>　　</label>
				<input type="submit" id="btn_submit" class="btn_submit" value="提　交" />
			</p>
		</div>
		<script type="text/javascript">
			$(function(){
				$('#btn_submit').click(function(){

					var account = $('input[name="account"]').val();
					var group = $('#group').val();
					var aid = <?php echo $adminId;?>;

					if(account == ''){
						layer.msg('账号不能为空');
						return false;
					}
					if(group == ''){
						layer.msg('请选择管理员组');
						return false;
					}
					$.ajax({
						type        : 'POST',
						data        : {
								account  : account,
								id       : aid,
								group    : group
						},
						url :         'admin_do.php?act=edit',
                        dataType:     'json',
						success :     function(data){
							var code = data.code;
							var msg  = data.msg;
							switch(code){
								case 1:
									layer.alert(msg, {icon: 6, shade: false}, function(index){
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