<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("config/reg_config.php");
if($reg_config)
{
	$config = array_merge($config,$reg_config);
}

include_once("config/connect_config.php");//connect
if ($connect_config['ucenter_connect'])
{
	if ('ucenter' != $_GET['type'])
	{
		$login_url   = $connect_config['ucenter_url'] . '?ctl=Login&met=index&act=reg&typ=e';

		$callback = $config['weburl'] . '/login.php?redirect=' . urlencode($config['weburl']) . '&type=ucenter';

		$login_url = $login_url . '&from=mall&callback=' . urlencode($callback);
		header('location:' . $login_url);
	}
}

if($buid)
{	//已经登录
	msg('main.php');
}
if(is_array($stop_reg))
{
	stop_ip($stop_reg);
	unset($stop_reg);
}
//----------------------------------------------------
if(!empty($_POST['user'])&&strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
{
	if($config['closetype']==2)
	{	//关闭注册
		die('access dined!');
	}
	if($config['user_reg_verf'])
	{	//验证码不对
		if(trim($_POST['ckyzwt'])!=trim($_SESSION['YZWT']))
			 die("Verification question error...");
	}
	
	$ip = getip();
	if($config['regctrl']>0)
	{
		$sql = "select * from ".MEMBER." where ip='$ip' and DATE_ADD( NOW() ,INTERVAL -".$config['regctrl']." HOUR ) - regtime < 0";
		$db -> query($sql);
		$num = $db -> num_rows();
		if($num > 0)
		{
			die("Your IP has been registered...");
		}
	}
	if($config['regfloodctrl'] > 0)
	{
		$sql = "select * from ".MEMBER." where ip='$ip' and to_days(regtime) = to_days(now()) ";
		$db -> query($sql);
		$num = $db -> num_rows();
		if($num >= $config['regfloodctrl'])
		{
			die("Your IP has been registered...");
		}	
	}
	
	if($config['ipregctrltime']>0&&$config['ipregctrl'])
	{
		$ipregctrl = explode("\r\n",$config['ipregctrl']);
		$ipregctrl = implode("','",$ipregctrl);
		$sql = "select * from ".MEMBER." where ip in ('$ipregctrl') and DATE_ADD( NOW() ,INTERVAL -".$config['ipregctrltime']." HOUR ) - regtime < 0";
		$db -> query($sql);
		$num = $db -> num_rows();
		if($num > 0)
		{
			die("Your IP has been registered...");
		}
	}
	$user = trim($_POST['user']);
	$pass = trim($_POST['password']);
	$time = time();

	if (isset($_POST['re_password']))
	{
		$re_pass = trim($_POST['re_password']);

		if ($pass != $re_pass)
		{
			die('<script>alert("两次输入密码不一致!");history.go(-1);</script>;');
		}
	}

	if(valid_mobile($user))
	{
		$_POST["mobile"] = $mobile = $user;
		//$_POST["user"] = $user = "M".$mobile;
		$_POST["user"] = $user = $mobile;
		if(!is_repeat($user))
		{
			$_POST["user"] = $user = $user.substr(md5($time),-5);
		}
	}
	if(valid_email($user))
	{
		$_POST["email"] = $email = $user;
		$user = explode("@",$email);
		$_POST["user"] = $user = $user[0];
		if(!is_repeat($user))
		{
			$_POST["user"] = $user = $user.substr(md5($time),-5);
		}
	}
	if($config['openbbs']==2)
	{	//关联UCHENTER
		include_once('uc_client/client.php');
		$uid = uc_user_register($user, $pass, $email);
		if($uid>0)
		{
			doreg($uid);
		}
	}
	else
		doreg();
}
else
{
	if (!empty($_POST['user']))
	{
		die('<script>alert("请填写正确的注册数据!");history.go(-1);</script>;');
	}
}

function is_repeat($str)
{
	global $db;
	$sql = "select * from ".MEMBER." where user = '$str'";
	$db -> query($sql);
	$num = $db->num_rows();
	return $num > 0 ? false : true;
}
function valid_mobile($str)//手机号码正则表达试
{                 
 	return ( ! preg_match("/^13[0-9]{1}[0-9]{8}$|14[57]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$str))?FALSE:TRUE;
}

function valid_email($str)
{
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

function doreg($guid=NULL)
{
	global $db,$config,$ip;
	$user = addslashes($_POST['user']);
	$pass = $_POST['password'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];
	$email_verify = $email&&$config['user_reg']==2 ? "1":"0";
	$mobile_verify = $mobile&&$config['user_reg']==3 ? "1":"0";
	
	$ip = getip();
	$ip = empty($ip)?NULL:$ip;
	$lastLoginTime = time();
	$regtime = date("Y-m-d H:i:s");
	$user_reg = "2";
	
	$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
	
	$sql="select * from ".MEMBER." where user = '$user'";
    $db->query($sql);
    if($db->num_rows())
		die('<script>alert("User name is have");history.go(-1);</script>;');

	$sql="insert into ".MEMBER." (user,password,ip,lastLoginTime,email,mobile,regtime,statu,email_verify,mobile_verify) values ('$user','".md5($pass)."','$ip','$lastLoginTime','$email','$mobile','$regtime','$user_reg','$email_verify','$mobile_verify')";
	$re=$db->query($sql);
	$userid=$db->lastid();
	
	if($userid)
	{	
		$sql="INSERT INTO ".MEMBERINFO." (member_id) VALUES ('$userid')";
		$re=$db->query($sql);
		
		if($re)
		{
			$post['userid'] = $userid;
			$post['email'] = $user;
			$pay_id = member_get_url($post,true);	
			if($pay_id)
			{
				$sql="update ".MEMBER." set pay_id='$pay_id' where userid='$userid'";
				$re=$db->query($sql);	
			}
			//-------------绑定一键连接
			if(!empty($_REQUEST['connect_id']))
			{
				$sql="update ".USERCOON." set userid='$userid' where id='$_REQUEST[connect_id]'";
				$db->query($sql);
			}
			//---------------设置加密的cookie
			bsetcookie("USERID","$userid\t$user",NULL,"/",$config['baseurl']);
			setcookie("USER",$user,NULL,"/",$config['baseurl']);

			//
			$PluginManager = Yf_Plugin_Manager::getInstance();
			$PluginManager->trigger('reg_done', $userid, $user);

			if($config['temp'] == 'wap')
				header("Location: main.php?cg_u_type=1");
			else
				header("Location: main.php?m=member&s=admin_member&cg_u_type=1&from_register=1");
		}
	 }
	 else
		 die("Can not register...");
}
include_once("footer.php");	
$tpl->display("register.htm");
?>