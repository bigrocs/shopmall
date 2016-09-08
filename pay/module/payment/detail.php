<?php

$order_id=$_GET['tradeNo']?$_GET['tradeNo']:NULL;

$sql="select a.*,b.real_name from ".RECORD." a left join ".MEMBER." b on a.seller_email=b.pay_email where flow_id='$order_id' and pay_uid='$buid'";
$db->query($sql);
$re=$db->fetchRow();
if($re['mold']=='8')
{
	$sql="SELECT a.*,c.name as supportTimeName FROM ".CASHPICKUP." a left join ".FEE." c on a.supportTime=c.id where a.id='$re[order_id]'";
	$db->query($sql);
	$sss=$db->fetchRow();
	@$re = array_merge($re,$sss);
}
$re['price']=$re['price']<0?($re['price']*(-1)):$re['price'];
$tpl->assign("re",$re);

$tpl->assign("current","record");
$tpl->assign("config",$config);
$output=tplfetch("detail.htm");
?>