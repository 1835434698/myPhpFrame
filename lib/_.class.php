<?php

/**
 * 样本类 【写新类时可以将本文件另存为，请注意删除不需要的注释和函数】
 *
 * @createtime	2018/12/13
 * @author      飘@&@
 * @copyright	自由开发（http://www.baidu.com）
 */

class Demo {
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		
	}
	
	//获取列表
	static public function getList(){
		$Table_xxx = new Table_xxx();
		return $Table_xxx->getList();
	}

	//获取统计
	static public function getCount(){
		$Table_xxx = new Table_xxx();
		return $Table_xxx->getList(array(), 1);
	}

	//获取单条记录详情
	static public function getInfoById($id){
		if(!ParamCheck::is_ID($id)) throw new MyException('ID不合法', 101);
		
		$Table_xxx = new Table_xxx();
		return $Table_xxx->getInfoById($id);
	}

	//增
	static public function add(){
		//【此处要检查参数有效性，如果校验规则或参数较多，可建一个私有函数和edit()共用】

		//【如有必要，此处检查新增的逻辑，比如：账号不能重复】

		$attr = array(

		);
		$Table_xxx = new Table_xxx();
        $id = $Table_xxx->add($attr);
		if($id){
            $msg = '添加yyy('.$id.':'.$name.')成功!';
            Adminlog::add($msg);

			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 103); 
		}
	}

	//删
	static public function del($id){
		if(!ParamCheck::is_ID($id)) throw new MyException('ID不合法', 101);

		//【如有必要，此处检查删除的前提条件，比如有相关连的对象】

		$Table_xxx = new Table_xxx();
        $rs = $Table_xxx->del($id);
		if($rs == 1){
			$msg = '删除yyy('.$id.')成功!';
			Adminlog::add($msg);
			
			return action_msg($msg, 1);
		}else{
			throw new MyException('操作失败', 103);
		}
	}

	//改
	static public function edit($id, ){
		if(!ParamCheck::is_ID($id)) throw new MyException('ID不合法', 101);
		//检查其他参数有效性

		//检查$id数据是否存在
		$info = self::getInfoById($id);
		if(empty($info)) throw new MyException('数据已删除或不存在', 104);
		
		//【如有必要，此处检查修改的逻辑，比如：不能重复的数据】

		$attr = array(

		);
		$Table_xxx = new Table_xxx();
        $rs = $Table_xxx->edit($id, $attr);
        if($rs >= 0){//未做修改也提示修改成功
            $msg = '修改yyy('.$id.')成功';
            Adminlog::add($msg);
			
			return action_msg($msg, 1);
        }else{
			throw new MyException('操作失败', 103);
        }
	}

}
?>