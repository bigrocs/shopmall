<?php

@include_once("$config[webroot]//config/logistics_config.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//----------------------------------------------------
if(!empty($_GET['id'])&&is_numeric($_GET['id']))
{
	$tpl->assign("de",$de = $order->orderdetail($_GET['id']));
}

@include_once("./config/logistics_config.php");
$logistics_kdniao_connect = @$logistics_config['logistics_kdniao_connect'];

if ($logistics_kdniao_connect)
{
	$id = $_GET['id'];
	$logistics_row = $order->get_logistics_order_status($id);

	if ($logistics_row)
	{
		$tpl->assign("kdniao_flag", 1);
	}
	else
	{
		$tpl->assign("kdniao_flag", 0);
	}
}



//==================================
$tpl->assign("order_id",$_GET['id']);
$tpl->assign("config",$config);
$tpl->assign("logistics_config",$logistics_config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_orderdetail.htm");
?>