<?php
	include_once("../includes/page_utf_class.php");
	include_once("module/shop/includes/plugin_shop_class.php");
	$shop=new shop();

	$get=$_GET;
	unset($get['editid']);
	unset($get['s']);
	unset($get['m']);
	unset($get['grade']);
	unset($get['catid']);
	unset($get['operation']);
	$getstr=implode('&',convert($get));
	$tpl->assign("getstr",$getstr);
	
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			if($_POST['submit']==$lang['pass1'] and $id)
			{
				$sql="update ".SHOP." set shop_statu='1' where userid in ($id)";
				$re=$db->query($sql);
			}
			if($_POST['submit']==$lang['npass'] and $id)
			{
				$sql="update ".SHOP." set shop_statu='-2' where userid in ($id)";	
				$re=$db->query($sql);
			}
			msg("?m=shop&s=shop_application.php",'');
		}
	}

	if($_GET['st']=='1')
		$sql=" and shop_statu in(0, -4)";
	elseif($_GET['st']=='2')
		$sql=" and shop_statu in(-5, -2)";
	else
		$sql=" and shop_statu in(0, -2, -4, -5)";


	if($_GET['shop_type']=='1')
		$sql.=" and shop_type in(1)";
	elseif($_GET['shop_type']=='2')
		$sql.=" and shop_type in(2)";
	else
		$sql.=" and shop_type in(1, 2, 3)";

	$tpl->assign("de",$shop->GetShopList($sql));
	
	//获取店铺类型 
	$tpl->assign("grade",$shop->GetShopGradeList());
		
	$tpl->assign("config",$config);
	$tpl->display("shop_application.htm");
?>