<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 14:09
 */
header("Access-Control-Allow-Origin: *");
require('../../init.php');


$attr['name'] = $name = $_POST['name'];
$attr['mobile'] = $mobile  = $_POST['mobile'];//
$attr['email'] = $email  = $_POST['email'];
$attr['passWord'] = $passWord  = $_POST['passWord'];

$attr['openid'] = $openid  = $_POST['openid'];

$attr['source'] = $source      = $_POST['source'];
$attr['vcode'] = $vcode     = $_POST['vcode'];

$attr1['request'] = json_encode_cn($attr);
$attr1['api'] = 'login.php';
$attr1['ip'] = $_SERVER['REMOTE_ADDR'];;
$apiLogId = Apilog::add($attr1);
$attr1=null;
$attr=array();
$api = New API($apiLogId);

$source      = safeCheck($source);
$vcode     = safeCheck($vcode, 0);

if($source == '')
    $api->ApiError('001', '来源不能为空');
$api ->source = $source;

$vcode_raw = md5(md5($source).$source.md5($source));

if($vcode_raw!=$vcode){
    $api->ApiError('002', '校验码不正确');
}

/**
 * @param $user
 * @param $data
 * @return mixed
 */
function getLoginData($user, $ARRAY_user_sex)
{
    $data = array();
    $data['id'] = $user['id'];
    $data['mobile'] = $user['mobile'];
    $data['name'] = $user['name'];
    $data['email'] = $user['email'];//邮箱
    if (!$user['sex']){
        $user['sex']=0;
    }
    $data['sex'] = $ARRAY_user_sex[$user['sex']];//性别0未知，1男，2女
    $data['province'] = $user['province'];//省
    $data['city'] = $user['city'];//市
    $data['area'] = $user['area'];//区
    if ($user['attribute'] == 1){
        $data['longitude'] = $user['longitude'];//经度
        $data['latitude'] = $user['latitude'];//维度
    }
    $data['openid'] = $user['openid'];//三方帐号
    return $data;
}

try{
    if (!empty($openid)){
        $openid  = safeCheck($openid, 0);//
        $user = User::getInfoByOpenId($openid);
        if (!$user){
            $api->ApiError('003', '该账号没有注册');
        }else{
            $data = getLoginData($user, $ARRAY_user_sex);

            $response = json_encode_cn(array('status' => '0', 'message' => '登录成功', 'data' => $data));
            echo $response;
            $attr['response']= $response;
            Apilog::edit($apiLogId, $attr);
            exit();
        }
    }else{
        if(empty($passWord))
            $api->ApiError('004', 'passWord不能为空');
        $passWord  = safeCheck($passWord, 0);//
        $attr['passWord'] = $passWord;
        if (!empty($email)) {
            $email  = safeCheck($email, 0);//
            $attr['email'] = $email;
        }else if (!empty($mobile)) {
            $mobile  = safeCheck($mobile);//
            $attr['mobile'] = $mobile;
        }else if (!empty($name)) {
            $name  = safeCheck($name, 0);//
            $attr['name'] = $name;
        }
        $user = User::getLogin($attr);
        if ($user){
            $data = getLoginData($user, $ARRAY_user_sex);
            $response = json_encode_cn(array('status' => '0', 'message' => '登录成功', 'data' => $data));
            echo $response;
            $attr['response']= $response;
            Apilog::edit($apiLogId, $attr);
            exit();
        }else{
            $api->ApiError('005', '帐号或者密码错误');
        }
    }
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->jsonMsg());
    echo $e->jsonMsg();
}







//if($email == '')
//    $api->ApiError('001', 'email不能为空');
//$user = User::getInfoByEmail($email);
//if ($user){
//    $api->ApiError('004', '该邮箱已经注册');
//}
//
//$attr['name'] = $name;
//$attr['mobile'] = $mobile;
//$attr['email'] = $email;
//
//try{
//    $rs = User::add($attr);
//    if ($rs > 0){
//        $response = json_encode_cn(array('status' => '0', 'message' => '注册成功'));
//        echo $response;
//        $attr['response']= $response;
//        Apilog::edit($apiLogId, $attr);
////    echo json_encode_cn(array('status' => 'ok', 'data' => $data));
////    $api->apicount();
//    }else{
//        $api->ApiError('005', '注册失败');
//    }
//}catch (MyException $e){
//    $api->ApiError($e->getCode(), $e->jsonMsg());
//    echo $e->jsonMsg();
//}

?>