<?php
## 传递参数列表
## uid【用户id】,orderId【订单id】,productId【产品id】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【产品id为空】,3【用户id为空】，4【退款原因为空】,5【退款金额为空】
## -1【订单不存在】, -2【订单产品不存在】,-3【产品未申请退款/退货】,
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
if(empty($return)){//新增退款信息
    $re['result'] = -3;
    echo json_encode($re);
    exit;
}else{//取消退款
    $sql="update mallbuilder_return set `status`=0 , close_reason='因您取消退款申请，退款已关闭，交易将正常进行。' where refund_id='".$return['refund_id']."'";
    $db->query($sql);
    $psql="update mallbuilder_product_order_pro set status=3 where id=".$pid;
    $db->query($psql);
    $re['productStatus'] = 3;
    $re['orderId'] = $orderid;
    $re['result'] = 0;
    echo json_encode($re);
}
?>
