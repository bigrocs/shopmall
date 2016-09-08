<?php
//==================================
include_once("$config[webroot]/module/activity/includes/plugin_activity_class.php");
$activity=new activity();

$activity_id=$_GET['id'];
$rule_id=$_GET['rule'];
if(!empty($rule_id))
{
	$sql="select title from ".ACTIVITY." where id='$activity_id' ";
	$db->query($sql);
	$activ_title=$db->fetchField("title");
	$tpl->assign("activ_title",$activ_title);
}
if($activity_id)
{
	$tpl->assign("ad",$activity->get_activity_prolist($activity_id));	//已参加活动的商品
}

if($_POST['act']=="add")
{
	$flag=$activity->save_activity_product();
	msg($config['weburl']."/main.php?m=activity&s=admin_activity_product_list&id=$_GET[id]");
}

//可以申请参加活动的商品
include_once("includes/page_utf_class.php");
$sql="select id,name as pname,pic from ".PRODUCT." where member_id='$buid' and promotion_id =0 and status>=1 and is_shelves=1 order by id desc";
//=============================
$page = new Page;
$page->listRows=2;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows=$db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",".$page->listRows;
//=====================
$db->query($sql);
$re["list"]=$db->getRows();
$re["page"]=$page->prompt();
$tpl->assign("pro",$re);

//==================================
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$tpl->assign("prolist",$activity->get_full_prolist());
$output=tplfetch("admin_activity_product_list.htm");
?>