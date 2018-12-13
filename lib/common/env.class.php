<?php

/**
 * 环境类
 *
 * 收集常用的获得服务器端或客户端的环境参数的函数
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发 (http:/www.baidu.com)
 */

class Env {
	
	/**
	 * 获得当前页面的URL地址
	 */
	static public function getPageUrl(){  
		$url = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';  
		$url .= $_SERVER['HTTP_HOST'];
		$url .= $_SERVER['REQUEST_URI'];
		return $url;
	}

	/**
	 * 获取当前的操作系统是Linux还是Windows
	 */
	static public function getOSType(){
		$ostype = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
		return $ostype;
	}

	/**
	 * 获得访问者IP
	 */
	static public function getIP(){
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "unknown";
		return $ip;
	}

	

}
?>