<?php
## 传递参数列表
## uid【用户id】,orderList【订单列表】

## 返回状态参数列表
## 0【成功】,1【订单列表为空】,2【用户id为空】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['orderList']) && !empty($_POST['orderList'])){
    $orderlist = $_POST['orderList'];
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
foreach($orderlist as $key=>$ord){
    if($ord['orderStatus']==4)
    {
        $sql="select seller_id,status,product_price,logistics_price from ".ORDER." where order_id='".$ord['orderId']."' and userid='$uid'";
        $db->query($sql);
        $de=$db->fetchRow();
        include_once("module/member/includes/plugin_member_class.php");
        $member = new member();
        $member->add_points(($de['product_price']+$de['logistics_price'])*1,'1',$ord['orderId'],$uid);
    }
    $sql="update ".ORDER." set status='".$ord['orderStatus']."',uptime=".time()." where order_id='".$ord['orderId']."'";
    $db->query($sql);
    $status = $ord['orderStatus'] -1;
    $sql = "select id,status from ".ORPRO." where order_id = ".$ord['orderId'];
    $db->query($sql);
    $products = $db->getRows();
    foreach($products as $val)
    {
        if($val['status'] != 5)
        {
            $sql="update ".ORPRO." set status = '$status' where id='$val[id]'";
            $db->query($sql);
        }

    }
    switch ($status)
    {
        case "3":
        {
            $close_reason = '因您确认收货，退款关闭。';
            break;
        }
        case "2":
        {
            $close_reason = '因卖家发货，导致退款关闭，如仍需退款，您可以重新发起申请。';
            break;
        }
        default:
            {
            $close_reason = '';
            break;
            }
    }
    if($close_reason)
    {
        $sql="update ".REFUND." set close_reason = '$close_reason',status = '0' where order_id = '".$ord['orderId']."' and status ='1' and status ='4' ";
        $db->query($sql);
    }
}

$re['orderList'] = $orderlist;
$re['result'] = 0;
echo json_encode($re);
?>
