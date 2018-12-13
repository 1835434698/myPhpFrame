<?php

/**
 * 数据库表:管理员日志表
 *
 * @createtime	2018/03/01
 * @author		飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Table_adminlog extends Table {

	protected $table_name       = 'adminlog';//表名
	protected $table_id         = 'adminlog_id';//指定ID字段名称，必须
	protected $table_status     = '';//指定状态字段名称，如果有
	protected $table_order      = '';//指定排序字段名称，如果有

	//数据库结构
	protected function struct(){
		$attr = array();
		
		$attr['id']      = 'adminlog_id';
		$attr['adminid'] = 'adminlog_admin';//操作管理员ID
		$attr['time']    = 'adminlog_time';//日志时间
		$attr['content'] = 'adminlog_log';//日志内容
		$attr['ip']      = 'adminlog_ip';//操作者IP

		return $attr;
	}

	//增
	//@param $attr array -- 键值同struct()返回的数组
	public function add($attr){

        $time  = time();
		$ip    = Env::getIP();
        
		$param = array (
			'adminlog_admin'    => array('number', $attr['adminid']),
			'adminlog_ip'       => array('string', $ip),
			'adminlog_time'     => array('number', $time),
			'adminlog_log'      => array('string', $attr['content'])
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
			$sql = "select * from ". $this->table_fullname ." $where order by adminlog_id desc";
			
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