<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
if(isset($_GET['flag'])&&isset($_GET['id']))
{
	if($_GET['flag']==4)
		$order->set_order_statu($_GET['id'],4);//确认收货
		
	if($_GET['flag']==0)
		$order->set_order_statu($_GET['id'],0);//取消定单
}
//=======================================
$status=isset($_GET['status'])?$_GET['status']:"";
$tpl->assign("blist",$re=$order->buyorder($status,1));

$order_status[2]="购买成功";
$order_status[4]="已使用";
$order_status[1]="等待买家付款";
$order_status[6]="退款中的订单";
$tpl->assign("order_status",$order_status);
$rate[1]="需我评价";
$rate[2]="我已评价";
$rate[3]="对方已评";
$rate[4]="双方已评";
$tpl->assign("rate",$rate);
//========================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_virtual_buyorder.htm");
?>
