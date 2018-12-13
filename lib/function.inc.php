<?php

/**
 * 本文件放置与业务有关又无需写成类的函数
 * 
 * 与业务无关的函数放置于function_common.inc.php 
 * 系统环境有关的函数见lib/common/Env类
 * 参数检查有关的函数见lib/common/ParamCheck类
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

/**
 * 获得执行程序的时间(秒)
 * 
 * @param $starttime 
 * @param $endtime
 *
 * @return
 */
function getRunTime($starttime = 0, $endtime = 0){
	global $PageStartTime;
	if(empty($starttime)){
		$starttime = $PageStartTime;
	}
	if(empty($endtime)){
		$PageEndTime = explode(' ',microtime());
		$PageEndTime = $PageEndTime[1] + $PageEndTime[0];
		$endtime = $PageEndTime;
	}
	
	$runtime = number_format(($endtime - $starttime), 3);
	return $runtime;
}

/**
 * 分页参数page传递后的处理
 * 
 * @param mixed $pagecount 页数
 * @return
 */
function getPage($pagecount){

	$page = empty($_GET['page']) ? 1 : safeCheck($_GET['page']);
	if(!is_numeric($page)) $page = 1;
	if($page < 1) $page = 1;
    if(empty($pagecount)) 
        $page = 1;
	elseif($page > $pagecount) 
        $page = $pagecount;

	return $page;
}
/**
 * 分页显示 dspPages()--具体样式再通过CSS控制
 * 形如：
 * 1 2 3 × × × 98 99 100
 * 1 × × × 7 8 9 × × × 100
 *
 * @param $page      当前页数
 * @param $pagecount 总页数
 * @return
 */
function dspPages($page, $pagecount){
		
		//当前页面的URL
		$url = Env::getPageUrl();
		
		//参数合法性检查
		if(!is_numeric($page))       $page = 0;
		if(!is_numeric($pagecount))  $pagecount = 0;
		
		//处理Page参数
		$p1 = strpos($url, '?page=');
        if($p1) $url = substr($url, 0, $p1);
        
        $p2 = strpos($url, '&page=');
        if($p2) $url = substr($url, 0, $p2);
		
		//构建显示
		$temppage="";
		$temppage.="<div class=\"pagenum\">";

		if($page>1){
			$temppage.="<div class=\"page_prev\"><a href=\"".$url."?page=".($page-1)."\">上一页</a></div>";
		}else{
			$temppage.="<div class=\"page_prev\">上一页</div>";
		}
		
		If($pagecount<9){
			for($p=1;$p<=$pagecount;$p++){
				if($p!=$page)
					$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				else
					$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
			}
		}else{
			if($page<=3){
				for($p=1;$p<=5;$p++){
					if($p!=$page)
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					else
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
				$temppage.=" <div class=\"pager\">...</div>";
				for($p=$pagecount-3;$p<=$pagecount;$p++){
					if($p!=$page)
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					else
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
			}else if($pagecount-$page<=3){
				for($p=1;$p<=3;$p++){
					$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
				$temppage.="<div class=\"pager\">...</div>";
				for($p=$pagecount-4;$p<=$pagecount;$p++){
					if($p!=$page){
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}else{
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}
				}
			}
			else{
				$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=1\">1</a></div>";
				$temppage.=" <div class=\"pager\">...</div>";
				for($p=$page-2;$p<=$page+2;$p++){
					if($p!=$page){
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}else{
						$temppage.=" <div class=\"pager active\">".$p."</div>";
					}
				}
				$temppage.="<div class=\"pager\">...</div>";
				$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$pagecount."\">".$pagecount."</a></div>";
			}
		}

		if($page<=$pagecount-1){
			$temppage.="<div class=\"page_prev\"><a href=\"".$url."?page=".($page+1)."\">下一页</a></div>";
		}else{
			$temppage.="<div class=\"page_prev\">下一页</div>";
		}
		
		$temppage .="</div>";		


		if(!strpos($url, "?") === false)
			$temppage=str_replace("?page=", "&page=", $temppage);

		return $temppage;
}

?>