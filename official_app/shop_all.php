<?php
include_once("../includes/global.php");

$where =" where 1 ";
$ssql = " select a.*,b.name as catname from mallbuilder_shop as a left join mallbuilder_shop_cat as b on a.catid=b.id ";

if(isset($_GET['catid']) && !empty($_GET['catid'])){
	$catid = $_GET['catid'];
	$where .= " and a.catid = ".$catid ;
}

$ssql .= $where ;
$db->query($ssql);
$shop=$db->getRows();

//分类
	
$catsql = " select id,name from mallbuilder_shop_cat ";
$db->query($catsql);
$cat=$db->getRows();

//综合返回
$list = array();
$list[0] =$shop;
$list[1] = $cat;

echo json_encode($list);

?>
