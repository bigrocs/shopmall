<?php

$dist_user_row = $distribution->getDistributionUser($buid);
$dist_user_total_row = $distribution->getDistributionChildUserNum($buid);


$dist_user_row['distribution_user_amount'] =  $dist_user_row['distribution_shop_amount_0'] +  $dist_user_row['distribution_shop_amount_1'] +  $dist_user_row['distribution_shop_amount_2']
                                                + $dist_user_row['distribution_click_amount_0'] +  $dist_user_row['distribution_click_amount_1'] +  $dist_user_row['distribution_click_amount_2']
                                                + $dist_user_row['distribution_reg_amount_0'] +  $dist_user_row['distribution_reg_amount_1'] +  $dist_user_row['distribution_reg_amount_2']
												+ $dist_user_row['distribution_buy_amount_0'] +  $dist_user_row['distribution_buy_amount_1'] +  $dist_user_row['distribution_buy_amount_2'];

$dist_user_row['distribution_user_unsettlement_amount'] = $dist_user_row['distribution_user_amount'] - $dist_user_row['distribution_user_settlement_amount'];



$tpl->assign("distribution_visit_flag", $distribution_visit_flag);
$tpl->assign("distribution_reg_flag", $distribution_reg_flag);
$tpl->assign("distribution_buy_flag", $distribution_buy_flag);
$tpl->assign("dist_user_row", $dist_user_row);
$tpl->assign("dist_user_total_row", $dist_user_total_row);

$tpl->assign("shop_id", $buid);

$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_distribution.htm");
?>