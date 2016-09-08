<?php
include_once("../includes/page_utf_class.php");
include_once("../lang/$langs/company_type_config.php");
//====================================
if(!empty($_POST['de'])&&empty($_POST['passall']))
{
	del_user($_POST['de']);
}

if(!empty($_POST['act']))
{	
	$statu="2";
	if($_POST['act']=="s2") $statu="1";
	if($_POST['act']=="s3") $statu="-2";
		
	if($statu&&$_POST['chk'])
	{
		$chk = implode(',',$_POST['chk']);
		$sql="update ".MEMBER." set statu = $statu where userid in ($chk)";
		$db->query($sql);
		unset($sql);
	}
}
function del_user($ar)
{
	global $db,$config;
	foreach($ar as $v)
	{	
		$userid=$v;
		
		ext_all('delete_by_uid',array('uid'=>$userid));
		$db->query("delete from ".MEMBER." where userid='$userid'");
		$db->query("delete from ".MEMBERINFO." where member_id='$userid'");
		$db->query("delete from ".SHOP." where userid='$userid'");
		$db->query("delete from ".FEED." where userid='$userid'");
		$db->query("delete from ".FEEDBACK." where touserid='$userid' or fromuserid='$userid'");
		$db->query("delete from ".READREC." where userid='$userid'");
		$db->query("delete from ".SHOPEARNEST." where shop_id='$userid'");
		$db->query("delete from ".ORDER." where userid='$userid'");
		$db->query("delete from ".ORPRO." where buyer_id='$userid'");
    	$db->query("delete from ".SNS." where member_id='$userid'");//删除广场信息
	}
}

if($_SESSION['province'])
	$scl.=" and provinceid='".getdistrictid($_SESSION['province'])."'";
if($_SESSION['city'])
	$scl.=" and cityid='".getdistrictid($_SESSION['city'])."'";
if($_SESSION['area'])
	$scl.=" and areaid='".getdistrictid($_SESSION['area'])."'";	
if($_SESSION['street'])
	$scl.=" and streetid='".getdistrictid($_SESSION['street'])."'";
		
if(!empty($_GET['type']) and !empty($_GET['name']))
	$scl.=" and a.".$_GET['type']." like '%".trim($_GET['name'])."%'";

if(!empty($_GET['statu']))
	$scl.=" and statu='".$_GET['statu']."'";
if(!empty($_GET['grade']))
	$scl.=" and grade_id='".$_GET['grade']."'";
if(!empty($_GET['stime']))
	$scl.=" and regtime>='".trim($_GET['stime'])."'";
if(!empty($_GET['etime']))
	$scl.=" and regtime<='".trim($_GET['etime'])."'";
if(!empty($_GET['invite']))
	$scl.=" and invite='$_GET[invite]'";

if(!empty($_GET['cardnum'])){
	if($_GET['cardnum']==1){
		//有卡会员
		$scl.=" and a.card_num!=''";
	}
	if($_GET['cardnum']==-1){
		//线上会员
		$scl.=" and IFNULL(a.card_num,1)=1";
	}
}

$sql="select 
a.logo,a.qq,a.ww,a.statu,a.userid,a.mobile,mobile_verify,a.email,email_verify,a.user,a.name,a.regtime,a.lastLoginTime,a.ip,
b.pic,
c.points from 
".MEMBER." a left join 
".MEMBERGRADE." b on a.grade_id = b.id left join 
".MEMBERINFO." c on a.userid = c.member_id  
WHERE 1 $scl order by lastLoginTime desc";


//=============================
$page = new Page;
$page->listRows=10;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",".$page->listRows;
$db->query($sql);
$de['page'] = $page->prompt();
$de['list'] = $db->getRows();

unset($_GET['m']);
unset($_GET['s']);
$getstr=implode('&',convert($_GET));
$tpl->assign("de",$de);
$tpl->assign("member_group",$member_group);

$arr = array("user"=>"用户名","name"=>"昵称","email"=>"邮箱","mobile"=>"手机"); 
$tpl->assign("arr",$arr);

$sql="select * from ".MEMBERGRADE;
$db->query($sql);
$re = $db->getRows();
$tpl->assign("re",$re);

$tpl->display("member.htm");
?>