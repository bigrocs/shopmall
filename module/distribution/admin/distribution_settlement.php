<?php
include_once("../includes/page_utf_class.php");
if (file_exists("$config[webroot]/module/product/lang/cn.php"))
{
	include("$config[webroot]/module/product/lang/cn.php");
}//#调用模块语言包

include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");

$distribution = new distribution();

$distribution_user_settlement_id = intval($_GET['distribution_user_settlement_id']);

if ($distribution_user_settlement_id)
{
	$dist_user_settlement_row = $distribution->getDistributionUserSettlementById($distribution_user_settlement_id);


	if ($dist_user_settlement_row)
	{
		$distribution->editDistributionUserSettlement($distribution_user_settlement_id, 1);

		$user_id   = $dist_user_settlement_row['user_id'];

		$sql = 'SELECT * FROm mallbuilder_member WHERE userid = "' . $user_id . '"';
		$db->query($sql);
		$t_re = $db->fetchRow();

		$user_name = $t_re['user'];

		$distribution_user_settlement_amount = $dist_user_settlement_row['distribution_user_settlement_amount'];
		//新的接口  api
		// 9

		$order_id = $dist_user_settlement_row['distribution_user_settlement_id'];

		//支付宝接口相关
		$post['action']       = 'add';//填加流水
		$post['type']         = 2;//担保接口
		$post['seller_email'] = $user_id;//卖家账号
		$post['buyer_email']  = $user_id;//卖家账号
		$post['order_id']     = $order_id;//外部订单号
		$post['price']        = $distribution_user_settlement_amount * 1;//订单总价，单价元
		$post['extra_param']  = '';//自定义参数，可存放任何内容
		$post['return_url']   = $config['weburl'] . '/api/dist_settlement.php?id=' . $order_id;//返回地址
		$post['notify_url']   = $config['weburl'] . '/api/dist_settlement.php?id=' . $order_id;//异步返回地址
		$post['name']         = " 分销佣金提现 ";
		$post['mold']         = 9;

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
	}
}

$page_str = true;
$level    = null;

if (isset($_GET['level']))
{
	$level = intval($_GET['level']);
}

$dist_user_settlement_rows = $distribution->getDistributionUserSettlement(null, $level, $page_str);

foreach ($dist_user_settlement_rows as $k => $v)
{
	$dist_user_settlement_rows[$k]['state_label'] = $distribution->getDistributionUserSettlementState($v['distribution_user_settlement_state']);
}

include_once("templates/distribution_settlement.htm");
?>
