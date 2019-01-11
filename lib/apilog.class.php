<?php

/**
 * api日志类
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Apilog {
    public  $id    = 0;   //组ID
    public  $api   = '';  //api
    public  $uid   = '';  //用户id
    public  $requeste   = "";   //请求内容
    public  $response   = "";   //响应内容
    public  $time   = 0;   //添加时间
    public  $ip   = "";   //操作者IP

	/**
	 * 记录api日志
	 * 
	 * @param $log       日志内容
	 * 
	 */
	static public function add($attr){
		if(empty($attr)) throw new MyException('日志内容不能为空', 101);
		if(empty($attr['api'])) throw new MyException('api内容不能为空', 102);
        $attr['time'] = time();
		$Table_apilog = new Table_apilog();
		return $Table_apilog->add($attr);
	}
    
	/** 
	 * api日志记录列表
	 * 
	 * @param $page        当前页
	 * @param $pagesize    每页大小
	 * 
	 */
	static public function getListByPage($attr, $count = 0, $page = 0, $pagesize = 0){
        $filter = array();

        if (!empty($attr['api']))
            $filter['api'] = array($attr['api'], '=s');

        if (!empty($attr['ip']))
            $filter['ip'] = array($attr['ip'], '=s');

        if (!empty($attr['uid']))
            $filter['uid'] = array($attr['uid'], '=s');

        if (!empty($attr['time']))
            $filter['time'] = array($attr['time'], '=s');

		$Table_apilog = new Table_apilog();
		return $Table_apilog->getList($filter, $count, $page, $pagesize);
	}

}
?>