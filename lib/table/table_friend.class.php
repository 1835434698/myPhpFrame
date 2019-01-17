<?php
/**
 * 数据库表:朋友关系表
 * User: Administrator
 * Date: 2019/1/17
 * Time: 15:47
 */

class Table_friend extends Table {

    protected $table_name       = 'friend';//表名
    protected $table_id         = 'friend_id';//指定ID字段名称，必须
    protected $table_status     = '';//指定状态字段名称，如果有
    protected $table_order      = '';//指定排序字段名称，如果有

    //数据库结构
    //完整展示数据库所有字段，替换字段名称
    protected function struct(){
        $attr = array();

        $attr['id']      = 'friend_id';//朋友关系id
        $attr['userid'] = 'friend_userid';//用户id
        $attr['touserid']    = 'friend_touserid';//用户id
        $attr['time'] = 'friend_time';//添加时间
        return $attr;
    }

    //增
    //@param $attr array -- 键值同struct()返回的数组
    public function add($attr){
        if (!empty($attr['userId'])){
            $param['friend_userid'] = array('number', $attr['userId']);
        }
        if (!empty($attr['toUserId'])){
            $param['friend_touserid'] = array('number', $attr['toUserId']);
        }
        if (!empty($attr['time'])){
            $param['friend_time'] = array('number', $attr['time']);
        }
        return $this->pdo->sqlinsert($this->table_fullname, $param);

    }

    /**
     * 根据id获取详情
     *
     * @param $id 朋友关系id
     *
     */
    public function getInfoById($id){

        $id = $this->pdo->sql_check_input(array('number', $id));

        $sql = "select * from ". $this->table_fullname ." where friend_id = $id limit 1";

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

            $sql = "select * from ". $this->table_fullname ." $where  order by friend_time desc";

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
     * 修改朋友关系信息
     *
     * @param $id         朋友关系ID
     * @param $attr       朋友关系属性数组，数组键值参考add()函数
     *
     */
    public function edit($id, $attr){
        if (!empty($attr['userId'])){
            $param['friend_userid'] = array('number', $attr['userId']);
        }
        if (!empty($attr['toUserId'])){
            $param['friend_touserid'] = array('number', $attr['toUserId']);
        }
        if (!empty($attr['time'])){
            $param['friend_time'] = array('number', $attr['time']);
        }

        $where = array(
            'friend_id'		  => array('number', $id)
        );

        return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
    }


    /**
     * Table_friend::del() 删除朋友关系
     *
     * @param Integer $friendId   朋友关系ID
     *
     * @return
     */
    public function del($friendId){
        $where = array(
            "friend_id" => array('number', $friendId)
        );
        return $this->pdo->sqldelete($this->table_fullname, $where);
    }

}
?>