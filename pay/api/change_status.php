<?php
/**
* Dsc : 改变订单流水状态
*/

if(!isset($_GET['id'])){die();}

include_once("../includes/global.php");
include_once("../config/table_config.php");

$id = intval($_GET['id']);
$sql = "update ".CASHFLOW." set `statu` = '2' where `order_id` = '$id' ";
$db -> query($sql);

?>