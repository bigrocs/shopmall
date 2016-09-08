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
foreach($orderlist as $key=>$ord){
    $sql="select * from ".ORDER." where order_id='".$ord['orderId']."'";
    $db->query($sql);
    $order = $db->fetchRow();
    $list[$key]['id'] = $order['order_id'];
    $list[$key]['status'] = $order['status'];
    $rsql="select * from mallbuilder_return where order_id=".$order['order_id'];
    $db->query($rsql);
    $return = $db->getRows();
    if(empty($return)){
        $list[$key]['reimburseStatus'] = 0;
    }else{
        $list[$key]['reimburseStatus'] = 1;
    }
}
$re['orderList'] = $list;
$re['result'] = 0;
echo json_encode($re);
?>
