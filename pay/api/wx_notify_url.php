<?php
/**
 * 
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
include_once("../includes/global.php");

include_once("../module/payment/log_.php");
include_once("../module/payment/lib/WxPayPubHelper/WxPayPubHelper.php");

//使用通用通知接口
$notify = new Notify_pub();

//存储微信的回调
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
$notify->saveData($xml);

//验证签名，并回应微信。
//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
//尽可能提高通知的成功率，但微信不保证通知最终能成功。
if($notify->checkSign() == FALSE){
	$notify->setReturnParameter("return_code","FAIL");//返回状态码
	$notify->setReturnParameter("return_msg","签名失败");//返回信息
}else{
	$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
}
$returnXml = $notify->returnXml();
echo $returnXml;

//以log文件形式记录回调信息
$log_ = new Log_();
$log_name="./notify_url.log";//log文件路径
$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

if($notify->checkSign() == TRUE)
{
	if ($notify->data["return_code"] == "FAIL") {
		//此处应该更新一下订单状态，商户自行增删操作
		$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
	}
	elseif($notify->data["result_code"] == "FAIL"){
		//此处应该更新一下订单状态，商户自行增删操作
		$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
	}
	else{
		//此处应该更新一下订单状态，商户自行增删操作
		$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");

		$ary = explode("|", $notify->data["attach"]);
        set_result($ary[0],$notify->data["out_trade_no"],($notify->data["total_fee"])/100,"微信支付",$ary[1]);

	}
	//商户自行增加处理流程,
	//例如：更新订单状态
	//例如：数据库操作
	//例如：推送支付完成信息
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

		//error_log(var_export($statu,true),3,__FILE__.'6.log');
		//$sql="update ".RECORD." set price='$total_fee',flow_id='$payflowid',statu='4' where id='$tradeno'";
		//$db->query($sql);
		
		// $sql="update ".MEMBER." set cash=cash+$total_fee where pay_id ='$userid'";
		// $db->query($sql);
		if($extra_common_param)
		{
			$order_id = $extra_common_param;
            if(substr($order_id,0,4)=='fpay'){     //处理友付订单
                $order_id=substr($order_id,4);
                $friend_payed=1;
            }
			//----------------当前流水详情	
			$sql="select * from ".RECORD." where order_id='$order_id' and pay_uid='$userid' and `seller_email` != ''";
			$db->query($sql);
			$de=$db->fetchRow();
			// //-------------减少账户金额
			// if($de['price']<0) $de['price']*=-1;
			// $sql = "update ".MEMBER." set cash=cash-".$de['price']." where pay_id='$userid'";
			// $db->query($sql);
			//直接到账，修改流水状态,直接标记为成功
			if($de['type']==1)
			{
				$sql="update ".RECORD." set statu='4' where order_id='$order_id'";
				$db->query($sql);
				$sql = "update ".MEMBER." set cash=cash+".($de['price']*-1)." where pay_email='$de[seller_email]'";
				$db->query($sql);
			}
			//如果是担保接口，标记为已付款。
			if($de['type']==2)
			{
				$sql="update ".RECORD." set statu='2' where order_id='$order_id'";
				$db->query($sql);
			}
            if($friend_payed){
                $url=$de['return_url']."&type=$type&friend_payed=1&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
            }else{
                $url=$de['return_url']."&type=$type&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
            }
            file_get_contents($url);
		}
	}
}
?>