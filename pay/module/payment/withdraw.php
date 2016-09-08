<?php
$fee=$pay->get_service_fee();
$tpl->assign("fee",$fee);
if($_POST['act']=='withdraw')
{
	//确认支付密码
	if ($de['pay_pass'] != md5(trim($_POST['pay_passwd'])))
	{
		echo '<script type="text/javascript">window.onload=function(){alert("支付密码错误!");history.go(-1);}</script>';//返回上一步操作
		die();
	}

	$amount = $_POST['amount']?$_POST['amount']*1:0;
	$reason = $_POST['reason']?$_POST['reason']:"提款";
	
	$sql = "select * from ".FEE." where id='$_POST[supportTime]'";
	$db->query($sql);
	$sf=$db->fetchRow();
	
	$num=0;
	$price = $amount*($sf['fee_rates']/100);
	
	if($price > 0)
	{
		if($price <= $sf['fee_min'])
		{
			$num = $sf['fee_min'];
		}
		elseif($price >= $sf['fee_max'])
		{
			$num = $sf['fee_max'];
		}
		else
		{
			$num = $price;
		}
	}
	
	if($amount+$num<=$de['cash'])
	{
		$m=$amount+$num;
		//------------减去费用
		$sql = "update ".MEMBER." set cash=cash-".$m." where pay_id='$buid'";
		$db->query($sql);
		
		//------------提现记录表
		$flow_id=date("Ymdhis").rand(0,9);
		$add_time=time();
		$cashflowid=$db->lastid();
		
		$sql = "insert into ".CASHPICKUP." (amount,pay_uid,cashflowid,add_time,bank,cardno,cardname,supportTime,fee,con) values ('".$amount."','".$buid."','$flow_id','$add_time','$_POST[bank]','$_POST[CardNo]','$_POST[CardName]','$_POST[supportTime]','$num','$reason')";
		$db->query($sql);
		$order_id=$db->lastid();
		//------------写进流水账
		$sql="insert into ".CASHFLOW."  (`pay_uid`,`order_id`,`price`,`flow_id`,`note`,`time`,statu,type,mold) values  ('$buid','$order_id','-".$m."','$flow_id','$reason','$add_time',1,1,8)";
		$db->query($sql);
		
		header("location:$config[weburl]/?m=payment&s=record&mold=2");
	}
	else{
		msg("$config[weburl]/?m=payment&s=withdraw","预存款不足。");
	}
	
}
$tpl->assign("re",$re);	
$tpl->assign("config",$config);
$output=tplfetch("withdraw.htm");
?>