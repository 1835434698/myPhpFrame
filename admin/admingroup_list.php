<?php
/**
 * 管理员组列表
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */

require_once('admin_init.php');
require_once('admincheck.php');

$POWERID        = '7001';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);
	
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>管理员组 - 管理设置 - 管理系统</title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php
				$FLAG_TOPNAV    = 'system';//主菜单焦点
				include('nav.inc.php');
			?>
		</div>
		<div id="container">
			<?php
				$FLAG_LEFTMENU  = 'admingroup_list';//左侧子菜单焦点
				include('admin_menu.inc.php');
			?>
			<div id="maincontent">
				<div class="zm_handle">
					<div class="btns">
						<input type="button" class="btn-handle btn-yellow" id="btn_order" value="保存排序"/>
						<input type="button" class="btn-handle" id="btn_add" value="添加管理员组"/>
					</div>
				</div>
				<div class="tablelist">
					<table>
						<tr>
							<th>组名称</th>
							<th>组类型</th>
							<th>排序</th>
							<th>操作</th>
						</tr>
						<?php
							$rows = Admingroup::getList();
							if(!empty($rows)){
								foreach($rows as $row){
						?>
						<tr>
							<td><?php echo $row['name'];?></td>
							<td class="center"><?php echo $ARRAY_admin_type[$row['type']];?></td>
							<td class="center"><input type="text" class="order-input" name="order" value="<?php echo $row['order'];?>"></td>
							<td class="center">
								<input type="hidden" value="<?php echo $row['groupid'];?>" name="rowid"/>
								<a href="admingroup_auth.php?id=<?php echo $row['groupid']?>" title="设置权限"><img src="images/action/dot_power.png"/></a>
								<a href="javascript:edit(<?php echo $row['groupid'];?>)" title="修改"><img src="images/action/dot_edit.png"/></a>
								<a href="javascript:del(<?php echo $row['groupid'];?>)" title="删除"><img src="images/action/dot_del.png"/></a>
							</td>
						</tr>
						<?php

								}
							}else{
								echo '<tr><td colspan="4" class="center">没有数据</td></tr>';
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
			//添加
			$('#btn_add').click(function(){
	            layer.open({
	                type: 2,
	                title: '添加管理员组',
	                shadeClose: true,
	                shade: 0.3,
	                area: ['500px', '300px'],
	                content: 'admingroup_add.php'
	            }); 
			});
			
            //排序
			$('#btn_order').click(function(){
				var orderList = $('input[name="order"]').map(function(){ return $(this).val(); }).get().toString();
				var idList = $('input:hidden[name="rowid"]').map(function(){ return $(this).val(); }).get().toString();
				$.getJSON('admingroup_do.php?act=order',{order:orderList, id:idList},function(data){
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
		//修改
		function edit(id){
			layer.open({
				type: 2,
				title: '修改管理员组',
				shadeClose: true,
				shade: 0.3,
				area: ['500px', '300px'],
				content: 'admingroup_edit.php?id='+id
			}); 
		}
		//删除
		function del(id){
			layer.confirm('确认删除该管理员组吗？', {
				btn: ['确认','取消']
				}, function(){
					var index = layer.load(0, {shade: false});
					$.ajax({
						type        : 'POST',
						data        : {
							id : id
						},
						dataType : 'json',
						url : 'admingroup_do.php?act=del',
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