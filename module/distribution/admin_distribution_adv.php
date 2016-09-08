<?php
$dist_user_row = $distribution->getDistributionUser($buid);

$tpl->assign("distribution_visit_flag", $distribution_visit_flag);
$tpl->assign("dist_user_row", $dist_user_row);
$tpl->assign("dist_user_total_row", $dist_user_total_row);

$tpl->assign("shop_id", $buid);

$tpl->assign("config", $config);
$tpl->assign("lang", $lang);

if ('add' == $_GET['act'])
{
	$amount  = floatval($_GET['money']);
	$user_id = $buid;

	$order_id = uniqid();
	$flag     = $distribution->addDistributionUserAdv($user_id, $order_id, $amount);

	//支付宝接口相关
	$post['action']       = 'add';//填加流水
	$post['type']         = 2;//担保接口
	$post['seller_email'] = "admin@systerm.com";//卖家账号
	$post['buyer_email']  = $user_id;//卖家账号
	$post['order_id']     = $order_id;//外部订单号
	$post['price']        = $amount * 1;//订单总价，单价元
	$post['extra_param']  = '';//自定义参数，可存放任何内容
	$post['return_url']   = $config['weburl'] . '/api/dist_adv.php?id=' . $order_id;//返回地址
	$post['notify_url']   = $config['weburl'] . '/api/dist_adv.php?id=' . $order_id;//异步返回地址
	$post['name']         = " 推广广告充值 ";
	$post['mold']         = 10;

	$res = pay_get_url($post, true);//跳转至订单生成页面

	if ($res < 0)
	{
		if ($res == -2)
		{
			msg('$config[weburl]/main.php?m=payment&s=admin_info', '您的支付账户还没有开通');
		}
		if ($res == -1)
		{
			msg("$config[weburl]/?m=product&s=confirm_order", '卖家没有开通支付功能，暂不能购买');
		}
	}
	else
	{
		msg($config['pay_url'] . "/?m=payment&s=pay&tradeNo=" . $order_id . "&temp=" . $config['temp']);//直接跳转到支付页面进行支付选择
		die;
	}
}
else if ('list' == $_GET['act'])
{
	$page_str = true;
	$dist_user_adv_rows     = $distribution->getDistributionUserAdv($user_id, null, $page_str);

	foreach ($dist_user_adv_rows as $k => $v)
	{
		$dist_user_adv_rows[$k]['state_label'] = $distribution->getDistributionUserAdvState($v['distribution_user_adv_state']);
	}

	$tpl->assign("dist_user_adv_rows", $dist_user_adv_rows);
	$tpl->assign("page", $page_str);
}

if ($_GET['log'])
{
	$output = tplfetch("admin_distribution_adv_log.htm");
}
else
{
   
	$output = tplfetch("admin_distribution_adv.htm");
}
?>