<?php
	include_once("$config[webroot]/module/sns/includes/plugin_sns_class.php");
	$sns=new sns();

	if($_GET['curpage'])
	{
		echo $sns->get_sns_list();
		die;
	}
	$sql="select count(*) as count from ".MEMBER;
	$db->query($sql);
	$count1=$db->fetchField('count');
	
	$min = strtotime(date("Y-m-d",time()));
	$max = strtotime(date("Y-m-d",time()+24*60*60));
	
	$sql="select count(*) as count from ".SNS." where create_time<=$max  and create_time>=$min";
	$db->query($sql);
	$count2=$db->fetchField('count');

	$num_a = 1;
	$tpl->assign("string",$sns->get_sns_list($num_a));

	$tpl->assign("count1",$count1?$count1:"0");
	$tpl->assign("count2",$count2?$count2:"0");
	$tpl->assign("current","sns");
	$tpl->assign("config",$config);
	include_once("footer.php"); 
	$out=tplfetch("sns_index.htm",$flag,true);
?>