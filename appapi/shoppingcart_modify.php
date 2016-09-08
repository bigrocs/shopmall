<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】,productId【商品id】,comboId【型号id】,productVolume【数量】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【商品id为空】,3【数量为空】,4【uid为空】
## -1【商品不存在】,-2【购物车没有该商品】,-3【修改失败】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and seller_id = ".$shopid ;
}else{
    $re['result'] = 1;
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
$sql0="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql0);
$mem = $db->fetchRow();
$uid = $mem['userid'];
$where.=" and buyer_id=".$uid;
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
    $sql1="select * from ".PRODUCT." where id=".$productid." and member_id=".$shopid;
    $db->query($sql1);
    $pro=$db->fetchRow();
    $sql2="select * from mallbuilder_product_setmeal where id=".$comboid." and pid=".$productid;
    $db->query($sql2);
    $meal=$db->getRows();
    if(empty($pro)||empty($meal)){
        $re['result'] = -1;
        echo json_encode($re);
        exit;
    }
}else{
    $comboid =0;
}$where.=" and spec_id=".$comboid;
if(!empty($_POST['productVolume'])){
    $quantity = $_POST['productVolume'];
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
$fsql = "select * from ".CART." where 1".$where;
$db->query($fsql);
$cpro = $db->fetchRow();
if(empty($cpro)){
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}else{
    $fsql = "select * from ".CART." where spec_id=".$comboid.$where;
    $db->query($fsql);
    $cpro = $db->fetchRow();
    if(!empty($cpro)){
        $ssql = "update ".CART." set quantity=".$quantity." where spec_id=".$comboid.$where;
    }else{
        $ssql = "update ".CART." set spec_id=".$comboid.",quantity=".$quantity." where 1".$where;
    }
}
$upre = $db->query($ssql);
if($upre){
//返回数据
    $sql3 = "select id,pic_more,name,price from ".PRODUCT." where member_id=".$shopid." and id=".$productid;
    $db->query($sql3);
    $pro=$db->fetchRow();
    $re['result']=0;
    $re['productId']=$pro['id'];
    $re['shopId']=$shopid;
    $re['comboId']=$comboid;
    $re['productVolume']=$quantity;
    $re['picUrl']=$pro['pic']."_60X60.jpg";
    $re['createTime']=$pro['start_time'];
    $sql4 = "select * from ".PRODETAIL." where proid=".$productid;
    $db->query($sql4);
    $det=$db->fetchRow();
    $re['ProductDescribe']=$det['detail'];
    echo json_encode($re);
}else{
    $re['result'] = -3;
    echo json_encode($re);
    exit;
}
?>
