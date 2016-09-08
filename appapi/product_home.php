<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】,productId【商品id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【商品id为空】,3【uid为空】
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
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 3;
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
$re['shopId'] = $shopid;
$sql = "select * from mallbuilder_shop where userid=".$shopid;
$db->query($sql);
$shop=$db->fetchRow();
$re['username'] = $shop['user'];
$re['shopName'] = $shop['company'];
$re['area']=$shop['area'];
$re['productId'] = $productid;
$re['productName'] = $product['name'];
$re['isShelves'] = $product['is_shelves'];
//是否收藏（根据用户名）
$sql = "select * from mallbuilder_sns_shareproduct where pid=".$productid." and uname='".$uname."'";
$db->query($sql);
$list=$db->getRows();
if(!empty($list)){
    $re['subscribe'] = 1;
}else{
    $re['subscribe'] = 0;
}
$re['productStatus'] = $product['status'];//产品状态
$re['totalSell'] = $product['sales'];//累计销量
//月销量*******************************************product表没有月销量字段
$time=time() - 30*24*60*60;
$sql1 = "select * from ".ORPRO." where pid=".$productid." and time>".$time;
$db->query($sql1);
$re['monthSell'] =$db->num_rows();
$re['stockVolume'] = $product['stock'];//库存
//评价条数
$sql2 = "select * from ".PCOMMENT." where pid=".$productid;
$db->query($sql2);
$re['commentVolume'] = $db->num_rows();
$re['price'] = $product['price'];
//运费
if(empty($product['freight_type']))
{   //卖家承担运费
    $re['postPrice'] =  0;
}else{
    $sql="select * from ".LGSTEMPCON." where temp_id=".$product['freight_id']." and define_citys = 'default'";
    $db->query($sql);
    $lgs=$db->fetchRow();
    if($lgs)
    {
        $re['postPrice'] = $lgs['default_price'];
    }
}
//有无更多选择************************************
$sql3 = "select * from ".SETMEAL." where pid=".$productid;
$db->query($sql3);
$re1=$db->fetchRow();
if(!empty($re1)){
    $re['selectMode'] = 1;
    $re['selectMore'] = $re1['spec_name'];
}else{
    $re['selectMode'] = 0;
}
/*preg_match_all("/((\/upload.*?)|(http.*?))\"/i",$list['detail'],$de);
//$images =explode(",",$de);
$re['productImage'] = $de[1];

include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
$addfield = new AddField('product');
$sql="select ext_table from ".PCAT." where catid='".$product['catid']."'";
$db->query($sql);
$ext=$db->fetchRow();
$extfiled=$addfield->addfieldinput($productid,$ext['ext_table']);
$re['productVersion'] = $extfiled['c'];//扩展属性键值对*/

//图片链接图片数量
$picList = explode(',',$product['pic_more']);
$re['picList'] = $picList;
$re['picVolume'] = count($picList);
$re['result'] =0;
echo json_encode($re);
?>
