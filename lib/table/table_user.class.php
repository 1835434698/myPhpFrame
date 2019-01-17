<?php
/**
 * 数据库表:用户表
 * User: Administrator
 * Date: 2019/1/17
 * Time: 15:47
 */

class Table_user extends Table {

    protected $table_name       = 'user';//表名
    protected $table_id         = 'user_id';//指定ID字段名称，必须
    protected $table_status     = '';//指定状态字段名称，如果有
    protected $table_order      = '';//指定排序字段名称，如果有

    //数据库结构
    //完整展示数据库所有字段，替换字段名称
    protected function struct(){
        $attr = array();

        $attr['id']      = 'user_id';//用户id
        $attr['mobile'] = 'user_mobile';//手机号
        $attr['name']    = 'user_name';//用户名
        $attr['email'] = 'user_email';//邮箱
        $attr['sex'] = 'user_sex';//性别0未知，1男，2女
        $attr['type'] = 'user_type';//0未知，1android，2ios，3web
        $attr['province'] = 'user_province';//省
        $attr['city'] = 'user_city';//市
        $attr['area'] = 'user_area';//区
        $attr['longitude'] = 'user_longitude';//经度
        $attr['latitude'] = 'user_latitude';//维度
        $attr['attribute'] = 'user_attribute';//1、公开坐标，2、保密坐标
        $attr['openid'] = 'user_openid';//三方帐号
        $attr['time'] = 'user_time';//添加时间
        return $attr;
    }

    //增
    //@param $attr array -- 键值同struct()返回的数组
    public function add($attr){
        if (!empty($attr['mobile'])){
            $param['user_mobile'] = array('number', $attr['mobile']);
        }
        if (!empty($attr['name'])){
            $param['user_name'] = array('string', $attr['name']);
        }
        if (!empty($attr['sex'])){
            $param['user_sex'] = array('number', $attr['sex']);
        }
        if (!empty($attr['email'])){
            $param['user_email'] = array('string', $attr['email']);
        }
        if (!empty($attr['type'])){
            $param['user_type'] = array('number', $attr['type']);
        }
        if (!empty($attr['province'])){
            $param['user_province'] = array('string', $attr['province']);
        }
        if (!empty($attr['city'])){
            $param['user_city'] = array('string', $attr['city']);
        }
        if (!empty($attr['area'])){
            $param['user_area'] = array('string', $attr['area']);
        }
        if (!empty($attr['longitude'])){
            $param['user_longitude'] = array('string', $attr['longitude']);
        }
        if (!empty($attr['latitude'])){
            $param['user_latitude'] = array('string', $attr['latitude']);
        }
        if (!empty($attr['openid'])){
            $param['user_openid'] = array('string', $attr['openid']);
        }
        if (!empty($attr['time'])){
            $param['user_time'] = array('number', $attr['time']);
        }
        return $this->pdo->sqlinsert($this->table_fullname, $param);

    }

    /**
     * 根据id获取详情
     *
     * @param $id 用户id
     *
     */
    public function getInfoById($id){

        $id = $this->pdo->sql_check_input(array('number', $id));

        $sql = "select * from ". $this->table_fullname ." where user_id = $id limit 1";

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
     * 根据手机号获取详情
     *
     * @param $mobile 用户手机
     *
     */
    public function getInfoByMobile($mobile){

        $mobile = $this->pdo->sql_check_input(array('number', $mobile));

        $sql = "select * from ". $this->table_fullname ." where user_mobile = $mobile limit 1";

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
     * 根据Email获取详情
     *
     * @param $Email Email
     *
     */
    public function getInfoByEmail($email){

        $email = $this->pdo->sql_check_input(array('string', $email));

        $sql = "select * from ". $this->table_fullname ." where user_email = $email limit 1";

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

            $sql = "select * from ". $this->table_fullname ." $where  order by user_time desc";

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
     * 修改用户信息
     *
     * @param $id         用户ID
     * @param $attr       用户属性数组，数组键值参考add()函数
     *
     */
    public function edit($id, $attr){
        if (!empty($attr['mobile'])){
            $param['user_mobile'] = array('number', $attr['mobile']);
        }
        if (!empty($attr['name'])){
            $param['user_name'] = array('string', $attr['name']);
        }
        if (!empty($attr['sex'])){
            $param['user_sex'] = array('number', $attr['sex']);
        }
        if (!empty($attr['email'])){
            $param['user_email'] = array('string', $attr['email']);
        }
        if (!empty($attr['type'])){
            $param['user_type'] = array('number', $attr['type']);
        }
        if (!empty($attr['province'])){
            $param['user_province'] = array('string', $attr['province']);
        }
        if (!empty($attr['city'])){
            $param['user_city'] = array('string', $attr['city']);
        }
        if (!empty($attr['area'])){
            $param['user_area'] = array('string', $attr['area']);
        }
        if (!empty($attr['longitude'])){
            $param['user_longitude'] = array('string', $attr['longitude']);
        }
        if (!empty($attr['latitude'])){
            $param['user_latitude'] = array('string', $attr['latitude']);
        }
        if (!empty($attr['openid'])){
            $param['user_openid'] = array('string', $attr['openid']);
        }
        if (!empty($attr['time'])){
            $param['user_time'] = array('number', $attr['time']);
        }

        $where = array(
            'user_id'		  => array('number', $id)
        );

        return $this->pdo->sqlupdate($this->table_fullname, $param, $where);
    }


    /**
     * Table_user::del() 删除用户
     *
     * @param Integer $userId   用户ID
     *
     * @return
     */
    public function del($userId){
        $where = array(
            "user_id" => array('number', $userId)
        );
        return $this->pdo->sqldelete($this->table_fullname, $where);
    }

}
?>