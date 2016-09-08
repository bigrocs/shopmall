<?php
if(isset($admin_read_config))
{
	$cron_config['name']=array('cn'=>'更新订单状态');
	$cron_config['des']=array('cn'=>'更新订单状态');
	$cron_config['week']='-1';//设置周几执行，此设置将覆盖下面的“日”选项。 -1,不作限制 Sunday,每周日 Monday,每周一 ....
	$cron_config['day']='-1';//设置任务哪天执行，默认为每天。  -1，不作限制 01每月一号
	$cron_config['hours']='00';//设置任务哪个小时执行 01,02....24
	$cron_config['minutes']='00';//设置任务分钟执行 01,02.....59
}
else
{
	include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
	$order=new order();
	
	$sql = "select status,userid,order_id,discounts,create_time,deliver_time,time_expand,is_virtual from ".ORDER." where ( status = 1 or status =3 or (status =2 and is_virtual = 1)) and buyer_id = '0' ";
	$db -> query($sql);
	$re = $db -> getRows();


	foreach($re as $val)
	{
		$T = time();
		$T1 = $val['create_time'] + 12*60*60; 
		$T2 = $val['deliver_time'] + ($va["time_expand"]?$va["time_expand"]*1:10)*24*60*60;
		
		if($val['is_virtual'])
		{
			// 如果商品同意退货则直接取消订单，如果不同意则直接完成订单 （接下来要做）
			$sql = "SELECT end_time,pid,recate FROM ".PROVIR." where pid =(SELECT pid from ".ORPRO." WHERE order_id = '".$val['order_id']."' limit 1) ";
			$db -> query($sql);
			$de = $db -> fetchRow();
			$T2 = $de['end_time'];


			if($de['recate'] == 1 && $T2<$T && $val['status'] == '2')
			{
				$order->set_order_statu($val['order_id'],4,$val['userid']);//确认收货
			}
			else if($de['recate'] == 1 && $T2<$T && $val['status'] == '1')
			{
				$order->set_order_statu($val['order_id'],0,$val['userid']);// 过期后自动取消订单
			}
		}
		else
		{
			if( ($T1<$T) && $val['status'] == '1' )
			{
				$order->set_order_statu($val['order_id'],0,$val['userid']);//取消订单	
			}
			if( ($T2<$T) && $val['status'] == '3' )
			{
				$order->set_order_statu($val['order_id'],4,$val['userid']);//确认收货
			}
		}
	}
}
?>