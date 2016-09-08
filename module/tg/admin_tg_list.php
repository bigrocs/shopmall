<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_product_class.php");
$pro=new product();
//============================================================================
if(!empty($deid))
{
	$pro->del_pro($deid);
}
if(isset($_GET['cstatu'])&&!empty($_GET['pid']))
{
	$pro->set_pro_statu($_GET['pid'],$_GET['cstatu']);
	$admin->msg("main.php?m=tg&s=admin_tg_list");
}
else if($_GET['pid'])
{
	$pid=explode(',',$_GET['pid']);
	foreach($pid as $val)
	{
		$pro->del_pro($val);
	}
	die;
}
$statu = $_GET['statu']?$_GET['statu']-3:"1";
$is_shelves = $statu=='0'?"0":($statu=='1'?"1":"ALL");
$tpl->assign("re",$pro->pro_list($statu,$is_shelves,'true'));

//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_tg_list.htm");
?>