<?php
/**
 * 修改密码【弹窗页面】
 *
 * @createtime		2018/03/01
 * @author			飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
require_once('admincheck.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
	</head>
	<body>
		<div class="zm_form_wrap">
			<p>
				<label>旧密码</label>
				<input type="password" class="text-input input-length-30" name="old_password" value="" />
				<span class="warn-inline">* </span>
			</p>
			<p>
				<label>新密码</label>
				<input type="password" class="text-input input-length-30" name="new_password" />
				<span class="warn-inline">* </span>
			</p>
			<p>
				<label>确认新密码</label>
				<input type="password" class="text-input input-length-30" name="re_password" value="" />
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
					var old_password = $('input[name="old_password"]').val();
					var new_password = $('input[name="new_password"]').val();
					var re_password = $('input[name="re_password"]').val();


					if(old_password == ''){
						layer.msg('旧密码不能为空');
						return false;
					}
					if(new_password == ''){
						layer.msg('新密码不能为空');
						return false;
					}
					if(re_password == ''){
						layer.msg('确认密码不能为空');
						return false;
					}
					if(new_password != re_password){
						layer.alert('两次输入密码不一致！', {icon: 5, shade: false});
						return false;
					}

					$.ajax({
						type        : 'POST',
						data        : {
								old_password  : old_password,
								new_password  : new_password,
								re_password   : re_password
						},
						dataType : 'json',
						url :         'admin_resetpass_do.php?act=editpass',
						success :     function(data){
							var code = data.code;
							var msg  = data.msg;
							switch(code){
								case 1:
									layer.alert('修改成功！', {icon: 6, shade: false}, function(index){
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