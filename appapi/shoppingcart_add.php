<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】,productId【商品id】,comboId【型号id】,productVolume【数量】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【商品id为空】,3【数量为空】,4【uid为空】
## -1【商品不存在】,-2【商品型号错误】,-3【用户不存在】,-4【加入购物车失败】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
$where=" where 1";
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
    $where.=" and seller_id=".$shopid;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productId'])){
    $pid = $_POST['productId'];
    $where.=" and product_id=".$pid;
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['comboId'])){
    $comboid = $_POST['comboId'];
    $sql1="select * from ".PRODUCT." where id=".$pid." and member_id=".$shopid." and is_shelves=1";
    $db->query($sql1);
    $pro=$db->fetchRow();
    $sql2="select * from mallbuilder_product_setmeal where id=".$comboid." and pid=".$pid;
    $db->query($sql2);
    $meal=$db->getRows();
    if(empty($pro)||empty($meal)){
        $re['result'] = -1;
        echo json_encode($re);
        exit;
    }
}else{
    $sql3="select * from mallbuilder_product_setmeal where pid=".$pid;
    $db->query($sql3);
    $meal=$db->getRows();
    if(empty($meal)){
        $comboid =0;
    }else{
        $re['result'] = -2;
        echo json_encode($re);
        exit;
    }
}
$where.=" and spec_id=".$comboid;
if(!empty($_POST['productVolume'])){
    $quantity = $_POST['productVolume'];
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
$sql1="select * from ".PRODUCT." where id=".$pid." and member_id=".$shopid." and is_shelves=1";
$db->query($sql1);
$pro=$db->fetchRow();
if(empty($pro)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$sql2="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql2);
$mem=$db->fetchRow();
if(empty($mem)){
    $re['result'] = -3;
    echo json_encode($re);
    exit;
}
$where.=" and buyer_id=".$mem['userid'];
$time = time();
$sql3="select * from ".CART.$where;
$db->query($sql3);
$cart=$db->fetchRow();
if(empty($cart)){
    $sql="insert into ".CART." (buyer_id,product_id,seller_id,price,spec_id,quantity,create_time) value('".$mem['userid']."','$pid','$shopid','".$pro['price']."','$comboid','$quantity','$time') ";
    $db->query($sql);
    $flag=$db->lastid();
}else{
    $sql="update ".CART." set quantity=quantity+".$quantity.$where;
    $flag=$db->query($sql);
}
if($flag){
    $re['result']=0;
    $re['productId'] = $pro['id'];
    $re['shopId'] = $shopid;
    $re['productStatus'] = $pro['status'];
    $re['comboId'] = $comboid;
    $re['productVolume'] = $quantity;
    $re['picUrl'] = $pro['pic']."_60X60.jpg";
    $re['price'] = $pro['price'];
    $re['createTime'] = $time;
    echo json_encode($re);
}else{
    $re['result'] = -4;
    echo json_encode($re);
    exit;
}
?>
