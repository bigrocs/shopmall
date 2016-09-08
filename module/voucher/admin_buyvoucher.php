<?php

/* 
 * Dsc: 买家中心  我的代金券
 */

include  $config['webroot']."/module/voucher/includes/plugin_voucher_class.php";
include_once("includes/page_utf_class.php");
$voucher = new voucher();

if(isset($_GET['del_v_id']) && $_GET['del_v_id'] > 0)
{
    $id = $_GET['del_v_id']*1;
    $voucher -> voucher_delete($id);
    $admin -> msg($config['weburl']."/main.php?m=voucher&s=admin_buyvoucher","删除成功");
}

$list = $voucher -> buyvoucher();
$tpl->assign("list",$list);
        
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_buyvoucher.htm");