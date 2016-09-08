<?php
## 传递参数列表
## productId【商品id】

## 返回状态参数列表
## 0【查询成功】,1【productId为空】
## -1【商品不存在】,-2【无产品详情】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
$where =" where 1 ";
$ssql = " select * from ".PRODUCT;

if(!empty($_POST['productId'])){
    $productid = $_POST['productId'];
	$where .= " and id = ".$productid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$ssql= $ssql.$where;
$db->query($ssql);
$product=$db->fetchRow();
if(empty($product)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
//商品细节描述
$sql4 = "select * from ".PRODETAIL." where proid=".$productid;
$db->query($sql4);
$list=$db->fetchRow();
if(empty($list)){
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
$re['htmlString'] = $list['detail'];
$re['result'] =0;
echo json_encode($re);
?>
