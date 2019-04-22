<div id="leftmenu">
	<div class="menu1"><a href="apilog_list.php">管理设置</a></div>
	<?php
    $sessionAuth = explode('|', $ADMINAUTH);
	if(in_array('7003', $sessionAuth)){
		echo '<div class="menu2"><a';
		if($FLAG_LEFTMENU == 'apilog_list') echo ' class="active"';
		echo ' href="apilog_list.php">系统日志</a></div><div class="menuline"></div>';
	}
	?>
</div>