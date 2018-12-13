<?php
/**
 * 管理员列表
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$POWERID        = '7002';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>管理员 - 管理设置 - 管理系统 </title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php 
				$FLAG_TOPNAV = "system";//主菜单焦点
				include('nav.inc.php');
			?>
		</div>
		<div id="container">
			<?php
				$FLAG_LEFTMENU  = 'admin_list';//左侧子菜单焦点
				include('admin_menu.inc.php');
			?>
			<div id="maincontent">
				<div class="zm_handle">
					<div class="btns">
						<input type="button" class="btn-handle" id="btn_add" value="添加管理员"/>
					</div>
				</div>
				<div class="tablelist">
					<table>
						<tr>
							<th>帐号</th>
							<th>管理员所属组</th>
							<th>最近一次登录IP</th>
							<th>最近一次登录时间</th>
							<th>登录次数</th>
							<th>操作</th>
						</tr>
						<?php
							$rows = Admin::getList();
							if(!empty($rows)){
								foreach($rows as $row){
									$groupInfo     = new Admingroup($row['group']);
                                    
									if(!empty($row['logintime']))
										$logintime = date('Y-m-d H:i:s',$row['logintime']);
									else
										$logintime = '';
                                        
									echo '<tr>
											<td>'.$row['account'].'</td>
											<td>'.$groupInfo->name.'</td>
											<td class="center">'.$row['loginip'].'</td>
											<td class="center">'.$logintime.'</td>
											<td class="center">'.$row['logincount'].'</td>
											<td class="center">';
												//当前管理员不能操作
												if($adminId != $row['id']) echo '<a href="javascript:reset('.$row['id'].')"><img src="images/action/dot_reset.png" class="reset"/></a>
												<a href="javascript:edit('.$row['id'].')" ><img src="images/action/dot_edit.png" class="editinfo"/></a>
												<a href="javascript:del('.$row['id'].')"><img src="images/action/dot_del.png" class="delete"/></a>';
												echo '
											</td>
										</tr>
									';
								}
							}else{
								echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
							}
						?>
						
					</table>
					<div id="pagelist">
						<div class="pageinfo">
							<span class="table_info">共<?php echo count($rows);?>条数据</span>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
		<script type="text/javascript">
			$(function(){
				//添加管理员
				$('#btn_add').click(function(){
		            layer.open({
		                type: 2,
		                title: '添加管理员',
		                shadeClose: true,
		                shade: 0.3,
		                area: ['500px', '300px'],
		                content: 'admin_add.php'
		            }); 
				});
				$(".reset").mouseover(function(){
					layer.tips('重置密码', $(this), {
					    tips: [4, '#3595CC'],
					    time: 500
					});
				});
				$(".editinfo").mouseover(function(){
					layer.tips('修改', $(this), {
					    tips: [4, '#3595CC'],
					    time: 500
					});
				});
				$(".delete").mouseover(function(){
					layer.tips('删除', $(this), {
					    tips: [4, '#3595CC'],
					    time: 500
					});
				});
			});

			//修改
			function edit(id){
				layer.open({
					type: 2,
					title: '修改管理员',
					shadeClose: true,
					shade: 0.3,
					area: ['500px', '300px'],
					content: 'admin_edit.php?id='+id
				}); 
			}
			//重置密码
			function reset(id){
				layer.confirm('确认重置该管理员账号的密码吗？', {
					btn: ['确认','取消']
					}, function(){
						var index = layer.load(0, {shade: false});
						$.ajax({
							type        : 'POST',
							data        : {
								id:id
							},
							dataType :    'json',
							url :         'admin_do.php?act=reset',
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
			}
			//删除管理员
			function del(id){
				layer.confirm('确认删除该管理员账号吗？', {
					btn: ['确认','取消']
					}, function(){
						var index = layer.load(0, {shade: false});
						$.ajax({
							type        : 'POST',
							data        : {
								id:id
							},
							dataType : 'json',
							url : 'admin_do.php?act=del',
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
			}
				
		</script>
	</body>
</html>