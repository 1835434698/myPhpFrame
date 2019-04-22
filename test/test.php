<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17
 * Time: 16:41
 */
require_once('../init.php');
//$attr['openid'] = 're8w9reueirfjsdfjlkzjxf';
//$attr['email'] = '1835434691@qq.com';
//$attr['name'] = 'zhang1';
//$attr['sex'] = 2;
//$attr['type'] = 1;
//$result = User::getInfoByOpenId( $attr['openid']);
//echo $result;
//$result = User::getListByPage();
//$result = User::del(3);
$attr['userId'] = 2;
$attr['toUserId'] = 9;
$result = Friend::getListByPage($attr);
echo sizeof($result);
//print_r($result);

//$attr['userId'] = 2;
//$attr['toUserId'] = 3;
//$result = Friend::edit(2, $attr);
//echo $result;


//$result = Friend::getListByPage();
//$result = Friend::del(3);
//print_r($result);




?>