<?php
## 传递参数列表
## subscribeId【收藏id】,productId【商品id】,shopId【店铺id】,uid【用户】

## 返回状态参数列表
## 0【成功】,1【店铺id为空】,2【商品id为空】,3【收藏id为空】,4【用户为空】
## -1【商品不存在】,-2【信息错误查询失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where1 .= " and member_id = ".$shopid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productId'])){
    $productid = $_POST['productId'];
    $where1.= " and id = ".$productid ;
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
$sql="select * from ".PRODUCT." where 1 ".$where1;
$db->query($sql);
$pro=$db->fetchRow();
if(empty($pro)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$where = " and pid=".$productid;
if(!empty($_POST['subscribeId'])){
    $subscribeid = $_POST['subscribeId'];
    $where.=" and id = ".$subscribeid ;
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uid = $_POST['uid'];
    $where .= " and uname = '".$uid."'";
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
$ssql="select * from mallbuilder_sns_shareproduct where 1 ".$where;
$db->query($ssql);
$subscribe=$db->fetchRow();
if($subscribe){
    $re['result']=0;
    $re['subscribeId']=$subscribeid;//收藏id
    $re['proudctId']=$pro['id'];//商品id
    $re['productName']=$pro['name'];//商品名
    $re['shopId']=$shopid;
    $re['productStatus']=$pro['status'];//商品状态
    $re['price']=$pro['price'];
    $re['createTime']=$subscribe['addtime'];//收藏时间
    $re['picUrl']=$pro['pic']."_60X60.jpg";//图片链接
    echo json_encode($re);
}else{
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
?>
