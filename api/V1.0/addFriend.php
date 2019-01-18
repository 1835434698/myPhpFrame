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
$attr['toUid'] = $toUid  = $_POST['toUid'];//
$attr['source'] = $source      = $_POST['source'];
$attr['vcode'] = $vcode     = $_POST['vcode'];

$attr1['request'] = json_encode_cn($attr);
$attr1['api'] = 'addFriend.php';
$attr1['ip'] = $_SERVER['REMOTE_ADDR'];;
$apiLogId = Apilog::add($attr1);
$attr1=null;
$attr=array();
$api = New API($apiLogId);

$uid  = safeCheck($uid);//
$toUid  = safeCheck($toUid);//
$source      = safeCheck($source);
$vcode     = safeCheck($vcode, 0);

if($uid == '')
    $api->ApiError('001', 'uid不能为空');
if($toUid == '')
    $api->ApiError('002', 'toUid不能为空');
if($source == '')  $api->ApiError('003', '来源不能为空');
$api ->source = $source;

$vcode_raw = md5(md5($toUid).md5($toUid).md5($source));

if($vcode_raw!=$vcode){
    $api->ApiError('004', '校验码不正确');
}
//$user = User::getInfoByEmail($email);
//if ($user){
//    $api->ApiError('005', '该邮箱已经注册');
//}
//
//$attr['name'] = $name;
//$attr['mobile'] = $mobile;
//$attr['email'] = $email;
//$attr['passWord'] = $passWord;
//$attr['sex'] = $sex;
//$attr['sex'] = $sex;
//$attr['province'] = $province;
//$attr['city'] = $city;
//$attr['area'] = $area;
//$attr['longitude'] = $longitude;
//$attr['latitude'] = $latitude;
//$attr['attribute'] = $attribute;
//$attr['openid'] = $openid;

try{
    $user = User::getInfoById($uid);
    if (!$user){
        $api->ApiError('005', '该用户不存在');
    }
    if ($uid == $toUid){
        $api->ApiError('006', '不能添加自己');
    }
    $toUser = User::getInfoById($toUid);
    if (!$toUser){
        $api->ApiError('007', '被添加用户不存在');
    }
    $attr['userId'] = $uid;
    $attr['toUserId'] = $toUid;
    $friendCount = Friend::getListByPage($attr, 1);
    if ($friendCount > 0){
        $api->ApiError('008', '你们已经是朋友了');
    }else{
        $friend = Friend::add($attr);
        if ($friend > 0){
            $response = json_encode_cn(array('status' => '0', 'message' => '添加成功'));
            echo $response;
            $attr['response']= $response;
            Apilog::edit($apiLogId, $attr);
        }else{
            $api->ApiError('009', '添加失败');
        }
    }
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->jsonMsg());
    echo $e->jsonMsg();
}

?>