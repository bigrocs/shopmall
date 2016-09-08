<?php
/* 
 * Dsc : 买家中心 会员卡
 */

include_once("$config[webroot]/module/member/includes/plugin_membercard_class.php");
include_once("includes/page_utf_class.php");
$card = new membercard();

/*** 删除绑定 ****/
if(isset($_GET['del_v_id']) && $_GET['del_v_id']>0)
{
    $id = $_GET['del_v_id'] * 1;
    $flag = $card -> relase_bind_card($id);
    if($flag)
        $admin -> msg($config['weburl']."/main.php?m=member&s=admin_buyer_card","删除成功,正在跳回会员卡列表页……");
    else
        $admin -> msg($config['weburl']."/main.php?m=member&s=admin_buyer_card","非法操作，跳回会员卡列表页……");
}

if(isset($_POST['act']) && $_POST['act'] == "add_bind_card")
{
    $flag = $card -> bind_card();
    if($flag == 1)
        $admin -> msg($config['weburl']."/main.php?m=member&s=admin_buyer_card","绑定会员卡成功,正在跳回会员卡列表页……");
    else
        $admin -> msg($config['weburl']."/main.php?m=member&s=admin_buyer_card","序列号不正确,绑定卡失败，跳回会员卡列表页……");
}


$list = $card -> member_card_list($buid);
$tpl->assign("list",$list);

$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_buyer_card.htm");