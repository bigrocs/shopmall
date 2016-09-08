<?php
include_once("../includes/global.php");
include_once("../config/table_config.php");

$uid = $_GET['userid']?$_GET['userid']:NULL;

if(empty($uid)){
	echo 0;
     die;
	}else{
$sql="select cash from ".MEMBER." where pay_id='$uid'";
$db->query($sql);
$cash=$db->fetchField('cash');
echo $cash;
die;
}
?>