<?php
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_encode(file_get_contents("php://input"));
if(isset($_POST['uid']) && !empty($_POST['uid'])){
    $uid = $_POST['uid'];
	$where .= " and buyer_id = ".$uid;
}
$sql1="select product_id from".CART." where 1 ".$where;
$db->query($sql1);
$goods=$db->getRows();
$count=$db->num_rows();

$sql2="select distinct mallid from".PRODUCT."where id in ".$goods['product_id'];
$db->query($sql2);            //假设product表里有mallid字段
$mallid=$db->getRows();
$sql3="select * from mallbuilder_webname where id in".$mallid['mallid'];      //假设存在mall表
$db->query($sql3);
$malls=$db->getRows();
foreach($malls as $k=>$m){
    $mall[$k]['mallId'] = $m['id'];
    $mall[$k]['mallName'] = $m['village'];
    $mall[$k] = $m;
}
$re['total'] = $count;
$re['list'] = $mall;
$re['result'] = 0;
echo json_encode($re);
?>
