<?php
$sql="select con_linkaddr,con_title from ".WEBCON." where con_statu=1 and type=0 order by con_no asc";
$db->query($sql);
while($v=$db->fetchRow())
{
	if(!empty($v['con_linkaddr']))
	{
		if(substr($v['con_linkaddr'],0,4)=='http')
			$url=$v['con_linkaddr'];
		else
			$url=$config['weburl'].'/'.$v['con_linkaddr'];
	}
	else
		$url=$config['weburl']."/aboutus.php?type=".$v['con_id'];
	$li[]="<a href='$url'>".$v['con_title']."</a>";
}
if(isset($li))
	$tpl->assign("web_con",implode("<em>|</em>",$li));
//------------------------------------------------------------------
if(!empty($config['copyright']))
{
	$config['copyright'].='<br />Powered by <a href="http://www.mall-builder.com">'.$config['version'].'</a>';
	$tpl->assign("bt",$config['copyright']);
}
$tpl->assign("config",$config);
?>