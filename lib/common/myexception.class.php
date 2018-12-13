<?php

/**
 * 自定义错误类
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发 (http:/www.baidu.com)
 */

class MyException extends Exception {

	public function jsonMsg(){

		$code = $this->getCode();
		$msg  = $this->getMessage();

		return action_msg($msg, $code, 1);
	}

}
?>