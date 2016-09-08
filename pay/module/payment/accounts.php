<?php
//=================================
$val=$_POST?$_POST:$_GET;
//-------返回应用充值结果
//error_log(var_export($val,true),3,__FILE__.'.log');
if(!empty($_GET['onlinepaytype'])&&$_GET['onlinepaytype']=='ccb')
{
	unset($_GET['s']);
	unset($_GET['m']);
	unset($_GET['onlinepaytype']);
 
	$configs = $pay -> get_pay_config('ccb');
	$strPubKey = $configs['ccb_key'];
	$strSign = $val["SIGN"];
	
	$POSID 		= 	$val["POSID"];
	$BRANCHID 	= 	$val["BRANCHID"];
	$ORDERID 	= 	$val["ORDERID"];
	$PAYMENT 	= 	$val["PAYMENT"];
	$CURCODE 	= 	$val["CURCODE"];
	$REMARK1 	= 	$val["REMARK1"];
	$REMARK2	= 	$val["REMARK2"];
	$ACC_TYPE 	= 	$val["ACC_TYPE"];
	$SUCCESS 	= 	$val["SUCCESS"];
	$ACCDATE 	= 	$val["ACCDATE"];
	
	$str = $ACC_TYPE ? "&ACC_TYPE=$ACC_TYPE" : "";
	
	$strSrc = "POSID=$POSID&BRANCHID=$BRANCHID&ORDERID=$ORDERID&PAYMENT=$PAYMENT&CURCODE=$CURCODE&REMARK1=$REMARK1&REMARK2=$REMARK2$str&SUCCESS=$SUCCESS";
	
	if($configs["ccb_type"]=='2')
	{
		$TYPE 		= 	$val["TYPE"];
		$REFERER 	= 	$val["REFERER"];
		$CLIENTIP 	= 	$val["CLIENTIP"];
		//$USRMSG 	= 	$val["USRMSG"];
		$strSrc.= "&TYPE=$TYPE&REFERER=$REFERER&CLIENTIP=$CLIENTIP";
	}
	if($ACCDATE)
		$strSrc.= "&ACCDATE=$ACCDATE";
	
	$rsasig = new COM("CCBRSA.RSASig");
	$rsasig -> setpublickey($strPubKey);
	$strRet = $rsasig -> StringVerifySigature($strSign,$strSrc);

	echo $strRet;die;
	if($strRet=='Y'&&$SUCCESS=='Y')
	{
		echo "asd";
		set_result($ORDERID,$ORDERID,$PAYMENT,"建设银行",$REMARK1);
	}
	else
	{
		die("支付失败 请联系管理员");	
	}
}

/******************
*****新增开始******
*******************/
if(!empty($_GET['onlinepaytype'])&&$_GET['onlinepaytype']=='anion_pay')
{
	unset($_GET['s']);
	unset($_GET['m']);
	unset($_GET['onlinepaytype']);
	
	//删除原有参数，不然会影响他原来的值
	//$sign=$val['sign'];
	$tradeno=$val['orderNo'];//商户订单号
	$payflowid=$val['paymentOrderId'];//通联订单号
	$suc=$val['is_success']=='T'?true:false;
	$total_fee=$val['orderAmount']/100;//商户订单金额
	$extra_common_param=$val['ext1'];//自定义参数

	$configs=$pay -> get_pay_config('anion_pay');

	require_once($config['webroot']."/module/payment/lib/allinpay/publickey.txt");
	$merchantId=$_POST["merchantId"];
	$version=$_POST['version'];
	$language=$_POST['language'];
	$signType=$_POST['signType'];
	$payType=$_POST['payType'];
	$issuerId=$_POST['issuerId'];
	$paymentOrderId=$_POST['paymentOrderId'];
	$orderNo=$_POST['orderNo'];
	$orderDatetime=$_POST['orderDatetime'];
	$orderAmount=$_POST['orderAmount'];
	$payDatetime=$_POST['payDatetime'];
	$payAmount=$_POST['payAmount'];
	$ext1=$_POST['ext1'];
	$ext2=$_POST['ext2'];
	$payResult=$_POST['payResult'];
	$errorCode=$_POST['errorCode'];
	$returnDatetime=$_POST['returnDatetime'];
	$signMsg=$_POST["signMsg"];
	
	
	$bufSignSrc="";
	if($merchantId != "")
	$bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
	if($version != "")
	$bufSignSrc=$bufSignSrc."version=".$version."&";		
	if($language != "")
	$bufSignSrc=$bufSignSrc."language=".$language."&";		
	if($signType != "")
	$bufSignSrc=$bufSignSrc."signType=".$signType."&";		
	if($payType != "")
	$bufSignSrc=$bufSignSrc."payType=".$payType."&";
	if($issuerId != "")
	$bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
	if($paymentOrderId != "")
	$bufSignSrc=$bufSignSrc."paymentOrderId=".$paymentOrderId."&";
	if($orderNo != "")
	$bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
	if($orderDatetime != "")
	$bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
	if($orderAmount != "")
	$bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
	if($payDatetime != "")
	$bufSignSrc=$bufSignSrc."payDatetime=".$payDatetime."&";
	if($payAmount != "")
	$bufSignSrc=$bufSignSrc."payAmount=".$payAmount."&";
	if($ext1 != "")
	$bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
	if($ext2 != "")
	$bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
	if($payResult != "")
	$bufSignSrc=$bufSignSrc."payResult=".$payResult."&";
	if($errorCode != "")
	$bufSignSrc=$bufSignSrc."errorCode=".$errorCode."&";
	if($returnDatetime != "")
	$bufSignSrc=$bufSignSrc."returnDatetime=".$returnDatetime;
	//验签
	//解析publickey.txt文本获取公钥信息
	$publickeyfile = $config['webroot'].'/module/payment/lib/allinpay/publickey.txt';
		$publickeycontent = file_get_contents($publickeyfile);
		//echo "<br>".$content;
		$publickeyarray = explode(PHP_EOL, $publickeycontent);
		$publickey = explode('=',$publickeyarray[0]);
		//去掉publickey[1]首尾可能的空字符
		$publickey[1]=trim($publickey[1]);
		$modulus = explode('=',$publickeyarray[1]).trim();
		//去掉modulus[1]首尾可能的空字符
		$modulus[1]=trim($modulus[1]);
		//echo "<br>publickey=".$publickey[1];
		//echo "<br>modulus=".$modulus[1];
	
	$keylength = 1024;
	//验签结果
 	$verify_result = rsa_verify($bufSignSrc,$signMsg, $publickey[1], $modulus[1], $keylength,"sha1");
	
	
	$value = null;
	if($verify_result){
		$value = "报文验签成功！";
	}
	else{
		$value = "报文验签失败！";
	}
	
	//验签成功，还需要判断订单状态，为"1"表示支付成功。
	$payvalue = null;
	$pay_result = false;
	if($verify_result and $payResult == 1){
		$pay_result = true;
			set_result($tradeno,$payflowid,$total_fee,"通联支付",$extra_common_param);
	}else{
		echo "支付失败";
	}
}

/******************
*****新增结束******
*******************/

if(!empty($_GET['onlinepaytype'])&&$_GET['onlinepaytype']=='bc')
{
	unset($_GET['s']);
	unset($_GET['m']);
	unset($_GET['onlinepaytype']);
	
	$configs=$pay -> get_pay_config('bc');
	
	include_once($config['webroot']."/module/payment/lib/bc/pkcs7/boc.class.php");
	$pays = new boc($configs["bc_key"]);
	$pays->cert = $config['webroot']."/module/payment/lib/bc/pkcs7/cert/cert1.pem";
	$pays->privateKey = $config['webroot']."/module/payment/lib/bc/pkcs7/cert/key1.pem";

	if($val)
	{
		//merchantNo|orderNo|orderSeq|cardTyp|payTime|orderStatus|payAmount
		$unsignData = $val['merchantNo']."|".$val['orderNo']."|".$val['orderSeq']."|".$val['cardTyp']."|".$val['payTime']."|".$val['orderStatus']."|".$val['payAmount'];
	
		if($pays->verifyFormStr($val['signData'],$unsignData)){
			set_result($val['orderNo'],$val['orderSeq'],$val['payAmount'],"中国银行",$_GET['attach']);
		}else{
			//print_r($pay->dnData);
			die("支付失败 请联系管理员");
		}
	}
}
if(!empty($_GET['onlinepaytype'])&&$_GET['onlinepaytype']=='alipay')
{
	unset($_GET['s']);
	unset($_GET['m']);
	unset($_GET['onlinepaytype']);
	
	//删除原有参数，不然会影响他原来的值
	$sign=$val['sign'];
	$tradeno=$val['out_trade_no'];//站内流水ID
	$payflowid=$val['trade_no'];//支付宝交易号
	$suc=$val['is_success']=='T'?true:false;
	$total_fee=$val['total_fee'];
	$extra_common_param=$val['extra_common_param'];

	$configs=$pay -> get_pay_config('alipay');

	require_once($config['webroot']."/module/payment/lib/alipay/lib/alipay_notify.class.php");

	$alipayNotify = new AlipayNotify($configs);
	
	$verify_result = $alipayNotify->verifyReturn();
	
	if($verify_result&&$suc)
	{
		set_result($tradeno,$payflowid,$total_fee,"支付宝",$extra_common_param);
	}
}
if(!empty($_GET['onlinepaytype'])&&$_GET['onlinepaytype']=='tenpay')
{
	require_once($config['webroot']."/module/payment/lib/tenpay/classes/PayResponseHandler.class.php");
	$configs=$pay->get_pay_config('tenpay');
	$key = $configs['tenpay_key'];/* 密钥 */
	$resHandler = new PayResponseHandler();/* 创建支付应答对象 */
	$resHandler->setKey($key);	//判断签名
	if($resHandler->isTenpaySign())
	{
		$payflowid = $resHandler->getParameter("transaction_id");//交易单号
		$tradeno= $resHandler->getParameter("sp_billno");//站内流水ＩＤ
		$total_fee = $resHandler->getParameter("total_fee")/100;//金额,以分为单位
		$pay_result = $resHandler->getParameter("pay_result");//支付结果
		$extra_common_param = $resHandler->getParameter("attach");
		if( "0" == $pay_result )
		{
			set_result($tradeno,$payflowid,$total_fee,"财付通",$extra_common_param);
		}
	}	
}
if(!empty($_GET['onlinepaytype'])&&$_GET['onlinepaytype']=='cbp')
{
	unset($_GET['s']);
	unset($_GET['m']);
	unset($_GET['onlinepaytype']);
	
	$configs = $pay->get_pay_config('cbp');
	
	$key		 = $configs['cbp_key'];
	$v_oid		 = trim($val['v_oid']);			//商户发送的v_oid定单编号   
	$v_pmode	 = trim($val['v_pmode']);		//支付方式（字符串）   
	$v_pstatus	 = trim($val['v_pstatus']);		//支付状态 ：20（支付成功）；30（支付失败）
	$v_pstring	 = trim($val['v_pstring']);		//支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
	$v_amount	 = trim($val['v_amount']);     //订单实际支付金额
	$v_moneytype = trim($val['v_moneytype']);  //订单实际支付币种    
	$remark1     = trim($val['remark1']);      //备注字段1
	$remark2     = trim($val['remark2']);      //备注字段2
	$v_md5str    = trim($val['v_md5str']);     //拼凑后的MD5校验值  
	
	$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
	
	if ($v_md5str == $md5string)
	{
		if($v_pstatus=="20")
		{
			set_result($v_oid,$v_oid,$v_amount,"网银支付",$remark1);
		}
		else
		{
			echo "支付失败";
		}
	}
	else
	{
		echo "<br>校验失败,数据可疑";
	}
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
			msg($url);
		}
		else
			msg("?m=payment&s=record&mold=1");
	}
}



?>