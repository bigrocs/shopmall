<?php
include_once("module/shop/includes/plugin_shop_class.php");
$shop = new shop();

global $buid;
$re = $shop->GetShop($buid);

if(isset($_POST['act']) && $_POST['act'] == "submit")
{
	$shop_free_shipping = $_POST['shop_free_shipping'];


	// 更新bannar图
	$sql = "update  " . SHOP . "  set `shop_free_shipping` = '$shop_free_shipping' where `userid` = $buid ";
	$db -> query($sql);


	$admin->msg('main.php?m=shop&s=admin_freightset',"更新成功");
}


$tpl->assign("shop_free_shipping", $re['shop_free_shipping']);
$tpl->assign("config", $config);


$output = tplfetch("admin_freightset.htm");
?>