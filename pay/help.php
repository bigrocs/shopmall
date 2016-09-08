<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");

$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
$db->query($sql);
$con_groups = $db->getRows();
$tpl->assign("con_groups",$con_groups);
//--------------------------------------------------------------------
$sql="select con_group,con_title,con_id from ".WEBCON." where type=1 limit 0,4";
$db->query($sql);
$help=$db->getRows();
$tpl->assign("help",$help);
//--------------------------------------------------------------------
$id=$_GET['id']=empty($_GET['id'])?$con_groups[0]['id']:$_GET['id']*1;
$type=empty($_GET['type'])?0:$_GET['type']*1;
if($type)
{
	$sql="select con_title,con_desc,title,keywords,description from ".WEBCON." where con_id = '$type' ";
	$db->query($sql);
	$de=$db->fetchRow();
	$tpl->assign("de",$de);
}
else
{
	$sql="select con_title,con_id,con_group from ".WEBCON." where con_group = '$id' ";
	$db->query($sql);
	$de=$db->getRows();
	$tpl->assign("de",$de);
}
//--------------------------------------------------------------------
$config['title']=$de['title'];
$config['keyword']=$de['keywords'];
$config['description']=$de['description'];
//======================================================================
include_once("footer.php");
if(!empty($de['template'])&&file_exists($tpl->template_dir.'/'.$de['template']))
	$page=$de['template'];
else
	$page='help.htm';
$tpl->display($page);
?>