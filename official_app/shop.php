<?php
$shopid=$_POST["shopid"]?$_POST["shopid"]:$_GET["shopid"];
//print_r($shopid);
if(!empty($shopid))
{
	include_once("../includes/global.php");
	include_once("../includes/smarty_config.php");
	$sql="select a.userid,a.user,a.logo,a.company,b.shop_banner from ".SHOP." a left join ".SSET." b on a.userid= b.shop_id where a.userid=$shopid";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re['logo']=='image/default/avatar.png'){
		$re['logo']="http://192.168.0.88/tech05/mall_sj/".$re['logo'];
	}
	$re['logo'] = $re['logo']?$re['logo']:"http://192.168.0.88/tech05/mall_sj/image/default/avatar.png";
	if($re)
	{
		$sql="select * from ".PRODUCT." where member_id='$re[userid]' order by sales DESC";
		$db->query($sql);
		$pros=$db->getRows();
		$re['pro_count']=$db->num_rows();
		$products=array();
		foreach($pros as $key=>$p){
			$products[$key]['id']=$p['id'];
			$products[$key]['pic']=$p['pic'];
			$products[$key]['name']=$p['name'];
			$products[$key]['price']=$p['price'];
			$products[$key]['sales']=$p['sales'];
			$products[$key]['stock']=$p['stock'];
		}
		$re["productlist"]=$products;
		$re["result"]="success";
	}else{
		$re["result"]="noshop";
	}
	print_r( json_encode($re));
}
?>