<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
include_once("../config/reg_config.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//---------------------------------
$post=$_GET;
$config = array_merge($config,$reg_config);
if(!empty($post["action"])&&$post["action"]=="submit")
{
		
	// no ucenter login
	$sql="select * from ".MEMBER." where user='$post[user]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re["userid"])
	{
		login($re['pay_id'],$re['pay_email']);
		msg('../main.php');
	}
}
//========================================================
function login($uid,$username)
{
	global $config;
	bsetcookie("USERID","$uid\t$username",time()+60*60*24,"/",$config['baseurl']);
	setcookie("USER",$username,time()+60*60*24,"/",$config['baseurl']);
	unset($_SESSION["IFPAY"]);
}
?>