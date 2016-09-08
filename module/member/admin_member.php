<?php
include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
$member=new member();
//===================================================================

if($_POST['submit']=='edit')
{	
	$member->update_member($_POST['uid']);
    if($_GET['from_register']){
        $admin->msg("$config[weburl]");
    }else{
        $admin->msg("main.php?m=member&s=admin_member");
    }
}
if($_POST['submit']=='password')
{	
	$flag=$member->resetpass($buid);
	if($flag=='0')
		$admin->msg("main.php?m=member&s=admin_member&type=password",'参数错误','failure');
	elseif($flag=='1')
		$admin->msg("main.php?m=member&s=admin_member&type=password",'密码错误','failure');
	elseif($flag=='2')
		$admin->msg("main.php?m=member&s=admin_member&type=password",'新密码两次输入不正确','failure');
	else
		$admin->msg("main.php?m=member&s=admin_member&type=password");
}
if($_POST['submit']=='email')
{
	if($_POST['yzm'] == $_SESSION['auth'])
	{
		$sql = "UPDATE ".MEMBER." SET email='".$_POST['email']."' , email_verify='1' WHERE userid='$buid'";
		$re = $db->query($sql);
		$admin->msg("main.php?m=member&s=admin_member");
	}
	else
	{
		$admin->msg("main.php?m=member&s=admin_member&type=email","验证码错误","failure");
	}
}
if($_POST['submit']=='mobile')
{
	if($_POST['yzm'] == $_SESSION['auth'])
	{
		$sql = "UPDATE ".MEMBER." SET mobile ='".$_POST['mobile']."' , mobile_verify='1' WHERE userid='$buid'";
		$re = $db->query($sql);
		$admin->msg("main.php?m=member&s=admin_member");
	}
	else
	{
		$admin->msg("main.php?m=member&s=admin_member&type=mobile","验证码错误","failure");
	}
}
include_once("lang/".$config['language']."/company_type_config.php");
$tpl->assign("de",$de=$member->get_member_detail($_GET['editid']));
$tpl->assign("prov",GetDistrict());


//wechat
include_once("config/connect_config.php");//connect
$config = array_merge($config,$connect_config);

if (1 == $config['weixin_connect'])
{
	$appid = $config['weixin_app_id'];
	$appsecret = $config['weixin_key'];

	$redirect_uri = urlencode("$config[weburl]/login.php?connect_type=weixin");
	$wechat_url = "https://open.weixin.qq.com/connect/qrconnect?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";

	$_SESSION['connect_name'] = '微信';
	$tpl -> assign("wechat_url",$wechat_url);
	$tpl->assign("wechat_connect_flag", true);

	$sql="select * from ".USERCOON." where type=3 and userid='$buid'";
	$db->query($sql);
	$cre=$db->fetchRow();

	$tpl->assign("wechat_connect_row", $cre);
}

if(!empty($_GET['guid']))
{
	include_once('uc_client/client.php');
	$tpl->assign("slogin",uc_user_synlogin($_GET['guid']));
}
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_member.htm");
?>