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

    //APILogId
    public $apiId;

    public function __construct($apiId) {
        $this->apiId = $apiId;
    }

    //发生错误
    public function ApiError($errcode, $msg){
        $err = array();
        $err['status'] = $errcode;
        $err['msg'] = $msg;
        $err_json = json_encode_cn($err);
        $attr['response']= $err_json;
        Apilog::edit($this->apiId, $attr);

        echo $err_json;
        exit();//发生错误直接退出
    }


}

?>