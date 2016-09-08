<?php
 session_start();
$order_id=$_GET['tradeNo']?$_GET['tradeNo']:NULL;


$sql="select a.*,b.real_name from ".RECORD." a left join ".MEMBER." b on a.pay_uid=b.pay_id 	 where order_id='$order_id' and pay_uid='$buid'";
$db->query($sql);
$re=$db->fetchRow();
$re['price']=$re['price']<0?($re['price']*(-1)):$re['price'];
$re_wx = $re;
$tpl->assign("re",$re);
$s=" and payment_type!='cards' ";

if($config['bw'] == "weixin")
{
//========= 调用微信支付===========================
/**
 * 
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	include_once("./module/payment/lib/WxPayPubHelper/WxPayPubHelper.php");
	//使用jsapi接口
	$jsApi = new JsApi_pub();
	//=========步骤1：通过code获得openid============
	$openid = $_SESSION['openid_f'];
	//=========步骤2：使用统一支付接口，获取prepay_id============
	$unifiedOrder = new UnifiedOrder_pub();
	
	$unifiedOrder->setParameter("openid",$openid);//商品描述
	$unifiedOrder->setParameter("body",$re_wx['note']);//商品描述
	//自定义订单号，此处仅作举例

	$timeStamp = time();
	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号 
	$unifiedOrder->setParameter("total_fee",$re_wx['price']*100);//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	$unifiedOrder->setParameter("attach",$re_wx['id']."|".$order_id);//附加数据 

	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);
	$jsApiParameters = $jsApi->getParameters();
	$tpl->assign("jsApiParameters",json_decode($jsApiParameters,true));
    $s.=" and payment_type != 'wap_alipay'";
}

if($re['price'] > $de['cash'])
{
	if($config['temp'] == 'wap'||$config['temp'] == 'wap_app')
		/********通联支付手机端修改*********/
		$s.=" and (payment_type = 'wap_alipay' or payment_type='anion_pay')";
	else
		$s.=" and payment_type!='account' and payment_type != 'wap_alipay' and payment_type != 'wx_pay'";
	$tpl->assign("account","false");
}
else
{
	if($config['temp'] == 'wap'||$config['temp'] == 'wap_app')
		/********通联支付手机端修改*********/
		$s.=" and( payment_type = 'account' or payment_type = 'wap_alipay' or payment_type='anion_pay') ";
	else
		$s.=" and payment_type != 'wap_alipay' and payment_type != 'wx_pay'";
}
$sql = "select * from ".PAYMENT." where active=1 $s order by payment_id";

$db->query($sql);
$pays=$db->getRows();

if($config['bw'] == "weixin")
{

	foreach ($pays as $k => $v)
	{
		if ('wx_qr' == $v['payment_type'])
		{
			unset($pays[$k]);
		}
	}
}
else
{
	foreach ($pays as $k => $v)
	{
		if ('wx_pay' == $v['payment_type'])
		{
			unset($pays[$k]);
		}

		if ($config['temp'] == 'wap'|| $config['temp'] == 'wap_app')
		{
			if ('wx_qr' == $v['payment_type'])
			{
				unset($pays[$k]);
			}
		}
	}
}

$tpl->assign("pay",$pays);


if($_POST['act']=='pay'&&$re['statu']==1)
{
	if($_POST['payment_type']=='account')
	{
		if($de['pay_pass']!=md5(trim($_POST['password'])))
		{
			msg("$config[weburl]/?m=payment&s=pay&tradeNo=$order_id",'支付密码错误');die;
		}
        $sql = "select cash from ".MEMBER." where pay_id='$re[pay_uid]'";
        $db->query($sql);
        $mecash = $db->fetchField('cash');
        if(($mecash-$re['price'])*1<0){
            msg("$config[weburl]/?m=payment&s=pay&tradeNo=$order_id",'余额不足');die;
        }
	}
	if($_POST['payment_type']=='account')
	{
		$sql = "update ".MEMBER." set cash= cash - ".$re['price']." where pay_id='$re[pay_uid]'";
		$db->query($sql);
		
		if($re['type']==2)
		{
			$sql = "update ".MEMBER." set unreachable=unreachable+".$re['price']." where pay_id='$re[pay_id]'";
			$db->query($sql);

			// 合并付款
			if(substr($order_id, 0,1) == "U")
			{
				$sql = "select `extra_param` from ".RECORD." where `order_id` = '".$order_id."' and  `buyer_email` IS NOT NULL";
				$db -> query($sql);
				$oid = $db -> fetchField("extra_param");

				$sql = "update ".RECORD." set statu='2' where order_id in ($oid)";
				$db->query($sql);

				$sql="update ".RECORD." set statu='-1' and `display` = 0 where order_id='$order_id'";
				$db->query($sql);
			}
			else
			{
				$sql="update ".RECORD." set statu='2' where order_id='$order_id'";
				$db->query($sql);
			}
		}
		//--异步处理,以后处理
		
        if($re['type']==1)
        {
        	$sql="update ".RECORD." set statu='4' where order_id='$order_id'";
			$db->query($sql);
                
            $sql = "update ".MEMBER." set cash= cash + ".$re['price']." where pay_email='admin@systerm.com'";
            $db->query($sql);
            
            
            $auth_key = rand(100,999999).rand(100,999999);
            $_SESSION['auth_key'] = $auth_key;
            $_SESSION['auth_price'] = $re['price'];
            
            $str = md5(md5($auth_key.$re['price']));
            
            //返回同步处理结果
            $url = $re['return_url']."&auth=".md5($config['authkey'])."&price=".$re['price']."&auth_key=".$auth_key."&code=$str";
            msg($url);
		}
        else
        {
            //返回同步处理结果
            $url = $re['return_url']."&type=预存款支付&statu=1&auth=".md5($config['authkey']);
            msg($url);
        }
	}
	else
	{
		$_POST['amount']=$re['price'];
		$_POST['id']=$order_id;
		$_POST['payment_type']=$_POST['payment_type'];
		$pay->online_pay();
		die;
	}
}
$tpl->assign("config",$config);
$output=tplfetch("pay.htm");
?>