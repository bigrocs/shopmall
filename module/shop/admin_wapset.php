<?php
	include_once("module/shop/includes/plugin_shop_class.php");
	$shop=new shop();
	global $buid;

	if(isset($_POST['act']) && $_POST['act'] == "submit")
	{
		$wap_bannar = $_POST['wap_bannar'];
		$lng = $_POST['lng'];
		$lat = $_POST['lat'];

		// 更新bannar图
		$sql = "update ".SSET." set `wap_bannar` = '$wap_bannar' where `shop_id` = $buid ";
		$db -> query($sql);

		// 更新 定位信息
		$sql = "update ".SHOP." set `lng` = '$lng', `lat` = '$lat'  where `userid` = '$buid' ";
		$db -> query($sql);

		$admin->msg('main.php?m=shop&s=admin_wapset',"更新成功");
	}

	$tpl -> assign("re",$shop -> get_shop_setting());
	$tpl -> assign("de",$shop -> get_shop_info($buid));
	
	$tpl->assign("config",$config);
	$output=tplfetch("admin_wapset.htm");
?>