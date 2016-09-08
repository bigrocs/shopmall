<?php
## 传递参数列表
## shopId【店铺id】,productId【商品id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【商品id为空】
## -1【商品不存在】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
$where =" where 1 ";
$ssql = " select * from ".PRODUCT;
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and member_id = ".$shopid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productId'])){
    $productid = $_POST['productId'];
	$where .= " and id = ".$productid ;
}else{
    $re['result'] = 2;
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
$re['shopId'] = $product['member_id'];
$seql="select * from mallbuilder_shop where userid=".$product['member_id'];
$db->query($seql);
$shop=$db->fetchRow();
$re['shopName'] = $shop['company'];
$re['productId'] = $product['id'];
$re['productName'] = $product['name'];
$re['priceFloat'] = 0;//是否固定价格,表中没有相应字段，设置为固定价格
//可选内容总数
$sql = "select * from ".SETMEAL." where pid=".$productid;
$db->query($sql);
$result=$db->getRows();
$re['totalType'] = $db->num_rows();
foreach($result as $k=>$ree){
    /*/可选内容列表
    $type = explode(',',$ree['spec_name']);
    for($i=0;$i<count($type);$i++){
        $list[$k]['typeTitle'] = $type[$i];
        $sql = "select * from mallbuilder_properity_value where field_name=".$type[$i];
        $db->query($sql);
        $perty = $db->fetchRow();
        $item = explode('|',$perty['item']);
        $list[$k]['typeCount'] = count($item);
    }
    $list[$k]['typeTitle'] = $ree['spec_name'];
    $list[$k]['typeCount'] = $ree['stock'];
    $typeName = explode(',',$ree['setmeal']);
    $list[$k]['typeList'] = $typeName;*/
    //型号价格列表
    $combo[$k]['comboId'] = $ree['id'];
    $combo[$k]['comboPicUrl'] = $product['pic'];
    $combo[$k]['comboDescribe'] = $product['name'];
    $combo[$k]['comboPrice'] = $ree['price'];
    $combo[$k]['comboStock'] = $ree['stock'];
    $propertyid = explode(',',$ree['property_value_id']);
    $combo[$k]['comboType'] = $propertyid;
}
//$re['totalList'] = $list;
$re['comboList']=$combo;
$re['result']=0;
echo json_encode($re);
?>
