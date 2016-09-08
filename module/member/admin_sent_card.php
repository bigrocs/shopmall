<?php
/* 
 * Dsc : 发放会员卡
 */
include  $config['webroot']."/module/member/includes/plugin_membercard_class.php";
$card = new membercard();

if(isset($_POST['act']) && $_POST['act'] == "add")
{
    $flag = $member -> bindMembercard();
    if($flag)
        $tpl->assign("result",1);
    else
    	$tpl->assign("result",2);
}

$de = $card -> shop_list_card($buid);
$tpl->assign("de",$de);

$id = $_GET['mid'] * 1;
$detail = $member -> get_member_detail($id);
$tpl->assign("detail",$detail);

$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
tplfetch("admin_sent_card.htm",'',true);