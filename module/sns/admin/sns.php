<?php
include_once("../includes/page_utf_class.php");

//====================================

		
if(is_array($_POST['chk']))
{
	$id=implode(",",$_POST['chk']);
	if($_POST['act']=='op')
	{
		$sql="delete from ".SNS." where id in ($id)";
		$db->query($sql);
		msg("?m=sns&s=sns.php");
	}
}	

$sql="select * from ".SNS." order by create_time desc";
include_once($config['webroot']."/includes/page_utf_class.php");
include_once($config['webroot']."/module/sns/face.php");
$page = new Page;
$page->listRows=30;
if (!$page->__get('totalRows'))
{
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",".$page->listRows;
$de['page'] = $page->prompt();
$db->query($sql);
$re=$db->getRows();

foreach($face_array as $key=>$val)
{
	$searcharray[] ="/\/".$key."/";
	$replacearray[] = "<img align='absmiddle' src='".$config['weburl']."/image/face/".$val."'>";
}
foreach($re as $key=>$val)
{
	$comment=$con=$del=$a=$img="";
	
	$sql="select logo,name from ".MEMBER."  WHERE userid='$val[member_id]'";
	$db->query($sql);
	$a=$db->fetchRow();

	$val['member_img'] = $a['logo']?$a['logo']:$config['weburl']."/image/default/user_admin/default_user_portrait.gif";
	
	$val['member_name']=$a['name']?$a['name']:$val['member_name'];
	if($val['img'])
	{
		$val['img']=$pic=explode(',',$val['img']);
	}
	$val['title']=preg_replace($searcharray,$replacearray,$val['title']);
	
	$de['list'][$key]=$val;
}
	
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->display("sns.htm");
?>