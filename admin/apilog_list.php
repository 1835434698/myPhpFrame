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
		<title>Api日志 - 管理系统</title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php
				$FLAG_TOPNAV    = 'apilog';//主菜单焦点
				include('nav.inc.php');
			?>
		</div>
		<div id="container">
			<?php
				$FLAG_LEFTMENU  = 'apilog_list';//左侧子菜单焦点
				include('apilog_menu.inc.php');
			?>
			<div id="maincontent">
				<div class="zm_handle">
                    <input type="text" id="api" name="api" placeholder="api" class="input-handle" style="width: 150px; value=""/>
                    <input type="text" id="uid" name="uid" placeholder="uid" class="input-handle" style="width: 100px; value=""/>
                    <input type="text" id="ip" name="ip" placeholder="ip" class="input-handle" style="width: 100px; value=""/>
                    <input type="button" class="btn-handle" href="javascript:" id="search" value="查 询"/>
<!--					<div class="btns">-->
<!--						<input type="button" class="btn-handle btn-yellow" id="btn_order" value="保存排序"/>-->
<!--						<input type="button" class="btn-handle" id="btn_add" value="添加管理员组"/>-->
<!--					</div>-->
				</div>
				<div class="tablelist">
					<table>
						<tr>
							<th>api</th>
							<th>uid</th>
							<th>request</th>
							<th>response</th>
							<th>time</th>
							<th>ip</th>
						</tr>
						<?php
                        $filter = array();
                        //初始化
                        $api = '';
                        if(isset($_GET['api'])){
                            $api = safeCheck($_GET['api'], 0);
                            $filter['api'] = $api;
                        }
                        $uid = '';
                        if(isset($_GET['uid'])){
                            $uid = safeCheck($_GET['uid'], 0);
                            $filter['uid'] = $uid;
                        }
                        $ip = '';
                        if(isset($_GET['ip'])){
                            $ip = safeCheck($_GET['ip'], 0);
                            $filter['ip'] = $ip;
                        }

                        $totalcount= Apilog::getListByPage($filter, 1);
                        $shownum   = 15;
                        $pagecount = ceil($totalcount / $shownum);
                        $page      = getPage($pagecount);

//                        $page = 1;
//                        $pagesize = 15;
							$rows = Apilog::getListByPage($filter, 0, $page, $shownum);

							if(!empty($rows)){
								foreach($rows as $row){

                                    if(!empty($row['time']))
                                        $logtime = date('Y-m-d H:i:s', $row['time']);
                                    else
                                        $logtime = '';
						?>
						<tr>
							<td width="10%" ><?php echo $row['api'];?></td>
							<td width="10%" ><?php echo $row['uid'];?></td>
							<td width="20%" ><?php echo $row['request'];?></td>
							<td width="20%" ><?php echo $row['response'];?></td>
							<td width="20%" ><?php echo $logtime;?></td>
                            <td width="20%" ><?php echo $row['ip'];?></td>
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
                            <span class="table_info">共<?php echo $totalcount;?>条数据，共<?php echo $pagecount;?>页</span>
                        </div>
                        <?php
                        if($pagecount>1){
                            echo dspPages($page, $pagecount);
                        }
                        ?>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>

		<script type="text/javascript">
		$(function(){
            //查找
            $('#search').click(function(){
                var api  = $('#api').val();
                var uid  = $('#uid').val();
                var ip  = $('#ip').val();
                var searchurl = "apilog_list.php?api="+api+"&uid="+uid+"&ip="+ip;
                location.href  = searchurl;
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