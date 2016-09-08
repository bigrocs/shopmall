<?php
include_once("../lang/$langs/company_type_config.php");
include_once("../includes/page_utf_class.php");
//==================================
if(!empty($_POST["act"]))
{
	if(!empty($_POST['password'])) 
	{
		$sql="update ".MEMBER." set password='".md5($_POST['password'])."' where userid='$_POST[userid]'";
		$db->query($sql);
	}
	$_POST['sex']=empty($_POST['sex'])?1:$_POST['sex'];
	$_POST['province']*=1;
	$_POST['city']*=1;
	$_POST['area']*=1;
	$_POST['street']*=1;
	$_POST['grade']*=1;
	$_POST['email_verify']*=1;
	$_POST['mobile_verify']*=1;
		
	$sql="UPDATE ".MEMBER." SET		name='$_POST[name]',qq='$_POST[qq]',provinceid='$_POST[province]',cityid='$_POST[city]',areaid='$_POST[area]',area='$_POST[t]',sex='$_POST[sex]',mobile='$_POST[mobile]',email='$_POST[email]',grade_id='$_POST[grade]',ww='$_POST[ww]',streetid='$_POST[street]',email_verify='$_POST[email_verify]',mobile_verify='$_POST[mobile_verify]',invite='$_POST[invite]' where userid='$_POST[userid]'";
	$re=$db->query($sql);
	
	if($_POST['points']*1!=0)
	{
		include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
		$member = new member();
		$member->add_points($_POST['points']*1,'6','',$_POST['userid']);
	}
	//-------------------------------
	unset($_GET['userid']);
	unset($_GET['s']);
	unset($_GET['m']);
	msg("?m=member&s=member.php&".implode('&',convert($_GET)));
}
//=====================================================
$sql="select a.*,b.pic,c.points from ".MEMBER." a left join ".MEMBERGRADE." b on a.grade_id = b.id left join ".MEMBERINFO." c on a.userid = c.member_id where userid='$_GET[userid]'";
$db->query($sql);
$de=$db->fetchRow();
$ipfile="../lib/tinyipdata.dat";
$de['ips'] = convertip($de['ip'], $ipfile);
$tpl->assign("de",$de);
			
if($_GET['t']=='p')
{
	$sql="select * from ".POINTSLOG." where member_id = '$de[userid]' order by create_time desc";
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$db->query($sql);
	$points['page'] = $page->prompt();
	$points['list'] = $db->getRows();
	$tpl->assign("points",$points);
}
elseif($_GET['t']=='o')
{
	$buid = $de["userid"];
	include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
	$order=new order();
	$tpl->assign("blist",$order=$order->buyorder());
	$tpl->assign("order_status",$order_status);
}
else
{
	$sql="select name from ".ADMIN." where name!='' order by name";
	$db->query($sql);
	$invite=$db->getRows();
	$tpl->assign("invite",$invite);		
			
	$tpl->assign("prov",GetDistrict());
	$sql="select * from ".MEMBERGRADE;
	$db->query($sql);
	$re = $db->getRows();
	$tpl->assign("re",$re);	
}
$tpl->assign("config",$config);				
$tpl->display("membermod.htm");
?>
