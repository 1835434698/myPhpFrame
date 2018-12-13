<?php

/**
 * 数据库表:（可将本文件另存为）
 *
 * @createtime	2018/10/17
 * @author      飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Table_admin extends Table {

	protected $table_name       = '';//表名
	protected $table_id         = '';//指定ID字段名称，必须
	protected $table_status     = '';//指定状态字段名称，如果有
	protected $table_order      = '';//指定排序字段名称，如果有

	//数据库结构
	//完整展示数据库所有字段，替换字段名称
	protected function struct(){
		$attr = array();
		
		//$attr['id']      = 'xxx_id';

		return $attr;
	}

	//增
	//@param $attr array -- 键值同struct()返回的数组
	public function add($attr){
		
		$param = array (
			//'xxx'   => array('string', $attr['yyy'])
		);
        return $this->pdo->sqlinsert($this->table_fullname, $param);

	}

	//改
	//@param $attr array -- 键值同struct()返回的数组
	public function add($id, $attr){
		
		$param = array (
			//'xxx'   => array('string', $attr['yyy'])
		);
		$where = array (
			//'xxx_id'   => array('number', $id)
		);
        return $this->pdo->sqlupdate($this->table_fullname, $param, $where);

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
			
			$sql = "select * from ". $this->table_fullname ." $where ";

			if($this->table_order){//排序
				$sql_order = " order by ". $this->table_order ." asc, ". $this->table_id ." desc ";
			}else{
				$sql_order = " order by ". $this->table_id ." desc ";
			}
			$sql .= $sql_order;

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
	
}
?>