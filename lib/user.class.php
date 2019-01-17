<?php

/**
 * 用户类
 *
 * User: Administrator
 * Date: 2019/1/17
 * Time: 15:47
 */

class User {
    public  $id    = 0;   //
    public  $mobile   = 0;  //
    public  $name   = '';
    public  $email   = "";
    public  $province   = "";   //
    public  $city   = "";   //
    public  $area   = "";   //
    public  $longitude   = "";   //
    public  $latitude   = "";   //
    public  $attribute   = "";   //
    public  $openid   = "";   //
    public  $sex   = 0;   //
    public  $type   = 0;   //
    public  $time   = 0;   //添加时间

	/**
	 * 记录用户
	 * 
	 * @param $log       用户内容
	 * 
	 */
	static public function add($attr){
		if(empty($attr)) throw new MyException('用户内容不能为空', 101);
		if(empty($attr['email'])) throw new MyException('email不能为空', 102);
        $attr['time'] = time();
		$Table_user = new Table_user();

        $rs = $Table_user->getInfoByEmail($attr['email']);
        if ($rs) throw new MyException('email已经被注册', 103);
        $rs = $Table_user->getInfoByMobile($attr['mobile']);
        if ($rs) throw new MyException('mobile已经被注册', 105);


		return $Table_user->add($attr);
	}

	/**
	 * 根据id获取详情
	 *
     * @param $id 用户id
	 *
	 */
	static public function getInfoById($id){
		if(empty($id)) throw new MyException('用户id不能为空', 101);
		$Table_user = new Table_user();
		return $Table_user->getInfoById($id);
	}

	/**
	 * 根据手机号获取详情
	 *
     * @param $mobile 用户手机
	 *
	 */
	static public function getInfoByMobile($mobile){
		if(empty($mobile)) throw new MyException('用户mobile不能为空', 101);
		$Table_user = new Table_user();
		return $Table_user->getInfoByMobile($mobile);
	}

	/**
	 * 根据Email获取详情
	 *
     * @param $Email Email
	 *
	 */
	static public function getInfoByEmail($email){
		if(empty($email)) throw new MyException('用户email不能为空', 101);
		$Table_user = new Table_user();
		return $Table_user->getInfoByEmail($email);
	}
    
	/** 
	 * 用户记录列表
	 * 
	 * @param $page        当前页
	 * @param $pagesize    每页大小
	 * 
	 */
	static public function getListByPage($attr = null, $count = 0, $page = 0, $pagesize = 0){
        $filter = array();
        if (!empty($attr)){

            if (!empty($attr['mobile']))
                $filter['mobile'] = array($attr['mobile'], '=n');

            if (!empty($attr['name']))
                $filter['name'] = array($attr['name'], '%s');

            if (!empty($attr['email']))
                $filter['email'] = array($attr['email'], '=s');

            if (!empty($attr['sex']))
                $filter['sex'] = array($attr['sex'], '=n');

            if (!empty($attr['type']))
                $filter['type'] = array($attr['type'], '=n');

            if (!empty($attr['province']))
                $filter['province'] = array($attr['province'], '=s');

            if (!empty($attr['city']))
                $filter['city'] = array($attr['city'], '=s');

            if (!empty($attr['area']))
                $filter['area'] = array($attr['area'], '=s');

            if (!empty($attr['longitude']))
                $filter['longitude'] = array($attr['longitude'], '=s');

            if (!empty($attr['latitude']))
                $filter['latitude'] = array($attr['latitude'], '=s');

            if (!empty($attr['attribute']))
                $filter['attribute'] = array($attr['attribute'], '=n');

            if (!empty($attr['openid']))
                $filter['openid'] = array($attr['openid'], '=s');

            if (!empty($attr['time']))
                $filter['time'] = array($attr['time'], '=n');
        }
		$Table_user = new Table_user();
		return $Table_user->getList($filter, $count, $page, $pagesize);
	}

    /**
     * 修改用户信息
     *
     * @param $id         用户ID
     * @param $attr       用户属性数组，数组键值参考add()函数
     *
     */
    static public function edit($id, $attr){
        if(empty($attr)) throw new MyException('用户内容不能为空', 101);
        if(empty($id)) throw new MyException('用户id不能为空', 102);
        $Table_user = new Table_user();
        return $Table_user->edit($id,$attr);
    }

    /**
     * 修改用户信息
     *
     * @param $id         用户ID
     * @param $attr       用户属性数组，数组键值参考add()函数
     *
     */
    static public function del($id){
        if(empty($id)) throw new MyException('用户id不能为空', 102);
        $Table_user = new Table_user();
        return $Table_user->del($id);
    }

}
?>