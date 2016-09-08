<?php
if(!empty($_GET["action"]))
	$post=$_GET;
else
	$post=$_POST;

if(!empty($_GET['forward'])&&strpos($_GET['forward'],'script')>0)
	header("Location:login.php");

if(!empty($post["action"])&&$post["action"]=="submit")
{
	include_once("includes/global.php");
	include_once("includes/smarty_config.php");
	include_once("config/reg_config.php");
	if(strtolower($_SESSION["auth"])!=strtolower($post["randcode"])&&empty($post['first_index'])&&empty($post['connect_id']))
	{
		header("Location: login.php?erry=-3");
		exit();
	} 
	$config = array_merge($config,$reg_config);
	if($config['openbbs']==2)
	{	
	//ucenter1.5 login
		$sql="select userid,user,password,email from ".MEMBER." a where user='$post[user]' or email='$post[user]'";
		$db->query($sql);
		$re=$db->fetchRow();
		if(!empty($re['password']))
		{
            if($re['statu']=='-2'){
                msg("login.php?erry=-5&connect_id=$post[connect_id]");
            }
			if(substr($re['password'],0,4)=='lock')
				msg("login.php?erry=-4&connect_id=$post[connect_id]");
			if($re['password']!=md5($post['password']))
				msg("login.php?erry=-2&connect_id=$post[connect_id]");
		}
		include_once('uc_client/client.php');
		
		$user = $re['user'] ? $re['user'] : $post['user'];
	
		list($uid, $username, $password, $email) = uc_user_login($user, $post['password']);
	
		if($uid>0||$re["userid"])
		{	
			if($uid<=0&&$re["userid"]>0)
			{
				$uid = uc_user_register($re['user'], $post['password'], $re['email']);
				if($re['pid'])
					login($re['pid'],$re['user'],$re['userid']);
				else
					login($re['userid'],$re['user']);
			}
			elseif($uid>0&&$re["userid"]<=0)
			{
				$dbc=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
				
				$ip=getip();
				$dbc->query("insert into ".MEMBER." (user,email,password,ip,statu) values 
				('$post[user]','$email','".md5($post['password'])."','$ip','1')");
				$re['userid']=$dbc->lastid();
				$re['user']=$_POST['user'];
				
				if(empty($config['user_reg']))
					$user_reg=1;
				elseif($config['user_reg']==3)
					$user_reg=1;
				else
					$user_reg=$config['user_reg'];
				login($re['userid'],$re['user']);
			}
			else
			{
				if($re['pid'])
					login($re['pid'],$re['user'],$re['userid']);
				else
					login($re['userid'],$re['user']);
			}
			echo uc_user_synlogin($uid);
			$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
			msg($forward);
		}
		else
		{
			header("Location: login.php?erry=-1&connect_id=".$post['connect_id']);//没
			exit();
		}
	}
	else
	{	
		// no ucenter login
		$sql="select * from ".MEMBER." where user='$post[user]' or email='$post[user]' or mobile='$post[user]'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($re["userid"])
		{
            if($re['statu']=='-2'){
                msg("login.php?erry=-5&connect_id=$post[connect_id]");
            }
			if(substr($re['password'],0,4)=='lock')
				msg("login.php?erry=-4&connect_id=$post[connect_id]");
			if($re['password']!=md5($post['password']))
				msg("login.php?erry=-2&connect_id=$post[connect_id]");
			
			if($re["password"]==md5($post['password']))
			{
				if($re['pid'])
					login($re['pid'],$re['user'],$re['userid']);
				else
					login($re['userid'],$re['user']);
					
				$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php?cg_u_type=1";
				msg($forward);
			}
		}
		else
			msg('login.php?erry=-1&connect_id='.$post['connect_id']);//没
	}
}
//========================================================
function login($uid,$username,$pid=NULL,$type=NULL)
{
	global $post,$config;
	$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
	if($uid)
		$sql="select pay_id,userid,user,statu from ".MEMBER." a where a.userid='$uid'";
	else
		$sql="select pay_id,userid,user,statu from ".MEMBER." where user='$username'";
	$db->query($sql);
	$re=$db->fetchRow();

	if($type)
	{
		$time=time()+3600*24*7;
	}
	else
	{
		$time=NULL;
	}
	//if(!empty($_COOKIE["MEMBERID"]))
	//{
		//bsetcookie("MEMBERID",NULL,time(),"/",$config['baseurl']);	
	//}
	bsetcookie("USERID","$uid\t$re[user]\t$pid",$time,"/",$config['baseurl']);
	setcookie("USER",$re['user'],$time,"/",$config['baseurl']);
	
	//bsetcookie("MEMBERID","$re[pay_id]",$time,"/",$config['baseurl']);
	//setcookie("PAYID",$re['pay_id'],NULL,"/",$config['baseurl']);
	$_SESSION["STATU"]=$re['statu'];
	$str = "" ;
	if(!$re['pay_id'])
	{
		$post['userid'] = $re['userid'];
		$post['email'] = $re['user'];
		$pay_id = member_get_url($post,true);	
		if($pay_id)
		{
			$str = " , pay_id='$pay_id'" ;
		}
	}
	$sql="update ".MEMBER." set lastLoginTime='".time()."' $str WHERE userid='$uid'";
	$db->query($sql);

	//--------------------------------------qq
	if(!empty($post['connect_id']))
	{
		$sql="update ".USERCOON." set userid='$uid' where id='$post[connect_id]'";
		$db->query($sql);
	}


	//自动根据openid登录操作
	if ($uid && $_SESSION['openid_f'])
	{
		$sql = "update " . MEMBER . " SET `open_id`='" . $_SESSION['openid_f'] . "' WHERE `userid`='$uid' AND open_id = ''";
		$re = $db -> query($sql);
	}
}
function do_post($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$ret =  curl_exec($ch);

	if (curl_errno($ch))
	{
		echo "Error Occured in Curl\n";
		echo "Error number: " . curl_errno($ch) . "\n";
		echo "Error message: " . curl_error($ch) . "\n";
	}

    curl_close($ch);
    return $ret;
}
function get_url_contents($url)
{
	/*
    if(ini_get("allow_url_fopen") == "1")
        return file_get_contents($url);
	*/
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result =  curl_exec($ch);

	if (curl_errno($ch))
	{
		echo "Error Occured in Curl\n";
		echo "Error number: " . curl_errno($ch) . "\n";
		echo "Error message: " . curl_error($ch) . "\n";
	}

    curl_close($ch);
    return $result;
}
//==================================================================================
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("config/reg_config.php");
include_once("config/connect_config.php");//connect
$config = array_merge($config,$reg_config);
$config = array_merge($config,$connect_config);



if (isset($_REQUEST['app_from']) && 'mall_app'==$_REQUEST['app_from'])
{
	//app 内嵌功能
	$rs =  file_get_contents($_REQUEST['verify_url']);
	$user_data_rows = json_decode($rs, true);

	$userid = $user_data_rows['data']['user_id'];

	if (200 == $user_data_rows['status'] && $userid)
	{
		//成功
		$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);

		$sql="select * from ".MEMBER." where userid = '$userid'";
		$db->query($sql);
		if($db->num_rows())
		{
			//登录成功
			login($userid, NULL);

			if(isset($_REQUEST['open_url']))
			{
				header("Location:" . $_REQUEST['open_url']);

				exit();
			}
		}
	}


	if($config['temp'] == 'wap')
		header("Location: main.php?cg_u_type=1");
	else
		header("Location: main.php?m=member&s=admin_member&cg_u_type=1&from_register=1");

	exit();
}


include_once("config/connect_config.php");//connect
if ($connect_config['ucenter_connect'])
{
	//isset($_REQUEST['app_url'])  app修正内嵌调用, 来用判断权限是否正确
	if (!isset($_REQUEST['app_url']) && 'ucenter' != $_GET['type'])
	{
		$login_url = $connect_config['ucenter_url'] . '?ctl=Login&met=index&typ=e';
		$reg_url = $connect_config['ucenter_url'] . '?ctl=Login&met=regist&typ=e';
		$findpwd_url = $connect_config['ucenter_url'] . '?ctl=Login&met=findpwd&typ=e';

		$callback = $config['weburl'] . '/login.php?redirect=' . urlencode($config['weburl']) . '&type=ucenter';


		$login_url = $login_url . '&from=mall&callback=' . urlencode($callback);
		header('location:' . $login_url);
		exit();
	}
	else
	{
		//app 内嵌功能
		if (isset($_REQUEST['app_url']))
		{
			$rs =  file_get_contents($_REQUEST['app_url']);
			$user_data_rows = json_decode($rs, true);

			$userid = $user_data_rows['data']['user_id'];

			$user_data_rows['data']['user_name'] = $user_data_rows['data']['user_account'];
			$user_data_rows['data']['password'] = $user_data_rows['data']['user_password'];
			$user_data_rows['data']['email'] = $user_data_rows['data']['user_email'];
		}
		else
		{
			$userid = intval($_GET['us']);
			$k = urldecode($_GET['ks']);

			//根据$userid和$k, 进入ucenter验证并获获得用户详细信息
			$rs =  file_get_contents($connect_config['ucenter_url'] . '?ctl=Api&met=checkLogin&typ=json&u=' . $userid . '&k=' . urlencode($k));
			$user_data_rows = json_decode($rs, true);

		}

		if (200 != $user_data_rows['status'])
		{
			//登录报错
		}

		//判断userid是否存在,不存在,启用注册激活功能
		$dbc=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);

		$user = addslashes($user_data_rows['data']['user_name']);
		$pass = $user_data_rows['data']['password'];
		$email = $user_data_rows['data']['email'];
		$mobile = $user_data_rows['data']['user_mobile'];
		$email_verify = $email&&$config['user_reg']==2 ? "1":"0";
		$mobile_verify = $mobile&&$config['user_reg']==3 ? "1":"0";

		$ip = getip();
		$ip = empty($ip)?NULL:$ip;
		$lastLoginTime = time();
		$regtime = date("Y-m-d H:i:s");
		$user_reg = "2";

		$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);

		$sql="select * from ".MEMBER." where userid = '$userid'";
		$db->query($sql);
		if($db->num_rows())
		{
			//登录成功
		}
		else
		{
			//激活
			$sql="insert into ".MEMBER." (userid, user,password,ip,lastLoginTime,email,mobile,regtime,statu,email_verify,mobile_verify) values ('$userid','$user','".md5($pass)."','$ip','$lastLoginTime','$email','$mobile','$regtime','$user_reg','$email_verify','$mobile_verify')";
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

					//
					$PluginManager = Yf_Plugin_Manager::getInstance();
					$PluginManager->trigger('reg_done', $userid, $user);
				}
			}
			else
				die("Can not register...");
		}


		login($userid, NULL);


		if($config['temp'] == 'wap')
			header("Location: main.php?cg_u_type=1");
		else
			header("Location: main.php?m=member&s=admin_member&cg_u_type=1&from_register=1");
		//$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
		//msg($forward);
		exit();
	}
}


if($config['sina_connect']==1)//sina
{
	define( "WB_AKEY" , $config['sina_app_id'] );
	define( "WB_SKEY" , $config['sina_key'] );
	define( "WB_CALLBACK_URL" , "$config[weburl]/login.php?type=sina" );
	include_once( 'includes/saetv2.ex.class.php' );
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	//------------------------------------------
	if($_GET['type']=='sina'&&isset($_REQUEST['code']))
	{
		$keys = array();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = WB_CALLBACK_URL;
		$token = $o->getAccessToken( 'code', $keys ) ;
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $token['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$ar = $c->show_user_by_id( $uid);
		//------------
		$sql="select * from ".USERCOON." where type=2 and client_id='$ar[id]'";
		$db->query($sql);
		$cre=$db->fetchRow();
		if(empty($cre['id']))
		{
			$sql="insert into ".USERCOON." 
			(nickname,figureurl,gender,type,access_token,client_id) 
			values 
			('$ar[name]','$ar[profile_image_url]','$ar[gender]',2,'$token[access_token]','$ar[id]')";
			$db->query($sql);
			$cre['id']=$db->lastid();
		}
		if($cre['userid'])
		{
			login($cre['userid'],NULL);
			$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
			msg($forward);
		}
		else
		{
			msg("login.php?connect_id=$cre[id]");
		}
	}
	//-------------------------------------------
	$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
	$tpl->assign("sina_login_url",$code_url);
}
if(!empty($_GET['code'])&&$config['qq_connect']==1&&$_GET['type']!='sina'&&$_GET['connect_type']!='weixin')//QQ
{
	//-----------------
	$config['return']=urlencode($config['weburl'].'/login.php');
	$url="https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
	."client_id=$config[qq_app_id]"
	."&client_secret=$config[qq_key]"
	."&code=$_GET[code]"
	."&state=$config[company]"
	."&redirect_uri=$config[return]";
	$takenid=@get_url_contents($url);
	//----------------
	$url2="https://graph.qq.com/oauth2.0/me?$takenid";
	$con=get_url_contents($url2);
	$lpos = strpos($con, "(");
	$rpos = strrpos($con, ")");
	$con  = substr($con, $lpos + 1, $rpos - $lpos -1);
	$ar2=json_decode($con,true);
	//----------------
	$url3 = "https://graph.qq.com/user/get_user_info?"
	. $takenid
	. "&oauth_consumer_key=" . $config['qq_app_id']
	. "&openid=" . $ar2["openid"]
	. "&format=json";
	$con=get_url_contents($url3);
	$ar=json_decode($con,true);
	//--------------------------
	$sql="select * from ".USERCOON." where type=1 and openid='$ar2[openid]'";
	$db->query($sql);
	$cre=$db->fetchRow();
	if(empty($cre['id']))
	{
		$sql="insert into ".USERCOON." 
		(nickname,figureurl,gender,vip,level,type,access_token,client_id,openid) 
		values 
		('$ar[nickname]','$ar[figureurl]','$ar[gender]','$ar[vip]','$ar[level]',1,'$takenid','$ar2[client_id]','$ar2[openid]')";
		$db->query($sql);
		$cre['id']=$db->lastid();
	}
	if($cre['userid'])
	{
		login($cre['userid'],NULL);
		$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
		msg($forward);
	}
	else
	{
		msg("login.php?connect_id=$cre[id]&connect_nickname=" . urlencode($ar['nickname']));
	}
	
}


//微信登录
@include_once("config/connect_config.php");


function is_repeat($str)
{
	global $db;
	$sql = "select * from ".MEMBER." where user = '$str'";
	$db -> query($sql);
	$num = $db->num_rows();
	if($num <= 0)
	{
		return $str;
	}
	else
	{
		$str = $str ."_". substr(md5(time()),0,2);
		return is_repeat($str);
	}
}

if ($config['weixin_connect'] && !isset($_GET['connect_id']))
{
	$appid = $config['weixin_app_id'];
	$appsecret = $config['weixin_key'];

	$redirect_uri = urlencode("$config[weburl]/login.php?connect_type=weixin");

	if($config['bw'] == "weixin")
	{
		$wechat_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_login&state=123&connect_redirect=1#wechat_redirect";
	}
	else
	{
		$wechat_url = "https://open.weixin.qq.com/connect/qrconnect?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
	}

	$_SESSION['connect_name'] = '微信';
	$tpl -> assign("wechat_url",$wechat_url);

	if ($config['bw'] == "weixin")
	{
		if (!isset($_GET['code']))
		{
			if (true || $wechat_login_flag == true)
			{
				header('location:' . $wechat_url);
				die();
			}
		}
	}

	$code = $_GET['code'];
	$state = $_GET['state'];

	if(!empty($code))
	{
		$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$token = json_decode(file_get_contents($token_url));

		$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
		$access_token = json_decode(file_get_contents($access_token_url));

		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
		$user_info = json_decode(@file_get_contents($user_info_url));

		$openid = $user_info -> openid;
		$nickname = $user_info -> nickname;


		if($openid)
		{
			//--------------------------
			$sql="select * from ".USERCOON." where type=3 and openid='$openid'";
			$db->query($sql);
			$cre=$db->fetchRow();

			if(empty($cre['id']))
			{
				$sql="insert into ".USERCOON."(nickname,figureurl,gender,vip,level,type,access_token,client_id,openid)
						values('$nickname','$ar[figureurl]','$ar[gender]','$ar[vip]','$ar[level]',3,'$takenid','$ar2[client_id]','$openid')";
				$db->query($sql);
				$cre['id']=$db->lastid();
			}
			else
			{
			}

			//判断userid ， bind
			if(!$cre['userid'])
			{
				//-------------绑定一键连接
				if(!empty($cre['id']))
				{
					$sql="update ".USERCOON." set userid='$buid' where id='$cre[id]'";
					$db->query($sql);
				}
			}

			if($cre['userid'])
			{
				login($cre['userid'],NULL);
				$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
				msg($forward);
			}
			else
			{
				$_SESSION['connect_name'] = '微信';
				msg("login.php?connect_id=$cre[id]");
			}
		}
		/*
		if($openid)
		{
			$sql = "select userid,user from ".MEMBER." where open_id = '$openid'";
			$db -> query($sql);
			$re = $db -> fetchRow();
			if($re['userid'])
			{
				$nickname = $re['user'];
				$member_id = $re['userid'];
			}
			else
			{
				$nickname = is_repeat($user_info -> nickname);
				$sql="insert into ".MEMBER." (user,password,ip,lastLoginTime,email,mobile,regtime,statu,email_verify,mobile_verify,open_id) values ('$nickname','','".getip()."','".time()."','','','".date("Y-m-d H:i:s")."','2','0','0','$openid')";
				$re = $db -> query($sql);
				$member_id = $db -> lastid();


				//
				$PluginManager = Yf_Plugin_Manager::getInstance();
				$PluginManager->trigger('reg_done', $member_id, $nickname);

			}
			login($member_id,$nickname);
			msg($config["weburl"] . "/main.php");
		}
		*/
	}
}

//===========================================
if($buid)
{
	header("Location:main.php");
	exit();
}

include_once("footer.php");
$tpl -> assign("lang",$lang);

if(!empty($_GET['connect_id']))
	$tpl->display("user_connect.htm");
else
	$tpl->display("login.htm");
?>