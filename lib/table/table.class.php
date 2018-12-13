<?php

/**
 * 数据库表抽象类
 *
 * 定义必须实现的抽象方法
 * 定义可以继承的方法，提高table层的书写效率
 *
 * @createtime	2018/03/01
 * @author      飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

abstract class Table {

	protected $table_name       = '';//表名，不带前缀，前缀在config中定义
	protected $table_id         = '';//指定ID字段名称，必须
	protected $table_status     = '';//指定状态字段名称，如果有
	protected $table_order      = '';//指定排序字段名称，如果有
	protected $pdo;
	protected $table_fullname;
	protected $table_struct;

	function __construct(){

		//初始数据库连接PDO
		global $mypdo;
		$this->pdo = $mypdo;
		$table_prefix = $this->pdo->prefix;
		
		//数据库全名
		$this->table_fullname = $table_prefix . $this->table_name;

		//数据库结构
		$this->table_struct = $this->struct();
		
	}
	
	////////////////////////////////////////////////////
	/**************抽象方法，子类必须实现**************/
	////////////////////////////////////////////////////
	//数据库结构
	//完整展示数据库所有字段及其含义
	//映射字段名称提供给其他层使用，不对外暴露数据库字段
	abstract protected function struct();

	//增
	//@param $attr array -- 键值同struct()返回的数组
	abstract public function add($attr);

	//获取列表（分页）
	//$count、$page和$pagesize都为0时，返回全部结果（适用于无需分页的情况）
	//
	//@param $filter array -- 过滤条件，格式见Table::filterToWhere
	//@param $count -- 0：返回列表 1：返回结果数量
	//@param $page -- 当前第几页
	//@param $pagesize -- 每页数量
	abstract public function getList($filter = array(), $count = 0, $page = 0, $pagesize = 0);


	///////////////////////////////////////////////////////////////
	/**************非抽象方法，用于继承，也可以重载***************/
	///////////////////////////////////////////////////////////////
	//获取详情
	public function getInfoById($id){

		//查询语句必须用sql_check_input检查参数
		$id = $this->pdo->sql_check_input(array('number', $id));
        
        $sql = "select * from ". $this->table_fullname ." where ". $this->table_id ." = $id limit 1";
        
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

	//删
	public function del($id){
		$where = array(
			$this->table_id => array('number', $id)
		);
        
        return $this->pdo->sqldelete($this->table_fullname, $where);
	}
	
	//--------------------------------------------------
	//------------未实现的常见方法，便于重载------------
	//--------------------------------------------------

	//改
	//@param $attr 数组，键值参考add()
	public function edit($id, $attr){}


	//--------------------------------------------------
	//------------依赖于字段设置的常见方法，可以重载----
	//--------------------------------------------------
	//单独修改状态
	public function updateStatus($id, $status){
        
        $where = array(
			$this->table_id      => array('number', $id)
		);
		$param = array(
			$this->table_status  => array('number', $status)
		);
        return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
	
	}
	
	//单独修改排序
	public function updateOrder($id, $order){
        
        $where = array(
			$this->table_id      => array('number', $id)
		);
		$param = array(
			$this->table_order   => array('number', $order)
		);
        return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
	}
	
	//--------------------------------------------------
	//------------其他方法------------------------------
	//--------------------------------------------------
	
	//从数据库取出的数据转化键值后输出
	protected function dataToAttr($data){
		$r = array();
		foreach($this->table_struct as $k => $v){
			$r[$k] = $data[$v];
		}
		return $r;
	}

	//把filter转化为where子句
	//仅支持最常见的=和like，不支持其他符号
	//@param $filter array 键值要符合struct()中的定义
	//参数示例：
	/**
	$filter = array(
		'account' => array('abc', '=s'),
		'title'   => array('xyz', '%s'),
		'type'    => array(1, '=n')
	)
	**/
	protected function filterToWhere($filter){
		
		$struct = $this->table_struct;
		$where = ' where 1=1 ';

		foreach($filter as $k => $v){
			if(is_array($v)){
				$val = $v[0];
				$operator = $v[1];
			}else{
				$val = $v;
				$operator = '=s';
			}
			$field_name = $struct[$k];

			if($operator == '=n'){//数字
				$val = $this->pdo->sql_check_input(array('number', $val));
				$where .= " and $field_name = $val ";
			}

			if($operator == '=s'){//字符串，精确搜索
				$val = $this->pdo->sql_check_input(array('string', $val));
				$where .= " and $field_name = $val ";
			}

			if($operator == '%s'){//字符串，模糊搜索
				$val = '%'.$val.'%';
				$val = $this->pdo->sql_check_input(array('string', $val));
				$where .= " and $field_name like $val " ;
			}

		}
		return $where;
	}
}
?>