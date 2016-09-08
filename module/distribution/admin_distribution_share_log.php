<?php
$dist_rows_temp = $distribution->getDistributionAnalyseShop(null, $buid, null);
$produce_id_row = array();
$dist_rows = array();
foreach ($dist_rows_temp as  $val) {
    array_push($produce_id_row, $val['product_id']);

    $dist_rows[$val['product_id']] = $val;
}


//根据商品Id获取商品详情
//$produce_id_row = array_filter_key('product_id', $dist_rows_temp);

$dist_pro = $distribution->getProductInfo($produce_id_row);

foreach ($dist_pro as $k => $val) {
    $dist_pro[$k ]['distribution_analyse_shop_num'] =  $dist_rows[$val['id']]['distribution_analyse_shop_num'] ;
}

foreach ($dist_pro as $key => $value) {
    $name[$key] =   $value['distribution_analyse_shop_num'];
}

if ($dist_pro)
{
	array_multisort($name, SORT_DESC, $dist_pro);
}

//============================================
$nocheck=true;

$tpl->assign("taobao_config",$taobao_config);
$tpl->assign("dist_pro",$dist_pro);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$tpl->assign("shop_id", $buid);

$output=tplfetch("admin_distribution_share_log.htm");

