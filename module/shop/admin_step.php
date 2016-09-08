<?php
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
if($shop_statu=$shop->GetShopStatus($buid) > 0)
{
	header("Location: main.php");die;
}

//============================================================
if($_POST['submit']=="edit")
{
	$re=$shop->update_user();
	unset($_SESSION['shop_type']);
	$admin->msg("main.php?cg_u_type=2&st=1");
	//$admin->msg("main.php?m=shop&s=myshop");     开店成功后转到卖家中心，而不是转到店铺资料编辑页
}
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$tpl->assign("distribution_open_flag",$distribution_open_flag);
$tpl->assign("cat",$shop->GetShopCatList());
include_once("footer.php");
if(isset($_GET['grade']) and is_numeric($_GET['grade']))
{
	$tpl->assign("prov",GetDistrict());
	$output=tplfetch("admin_step1.htm",$flag,true);
}
else
{
	//是否开启分销店铺
	if ($_SESSION['shop_type'] || ($distribution_open_flag && 1==$_GET['shop_type']) || (2==$_GET['shop_type']) || !$distribution_open_flag)
	{
		if ($_GET['shop_type'])
		{
			$_SESSION['shop_type'] = $_GET['shop_type'];
		}
		else
		{
			$_SESSION['shop_type'] = 2;
		}

		//是否需要选择店铺类型
		//header('location:./main.php?m=shop&s=admin_step&grade=1');
		//die();

		$sql="select `id`,`name`,`desc` from ".SHOPGRADE." where status='1'";
		$db->query($sql);
		$re=$db->getRows();
		$tpl->assign("re",$re);
		$output=tplfetch("admin_step.htm",$flag,true);
	}
	else
	{
		//选择店铺 分销|卖家
		$output=tplfetch("admin_step_0.htm", $flag, true);
	}
}
?>