<?php
include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
$member=new member();
$points=$member->get_points();

$sql="select * from ".POINTSGOODS." where id='$_GET[id]'";
$db->query($sql);
$de=$db->fetchRow();
if($points>=$de[points])
{
	include_once("$config[webroot]/module/member/includes/plugin_orderadder_class.php");
	$orderadder=new orderadder();
	$tpl->assign("listadder",$adlist=$orderadder->get_orderadderlist());
	if(!$adlist)
	{
		msg("$config[weburl]/main.php?m=member&s=admin_orderadder&type=add");	
	}
	include_once("footer.php");
}else{
    $tpl->assign("nopoint","1");
}
$tpl->assign("current","points");
$out=tplfetch("points_order.htm",$flag,true);
?>