<?php
$pays=$pay->get_payment_type();
$tpl->assign("pay",$pays);
if(!empty($_POST['amount']) || (!empty($_POST['card_num']))&&!empty($_POST['password']))
{
	$pay->online_pay();
}
$tpl->assign("config",$config);
$output=tplfetch("recharge.htm");
?>