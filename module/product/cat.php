<?php
$tpl->assign("de",get_cat());
function get_cat($catid="")
{
	global $db;
	if($catid)
	{
		$max = $catid."99";
		$min = $catid."00";
		$sql = "select cat,catid from ".PCAT." where catid <= $max and catid >= $min order by nums,catid";	
	}
	else
	{
		$sql = "select cat,catid from ".PCAT." where catid < 9999 order by nums,catid";
	}
	$db -> query($sql);
	$re = $db -> getRows();
	if($re)
	{
		foreach($re as $key => $val)
		{
			$re[$key]["scat"]=get_cat($val['catid']);	
		}
		return $re;	
	}
}
$config['title']="商品分类";
$tpl->assign("current","product");
include_once("footer.php");
$out=tplfetch("product_cat.htm");
?>