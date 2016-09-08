<?php
//提现操作
$dist_user_row = $distribution->getDistributionUser($buid);
$dist_user_total_row = $distribution->getDistributionChildUserNum($buid);


$dist_user_row['distribution_user_amount'] =  $dist_user_row['distribution_shop_amount_0'] +  $dist_user_row['distribution_shop_amount_1'] +  $dist_user_row['distribution_shop_amount_2']
	+ $dist_user_row['distribution_click_amount_0'] +  $dist_user_row['distribution_click_amount_1'] +  $dist_user_row['distribution_click_amount_2']
	+ $dist_user_row['distribution_reg_amount_0'] +  $dist_user_row['distribution_reg_amount_1'] +  $dist_user_row['distribution_reg_amount_2']
	+ $dist_user_row['distribution_buy_amount_1'] + $dist_user_row['distribution_buy_amount_2'];

$dist_user_row['distribution_user_unsettlement_amount'] = $dist_user_row['distribution_user_amount'] - $dist_user_row['distribution_user_settlement_amount'];



if (isset($_POST['distribution_user_settlement_value']) && $_POST['distribution_user_settlement_value'] >= $distribution_config['distribution_commission_min'])
{
	$distribution_user_settlement_value = floatval($_POST['distribution_user_settlement_value']);

	if ($distribution_user_settlement_value>=1 && $distribution_user_settlement_value <= $dist_user_row['distribution_user_unsettlement_amount'])
	{
		$distribution->editDistributionSettlementAmount($buid, $distribution_user_settlement_value, $dist_user_row['distribution_user_settlement_amount']);
		$distribution->addDistributionUserSettlement($buid, $distribution_user_settlement_value);


		msg("$config[weburl]/main.php?cg_u_type=2&st=0", "结算申请已经提交完成！");
	}
	else
	{
		msg("$config[weburl]/main.php?cg_u_type=2&st=0", "结算申请已经提交失败！");
	}

	die();
}
else
{
	if (isset($_POST['distribution_user_settlement_value']))
	{
		msg("$config[weburl]/main.php?cg_u_type=2&st=0", "结算申请已经提交失败！" . "每次结算最小额度为: {$config['money']}" . $distribution_config['distribution_commission_min']);
	}
}


if (isset($_GET['level']))
{
	$state = intval($_GET['level']);
}
else
{
	$state = null;
}

$dist_user_settlement_rows = $distribution->getDistributionUserSettlement($buid, $state);


foreach ($dist_user_settlement_rows as $k => $v)
{
	$dist_user_settlement_rows[$k]['state_label'] = $distribution->getDistributionUserSettlementState($v['distribution_user_settlement_state']);
}


$tpl->assign("dist_user_settlement_rows", $dist_user_settlement_rows);
$tpl->assign("distribution_commission_min", $distribution_config['distribution_commission_min']);


$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_distribution_settlement.htm");
?>