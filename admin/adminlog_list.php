<?php
/**
 * 管理员日志列表
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$POWERID       = '7003';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>管理员日志 - 管理设置 - 管理系统 </title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php
				$FLAG_TOPNAV   = "system";
				include('nav.inc.php');
			?>
		</div>
		<div id="container">
			<?php
				$FLAG_LEFTMENU = 'adminlog_list';
				include('admin_menu.inc.php');
			?>
			<div id="maincontent">
				<div class="tablelist">
					<table>
						<tr>
							<th>时间</th>
                            <th>管理员帐号</th>
                            <th>内容</th>
                            <th>IP</th>
						</tr>
						<?php
                        	$totalcount= Adminlog::getCountAll();
                        	$shownum   = 15;
                        	$pagecount = ceil($totalcount / $shownum);
                        	$page      = getPage($pagecount);

                        	$rows      = Adminlog::getListByPage($page, $shownum);
							if(!empty($rows)){
								foreach($rows as $row){

								    //获取管理员账号
                                    try {
                                        $admin       = new Admin($row['adminid']);
                                        $account     = $admin->getAccount();
                                    }catch(MyException $e){
                                        $account = '-';
                                    }
                                    
									if(!empty($row['time']))
										$logtime = date('Y-m-d H:i:s', $row['time']);
									else
										$logtime = '';

									echo '<tr>
                                            <td width="20%" class="center">'.$logtime.'</td>
											<td width="20%">'.$account.'</td>
                                            <td width="40%">'.$row['content'].'</td>
                                            <td width="20%">'.$row['ip'].'</td>
										</tr>
									';
								}
							}else{
								echo '<tr><td class="center" colspan="4">没有数据</td></tr>';
							}
						?>
						
					</table>
				</div>
                <!-- 分页信息 -->
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
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>