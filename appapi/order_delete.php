<?php
## 传递参数列表
## uid【用户id】,orderId【订单id】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【用户id为空】
## -1【取消收藏失败】,-2【店铺商品不存在】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
    $_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['orderId']) && !empty($_POST['orderId'])){
    $orderid = $_POST['orderId'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
$sql0="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql0);
$mem = $db->fetchRow();
$uid = $mem['userid'];
$sql1="delete from mallbuilder_product_snapshot where 1 and order_id=".$orderid." and member_id=".$uid;
$db->query($sql1);
$sql2="delete from mallbuilder_product_order_pro where 1 and order_id=".$orderid." and buyer_id=".$uid;
$db->query($sql2);
$sql="delete from ".ORDER." where 1 and order_id=".$orderid;
$db->query($sql);
$re['orderId'] = $orderid;
$re['result'] = 0;
echo json_encode($re);
?>
