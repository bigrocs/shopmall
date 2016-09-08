<?php
include_once("../includes/global.php");

$where =" where 1 ";
$status = '';

if(isset($_GET['status']) && !empty($_GET['status'])){
	$status = $_GET['status'];
	$where .= " and a.status = ".$status ;
}


$sql = "
select a.*,b.user as seller,c.user as buyer from mallbuilder_product_order as a left join 
mallbuilder_member as b on a.seller_id = b.userid left join mallbuilder_member as c on a.buyer_id = c.userid ".$where." order by a.uptime,id desc  " ;


$re = mysql_query($sql);

$arr =  array();
while($row = mysql_fetch_array($re)){
    $arr[] =  $row; 
}
//现在数组就是二维数组了 想输出json 的话就
echo json_encode($arr);

?>
