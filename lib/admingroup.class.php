<?php

/**
 * 管理员分组类
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Admingroup {
	
	public  $gid    = 0;   //组ID
    public  $name   = '';  //组名
    public  $auth   = '';  //组权限，示例值：7001|7002|7003
    public  $type   = 0;   //组类型，默认值：0-普通管理员组；9-超级管理员组
    
    /**
     * 构造函数
     *
     * @param $gid   管理员分组组ID
     * 
     * @return
     */
	public function __construct($gid = 0) {
		if(!ParamCheck::is_ID($gid)) {
			throw new MyException('管理员组ID不合法', 901);
		}else{
			$group = self::getInfoById($gid);
			if($group){
				$this->gid  = $gid;
				$this->auth = $group['auth'];
				$this->name = $group['name'];
				$this->type = $group['type'];
			}else{
				throw new MyException('管理员组不存在', 902);
			}
			
		}
	}

	/**
	 * 添加管理员组
	 * 
	 * @param $name      组名
	 * @param $type      组类别值
	 */
	static public function add($name, $type = 0){
		global $mypdo;
		
		if(empty($name)) throw new MyException('管理员组名不能为空', 101);

		//判断组名是否重复
		$Table_admingroup = new Table_admingroup();
        $g = $Table_admingroup->getInfoByName($name);
		if($g) throw new MyException('组名已存在', 102); 
		
		$attr = array(
			'name' => $name,
			'type' => $type
		);
        $gid = $Table_admingroup->add($attr);
		if($gid){
            $msg = '添加管理员组('.$gid.':'.$name.')成功!';
            Adminlog::add($msg);

			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 103); 
		}
	}
	
	/**
	 * 修改管理员组
	 * 
	 * @param $id       管理组ID
	 * @param $name
	 * @param $type
	 */
	static public function edit($id, $name, $type = 0){
		
		if(!ParamCheck::is_ID($id)) throw new MyException('管理员组ID不合法', 101);
		if(empty($name)) throw new MyException('管理员组名不能为空', 102);
		
		$attr = array(
			'name' => $name,
			'type' => $type
		);
		$Table_admingroup = new Table_admingroup();
        $rs = $Table_admingroup->edit($id, $attr);
        if($rs >= 0){//未做修改也算是修改成功
            $msg = '修改管理员组('.$id.')成功';
            Adminlog::add($msg);
			
			return action_msg($msg, 1);
        }else{
			throw new MyException('操作失败', 103);
        }
	}

	/**
	 * 删除管理员组
	 * 
	 * @param $gid   管理员组ID
	 * 
	 */
	static public function del($gid){
		if(!ParamCheck::is_ID($gid)) throw new MyException('管理员组ID不合法', 101);

		//判断该组下是否有管理员
		if(self::getAdminCount($gid) > 0)  throw new MyException('该组有管理员。请先删除或转移管理员。', 102);
		
		$Table_admingroup = new Table_admingroup();
        $rs = $Table_admingroup->del($gid);
		if($rs == 1){
			$msg = '删除管理员组('.$gid.')成功!';
			Adminlog::add($msg);
			
			return action_msg($msg, 1);//成功
		}else{
			throw new MyException('操作失败', 103);
		}
	}

	//获取管理员组中管理员的数量
	static public function getAdminCount($gid){
		$filter = array(
			'group' => $gid
		);
		$Table_admin = new Table_admin();
		return $Table_admin->getList($filter, 1);
	}

	//获取管理员组列表
	static public function getList(){
		$filter = array();

		$Table_admingroup = new Table_admingroup();
		return $Table_admingroup->getList($filter);
	}

	/**
	 * 管理员组详细信息
	 * 
	 * @param $gid 管理组ID
	 * 
	 */
	static public function getInfoById($gid){
		if(!ParamCheck::is_ID($gid)) throw new MyException('管理员组ID不合法', 101);
		
		$Table_admingroup = new Table_admingroup();
		return $Table_admingroup->getInfoById($gid);
	}

	/**
	 * 管理员组排序
	 * 
	 * @param $gid       管理员组ID
	 * @param $order     顺序数值
	 * @return
	 */
	static function updateOrder($gid, $order){
		if(!ParamCheck::is_ID($gid)) throw new MyException('管理员组ID不合法', 101);
		if(!ParamCheck::is_ID($order)) throw new MyException('排序值必须为正整数', 103);
		
		$Table_admingroup = new Table_admingroup();
        $r = $Table_admingroup->updateOrder($gid, $order);
		if($r >= 0){
			return action_msg('排序成功', 1);
		}else{
			throw new MyException('操作失败', 104);
		}
	}
	
	/**
	 * 修改管理员组权限
	 * 
	 * @param $gid    管理员组ID
	 * @param $auth   权限字符串
	 */
	static public function updateAuth($gid, $auth){
		if(!ParamCheck::is_ID($gid)) throw new MyException('管理员组ID不合法', 101);
		//if(empty($auth)) throw new MyException('权限不能为空', 102);
		
		$Table_admingroup = new Table_admingroup();
        $rs = $Table_admingroup->updateAuth($gid, $auth);
		if($rs >= 0){
			$msg = '修改管理员组('.$gid.')权限成功!';
			Adminlog::add($msg);

			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 103);
		}
	}
    
	//获得管理员组权限
	public function getAuth(){
		return $this->auth;
	}

	//获得管理员组名称
	public function getName(){
		return $this->name;
	}

}

?>