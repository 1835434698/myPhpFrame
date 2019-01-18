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
$attr['page'] = $page  = $_POST['page'];//
$attr['pagesize'] = $pagesize = $_POST['pagesize'];//
$attr['source'] = $source      = $_POST['source'];
$attr['vcode'] = $vcode     = $_POST['vcode'];

$attr1['request'] = json_encode_cn($attr);
$attr1['api'] = 'getFriends.php';
$attr1['ip'] = $_SERVER['REMOTE_ADDR'];;
$apiLogId = Apilog::add($attr1);
$attr1=null;
$attr=array();
$api = New API($apiLogId);

$uid  = safeCheck($uid);//
$page  = safeCheck($page);//
$pagesize  = safeCheck($pagesize);//
$source      = safeCheck($source);
$vcode     = safeCheck($vcode, 0);

if($uid == '')
    $api->ApiError('001', 'uid不能为空');
if($page == '')
    $api->ApiError('002', 'page不能为空');
if($pagesize == '')
    $api->ApiError('003', 'pagesize不能为空');
if($source == '')  $api->ApiError('004', '来源不能为空');
$api ->source = $source;

$vcode_raw = md5(md5($pagesize).md5($page).md5($source));

if($vcode_raw!=$vcode){
    $api->ApiError('005', '校验码不正确');
}

try{
    $user = User::getInfoById($uid);
    if (!$user){
        $api->ApiError('006', '该用户不存在');
    }

    $attr['userId'] = $uid;
    $friendCount = Friend::getListByPage($attr, 1, $page, $pagesize);
    $friends = Friend::getListByPage($attr, 0, $page, $pagesize);
    $data=array();

    for ($i = 0; $i < $friendCount; $i++){
        $friend = $friends[$i];
        $userI = User::getInfoById($friend['touserid']);
        if (!$userI){
            Friend::del($friend['id']);
        }

        $data['friends'][$i]['uid'] = $userI['id'];
        $data['friends'][$i]['mobile'] = $userI['mobile'];
        $data['friends'][$i]['name'] = $userI['name'];
        $data['friends'][$i]['email'] = $userI['email'];
        if (!$userI['sex']){
            $userI['sex']=0;
        }
        $data['friends'][$i]['sex'] = $ARRAY_user_sex[$userI['sex']];
        $data['friends'][$i]['province'] = $userI['province'];
        $data['friends'][$i]['city'] = $userI['city'];
        $data['friends'][$i]['area'] = $userI['area'];

        if ($user['attribute'] == 1){
            $data['friends'][$i]['longitude'] = $userI['longitude'];
            $data['friends'][$i]['latitude'] = $userI['latitude'];
        }

    }

    $response = json_encode_cn(array('status' => '0', 'message' => '查询成功', 'data' => $data));
    echo $response;
    $attr['response']= $response;
    Apilog::edit($apiLogId, $attr);
    exit();

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->jsonMsg());
    echo $e->jsonMsg();
}

?>