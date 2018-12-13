<?php

/**
 * 数据库表:管理员组
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Table_admingroup extends Table {

	protected $table_name       = 'admingroup';//表名，不带前缀，前缀在config中定义
	protected $table_id         = 'admingroup_id';//指定ID字段名称，必须
	protected $table_status     = '';//指定状态字段名称，如果有
	protected $table_order      = 'admingroup_order';//指定排序字段名称，如果有

	//数据库结构
	protected function struct(){
		$attr = array();
		
		$attr['groupid']      = 'admingroup_id';
		$attr['name']         = 'admingroup_name';//名称
		$attr['auth']         = 'admingroup_auth';//管理权限
		$attr['type']         = 'admingroup_type';//类型，对应值在setting.inc.php中
		$attr['order']        = 'admingroup_order';//排序，默认值99

		return $attr;
	}

	//增
	//@param $attr array -- 键值同struct()返回的数组
	public function add($attr){
		$param = array (
			'admingroup_name'    => array('string', $attr['name']),
			'admingroup_type'    => array('number', $attr['type'])
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
			
			$sql = "select * from ". $this->table_fullname ." $where order by admingroup_order asc, admingroup_id desc";

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
     * getInfoByName() 根据名称获取管理员组详情
     * 
     * @param $name  管理员组名
     * 
     * @return
     */
	public function getInfoByName($name){
		
		$name = $this->pdo->sql_check_input(array('string', $name));
		
		$sql = "select * from ". $this->table_fullname ." where admingroup_name = $name limit 1";
		$rs  = $this->pdo->sqlQuery($sql);
		$r   = array();
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
     * 修改管理员组信息
     * 
     * @param $attr  组属性数组
     * @param $id    组ID
     */
	public function edit($id, $attr){
		
		$param = array (
			'admingroup_name'  => array('string', $attr['name']),
			'admingroup_type'  => array('number', $attr['type'])
		);
		
		$where = array(
			"admingroup_id"    => array('number', $id)
		);
		
		return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
    }
    
    /**
     * 修改管理员组权限
     *
     * @param $id     管理组ID
     * @param $auth   权限字符串
     * @return
     */
	public function updateAuth($id, $auth){
		
		$param = array (
			'admingroup_auth'  => array('string', $auth)
		);

		$where = array(
			"admingroup_id"    => array('number', $id)
		);
		
		return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
    }
}
?>