<?php
## 传递参数列表
## uid【用户】,addressId【地址id】

## 返回状态参数列表
## 0【成功】,1【用户为空】,2【地址id为空】
## -1【收货地址不存在】，-2【删除地址失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$sql0="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql0);
$mem = $db->fetchRow();
$uid = $mem['userid'];
$where .= " and userid = ".$uid ;
if(!empty($_POST['addressId'])){
    $addressId = $_POST['addressId'];
    $where .= " and id = ".$addressId ;
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
$fsql="select * from mallbuilder_delivery_address where 1".$where;
$db->query($fsql);
$fre = $db->fetchRow();
if(empty($fre)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$sql="delete from mallbuilder_delivery_address where 1".$where;
$dre = $db->query($sql);
if(!$dre){
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
$re['result'] = 0;
echo json_encode($re);
?>
