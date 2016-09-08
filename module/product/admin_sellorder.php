<?php
/*
* @Auth:bruce
* @Uptime:2014-11-26
* @Desc:卖家在订单状态改变过，在期页面上还可以修改价格,返回失败后的页面跳转。
*/

include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
include_once("$config[webroot]/includes/page_utf_class.php");
$order=new order();
if($_POST['act'] == 'expand')
{
	$order -> expand_time($_POST['order_id'],$_POST['days']);
	$admin->msg("main.php?m=product&s=admin_sellorder","成功延长订单 ".$_POST['order_id']." 的超时时间。");
}
if($_POST['act'] == 'edit_price')
{
	$flag = $order -> update_price();
	if($flag == "88")
		$admin->msg("main.php?m=product&s=admin_sellorder","更新失败，当前状态下不允许修改价格");
	else
		$admin->msg("main.php?m=product&s=admin_sellorder");
}
if($_GET['order_id'])
{
	if($_GET['act']=='edit_price')
	{
		$tpl->assign("de",$de = $order->orderdetail($_GET['order_id']));	
	}
	$output=tplfetch("admin_sellorder.htm",$flag,true);
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

	$re=$order->sellorder($status);

	@include_once("./config/logistics_config.php");

	$logistics_kdniao_connect = @$logistics_config['logistics_kdniao_connect'];

	if ($logistics_kdniao_connect)
	{
		$kdniao_logistics_config = @include_once("./config/kdniao_logistics_config.php");


		if (isset($re['list']))
		{
			foreach ($re['list'] as $key=>$item)
			{
				$re['list'][$key]['order_logistics_status'] = 0;

				if ($logistics_kdniao_connect)
				{
					$logistics_row = $order->get_logistics_order_status($item['order_id']);

					$re['list'][$key]['order_logistics_status'] = $logistics_row['order_logistics_status'];
					$re['list'][$key]['order_logistics_code'] = $logistics_row['order_logistics_code'];


					$re['list'][$key]['order_logistics_name'] = $kdniao_logistics_config[$logistics_row['order_logistics_code']];
					$re['list'][$key]['order_logistics_time'] = $logistics_row['order_logistics_time'];
				}
			}
		}
	}

	$tpl->assign("logistics_kdniao_connect", $logistics_kdniao_connect);
	$tpl->assign("slist", $re);
	//===================================================
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_sellorder.htm");
}
?>