<?php
include_once("../includes/global.php");

$configs = get_pay_config('alipay');
require_once("../module/payment/lib/alipay/lib/alipay_notify.class.php");
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($configs);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result)
{	
    $trade_no		= $_POST['out_trade_no'];	    //获取订单号
    $payflowid		= $_POST['trade_no'];	    	//获取支付宝交易号
    $total_fee		= $_POST['total_fee'];			//获取总价格
	$extra 			= $_POST['extra_common_param'];
	
	set_result($trade_no,$payflowid,$total_fee,"支付宝",$extra);
	echo "success";		
}
else {
     echo "fail";
}
function get_pay_config($type)
{
	global $db,$config;
	$sql="select * from ".PAYMENT." where payment_type='$type'";
	$db->query($sql);
	$re=$db->fetchRow();
	$re=unserialize($re['payment_config']);
	foreach($re as $v)
	{
		$name=$v['name'];
		$cn[$name]=$v['value'];
	}
	if($type=='alipay')
	{
		$return_url=$config['weburl']."/?m=payment&s=accounts&onlinepaytype=alipay";
		$notify_url=$config['weburl']."/api/pay.php";
		$cn['sign_type']    = 'MD5';
		$cn['input_charset']= 'utf-8';
		$cn['transport']    = 'http';
		$cn['return_url']   = $return_url;
		$cn['notify_url']   = $notify_url;
	}
	return $cn;
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
		error_log(var_export($statu,true),3,__FILE__.'6.log');
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
			$url=$de['return_url']."&type=$type&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
			
			file_get_contents($url);
		}
	}
}
?>