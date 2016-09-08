<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();

/*
if(isset($_GET['flag'])&&isset($_GET['id']))
{
	if($_GET['flag']==4){

        $tmpPwd = $_POST['passwd_order'];
        $tmpPwd = md5($tmpPwd);
        $sql="select pay_pass from pay_member where pay_pass='$tmpPwd' and userid='$buid'";
        if( mysql_num_rows($db->query($sql)) == 1){
            $order->set_order_statu($_GET['id'],4);//确认收货
            $admin->msg("$config[weburl]/main.php?m=product&s=admin_buyorder","支付成功");
            
        }else{
            msg("$config[weburl]/main.php?m=product&s=admin_buyorder","支付密码错误");
            
            

        }
		
		}
    if($_GET['flag']==0){
        if($_POST['state_info']){
		
            $sql="update ".ORPRO." set reason = '$_POST[state_info]' where order_id = '$_GET[id]'";
            
            $db->query($sql);
        }
    
		$order->set_order_statu($_GET['id'],0);//取消定单
	}
}
*/

if(isset($_GET['flag'])&&isset($_GET['id']))
{
    if($_GET['flag']==4)
        $order->set_order_statu($_GET['id'],4);//确认收货

    if($_GET['flag']==0)
        $order->set_order_statu($_GET['id'],0);//取消定单
}

//=======================================
$status=isset($_GET['status'])?$_GET['status']:"";
$tpl->assign("blist",$re=$order->buyorder($status));
$order_status[2]="等待买家付款";
$order_status[3]="买家已付款";
$order_status[4]="卖家已发货";
$order_status[5]="交易成功";
$order_status[1]="交易关闭";
$order_status[6]="退款中的订单";
$tpl->assign("order_status",$order_status);
$rate[1]="需我评价";
$rate[2]="我已评价";
$rate[3]="对方已评";
$rate[4]="双方已评";
$tpl->assign("rate",$rate);
//========================================
$tpl->assign("uid",$buid);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_buyorder.htm");
?>
