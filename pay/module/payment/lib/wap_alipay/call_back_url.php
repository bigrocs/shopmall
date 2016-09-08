<?php
include_once("../../../../includes/global.php");
include_once("lib/alipay_notify.class.php");
$val = $_POST ? $_POST : $_GET;
$val = array_merge($_POST,$_GET);

$alipay_config = get_pay_config("wap_alipay");
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	//商户订单号
	$out_trade_no = $val['out_trade_no'];
	$order_id = explode("-", $out_trade_no);
	$tradeno = $order_id[0];
	$extra_common_param = $order_id[1];
	//支付宝交易号
	$trade_no = $val['trade_no'];

	//交易状态
	$result = $val['result'];

	if($result == "success")
		set_result($tradeno,$trade_no,"支付宝",$extra_common_param);
}
else {
    echo "验证失败";
}

function set_result($tradeno,$payflowid,$type,$extra_common_param)
{
	global $db,$config;
	//验证一下是不是被异步处理了。
	$sql="select * from ".RECORD." where id='$tradeno'";
	$db->query($sql);
	$re=$db->fetchRow();

	$userid=$re['pay_uid'];
	$statu=$re['statu'];
	
	$total_fee = $re['price'];
	//如果验证成功,并且流水表中的记录为新提交
	if($statu==1)
	{	

		$sql="update ".RECORD." set flow_id='$payflowid',statu='4' where id='$tradeno'";
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
			msg($url);
			
		}
		msg("$config[weburl]?m=payment&s=record&mold=1");
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
			msg($url);
		}
		else
			msg("$config[weburl]?m=payment&s=record&mold=1");
	}
}

function get_pay_config($type)
{
	global $config,$db;
	$sql="select * from ".PAYMENT." where payment_type='$type'";
	$db->query($sql);
	$re=$db->fetchRow();
	$re=unserialize($re['payment_config']);
	foreach($re as $v)
	{
		$name=$v['name'];
		$cn[$name]=$v['value'];
	}
	if($type=='wap_alipay')
	{
		$url = $config['weburl']."/module/payment/lib/wap_alipay/call_back_url.php";
		$cn['partner'] = $cn['wap_alipay_partner'];
		$cn['key'] = $cn['wap_alipay_key'];
		$cn['seller_email'] = $cn['wap_alipay_seller_email'];

		$cn["private_key_path"] = $config['webroot'].'\module\payment\lib\wap_alipay\key\rsa_private_key.pem';
		$cn["ali_public_key_path"] = $config['webroot'].'\module\payment\lib\wap_alipay\key\alipay_public_key.pem';
		$cn['sign_type']    = 'MD5';
		$cn['input_charset']= 'utf-8';
		$cn['transport']    = 'http';
		$cn["cacert"]       = $config['webroot'].'\module\payment\lib\wapalipay\cacert.pem';
		$cn['return_url']   = $url;
		$cn['notify_url']   = $url;
	}
	return $cn;
}
?>