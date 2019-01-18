<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 14:09
 */
header("Access-Control-Allow-Origin: *");
require('../../init.php');


$attr['uid'] = $uid = $_POST['uid'];
$attr['friendId'] = $friendId = $_POST['friendId'];//
$attr['source'] = $source      = $_POST['source'];
$attr['vcode'] = $vcode     = $_POST['vcode'];

$attr1['request'] = json_encode_cn($attr);
$attr1['api'] = 'delFriend.php';
$attr1['ip'] = $_SERVER['REMOTE_ADDR'];;
$apiLogId = Apilog::add($attr1);
$attr1=null;
$attr=array();
$api = New API($apiLogId);

$uid  = safeCheck($uid);//
$friendId  = safeCheck($friendId);//
$source      = safeCheck($source);
$vcode     = safeCheck($vcode, 0);

if($uid == '')
    $api->ApiError('001', 'uid不能为空');
if($friendId == '')
    $api->ApiError('002', 'friendId不能为空');
if($source == '')  $api->ApiError('003', '来源不能为空');
$api ->source = $source;

$vcode_raw = md5(md5($uid).md5($friendId).md5($source));

if($vcode_raw!=$vcode){
    $api->ApiError('004', '校验码不正确');
}

try{
    $user = User::getInfoById($uid);
    if (!$user){
        $api->ApiError('005', '该用户不存在');
    }
    if ($uid == $friendId){
        $api->ApiError('006', '不能删除自己');
    }
    $attr['userId'] = $uid;
    $attr['toUserId'] = $friendId;
    $friends = Friend::getListByPage($attr);
    if (sizeof($friends)> 0){
        foreach ($friends as $friend){
            Friend::del($friend['id']);
        }
    }else{
        $api->ApiError('007', '你们不是好友关系');
    }

    $toUser = User::getInfoById($friendId);
    if (!$toUser){
        $api->ApiError('007', 'friendId不存在');
    }

    $response = json_encode_cn(array('status' => '0', 'message' => '删除成功'));
    echo $response;
    $attr['response']= $response;
    Apilog::edit($apiLogId, $attr);
    exit();
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->jsonMsg());
    echo $e->jsonMsg();
}

?>