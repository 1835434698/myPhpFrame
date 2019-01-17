<?php

/**
 * api.class.php 接口类
 *
 * @version       v0.01
 * @create time   2016/10/1
 * @update time
 * @author        ddk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class API {

    //API编号，数字
    public $code;
//
//    //API来源
//    public $source = 0;
//
//    //API 某一天，时间戳
//    public $day;
//
//    //发生错误
//    public $error = false;

    public function __construct($code) {
        $this->code = $code;
//        $this->day = strtotime(date('Y-m-d'));
    }

    //发生错误
    public function ApiError($errcode, $msg, $apiLogId){
//        $this->error = true;
//
//        //统计
//        $this->apicount();

        $err = array();
        $err['status'] = $errcode;
        $err['msg'] = $msg;
        $err_json = json_encode_cn($err);

        if (!empty($apiLogId)){
            $attr['response']= $err_json;
            Apilog::edit($apiLogId, $attr);
        }
        echo $err_json;
        exit();//发生错误直接退出
    }

//    //API调用统计
//    public function apicount(){
//
//        if($this->is_newday()){
//            $this->count_newday();
//        }else{
//            $this->count_update();
//        }
//
//    }
//
//    //写入统计数据
//    private function count_newday(){
//        global $mypdo;
//
//        if($this->error)
//            $errcount = 1;
//        else
//            $errcount = 0;
//
//        //写入数据库
//        $param = array (
//            'apiactive_code'      => array('number', $this->code),
//            'apiactive_source'    => array('number', $this->source),
//            'apiactive_day'       => array('number', $this->day),
//            'apiactive_count'     => array('number', 1),
//            'apiactive_errcount'  => array('number', $errcount)
//        );
//
//        $mypdo->sqlinsert($mypdo->prefix.'apiactive', $param);
//    }

//    //更新统计数据
//    private function count_update(){
//        global $mypdo;
//
//        if($this->error)
//            $errcount = 1;
//        else
//            $errcount = 0;
//
//        //更新数据
//        $param = array (
//            'apiactive_count'      => array('expression', 'apiactive_count+1'),
//            'apiactive_errcount'   => array('expression', 'apiactive_errcount+'.$errcount)
//        );
//        $where = array (
//            'apiactive_code'      => array('number', $this->code),
//            'apiactive_source'    => array('number', $this->source),
//            'apiactive_day'       => array('number', $this->day)
//        );
//
//        $mypdo->sqlupdate($mypdo->prefix.'apiactive', $param, $where);
//    }

//    //判断是否是新的一天
//    private function is_newday(){
//        global $mypdo;
//        $date = $this->day;
//        $code = $this->code;
//        $source = $this->source;
//
//        $sql = "select apiactive_id from ".$mypdo->prefix."apiactive where apiactive_code=$code and apiactive_source=$source and apiactive_day=$date limit 1";
//
//        $q   = $mypdo->sqlQuery($sql);
//
//        if($q){
//            return false;
//        }else{
//            return true;
//        }
//
//    }
//
//    //API 设置来源
//    public function setsource($source){
//        $this->source = $source;
//    }


}

?>