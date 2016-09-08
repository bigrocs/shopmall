<?php
include_once("../includes/global.php");
include_once("../config/table_config.php");
//============================================================

if(isset($_GET['logo']) && !empty($_GET['logo']))
{
	if(isset($_GET['uid']))
	{
		$id = $_GET['uid'] * 1;
		$pid = $_GET['pay_id'] * 1;
		$sql = "update ".MEMBER." set `logo` = '".$_GET['logo']."' where `pay_id` = '".$pid."' and `userid` = '".$id."'  ";
		$db -> query($sql);
	}
}
?>