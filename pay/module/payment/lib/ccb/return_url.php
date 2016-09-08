<?php
include_once("../../../../includes/global.php");
//=================================
$val=$_POST?$_POST:$_GET;

//error_log(var_export($val,true),3,__FILE__.'.log');

$configs = get_pay_config('ccb');
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

if($strRet=='Y'&&$SUCCESS=='Y')
{
	set_result($ORDERID,$ORDERID,$PAYMENT,"建设银行",$REMARK1);
}
else
{
	die("支付失败 请联系管理员");	
}
function get_pay_config($type)
{
	global $db,$config;
	$sql = "select * from ".PAYMENT." where payment_type='$type'";
	$db -> query($sql);
	$re = $db -> fetchRow();
	$re = unserialize($re['payment_config']);
	foreach($re as $v)
	{
		$name = $v['name'];
		$cn[$name] = $v['value'];
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
		$sql="update ".RECORD." set price='$total_fee',flow_id='$payflowid',statu='4' where id='$tradeno'";
		$db->query($sql);
		
		$sql="update ".MEMBER." set cash=cash+$total_fee where pay_id ='$userid'";
		$db->query($sql);
		
		if($extra_common_param)
		{
			$order_id=$extra_common_param;
			//----------------当前流水详情	
			$sql="select * from ".RECORD." where order_id='$order_id' and pay_uid='$userid'";
			$db->query($sql);
			$de=$db->fetchRow();
			//----------------减少账户金额
			if($de['price']<0) $de['price']*=-1;
			$sql = "update ".MEMBER." set cash=cash-".$de['price']." where pay_id='$userid'";
			$db->query($sql);
			//----------------直接到账，修改流水状态,直接标记为成功
			if($de['type']==1)
			{		
				$sql="update ".RECORD." set statu='4' where order_id='$order_id'";
				$db->query($sql);
				
				$sql = "update ".MEMBER." set cash=cash+".($de['price']*-1)." where email='$de[seller_email]'";
				$db->query($sql);
			}
			//----------------担保接口，标记为已付款。
			if($de['type']==2)
			{
				$sql="update ".RECORD." set statu='2' where order_id='$order_id'";
				$db->query($sql);
			}
			//----------------异步处理,以后处理 返回同步处理结果
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
			$sql="select return_url from ".RECORD." where order_id='$order_id' and pay_uid='$userid'";
			$db->query($sql);
			$de=$db->fetchRow();
			msg($de['return_url']);
		}
		else
			msg("?m=payment&s=record&mold=1");
	}
}

?>