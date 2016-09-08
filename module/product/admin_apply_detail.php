<?php
include_once("$config[webroot]/module/product/includes/plugin_refund_class.php");
@include_once("$config[webroot]/config/image_config.php");

$lock_dir = $config['webroot'] . '/cache/lock';
if (!file_exists($lock_dir))
{
	mkdir($lock_dir);
}

$lock_name = 'admin_apply_detail|' . $buid;
$lock = new Yf_Lock_File($lock_name, $lock_dir);

$flag = $lock->lock();



$refund=new refund();
$type = $_SESSION['USER_TYPE']==2 ? "seller" : "buyer"; 
if($_GET['order_id']&&$_GET['pid'])
{
	$re=$refund->order_detail($_GET['order_id'],$_GET['pid'],$type);
	$_GET['id'] = $re['refund_id'];
}
$de = $refund -> refund_detail($_GET['id'],$type);

if(!$de)
{
	header("Location: 404.php");
}
$tpl->assign("de",$de);
$tpl->assign("type",$type);
if($_GET['act'] == 'close')
{
	if($de['status']==1||$de['status']==4)
	{
		$refund -> close_refund($de['refund_id']);
		$admin->msg("main.php?m=product&s=admin_apply_detail&id=$de[refund_id]");
	}
}
if($_POST['act'] =='agree')
{
	if (5 == $de['refund_status'])
	{
		$admin->msg("main.php?m=product&s=admin_apply_detail&id=$de[refund_id]");
		die();
	}

	$msg = "卖家（".$_COOKIE['USER']."）于 ".date("Y-m-d H:i:s",time())." 同意退款申请。";
	$refund->add_talk($de['refund_id'],$de['order_id'],$msg,'');
	$refund->agree_refund($de['refund_id']);
	$admin->msg("main.php?m=product&s=admin_apply_detail&id=$de[refund_id]");
}
if($_POST['act'] =='refuse')
{
	$msg = "卖家（".$_COOKIE['USER']."）已经拒绝了退款申请 拒绝理由为：".$_POST['refuse_reason'];
	$refund->add_talk($de['refund_id'],$de['order_id'],$msg,'');
	$refund->refuse($de['refund_id'],$_POST['refuse_reason']);
	$admin->msg("main.php?m=product&s=admin_apply_detail&id=$de[refund_id]");
}
if($_POST['act'] =='review')
{
	$refund->add_talk($de['refund_id'],$de['order_id'],$_POST['msg'],$_POST['pic']);
	$admin->msg("main.php?m=product&s=admin_apply_detail&id=$de[refund_id]");
}

$talk = $refund->get_talk();
$tpl->assign("talk",$talk);

$image_config['image_size'] = floor($image_config['image_size']/1024);
$image_config['image_extension'] = strtoupper($image_config['image_extension']);
$tpl->assign("image_config",$image_config);

$tpl->assign("config",$config);
$output=tplfetch("admin_apply_detail.htm");
?>