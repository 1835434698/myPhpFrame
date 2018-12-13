<?php
/**
 * 系统技术信息
 *
 * @createtime		2018/4/14
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV   = "system";
$FLAG_LEFTMENU = 'sys_tech_info';

$POWERID       = '7003';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);

//判断操作系统位数的简易方法
if(is_float(12345678900)){
	$sysbit = '32';
}else{
	$sysbit = '64';
}

//获取目录大小
function get_folder_size($path) {
	$total_size = 0;
	$files = scandir($path);

	foreach($files as $t) {
		if (is_dir(rtrim($path, '/') . '/' . $t)) {
			if ($t<>"." && $t<>"..") {
				$size = get_folder_size(rtrim($path, '/') . '/' . $t);
				$total_size += $size;
			}
		} else {
			$size = filesize(rtrim($path, '/') . '/' . $t);
			$total_size += $size;
		}
	}
	return $total_size;
}

function get_format_filesize($size) {
	$mod = 1024;
	$units = explode(' ','B KB MB GB TB PB');
	for ($i = 0; $size > $mod; $i++) {
		$size /= $mod;
	}
	return round($size, 2) . ' ' . $units[$i];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('htmlhead.inc.php');?>
		<title>技术信息 - 系统信息 - 管理系统 </title>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
			<?php include('admin_menu.inc.php');?>
			<div id="maincontent">
				<div class="tablelist">
					<table>
						<tr>
							<th width="10%">No.</th>
                            <th width="30%">Item</th>
							<th width="60%">Value</th>
						</tr>
						<tr>
							<td class="center">1</td>
                            <td>技术架构</td>
							<td>PHP+MySQL</td>
						</tr>
						<tr>
							<td class="center">2</td>
                            <td>Server版本</td>
							<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
						</tr>
						<tr>
							<td class="center">3</td>
                            <td>ZhimaPHP版本</td>
							<td><?php echo ZHIMAPHP_VERSION.'('.ZHIMAPHP_UPDATE.')';?></td>
						</tr>
						<tr>
							<td class="center">4</td>
                            <td>服务器操作系统</td>
							<td><?php echo php_uname();?>（<?php echo $sysbit?>位）</td>
						</tr>
						<tr>
							<td class="center">5</td>
                            <td>服务器IP地址</td>

							<td><?php $serverip = gethostbyname($_SERVER['SERVER_NAME']); echo $serverip;?>（<a href="https://www.baidu.com/s?wd=<?php echo $serverip?>" target="_blank">点击查看服务器地理位置</a>）</td>
						</tr>
						<tr>
							<td class="center">6</td>
                            <td>服务器时区</td>
							<td><?php echo date_default_timezone_get();?></td>
						</tr>
						<tr>
							<td class="center">7</td>
                            <td>服务器时间</td>
							<td><?php echo date('Y-m-d H:i:s');?></td>
						</tr>
						<tr>
							<td class="center">8</td>
                            <td>服务器启用gzip</td>
							<td><?php echo $_SERVER["HTTP_ACCEPT_ENCODING"];?></td>
						</tr>
						<tr>
							<td class="center">9</td>
                            <td>服务器PHP环境设置(常用)</td>
							<td>
								POST大小限制：<?php echo get_cfg_var('post_max_size')?><br/>
								上传单个文件大小限制：<?php echo get_cfg_var('upload_max_filesize')?><br/>
								函数限制：<?php echo get_cfg_var('disable_functions')?><br/>
								错误调试状态：<?php echo get_cfg_var('display_errors')?>
							</td>
						</tr>
						<tr>
							<td class="center">10</td>
                            <td>用户上传文件占用空间</td>
							<td><?php echo get_format_filesize(get_folder_size($FILE_PATH.'userfiles'));?>（根目录下userfiles的大小）</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>