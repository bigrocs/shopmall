<?php
/* 
 * Dsc : 卖家中心 顾客管理
 */

include_once("includes/page_utf_class.php");
include  $config['webroot']."/module/member/includes/plugin_membercard_class.php";
$card = new membercard();

$customer = $member -> MyCusomer($buid);
if(count($customer) > 0)
{
    foreach($customer['list'] as $key => $val)
    {
        $customer['list'][$key]['info'] = $member -> get_member_detail($val['buyer_id']);
        $customer['list'][$key]['card'] = $card ->getMemberCardInfo($val['buyer_id'], $buid);
    }
}

$tpl->assign("customer",$customer);
//---------------------------------
$tpl->assign("config",$config);
$tpl->assign("prov",GetDistrict());
$tpl->assign("lang",$lang);
$output=tplfetch("admin_customer.htm");