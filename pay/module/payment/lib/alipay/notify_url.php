<?php
	
	//add by windfnn 2015-11-30 支付宝异步返回 状态不改变问题	
	$val=$_POST?$_POST:$_GET;
	include_once("../../includes/plugin_pay_class.php");
	include_once("../../../../includes/global.php");
	 
	$sign=$val['sign'];
	$tradeno=$val['out_trade_no'];//站内流水ID
	$payflowid=$val['trade_no'];//支付宝交易号
	$total_fee=$val['total_fee'];
	$extra_common_param=$val['extra_common_param'];

	if($val['trade_status']=='TRADE_SUCCESS'){
		echo "success";
		set_result($tradeno,$payflowid,$total_fee,"支付宝",$extra_common_param);
	}

	function set_result($tradeno,$payflowid,$total_fee,$type,$extra_common_param)
	{
		global $db,$config;
		//验证一下是不是被异步处理了。
		$sql="select * from ".RECORD." where id='$tradeno'";
		$db->query($sql);
		$re=$db->fetchRow();

		$userid=$re['pay_uid'];
		$statu=$re['statu'];
		
		//如果验证成功,并且流水表中的记录为新提交
		if($statu==1)
		{	
			$sql="update ".RECORD." set price='$total_fee',flow_id='$payflowid',statu='4' where id='$tradeno'";
			$db->query($sql);
			
			$sql="update ".MEMBER." set cash=cash+$total_fee where pay_id ='$userid'";
			$db->query($sql);
			
			if($extra_common_param)
			{
				$order_id = $extra_common_param;
				
				//----------------当前流水详情	
				$sql="select * from ".RECORD." where order_id='$order_id' and pay_uid='$userid'";
		
				$db->query($sql);
				$de=$db->fetchRow();
			
				//-------------减少账户金额
				if($de['price']<0) $de['price']*=-1;
				
			
				$sql = "update ".MEMBER." set cash=cash-".$de['price']." where pay_id='$userid'";
				$db->query($sql);
				//直接到账，修改流水状态,直接标记为成功
				
				if($de['type']==1)
				{		
					$sql="update ".RECORD." set statu='4' where order_id='$order_id'";
					$db->query($sql);
					
					$sql = "update ".MEMBER." set cash=cash+".($de['price']*-1)." where email='$de[seller_email]'";
					$db->query($sql);
				}
				//如果是担保接口，标记为已付款。
				if($de['type']==2)
				{
					$sql="update ".RECORD." set statu='2' where order_id='$order_id'";
					$db->query($sql);
				}
				
				//--异步处理,以后处理
				//返回同步处理结果
				
				$url=$de['return_url']."&type=$type&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
				file_get_contents($url);
				msg($url);
				
			}
			msg("?m=payment&s=record&mold=1");
		}
		else
		{
			if($extra_common_param)
			{
				$order_id = $extra_common_param;
				//----------------当前流水详情	
				$sql="select * from ".RECORD." where order_id='$order_id' and pay_uid='$userid'";
				$db->query($sql);
				$de=$db->fetchRow();
				
				$url=$de['return_url']."&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
				file_get_contents($url);
				msg($url);
			}
			else
				msg("?m=payment&s=record&mold=1");
		}
	}
?>