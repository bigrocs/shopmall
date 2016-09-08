<?php
/**
* Auth:bruce
*
* Date:2014-12-32
*
* Dsc : 店铺百度地图
*/

if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');

if(!isset($_GET['id'])){die();}

$id = $_GET['id']*1;

$sql = "select lng,lat,company,area,addr from ".SHOP." where userid = ".$id;
$db -> query($sql);
$re = $db -> fetchRow();

$tpl->assign("re",$re);

$tpl->assign("current","shop");
include_once("footer.php"); 

if(isset($_GET['action']) && !empty($_GET['action']))
{
	tplfetch("location_s_info.htm","",true);
}
else if(isset($_GET['act']) && $_GET['act'] == "searchline")
{
	tplfetch("location_s.htm","",true);
}
else
{
	$out=tplfetch("location.htm",$flag);
}

?>