<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
$sql="select con_title,con_id from ".WEBCON." where con_statu=1 and type=0 and lang='cn' order by con_no asc";
$db->query($sql);
$re = $db->getRows();
$tpl->assign("re",$re);
//--------------------------------------------------------------------
$sql="select con_group,con_title,con_id from ".WEBCON." where type=1 limit 0,4";
$db->query($sql);
$help=$db->getRows();
$tpl->assign("help",$help);
//--------------------------------------------------------------------
$id=empty($_GET['id'])?$re[0]['con_id']:$_GET['id'];
$sql="select con_desc,template,con_title,title,keywords,description,msg_online from ".WEBCON." WHERE con_id='$id'";
$db->query($sql);
$de=$db->fetchRow();
$tpl->assign("de",$de);
//--------------------------------------------------------------------
$config['title']=$de['title'];
$config['keyword']=$de['keywords'];
$config['description']=$de['description'];
//======================================================================
include_once("footer.php");
if(!empty($de['template'])&&file_exists($tpl->template_dir.'/'.$de['template']))
	$page=$de['template'];
else
	$page='aboutus.htm';
$tpl->display($page);
?>