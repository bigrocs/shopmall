<?php
## 传递参数列表
## uid【用户id】,orderId【订单id】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【用户id为空】
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

$sql="select seller_id,status,product_price,logistics_price from ".ORDER." where order_id='$orderid' and userid='$uid'";
$db->query($sql);
$de=$db->fetchRow();
include_once("module/member/includes/plugin_member_class.php");
$member = new member();
$member->add_points(($de['product_price']+$de['logistics_price'])*1,'1',$orderid,$uid);
$sql="update ".ORDER." set status='4',uptime=".time()." where order_id='$orderid'";
$db->query($sql);
$sql = "select id,status from ".ORPRO." where order_id = ".$orderid;
$db->query($sql);
$products = $db->getRows();
foreach($products as $val)
{
    if($val['status'] != 5)
    {
        $sql="update ".ORPRO." set status = '3' where id='$val[id]'";
        $db->query($sql);
    }
}
$re['orderId'] = $orderid;
$re['orderStatu'] = 4;
$re['result'] = 0;
echo json_encode($re);
?>
