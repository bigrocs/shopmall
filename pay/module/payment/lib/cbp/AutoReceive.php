<?php
include_once("../../../../includes/global.php");
include_once("../../includes/plugin_pay_class.php");

$val=$_POST?$_POST:$_GET;

$pay=new pay();
$configs = $pay->get_pay_config('cbp');

$key		 = $configs['cbp_key'];
//****************************************	//MD5密钥要跟订单提交页相同，如Send.asp里的 key = "test" ,修改""号内 test 为您的密钥
											//如果您还没有设置MD5密钥请登陆我们为您提供商户后台，地址：https://merchant3.chinabank.com.cn/
	//$key='test';							//登陆后在上面的导航栏里可能找到“资料管理”，在资料管理的二级导航栏里有“MD5密钥设置”
											//建议您设置一个16位以上的密钥或更高，密钥最多64位，但设置16位已经足够了
//****************************************

$v_oid     =trim($_POST['v_oid']);      
$v_pmode   =trim($_POST['v_pmode']);      
$v_pstatus =trim($_POST['v_pstatus']);      
$v_pstring =trim($_POST['v_pstring']);      
$v_amount  =trim($_POST['v_amount']);     
$v_moneytype  =trim($_POST['v_moneytype']);     
$remark1   =trim($_POST['remark1' ]);     
$remark2   =trim($_POST['remark2' ]);     
$v_md5str  =trim($_POST['v_md5str' ]);     
/**
 * 重新计算md5的值
 */

$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //拼凑加密串
if ($v_md5str==$md5string)
{
	
   if($v_pstatus=="20")
	{
	   //支付成功
		//商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）......

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
  echo "ok";
	
}else{
	echo "error";
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