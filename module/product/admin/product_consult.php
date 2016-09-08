<?php
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			$sql="delete from ".CONSULT." where id in ($id)";
			$db->query($sql);
			msg("?m=product&s=product_consult.php");
		}
	}
	if($_GET['name'])
	{
		$psql=" and product_name like '%$_GET[name]%' ";	
	}
	if($_GET['key'])
	{
		$psql=" and question like '%$_GET[key]%' ";	
	}
	if($_GET['id'] and is_numeric($_GET['id']))
	{
		$psql=" and product_id = '$_GET[id]' ";	
	}
	if($_GET['status'] and is_numeric($_GET['status']))
	{
		$psql=" and status = '$_GET[status]' ";	
	}
	
	$sql="select * from ".CONSULT." where 1 $psql order by question_time desc";
	//====================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=10;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	//=====================
	$db->query($sql);
	$de['list']=$db->getRows();
	foreach($de['list'] as $k=>$v)
	{
		$sqls="select name as pname,price,id,stock from ".PRODUCT." where id='$v[product_id]'";
		$db->query($sqls);
		$c[$v['product_id']]=$db->fetchRow();
	}
	foreach($de['list'] as $k=>$v)
	{
		$c[$v['product_id']]['consult'][]=$v;
	}
	$de['list']=$c;
	$de['page'] = $page->prompt();
	unset($_GET['operation']);
	$tpl->assign("url",'&'.implode('&',convert($_GET)));

	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$tpl->display("product_consult.htm");
?>