<?php
include_once("$config[webroot]/module/product/includes/plugin_refund_class.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$refund = new refund();
$order = new order();
//=====================================================================
if($_POST['act'])
{
	$R = $refund -> operate_refund($_POST['act']);
	$order -> set_order_product_statu($_POST['order_id'],'5',$_POST['id']);
	$admin->msg("main.php?m=product&s=admin_apply_detail&id=$R");
}
$de=$refund->order_detail($_GET['order_id'],$_GET['id']);
//print_r($de);
if(!$de||$de['status']>4||$de['status']<2)
{
	header("Location: 404.php");
}
$tpl->assign("de",$de);
//=====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_apply.htm");
?>