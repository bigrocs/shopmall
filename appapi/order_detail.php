<?php
## 传递参数列表
## uid【用户】,orderId【订单id】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【用户为空】
## -1【查询订单失败】，-2【查询订单产品失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
//订单
$where =" where 1 ";
$psql = "select * from ".ORDER;
if(!empty($_POST['orderId'])){
    $orderid = $_POST['orderId'];
	$where .=" and order_id = ".$_POST['orderId'];
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
$sql = "select * from mallbuilder_member where user = '".$uname."'";
$db->query($sql);
$mem = $db->fetchRow();
$uid = $mem['userid'];
$where.=" and userid = ".$uid;

$psql= $psql.$where;
$db->query($psql);
$order=$db->fetchRow();
if(empty($order)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
//返回
$re['orderId'] = $order['order_id'];
$re['orderStatus'] = $order['status'];
$re['additionWord'] = $order['des'];
$rsql="select * from mallbuilder_return where order_id=".$order['order_id'];
$db->query($rsql);
$return = $db->getRows();
if(empty($return)){
    $re['reimburseStatus'] = 0;
}else{
    $re['reimburseStatus'] = 1;
}
$re['buyerComment'] = $order['buyer_comment'];
$re['sellerComment'] = $order['seller_comment'];
$re['paidMoney'] = $order['product_price'] + $order['logistics_price'];//实付款
$re['postType'] = $order['logistics_type'];
$re['postPrice'] = $order['logistics_price'];
$re['logisticsName'] = $order['logistics_name'];
$re['invoiceNo'] = $order['invoice_no'];
$re['priceTotal'] = $order['product_price'] + $order['logistics_price'];//总价
/*$adsql ="select * from mallbuilder_delivery_address where name=".$order['consignee']." and userid = ".$uid."";
$db->query($adsql);
$address = $db->fetchRow();
$order['addressId'] = $address['id'];*/
$re['connectName'] = $order['consignee'];
$re['address'] = $order['consignee_address'];
$re['mobilephone'] = $order['consignee_mobile'];
$re['createTime'] = $order['create_time'];
$re['shopId'] = $order['seller_id'];
//店铺名
$sql = "select * from mallbuilder_shop where userid=".$order['seller_id'];
$db->query($sql);
$shop=$db->fetchRow();
$re['shopName'] = $shop['company'];
$re['username'] = $shop['user'];
//产品总数
$where2 =" where 1 and order_id = ".$order['order_id']." and buyer_id = ".$order['userid'];
$psql1 = "select sum(num) as totle from ".ORPRO;
$psql2 = "select * from ".ORPRO;
$sql= $psql1.$where2;
//echo $sql;die;
$db->query($sql);
$prot = $db->fetchRow();
$re['productTotal'] = $prot['totle'];
$sql= $psql2.$where2;
$db->query($sql);
$products=$db->getRows();
if(!empty($products)){
    foreach($products as $key=>$pro){
        $prolist[$key]['picUrl'] = $pro['pic']."_120X120.jpg";
        $prolist[$key]['productId'] = $pro['pid'];
        $prolist[$key]['productName'] = $pro['name'];
        $prolist[$key]['comboId'] = $pro['setmeal'];
        $combosql = "select * from mallbuilder_product_setmeal where id=".$pro['setmeal'];
        $db->query($combosql);
        $combo = $db->fetchRow();
        $prolist[$key]['comboName'] = $combo['setmeal'];
        $prolist[$key]['price'] = $pro['price'];
        $prolist[$key]['productVolume'] = $pro['num'];
        $prolist[$key]['productPrice'] = $pro['price']*$pro['num'];//商品总价
        $prolist[$key]['productStatus'] = $pro['status'];
    }
    $re['list'] = $prolist;
    $re['result'] = 0;
    echo json_encode($re);
}else{
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
?>