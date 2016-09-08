<?php
## 传递参数列表
## uid【用户id】,orderId【订单id】,productId【产品id】,msg【留言】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【产品id为空】,3【用户id为空】，4【退款信息为空】
## -1【订单不存在】, -2【订单产品不存在】,-3【产品未申请退款/退货】,
include_once("../includes/global.php");
include_once("$config[webroot]/module/product/includes/plugin_refund_class.php");
$refund=new refund();
if(file_get_contents("php://input"))
    $_POST = json_decode(file_get_contents("php://input"),true);
$where = "where 1";
if(isset($_POST['orderId']) && !empty($_POST['orderId'])){
    $orderid = $_POST['orderId'];
    $where .= " and order_id = ".$orderid;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productId'])){
    $productid = $_POST['productId'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
$sql0="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql0);
$mem = $db->fetchRow();
$uid = $mem['userid'];
if(!empty($_POST['msg'])){
    $msg = $_POST['msg'];
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
$sql = "select * from mallbuilder_product_order ".$where." and buyer_id=".$uid;
$db->query($sql);
$order = $db->fetchRow();
if(empty($order)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$psql0 = "select * from mallbuilder_product_order_pro ".$where." and pid=".$productid;
$db->query($psql0);
$order_pro = $db->fetchRow();
if(empty($order_pro)){
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
if($order_pro['status']!=4){
    $re['result'] = -3;
    echo json_encode($re);
    exit;
}
$pid=$order_pro['id'];    //return表里面存的是商品在订单商品表里的id
$srsql="select * from mallbuilder_return where order_id=".$orderid." and status>0 and product_id=".$pid;
$db->query($srsql);
$return = $db->fetchRow();
$time=time();
if(empty($return)){
    $re['result'] = -3;
    echo json_encode($re);
    exit;
}else{
    $tsql="insert into mallbuilder_talk (refund_id,order_id,member_id,type,content,create_time) values ('".$return['refund_id']."','$orderid','$uid','1','$msg','".$time."')";
    $db->query($tsql);
    $re['result'] = 0;
    $re['orderId'] = $orderid;
    $re['productId'] = $productid;
    echo json_encode($re);
    exit;
}
?>
