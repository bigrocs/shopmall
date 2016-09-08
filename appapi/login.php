<?php

	## 传递参数列表
	## user【用户名】, password【密码】,randcode【验证码】

	## 返回状态参数列表
	## 1【用户已被锁定】,2【账号或者密码不正确】,4【用户不存在】,6【登录成功】

	include_once("../includes/global.php");
	include_once("../includes/smarty_config.php");
	include_once("../config/reg_config.php");

	$t = "6";
	
	
	if(file_get_contents("php://input"))
		$_POST = json_decode(file_get_contents("php://input"),true);
	$post = $_POST;
	$config = array_merge($config,$reg_config);

	$sql="select userid,user,password,email from ".MEMBER." a where user='$post[user]' or email='$post[user]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(!empty($re['password']))
	{
		if(substr($re['password'],0,4)=='lock')
			$t = 1;
		if($re['password']!=md5($post['password']))
			$t = 2;
	}
	include_once('../uc_client/client.php');
	
	list($uid, $username, $password, $email) = uc_user_login($post['user'], $post['password']);
	
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
			$dbc->query("insert into ".MEMBER." (user,email,password,ip) values 
			('$post[user]','$email','".md5($post['password'])."','$ip')");
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
	}
	else
	{
		$t = 3;
	}

	
	echo $t;

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
		
		bsetcookie("USERID","$uid\t$re[user]\t$pid",$time,"/",$config['baseurl']);
		setcookie("USER",$re['user'],$time,"/",$config['baseurl']);
		
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
	}
?>