<?php

/**
 * CURL类
 *
 * @createtime	2018/03/01
 * @author      飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Curl {
	
	/**
	 * post()
	 * 
	 * @param mixed $url
	 * @param mixed $postData 格式形如：id=3&name=tester //// TODO: wait 补充cookie情况和文件提交情况
	 * @param bool $is_https 
	 * @return
	 */
	static public function post($url, $postData, $is_https = false){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);//设置超时
		curl_setopt($ch, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//要求结果为字符串且输出到屏幕上
		if($is_https){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			throw new Exception("Curl出错，错误码:$error。");
		}
	}

	/**
	 * get()
	 * 
	 * @param mixed $url
	 * @return
	 */
	static public function get($url, $is_https = false){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);//设置超时
		curl_setopt($ch, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		if($is_https){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			throw new Exception("Curl出错，错误码:$error。");
		}
	}

	

}
?>