<?php

/**
 * 朋友关系类
 *
 * Friend: Administrator
 * Date: 2019/1/17
 * Time: 15:47
 */

class Friend {
    public  $id    = 0;   //
    public  $friendId   = 0;  //
    public  $toFriendId   = 0;  //
    public  $time   = 0;   //添加时间

	/**
	 * 记录朋友关系
	 * 
	 * @param $log       朋友关系内容
	 * 
	 */
	static public function add($attr){
		if(empty($attr)) throw new MyException('朋友关系内容不能为空', 101);
		if(empty($attr['userId'])) throw new MyException('userId不能为空', 102);
		if(empty($attr['toUserId'])) throw new MyException('toUserId不能为空', 103);
        $attr['time'] = time();
		$Table_friend = new Table_friend();
		return $Table_friend->add($attr);
	}

	/**
	 * 根据id获取详情
	 *
     * @param $id 朋友关系id
	 *
	 */
	static public function getInfoById($id){
		if(empty($id)) throw new MyException('朋友关系id不能为空', 101);
		$Table_friend = new Table_friend();
		return $Table_friend->getInfoById($id);
	}
    
	/** 
	 * 朋友关系记录列表
	 * 
	 * @param $page        当前页
	 * @param $pagesize    每页大小
	 * 
	 */
	static public function getListByPage($attr = null, $count = 0, $page = 0, $pagesize = 0){
        $filter = array();
        if (!empty($attr)){

            if (!empty($attr['userId']))
                $filter['userid'] = array($attr['userId'], '=n');

            if (!empty($attr['toUserId']))
                $filter['touserid'] = array($attr['toUserId'], '=n');

            if (!empty($attr['time']))
                $filter['time'] = array($attr['time'], '=n');
        }
		$Table_friend = new Table_friend();
		return $Table_friend->getList($filter, $count, $page, $pagesize);
	}

    /**
     * 修改朋友关系信息
     *
     * @param $id         朋友关系ID
     * @param $attr       朋友关系属性数组，数组键值参考add()函数
     *
     */
    static public function edit($id, $attr){
        if(empty($attr)) throw new MyException('朋友关系内容不能为空', 101);
        if(empty($id)) throw new MyException('朋友关系id不能为空', 102);
        $Table_friend = new Table_friend();
        return $Table_friend->edit($id,$attr);
    }

    /**
     * 修改朋友关系信息
     *
     * @param $id         朋友关系ID
     * @param $attr       朋友关系属性数组，数组键值参考add()函数
     *
     */
    static public function del($id){
        if(empty($id)) throw new MyException('朋友关系id不能为空', 102);
        $Table_friend = new Table_friend();
        return $Table_friend->del($id);
    }

}
?>