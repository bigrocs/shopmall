<?php

/**
* 银联接口同步返回文件；
* Auth: Bruce
*/
include_once("../includes/global.php");
include_once("../includes/php_rsa.php");

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
	
	return $cn;
}

$configs = get_pay_config('anion_pay');
$ret = TRUE;
$merchantId="";
$version="";
$language="";
$signType="";
$payType="";
$issuerId="";
$paymentOrderId="";
$orderNo="";
$orderDatetime="";
$orderAmount="";
$payDatetime="";
$payAmount="";
$ext1="";
$ext2="";
$payResult="";
$errorCode="";
$returnDatetime="";
$signMsg="";

$strPKey	= $configs['anion_pay_key'];


//接受银联在线返回数据
	//----------------------------------------------------------------------------------------
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
		$publickeyfile = '../module/payment/lib/allinpay/publickey.txt';
		$publickeycontent = file_get_contents($publickeyfile);
		//echo "<br>".$content;
		$publickeyarray = explode(PHP_EOL, $publickeycontent);
		$publickey = explode('=',$publickeyarray[0]);
		$publickey[1]=trim($publickey[1]);
		$modulus = explode('=',$publickeyarray[1]);
		$modulus[1]=trim($modulus[1]);
	
	$keylength = 1024;
	//验签结果
 	$verifyResult = rsa_verify($bufSignSrc,$signMsg, $publickey[1], $modulus[1], $keylength,"sha1");
	
	$verify_Result = null;
	$pay_Result = null;
	if($verifyResult){
		$verify_Result = "报文验签成功!";
		if($payResult == 1){
			$pay_Result = "订单支付成功!";
			set_result($orderNo,$paymentOrderId,$payAmount,"国际银联支付",$ext1);
		}else{
			$pay_Result = "订单支付失败!";
			die("error");
		}
	}else{
		$verify_Result = "报文验签失败!";
		$pay_Result = "因报文验签失败，订单支付失败!";
		die("error");
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
	$order_id = $extra_common_param;
	$total_fee = $total_fee/100;

	//如果验证成功,并且流水表中的记录为新提交
	if($statu==1)
	{	
		$sql="update ".RECORD." set price='$total_fee',flow_id='$payflowid',statu='4' where id='$tradeno'";
		$db->query($sql);
		
		$sql="update ".MEMBER." set cash=cash+$total_fee where pay_id ='$userid'";
		$db->query($sql);
			
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
			
              updateorders($order_id,$tradeno,$type);


			//--异步处理,以后处理
			//返回同步处理结果
			$url=$de['return_url']."&type=$type&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&tradeno=$tradeno&auth=".md5($config['authkey']);
			msg($url);
	
		msg("?m=payment&s=record&mold=1");
	}
	else
	{
			//----------------当前流水详情	
			$sql="select * from ".RECORD." where order_id='$order_id' and pay_uid='$userid'";
			$db->query($sql);
			$de=$db->fetchRow();
			

			$url=$de['return_url']."&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
			msg($url);
		}

	}




//statu 表示状态 等于1
//auth 验证码
function  updateorders($id,$tradeno,$type){
//start

    global $db ;
	global $config ;

	define("ORDER","mallbuilder_product_order");//订购模块表
	define("ORPRO","mallbuilder_product_order_pro");//订购产品表
	define("PRODUCT","mallbuilder_product");//商品
	define("SETMEAL","mallbuilder_product_setmeal");//产品套餐表
	define("MAILMOD","mallbuilder_mail_mod");//邮件模板

	$sql = "select `status`,`pid` from ".ORPRO." where order_id='$id'";
	$db -> query($sql);
	$t_re = $db -> fetchRow();
	$status = $t_re['status'];
	$pid = $t_re['pid'];


	$sql = "select `is_virtual`,`consignee_mobile` from ".ORDER." where  order_id='$id'";
	$db -> query($sql);
	$t_res = $db -> fetchRow();
	$is_virtual = $t_res['is_virtual'];
	$mobile =  $t_res['consignee_mobile'];

	if($status < 2)
	{
		//---------------------付款成功减库存，
		$sql="select pid,num,setmeal from ".ORPRO." where order_id='$id'";
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

		$sql="update ".ORDER." set status='2',`out_trade_no` = '$tradeno' ,payment_name='$type',payment_time=".time()." where order_id='$id'";
		$db->query($sql);
		$sql="update ".ORPRO." set status='2' where order_id='$id'";
		$db->query($sql);	
	    } 
        

		// 虚拟商品订单 发送短信
		if($is_virtual)
		{
			$sql = "select message from ".MAILMOD." where `flag` ='v-order-msg' and type=2 ";
			$db -> query($sql);
			$me = $db -> fetchField("message");

			$sql = "select a.`start_time`,b.`end_time`,a.name from ".PRODUCT." a left join ".PROVIR." b on a.id = b.pid where a.id =".$pid;
			$db -> query($sql);
			$ttime = $db -> fetchRow();

			$serial = randomkeys();
			$me = str_replace("[webname]", $config['company'], $me);
			$me = str_replace("[serial]", $serial, $me);
			$me = str_replace("[stime]", date("Y-m-d",$ttime['start_time']), $me);
			$me = str_replace("[etime]", date("Y-m-d",$ttime['end_time']), $me);
			$me = str_replace("[order_id]", $_GET['id'], $me);
			$me = str_replace("[time]", date("Y-m-d"), $me);
			$me = str_replace("[product_name]", $ttime['name'], $me);

			$sql = "insert into ".MSGCORD." (`msg`,`count`,`create_time`,`update_time`,`serial`,`order_id`) values ('$me',1,'".time()."','".time()."','$serial','$_GET[id]') ";
			$db -> query($sql);
			//$id = $db -> lastid(); // 后期考虑是否做接口
			$url = $config['weburl']."/api/msg.php?mobile=".$mobile."&me=".urlencode($me);
			$str = file_get_contents($url);

			$str = "短信将于10分钟之内发至您的手机，请注意查收，如果没有收到短信请在个人中心，我的虚拟订单中点击再次发送按钮。";
		}
        
}





?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="zh-CN"/>
		<meta http-equiv="Expires" content="0" />        
		<meta http-equiv="Cache-Control" content="no-cache" />        
		<meta http-equiv="Pragma" content="no-cache" />
		<title>通联网上支付平台-商户接口范例-支付结果</title>
		<link href="css.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<center> <font size=16><strong>支付结果</strong></font></center>
<div style="padding-left:40px;">			
			<div>验证结果：<?php echo $verify_Result?></div>
			<div>支付结果：<?php echo $pay_Result?></div>
			<hr/>
			<div>商户号：<?php echo $merchantId ?> </div>
			<div>商户订单号：<?php echo $orderNo ?> </div>
			<div>商户订单金额：<?php echo $orderAmount ?></div>
			<div>商户订单时间：<?php echo $orderDatetime ?> </div>
			<div>网关支付金额：<?php echo $payAmount ?></div>
			<div>网关支付时间：<?php echo $payDatetime ?></div>

	</div>	
 </body>
</html>
