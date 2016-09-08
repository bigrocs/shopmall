<?php
/*
* @Auth:bruce
* @Uptime:2014-12-09
* @ 虚拟订单 卖家列表页
*/

include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
include_once("$config[webroot]/includes/page_utf_class.php");
$order=new order();
global $buid;

// 确认完成 使用
if(isset($_GET['virtual_id']) && !empty($_GET['virtual_id']))
{
	$order -> set_order_statu($_GET['virtual_id'],4);
	$admin->msg("main.php?m=product&s=admin_virtual_sellorder&status=4","确认成功");
}


if($_POST['act'] == 'expand')
{
	$order -> expand_time($_POST['order_id'],$_POST['days']);
	$admin->msg("main.php?m=product&s=admin_virtual_sellorder","成功延长订单 ".$_POST['order_id']." 的超时时间。");
}
if($_POST['act'] == 'edit_price')
{
	$flag = $order -> update_price();
	if($flag == "88")
		$admin->msg("main.php?m=product&s=admin_virtual_sellorder","更新失败，当前状态下不允许修改价格");
	else
		$admin->msg("main.php?m=product&s=admin_virtual_sellorder");
}
if($_GET['order_id'])
{
	if($_GET['act']=='edit_price')
	{
		$tpl->assign("de",$de = $order->orderdetail($_GET['order_id']));	
	}
	$output=tplfetch("admin_virtual_sellorder.htm",$flag,true);
}
else
{
	if(isset($_GET['flag'])&&isset($_GET['id']))
	{
		if($_GET['flag']==0)
			$order->set_order_statu($_GET['id'],0);//取消定单
	}
	//===================================================
	$status=isset($_GET['status'])?$_GET['status']:"";
	$tpl->assign("slist",$re=$order->sellorder($status,1));
	//===================================================
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_virtual_sellorder.htm");
}
?>