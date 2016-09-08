<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】,productId【商品id】,comboId【组合id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【商品id为空】,3【uid为空】
## -1【购物车没有该商品】,-2【删除失败】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and seller_id = ".$shopid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productId'])){
    $productid = $_POST['productId'];
    $where .= " and product_id = ".$productid ;
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['comboId'])){
    $comboid = $_POST['comboId'];
    $where .= " and spec_id = ".$comboid ;
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
$where.=" and buyer_id=".$uid;
$fsql = "select * from ".CART." where 1".$where;
//echo $fsql;die;
$db->query($fsql);
$cpro = $db->fetchRow();
if(empty($cpro)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$ssql = "delete from ".CART." where 1".$where;
$dre=$db->query($ssql);
if($dre){
    $re['result']=0;
    $re['productId']=$productid;
    $re['comboId']=$comboid;
    $re['shopId']=$shopid;
    echo json_encode($re);
}else{
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
?>
