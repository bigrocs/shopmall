<?php

/* 
 * Dsc: 卖家中心 代金券管理
 */

include  $config['webroot']."/module/voucher/includes/plugin_voucher_class.php";
include_once("includes/page_utf_class.php");
$voucher = new voucher();


/*** 购买成功返回 ****/
if(isset($_GET['auth_key']) && isset($_GET['auth']))
{
    
    if($_SESSION['auth_key'] != $_GET['auth_key'])
    {
       if(md5(md5($_GET['auth_key'].$_GET['price'])) != $_GET['code'])
        {
            $admin->msg("main.php?m=voucher&s=admin_voucher&type=buy","非法进入");exit;
        }
    }
    
    $re = $voucher -> buyApply($buid);
    if($re){$admin->msg("main.php?m=voucher&s=admin_voucher","购买成功,自动返回代金券列表页......");exit;} 
}


/*** 本店铺该应用状态 ****/
$shop_app_info = $voucher -> getApplyInfo($buid);
if($shop_app_info)
{
    if($shop_app_info['end_time'] < time())
    {
        $tpl->assign("shop_app_status",-1);
        $shop_app_status = -1;
    }
    else
    {
        $tpl->assign("shop_app_status",1);
        $shop_app_status = 1;
    }
}
else
{
    $tpl->assign("shop_app_status",0);
    $shop_app_status = 0;
}

/*** 店铺代金券领取统计 ****/
if(isset($_GET['listid']) && $_GET['listid'] > 0)
{
    $id = $_GET['listid'] *1;
    $total = $voucher -> total_temp($id);
    $tpl->assign("total",$total);
}

/*** 获取面额种类 ****/
if((isset($_GET['type']) && $_GET['type']=="add") || $_GET['listid'] > 0)
{
    @include "config/p_voucher_config.php";
    $tpl->assign("voucher_config",$p_voucher_config);
    
    //$de = $voucher ->amount_list_all();
    //$tpl->assign("de",$de);
    
    $re = $voucher -> getApplyInfo($buid);
    $tpl -> assign("re",$re);
    
    if((isset($_GET['act']) && $_GET['act'] == "edit") || $_GET['listid'] > 0)
    {
        $id = $_GET['id']*1;
        $id = $id >0 ?$id:$_GET['listid']*1;
        $mu = $voucher -> voucher_detial($id);
        if($mu == -1 || !$mu)
        {
            $admin->msg("main.php?m=voucher&s=admin_voucher","非法进入");
            exit;
        }
        else
        {
            $tpl -> assign("mu",$mu);
        }
    }
}

/*** 添加代金券模板 ****/
if(isset($_POST['act']) && $_POST['act'] == "add_voucher" )
{
    @include "config/p_voucher_config.php";
    
    $m = $voucher -> shop_add_voucher($buid,$p_voucher_config['p_v_limittimes']);
    if($m == 1)
    {
        $admin->msg("main.php?m=voucher&s=admin_voucher","添加成功");
    }
    else if ($m == -1)
    {
        $admin->msg("main.php?m=voucher&s=admin_voucher","添加失败，应用未开通");
    }
    else if($m == -2)
    {
        $admin->msg("main.php?m=voucher&s=admin_voucher","添加失败，应用已过期");
    }
    else if($m == -3)
    {
        $admin->msg("main.php?m=voucher&s=admin_voucher","添加失败，超过每月活动限制数量");
    }
    else
    {
        $admin->msg("main.php?m=voucher&s=admin_voucher","添加失败，非法进入");
    }
    exit;
}

/*** 编辑代金券模板 ****/
if(isset($_POST['act']) && $_POST['act'] == "edit_voucher")
{
    $m = $voucher -> shop_eidt_voucher();
    $admin->msg("main.php?m=voucher&s=admin_voucher","编辑成功");
    exit;
}

/*** 购买功能 ****/
if((isset($_GET['type']) && $_GET['type'] == "buy" ) || $shop_app_status != 1)
{
    $re = $voucher -> getApplyInfo($buid);
    $tpl -> assign("re",$re);
    
    $tpl -> assign("de",$shop_app_info);
    @include "config/p_voucher_config.php";
    $tpl -> assign("voucher_config",$p_voucher_config);
}

if(isset($_POST['act']) && $_POST['act'] == "buy")
{
    @include "config/p_voucher_config.php";
    
    if($_POST['mounth']*1 < 1){$admin->msg("main.php?m=voucher&s=admin_voucher&type=buy","输入月数不正确");exit;}
    
    $order_id = date("Ymdhis").rand(0,9);//订单号
    $price = $p_voucher_config['p_v_price']*$_POST['mounth'];
    $str = "[申请代金券功能(".$_POST['mounth']."个月)订单消费]";

    $post['action']='add';//填加流水
    $post['type']=1;//直接到账
    $post['seller_email'] = "admin@systerm.com";//卖家账号
    $post['buyer_email'] = $buid;//买家账号
    $post['order_id'] = $order_id;//外部订单号
    $post['price'] = $price;//订单总价，单价元
    $post['extra_param'] = '';//自定义参数，可存放任何内容
    $post['return_url'] = $config['weburl'].'/main.php?m=voucher&s=admin_voucher';//返回地址
    $post['notify_url'] = $config['weburl'].'/main.php?m=voucher&s=admin_voucher';//异步返回地址
    $post['name']="$str";
    $res=pay_get_url($post,true);//跳转至订单生成页面

    if(!$res){msg($config['pay_url']."?m=payment&s=pay&tradeNo=$order_id");exit;}
}

/*** 店铺代金券列表 ****/
if(!isset($_GET['type']) || empty($_GET['type']))
{
    $re = $voucher -> shop_voucher_list($buid);
    $tpl->assign("re",$re);
    
    $de = $voucher -> getApplyInfo($buid);
    $tpl -> assign("de",$de);
}

/*** 代金券模板删除 ****/
if(isset($_GET['delid']) && $_GET['delid'] > 0)
{
    $de = $voucher -> voucher_temp_delete($_GET['delid']*1);
    $admin->msg("module.php?m=voucher&s=admin_voucher","删除成功");
}

$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_voucher.htm");
