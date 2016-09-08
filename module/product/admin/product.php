<?php
if(is_array($_POST['chk']))
{
	$id=implode(",",$_POST['chk']);
	if($_POST['act']=='del')
	{
		$sql="delete from ".PRODUCT." where id in ($id)";
		$db->query($sql);
		$sql="delete from ".SETMEAL." where pid in ($id)";
		$db->query($sql);
		$sql="delete from ".PRODETAIL." where proid in ($id)";
		$db->query($sql);
		msg("?m=product&s=product.php");
	}
	if($_POST['act']=='up')
	{
		$sql="update ".PRODUCT." set status='1' where id in ($id)";
		$db->query($sql);
	}
	if($_POST['act']=='down')
	{
        $_POST['down_reason']=$_POST['down_reason']?$_POST['down_reason']:"";
		$sql="update ".PRODUCT." set status='-1',down_reason='".$_POST['down_reason']."' where id in ($id)";
		$db->query($sql);
	}
	if($_POST['act']=='tj')
	{
		$sql="update ".PRODUCT." set status='2' where id in ($id)";
		$db->query($sql);
	}
}
if($_GET['name'])
{
	$psql.=" and a.name like '%$_GET[name]%'";	
}
if($_GET['user'])
{
	$psql.=" and b.company like '%$_GET[user]%'";	
}
if($_GET['operation']=='wait')
{
	$psql.=" and a.status = '0'";	
}
if($_GET['operation']=='down')
{
	$psql.=" and a.status = '-1'";	
}

$sql="SELECT a.id,name as pname,a.catid,a.pic,brand,price,clicks as read_nums,a.uptime,stock as amount,a.status as statu,a.down_reason,a.rank,b.company FROM ".PRODUCT." a  left join ".SHOP." b on a.member_id=b.userid WHERE 1 $psql order by a.uptime desc";
//========================================
include_once("../includes/page_utf_class.php");
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$de['count']=$page->totalRows;
$sql.= "  limit ".$page->firstRow.",".$page->listRows;
$de['page'] = $page->prompt();
//=========================================
$db->query($sql);
$de['list']=$db->getRows();
foreach($de['list'] as $key=>$val)
{
	$catname=array();
	if(strlen($val['catid'])>8)
		$catname[]=getName(substr($val['catid'],0,-6));
	if(strlen($val['catid'])>6)
		$catname[]=getName(substr($val['catid'],0,-4));
	if(strlen($val['catid'])>4)
		$catname[]=getName(substr($val['catid'],0,-2));
	$catname[]=getName($val['catid']);
	$de['list'][$key]['catname']=implode('->',$catname);
}
unset($_GET['s']);
$tpl->assign("url",'&'.implode('&',convert($_GET)));
$tpl->assign("de",$de);
$status=array('-1'=>'违规下架','0'=>'待审核','1'=>'可售','2'=>'推荐');
$tpl->assign("status",$status);
$tpl->assign("config",$config);
$tpl->display("product.htm");
?>