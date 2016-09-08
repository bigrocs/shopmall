<?php

/* 
 * Dsc : 会员卡管理
 */
include  $config['webroot']."/module/member/includes/plugin_membercard_class.php";
include_once("includes/page_utf_class.php");
$card = new membercard();

/*** 批量生成会员卡 ****/
if(isset($_POST['act']) && $_POST['act'] == "add_card")
{
    $flag = $card -> shop_add_card($buid);
    $admin -> msg($config['weburl']."/main.php?m=member&s=admin_member_card","添加成功");
}

/*** 店铺会员卡列表 ****/
if(!isset($_GET['type']))
{
    $de = $card -> shop_list_card($buid);
    $tpl->assign("de",$de);
}
/* 增加的一个调用*/
if(!isset($_GET['type']))
{
    $de1 = $card -> shop_list_card1($buid);
    $tpl->assign("de1",$de1);
}
/*** 删除会员卡模板 默认已生成的卡全部失效 ****/
if(isset($_GET['delid']) && $_GET['delid'] > 0)
{
    $id = $_GET['delid']*1;
    $de = $card -> shop_delete_card($id);
    if(!de) if(!de) $admin -> msg($config['weburl']."/main.php?m=member&s=admin_member_card","非法进入");
    $admin -> msg($config['weburl']."/main.php?m=member&s=admin_member_card","删除成功");
}

/**** 编辑会员卡 ***/
if(isset($_GET['act']) && $_GET['act'] == "edit")
{
    $id = $_GET['id'] * 1;
    $de = $card -> shop_detial_card($id);
    if(!de) $admin -> msg($config['weburl']."/main.php?m=member&s=admin_member_card","非法进入");
    $tpl->assign("de",$de);
}

if(isset($_POST['act']) && $_POST['act'] == "edit_card")
{
    $de = $card -> shop_edit_card();
    $admin -> msg($config['weburl']."/main.php?m=member&s=admin_member_card","编辑成功");
}

/*** 会员卡统计 ****/
if(isset($_GET['listid']) && $_GET['listid'] > 0)
{
    $id = $_GET['listid'] * 1;
    $mu = $card -> shop_detial_card($id);
    $total = $card -> get_shopcard_total($id);
    
    $tpl->assign("mu",$mu);
    $tpl->assign("total",$total);
}
//---------------------------------
$tpl->assign("config",$config);
$tpl->assign("prov",GetDistrict());
$tpl->assign("lang",$lang);
$output=tplfetch("admin_membercard.htm");