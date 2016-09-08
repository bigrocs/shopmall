<?php
include_once("../includes/global.php");

if(!empty($_GET['statu'])&&$_GET['statu']==1)
{
	if($_GET['auth']!=md5($config['authkey']))
		die('参数错误');
	
	$sql = "select `status` from ".ORPRO." where order_id='$_GET[id]'";
	$db -> query($sql);
	$status = $db->fetchField('status');
	
	if($status < 2)
	{
		//---------------------付款成功减库存，
		$sql="select pid,num,setmeal from ".ORPRO." where order_id='$_GET[id]'";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $val)
		{
			if(!empty($val['num']))
			{
				$sql="update ".PRODUCT." set sales= sales + $val[num] where id=$val[pid]";
				$db->query($sql);
						
				if($val['setmeal'])
				{
					$sql="update ".SETMEAL." set stock = stock - $val[num] where id = '$val[setmeal]'";
					$db->query($sql);
				}		
			
				$sql="update ".PRODUCT." set stock = stock - $val[num] where id = '$val[pid]'";
				$db->query($sql);
				
				$sql="select stock from ".PRODUCT." where id='$val[pid]'";
				$db->query($sql);
				
				if($db->fetchField('stock')<=0)
				{
					$sql="update ".PRODUCT." set is_shelves = '0' where id=$val[pid]";
					$db->query($sql);
				}
			}
		}
		$sql="update ".ORDER." set status='2',payment_name='$_GET[type]',payment_time=".time()." where order_id='$_GET[id]'";
		$db->query($sql);
		
		$sql="update ".ORPRO." set status='1' where order_id='$_GET[id]'";
		$db->query($sql);
	}
	$url=$config["weburl"]."/main.php?cg_u_type=1&m=product&s=admin_orderdetail&id=$_GET[id]";
	msg($url);
}
else
{
	$url=$config["weburl"]."/main.php?m=product&s=admin_orderdetail&id=$_GET[id]";
	msg($url);
}

?>