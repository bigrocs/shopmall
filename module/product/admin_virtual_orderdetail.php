<?php

@include_once("$config[webroot]//config/logistics_config.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//----------------------------------------------------
if(!empty($_GET['id'])&&is_numeric($_GET['id']))
{
	$tpl->assign("de",$de = $order->orderdetail($_GET['id']));
}
$order_status[2]="购买成功";
$order_status[4]="已使用";
$order_status[5]="退款中的订单";
$order_status[1]="等待买家付款";
$order_status[6]="退款中的订单";
$tpl->assign("order_status",$order_status);
$rate[1]="需我评价";
$rate[2]="我已评价";
$rate[3]="对方已评";
$rate[4]="双方已评";
$tpl->assign("rate",$rate);


//==================================
$tpl->assign("config",$config);
$tpl->assign("logistics_config",$logistics_config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_virtual_orderdetail.htm");
?>