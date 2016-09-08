<?php
	include_once("includes/page_utf_class.php");
	include_once("$config[webroot]/module/product/includes/plugin_product_class.php");
	$pro=new product();
	//============================================================================
	
	if(!empty($deid))
	{
		$pro->del_pro($deid);
		$admin->msg("main.php?m=product&s=admin_product_storage&statu=$_GET[statu]");
	}
	if(!empty($_GET['cstatu'])&&!empty($_GET['ppid']))
	{
		$pro->set_pro_statu($_GET['ppid'],$_GET['cstatu']);
		$admin->msg("main.php?m=product&s=admin_product_storage&statu=$_GET[statu]");
	}

	$statu = $_GET['statu']=='-1'?'-1':($_GET['statu']=='-2'?"-2":"0");
	$_GET['is_shelves'] = $statu=='0'?"0":"ALL";
	$tpl->assign("re",$pro->pro_list($statu,$_GET['is_shelves']));
	
	
	if($_GET['pid'])
	{
		$pid=explode(',',$_GET['pid']);
		foreach($pid as $val)
		{
			$pro->del_pro($val);
		}
		die;
	}

	/*** 批量下架 ****/
	if($_GET['pcsid'])
	{
		$pid = $_GET['pcsid'];
		$pro->change_status($pid,1);
		die;
	}

	/*** 批量上架 ****/
	if($_GET['pcsidd'])
	{
		$pid = $_GET['pcsidd'];
		$pro->change_status($pid);
		die;
	}


	//==================================
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_product_storage.htm");
?>