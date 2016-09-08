<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");

include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");

$distribution = new distribution();


if (!empty($_GET['statu']) && $_GET['statu'] == 1)
{
	if ($_GET['auth'] != md5($config['authkey']))
	{
		die('参数错误');
	}

	$order_id = $_GET['id'];

	$distribution = new distribution();

	$adv_row = $distribution->getDistributionUserAdvById($order_id);

	$user_id                    = $adv_row['user_id'];
	$distribution_adv_money_add = $adv_row['distribution_user_adv_money'];

	$flag = $distribution->editDistributionUserAdv($order_id, 1, 0);

	//更新状态，发放广告费
	if ($flag)
	{
		$distribution->editDistributionAdvAmount($user_id, $distribution_adv_money_add);

	}
	else
	{
	}

	$str = "广告费充值成功！";
	$url = $config['weburl'] . "/main.php?m=distribution&s=admin_distribution_settlement";

	msg($url, $str);
	
	exit;
}
else
{
	$url = $config['weburl'] . "/main.php?m=distribution&s=admin_distribution_settlement";
	msg($url);
}

?>