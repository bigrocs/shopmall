<?php
## 传递参数列表
## orderId【订单id】,productId【商品id】,comboId【组合id】,type【评价类型】,message【评价内容】,uid【用户】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【商品id为空】,3【组合id为空】,5【评价内容为空】,6【用户为空】
## -1【订单中没有该商品】,-2【评论失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['orderId'])){
    $orderid = $_POST['orderId'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productId'])){
    $pid = $_POST['productId'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
$cpid = $_POST['comboId'];
if(!empty($_POST['type'])){
    $type = $_POST['type'];
}else{
    $type=1;
}
if(!empty($_POST['asdiscribe'])){//描述相符
    $asdiscribe = $_POST['asdiscribe'];
}else{
    $asdiscribe = 5;
}
if(!empty($_POST['attitude'])){//服务态度
    $attitude = $_POST['attitude'];
}else{
    $attitude = 5;
}
if(!empty($_POST['sendspeed'])){//发货速度
    $sendspeed = $_POST['sendspeed'];
}else{
    $sendspeed = 5;
}
if(!empty($_POST['lospeed'])){//物流速度
    $lospeed = $_POST['lospeed'];
}else{
    $lospeed = 5;
}
if(!empty($_POST['message'])){
    $message = $_POST['message'];
}else{
    $re['result'] = 5;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 6;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_product_order_pro where order_id=".$orderid." and pid=".$pid." and setmeal=".$cpid;
$db->query($sql);
$pre=$db->fetchRow();
if(empty($pre)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}else{
    $pname = $pre['name'];
    $price = $pre['price'];
}
$uptime=time();
$sql="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql);
$user=$db->fetchRow();
$uid=$user['userid'];

$sql="insert into ".PCOMMENT." (user,userid,fromid,pid,puid,pname,price,goodbad,con,uptime) value('$uname','$uid','$uid','$pid','".$by['seller_id']."','$pname','$price','$type','$message','$uptime')";
$res = $db->query($sql);
if($res){
    $selete="select * from ".ORDER." where order_id=".$orderid." and userid=".$uid;
    $db->query($selete);
    $by=$db->fetchRow();
    $sql="insert into ".UCOMMENT." (user,userid,byid,item1,item2,item3,item4,uptime) value('$uname','$uid','".$by['seller_id']."','$asdiscribe','$attitude','$sendspeed','$lospeed','$uptime')";
    $res = $db->query($sql);
    $re['result'] = 0;
    $re['orderId'] = $orderid;
    $re['productId'] = $pid;
    $re['comboId'] = $cpid;
    echo json_encode($re);
}else{
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
?>
