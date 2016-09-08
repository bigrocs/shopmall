<?php
## 传递参数列表
## uid【用户id】,orderId【订单id】,productId【产品id】,money【退款金额】,reason【退款理由】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【产品id为空】,3【用户id为空】，4【退款原因为空】,5【退款金额为空】
## -1【订单不存在】, -2【订单状态错误】,-3【订单产品不存在】,
include_once("../includes/global.php");
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
if(!empty($_POST['reason'])){
    $reason = $_POST['reason'];
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['money'])){
    $money = $_POST['money'];
}else{
    $re['result'] = 5;
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
if($order['status']!=4){
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
$psql0 = "select * from mallbuilder_product_order_pro ".$where." and pid=".$productid;
$db->query($psql0);
$order_pro = $db->fetchRow();
if(empty($order_pro)){
    $re['result'] = -3;
    echo json_encode($re);
    exit;
}
$pid=$order_pro['id'];    //return表里面存的是商品在订单商品表里的id
$srsql="select * from mallbuilder_return where order_id=".$orderid." and status>0 and product_id=".$pid;
$db->query($srsql);
$return = $db->fetchRow();
$type = $order['status']==2 ? 1:2;
$type_name = $order['status']==2 ? "退货退款":"仅退款";
$goods_status = $order['status']==4 ? 1:0;
$goods_status_name = $order['status']==4 ? "买家已收到货":"买家未收到货";
$time = time();
if(empty($return)){//新增退款信息
    $refund_id = "R".$time;
    $sql="insert into mallbuilder_return (order_id,refund_id,member_id,seller_id,product_id,refund_price,reason,status,goods_status,type,create_time) value('$orderid','$refund_id','$uid','".$order['userid']."','$pid','$money','$reason','1','$goods_status','$type','$time')";
    $db->query($sql);
    $psql="update mallbuilder_product_order_pro set status=4 where id=".$pid;
    $db->query($psql);
    $msg = "买家（".$uname."）于 ".date("Y-m-d H:i:s",$time)." 创建了退款申请。买家要求：".$type_name."，货物状态：".$goods_status_name."，退款金额：$money元，退款原因：$reason";
    $tsql="insert into mallbuilder_talk (refund_id,order_id,member_id,type,content,create_time) values ('".$refund_id."','$orderid','$uid','1','$msg','".$time."')";
    $db->query($tsql);
}else{//修改退款信息
    $sql="update mallbuilder_return set `status`=1 , refund_price=".$money." , reason='".$reason."' , create_time=".$time." where refund_id='".$return['refund_id']."'";
    //echo $sql;die;
    $db->query($sql);
    $psql="update mallbuilder_product_order_pro set status=4 where id=".$pid;
    $db->query($psql);
    $msg = "买家（".$uname."）于 ".date("Y-m-d H:i:s")." 修改了退款申请。";
    $tsql="insert into mallbuilder_talk (refund_id,order_id,member_id,type,content,create_time) values ('".$return['refund_id']."','$orderid','$uid','1','$msg','".$time."')";
    $db->query($tsql);
}
$re['orderId'] = $orderid;
$re['result'] = 0;
echo json_encode($re);
?>
