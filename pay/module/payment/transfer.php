<?php
if($_POST['act']=='transfer')
{
	//确认支付密码
	if ($de['pay_pass'] != md5(trim($_POST['pay_passwd'])))
	{
		echo '<script type="text/javascript">window.onload=function(){alert("支付密码错误!");history.go(-1);}</script>';//返回上一步操作
		die();
	}


	$email=$_POST['email']?$_POST['email']:NULL;
	$amount=$_POST['amount']?$_POST['amount']:NULL;
	$reason=$_POST['reason']?$_POST['reason']:"付款";
	unset($_POST);
	if($email&&is_numeric($amount))
	{
		if($amount<=$de["cash"])
		{
			$sql="select pay_id from ".MEMBER." where pay_email='$email'";
			$db->query($sql);
			$de=$db->fetchRow();	
			
			//$sql="select pay_email from ".MEMBER." where pay_email='$buid'";               bug 转账详情收款方看不到付款方的信息
			$sql="select pay_email from ".MEMBER." where pay_id='$buid'";
			$db->query($sql);
			$re=$db->fetchRow();
			if($de['pay_id'])
			{
				$time = time();
				$flow_id = time();
				
				$sql="insert into ".CASHFLOW." (`pay_uid`,flow_id,buyer_email,seller_email,`price`,`time`,`note`,`type`,`mold`,`statu`) values ('".$buid."','$flow_id','','$email','-$amount','$time','$reason','1','0','4')";
				$db->query($sql);
				
				$sql="insert into ".CASHFLOW." (`pay_uid`,flow_id,buyer_email,seller_email,`price`,`time`,`note`,`type`,`mold`,`statu`) values ('".$de['pay_id']."','$flow_id','$re[pay_email]','','$amount','$time','$reason','1','3','4')";
				$db->query($sql);
				
				$sql="update ".MEMBER." set cash=cash-$amount where pay_id='".$buid."'";
				$db->query($sql);
	
				$sql="update ".MEMBER." set cash=cash+$amount where pay_id='".$de['pay_id']."'";			
				$db->query($sql);
				header("location:$config[weburl]/?m=payment&s=record");
			}
			else
			{
				msg("$config[weburl]/?m=payment&s=transfer","用户不存在。");	
			}
		}
		else
		{
			msg("$config[weburl]/?m=payment&s=transfer","预存款不足。");	
		}
	}
}

$tpl->assign("config",$config);
$output=tplfetch("transfer.htm");
?>