<?php

/**
 * 管理员日志类
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Adminlog {

	/**
	 * 记录管理员日志
	 * 
	 * @param $log       日志内容
	 * 
	 */
	static public function add($log){
		if(empty($log)) throw new MyException('日志内容不能为空', 101);

		$adminid = Admin::getSession();
		
		$attr = array(
			'adminid' => $adminid,
			'content' => $log
		);

		$Table_adminlog = new Table_adminlog();
		return $Table_adminlog->add($attr);
	}
    
	/** 
	 * 管理员日志记录列表
	 * 
	 * @param $page        当前页
	 * @param $pagesize    每页大小
	 * 
	 */
	static public function getListByPage($page, $pagesize){
		$filter = array();

		$Table_adminlog = new Table_adminlog();
		return $Table_adminlog->getList($filter, 0, $page, $pagesize);
	}
    
	//管理员日志总数
	static public function getCountAll(){
		$filter = array();

		$Table_adminlog = new Table_adminlog();
		return $Table_adminlog->getList($filter, 1);
	}
}
?>