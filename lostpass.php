<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include("config/nav_menu.php");
//=========================================
$page="lostpass.htm";
if(!empty($_GET['msg']))
{
	$page="reset_pass.htm";
}
if(!empty($_POST['resetpass'])&&!empty($_POST['newpass'])&&!empty($_GET['md5']))
{
	//重设密码
	if($_POST['newpass']!=$_POST['newpass1'])
		msg("lostpass.php?msg=1&userid=$_GET[userid]&md5=$_GET[md5]");
	else
	{
		$db->query("update ".MEMBER." set password='".md5($_POST['newpass'])."'
		           where userid='$_GET[userid]' and password='$_GET[md5]'");
		msg("lostpass.php?msg=2");
	}
}
if(!empty($_GET['md5'])&&!empty($_GET['userid']))
{	
	//调出重设密码的模板
	$sql="select * from ".MEMBER." where userid='$_GET[userid]' and password='$_GET[md5]'";
	$db->query($sql);
	$uid=$db->fetchField('userid');
	if($uid)
		$page='reset_pass.htm';
}
if(!empty($_POST["action"])&&$_POST["action"]=="com"&&!empty($_POST['user']))
{//根据用户名和密码确定是哪一个公司在找回密码
	$sql="select * from ".MEMBER." where user='$_POST[user]' and email='$_POST[email]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(!$re)
	{
		msg("lostpass.php","会员不存在！");
	}
	else
	{
		$md5=md5(time().rand(0,100));
		$md5='lock'.substr($md5,5,strlen($md5));
		$db->query("update ".MEMBER." SET password='$md5' where userid='$re[userid]'");
		
		$mail_temp=get_mail_template('find_pwd');

		$link=$config['weburl']."/lostpass.php?md5=$md5&userid=$re[userid]";
		$link="<a target='_blank' href='".$link."'>".$link."</a>";
		
		$con=$mail_temp['message'];
		$title=$mail_temp['title'];
		$user=$re['user'];
		$time=date("Y-m-d H:i:s");
		
		$logo='<img height="24" src="'.$config['weburl'].'/image/logo.gif"  style="border:none;margin:0;">';
	
		$ar1=array('[time]','[logo]','[member_name]','[weburl_name]','[link]','[weburl_email]','[weburl_tel]','[weburl_url]','[weburl_desc]','[weburl_desc]');
		$ar2=array($time,$logo,$user,$config['company'],$link,$config['email'],$config['owntel'],$config['weburl'],$config['description']);
		$con=str_replace($ar1,$ar2,$con);
	
		$ar3=array('[member_name]','[weburl_name]');
		$ar4=array($user,$config['company']);
		$title=str_replace($ar3,$ar4,$title);
		send_mail($re["email"],$re["user"], $title,$con);
		$tpl->assign("email",$re["email"]);
	}
	$tpl->assign('p_email',$re["email"]);
	$page="lostpass_steptwo.htm";
}
//===========页面底部===============
include_once("footer.php");
if($config['language']=='en')
{
	$tpl->assign("output",$tpl -> fetch($page));
	$tpl->display("register_inc.htm");
}
else
	$tpl->display($page);
?>