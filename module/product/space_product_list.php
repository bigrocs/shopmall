<?php
include_once("module/product/includes/plugin_product_class.php");
$product=new product();
//====================================================================
//是否启用Sphinx搜索
if ($sphinx_search_flag && $key=$_GET['keyword'] && extension_loaded("sphinx") && extension_loaded("scws"))
{
	$re=$product->shop_pro_list_by_sphinx();
}
else
{
	$re=$product->shop_pro_list();
}

$de=$product->get_user_common_lower_cat();
$tpl->assign("info",$re);
$tpl->assign("cat",$de);
//------------------------------------Seo config
$shopconfig["hometitle"]=$lang['pro_list'].'-'.$shopconfig["hometitle"];
$shopconfig["homedes"]=$lang['pro_list'].','.$shopconfig["homedes"];
$shopconfig["homekeyword"]=$lang['pro_list'].','.$shopconfig["homekeyword"];

//

$PluginManager = Yf_Plugin_Manager::getInstance();
$PluginManager->trigger('dist_product', intval($_GET['uid']));

//====================================================================
$tpl->assign("lang",$lang);
$tpl->assign("config",$config);
$tpl->assign("com",$company);
$output=tplfetch("space_product_list.htm",$flag);
?>