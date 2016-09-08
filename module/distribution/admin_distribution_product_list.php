<?php
if(!empty($_GET['deid']))
{
	$product_id = intval($_GET['deid']);
	$user_id    = $buid;

	//未加product_lock_flag判断
	$distribution->removeDistributionProduct($user_id, $product_id);
}

//获取详细产品信息
$dist_rows = $distribution->getDistributionProduct($buid);

//根据商品Id获取商品详情
$produce_id_row = array_filter_key('product_id', $dist_rows);
$dist_pro = $distribution->getProductInfo($produce_id_row);


$dist_p_rows = array();

foreach ($dist_rows as $k => $row)
{
	$dist_p_rows[$row['product_id']] = $row;
}

$distribution_visit_price = $distribution_config['distribution_visit_price'];

foreach ($dist_pro as $k => $row)
{
	$row['product_lock_flag'] = $dist_p_rows[$row['product_id']]['product_lock_flag'];
	$dist_pro[$k] = $row;

	//判断卖家是否有广告费
	$sellder_row = $distribution->getDistributionUser($row['member_id']);

	if ($sellder_row && $sellder_row['distribution_adv_money'] >0)
	{
		$dist_pro[$k]['share_money'] = $distribution_visit_price;
	}
	else
	{
		$dist_pro[$k]['share_money'] = 0;
	}
}

//============================================
$nocheck=true;

$tpl->assign("taobao_config",$taobao_config);
$tpl->assign("dist_pro",$dist_pro);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$tpl->assign("shop_id",$buid);

$output=tplfetch("admin_distribution_product_list.htm");

