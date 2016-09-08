<?php
error_reporting(E_ERROR);

include_once("../../includes/plugin_pay_class.php");
include_once("../../../../includes/global.php");

require_once "../WxPayPubHelper/WxPay.Api.php";
require_once '../WxPayPubHelper/WxPay.Notify.php';

Yf_Log::log('Request : ' . json_encode($_REQUEST), Yf_Log::INFO, 'wx_qr');

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Yf_Log::log("query:" . json_encode($result), Yf_Log::INFO, 'wx_qr');
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Yf_Log::log("call back:" . json_encode($data), Yf_Log::INFO, 'wx_qr');
		$notfiyOutput = array();

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}



		$tradeno    = $data['out_trade_no'];//站内流水ID
		$payflowid  = $data['transaction_id'];//交易号
		$total_fee  = $data['cash_fee'] / 100;
		$extra_common_param=$data['attach'];


		set_result($tradeno,$payflowid,$total_fee,"微信扫码",$extra_common_param);

		return true;
	}
}

Yf_Log::log('start', Yf_Log::INFO, 'wx_qr');

$notify = new PayNotifyCallBack();
$notify->Handle(false);

Yf_Log::log('GetReturn_code:' . $notify->GetReturn_code(), Yf_Log::INFO, 'wx_qr');
Yf_Log::log('GetReturn_msg:' . $notify->GetReturn_msg(), Yf_Log::INFO, 'wx_qr');

//

//处理业务逻辑


function set_result($tradeno,$payflowid,$total_fee,$type,$extra_common_param)
{
	global $db,$config;


	$cache_dir = $config['webroot'] . '/cache/wx_qr/';

	if (!file_exists($cache_dir))
	{
		mkdir($cache_dir, 0777, true);
	}

	//设置cache 参数
	//cacheType 1:file  2:memcache   3：redis
	$config_cache['wx_qr'] = array(
		'cacheType' => 1,
		'cacheDir' => $cache_dir,
		'memoryCaching' => false,
		'automaticSerialization' => true,
		'hashedDirectoryLevel' => 3,
		'hashedDirectoryUmask' => 0777,
		'cacheFileMode' => 0777,
		'lifeTime' =>  86400
	);

	$qr_cache = new Cache_Lite_Output($config_cache['wx_qr']);


	$qr_cache->save($tradeno, $tradeno);



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
