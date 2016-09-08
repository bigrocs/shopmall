<?php
	include_once("$config[webroot]/module/shop/includes/plugin_temp_class.php");
	$temp=new temp();
	if(is_numeric($_GET['id']))
    {
        $re=$temp->get_shop_nav_content($_GET['id']);
        if(!empty($re['product'])){
        $sql="select id,name,price,pic from ".PRODUCT." where id in($re[product])";
        $db->query($sql);
        $de = $db->getRows($sql);
        $tpl->assign("de",$de);
        }
        $tpl->assign("re",$re);
		
	}
	//------------------------------------Seo config
	$shopconfig["hometitle"]="信用评价".'-'.$shopconfig["hometitle"];
	$shopconfig["homedes"]="信用评价".','.$shopconfig["homedes"];
	$shopconfig["homekeyword"]="信用评价".','.$shopconfig["homekeyword"];
	//====================================================================
	$tpl->assign("lang",$lang);
	$tpl->assign("config",$config);
	$tpl->assign("com",$company);
	$output=tplfetch("space_public.htm",$flag);
?>
