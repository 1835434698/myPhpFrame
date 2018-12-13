<?php

/**
 * 数据库表:管理员表
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Table_admin extends Table {

	protected $table_name       = 'admin';//表名
	protected $table_id         = 'admin_id';//指定ID字段名称，必须
	protected $table_status     = '';//指定状态字段名称，如果有
	protected $table_order      = '';//指定排序字段名称，如果有

	//数据库结构
	protected function struct(){
		$attr = array();
		
		$attr['id']         = 'admin_id';
		$attr['name']       = 'admin_name';//名称
		$attr['account']    = 'admin_account';//账号
		$attr['password']   = 'admin_password';//加密后的密码
		$attr['salt']       = 'admin_salt';//密码salt
		$attr['group']      = 'admin_group';//所属管理员组ID
		$attr['loginip']    = 'admin_lastloginip';//最后一次登录IP
		$attr['logintime']  = 'admin_lastlogintime';//最后一次登陆时间
		$attr['logincount'] = 'admin_logincount';//登录次数，默认值0
		$attr['addtime']    = 'admin_addtime';//添加时间
		
		return $attr;
	}

	//增
	//@param $attr array -- 键值同struct()返回的数组
	public function add($attr){
		$param = array (
			'admin_account'   => array('string', $attr['account']),
			'admin_password'  => array('string', $attr['password']),
			'admin_salt'      => array('string', $attr['salt']),
			'admin_group'     => array('number', $attr['group']),
			'admin_addtime'   => array('number', time())
		);
        return $this->pdo->sqlinsert($this->table_fullname, $param);
	}

	//获取列表（分页）
	//$count、$page和$pagesize都为0时，返回全部结果（适用于无需分页的情况）
	//
	//@param $filter array -- 过滤条件，格式见Table::filterToWhere
	//@param $count -- 0：返回列表 1：返回结果数量
	//@param $page -- 当前第几页
	//@param $pagesize -- 每页数量
	public function getList($filter = array(), $count = 0, $page = 0, $pagesize = 0){
		
		$where = $this->filterToWhere($filter);
		
		if($count == 0){//列表
			
			$sql = "select * from ". $this->table_fullname ." $where order by admin_id desc";

			if($page > 0){//分页
				$startrow = ($page - 1) * $pagesize;
				$sql_limit = " limit $startrow, $pagesize";
				$sql .= $sql_limit;
			}
			
			$rs  = $this->pdo->sqlQuery($sql);
			$r   = array();
			if($rs){
				foreach($rs as $key => $val){
					$r[$key] = $this->dataToAttr($val);
				}
				return $r;
			}else{
				return $r;
			}

		}else{//统计
			
			$sql = "select count(*) as c from ". $this->table_fullname . " $where ";
			$rs  = $this->pdo->sqlQuery($sql);
			if($rs){
				return $rs[0]['c'];
			}else{
				return 0;
			}
		}
	}
	
    
	/**
	 * 根据账号获取详情
	 * 
	 * @param $acount 管理员账号
	 * 
	 */
	public function getInfoByAccount($account){

		$account = $this->pdo->sql_check_input(array('string', $account));
        
		$sql = "select * from ". $this->table_fullname ." where admin_account = $account limit 1";
        
		$rs = $this->pdo->sqlQuery($sql);
		$r  = array();
		if($rs){
			foreach($rs as $key => $val){
				$r[$key] = $this->dataToAttr($val);
			}
			return $r[0];
		}else{
			return $r;
		}
	}

	/**
     * 登录时更新管理员信息
     * 
     * @param $id 管理员ID
     * 
     */
	public function updateLoginInfo($id){
        
        $ip = Env::getIP();

		$param = array(
			'admin_lastloginip'   => array('string', $ip),
			'admin_lastlogintime' => array('number', time()),
			'admin_logincount'    => array('expression', 'admin_logincount+1'),
		);

		$where = array(
			'admin_id'			  =>array('number', $id)
		);
        
        return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
    }
    
    /**
     * 修改管理员账号和组信息
     * 
     * @param $id         管理员ID
     * @param $attr       管理员属性数组，数组键值参考add()函数
     * 
     */
	public function edit($id, $attr){	
		$param = array (
			'admin_account'   => array('string', $attr['account']),
			'admin_group'     => array('number', $attr['group'])
		);

		$where = array(
			'admin_id'		  => array('number', $id)
		);

		return $this->pdo->sqlupdate($this->table_fullname, $param, $where); 
    }
    
	/**
	 * 修改密码
	 * 
	 * @param $id        管理员ID
	 * @param $newpass   新密码
	 * @param $salt      Salt
	 * 
	 */
	public function updatePwd($id, $newpass, $salt){

		$param = array(
			"admin_password" => array('string', $newpass),
			"admin_salt"     => array('string', $salt)
		);

		$where = array(
			"admin_id" => array('number', $id)
		);

		return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
    }
}
?>