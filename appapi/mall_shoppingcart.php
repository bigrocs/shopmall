<?php
## 传递参数列表
## uid【用户id】

## 返回状态参数列表
## 0【查询成功】,1【uid为空】
## -1【用户不存在】,-2【查询失败】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql);
$mem=$db->fetchRow();
if(empty($mem)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$where .= " and buyer_id = ".$mem['userid'];
$sql1 = "select distinct seller_id from ".CART." where 1".$where." order by create_time desc";
//echo $sql1;die;
$db->query($sql1);
$shopid=$db->getRows();
$re['shopTotal']=$db->num_rows();
$sql2 = "select * from ".CART." where 1".$where;
$db->query($sql2);
$re['productTotal']=$db->num_rows();
$i = 0;
foreach($shopid as $key=>$id){
    $shopList[$key]['shopId']=$id['seller_id'];
    $sql="select * from mallbuilder_shop where userid=".$id['seller_id'];
    $db->query($sql);
    $shop = $db->fetchRow();
    $shopList[$key]['shopName'] = $shop['company'];

    $sql="select * from ".SHOPSETTING." where shop_id=".$id['seller_id'];
    $db->query($sql);
    $shopdis = $db->fetchRow();
    $shopList[$key]['shopDiscribe'] = $shopdis['shop_description'];

    $sql="select * from ".CART." where seller_id=".$id['seller_id']." and buyer_id=".$mem['userid']." order by create_time desc,id asc ";
    $db->query($sql);
    $cartProduct=$db->getRows();
    $shopList[$key]['shopProduct']=$db->num_rows();
    $productList = "";
    $j=0;
    foreach($cartProduct as $kky=>$pro){
        $psql="select * from ".PRODUCT." where id=".$pro['product_id'];
        $db->query($psql);
        $product=$db->fetchRow();
        if($product['is_shelves']==1){
            $productList[$j]['productId'] = $pro['product_id'];
            $productList[$j]['shopId'] = $pro['seller_id'];
            $productList[$j]['comboId'] = $pro['spec_id'];
            $productList[$j]['productVolume'] = $pro['quantity'];
            $productList[$j]['price'] = $product['price'];
            $productList[$j]['productName'] = $product['name'];
            $productList[$j]['productStatus'] = $product['status'];
            $productList[$j]['picUrl'] = $product['pic']."_120X120.jpg";
            $productList[$j]['createTime'] = $pro['create_time'];
            $j++;
        }else{
            $re['invalidList'][$i]['productId'] = $pro['product_id'];
            $re['invalidList'][$i]['comboId'] = $pro['spec_id'];
            $re['invalidList'][$i]['shopId'] = $pro['seller_id'];
            $re['invalidList'][$i]['productVolume'] = $pro['quantity'];
            $psql="select * from ".PRODUCT." where id=".$pro['product_id'];
            $db->query($psql);
            $product=$db->fetchRow();
            $re['invalidList'][$i]['productName'] = $product['name'];
            $re['invalidList'][$i]['picUrl'] = $product['pic']."_120X120.jpg";
            $i++;
        }
    }
    if(!empty($productList)){
        $shopList[$key]['productList']=$productList;
    }else{
        unset($shopList[$key]);//没有商品就不返回店铺信息
    }
}
$re['shopList']=$shopList;
$re['result']=0;
echo json_encode($re);
?>
