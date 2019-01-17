<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 14:09
 */
header("Access-Control-Allow-Origin: *");
require('../../init.php');
$api = New API(1);


$attr['name'] = $name = $_POST['name'];
$attr['mobile'] = $mobile  = $_POST['mobile'];//
$attr['email'] = $email  = $_POST['email'];
$attr['sex'] = $sex  = $_POST['sex'];
$attr['province'] = $province  = $_POST['province'];
$attr['city'] = $city  = $_POST['city'];
$attr['area'] = $area  = $_POST['area'];
$attr['longitude'] = $longitude  = $_POST['longitude'];
$attr['latitude'] = $latitude  = $_POST['latitude'];
$attr['attribute'] = $attribute  = $_POST['attribute'];
$attr['openid'] = $openid  = $_POST['openid'];
$attr['source'] = $source      = $_POST['source'];
$attr['vcode'] = $vcode     = $_POST['vcode'];

$attr1['request'] = json_encode_cn($attr);
$attr1['api'] = 'register.php';
$attr1['ip'] = $_SERVER['REMOTE_ADDR'];;
$apiLogId = Apilog::add($attr1);
$attr1=null;
$attr=array();

$name  = safeCheck($name, 0);//登录名
$mobile  = safeCheck($mobile);//
$email  = safeCheck($email, 0);//
$sex  = safeCheck($sex);//
$province  = safeCheck($province, 0);//
$city  = safeCheck($city, 0);//
$area  = safeCheck($area, 0);//
$longitude  = safeCheck($longitude, 0);//
$latitude  = safeCheck($latitude, 0);//
$attribute  = safeCheck($attribute);//
$openid  = safeCheck($openid, 0);//

$source      = safeCheck($source);
$vcode     = safeCheck($vcode, 0);


if($email == '')
    $api->ApiError('001', 'email不能为空', $apiLogId);
if($source == '')  $api->ApiError('002', '来源不能为空', $apiLogId);
$api ->source = $source;

$vcode_raw = md5(md5($email).md5($email).md5($source));

if($vcode_raw!=$vcode){
    $api->ApiError('003', '校验码不正确', $apiLogId);
}
$user = User::getInfoByEmail($email);
if ($user){
    $api->ApiError('004', '该邮箱已经注册', $apiLogId);
}

$attr['name'] = $name;
$attr['mobile'] = $mobile;
$attr['email'] = $email;
$attr['sex'] = $sex;
$attr['sex'] = $sex;
$attr['province'] = $province;
$attr['city'] = $city;
$attr['area'] = $area;
$attr['longitude'] = $longitude;
$attr['latitude'] = $latitude;
$attr['attribute'] = $attribute;
$attr['openid'] = $openid;

try{
    $rs = User::add($attr);
    if ($rs > 0){
        $response = json_encode_cn(array('status' => '0', 'message' => '注册成功'));
        echo $response;
        $attr['response']= $response;
        Apilog::edit($apiLogId, $attr);
//    echo json_encode_cn(array('status' => 'ok', 'data' => $data));
//    $api->apicount();
    }else{
        $api->ApiError('005', '注册失败', $apiLogId);
    }
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->jsonMsg(), $apiLogId);
    echo $e->jsonMsg();
}

?>