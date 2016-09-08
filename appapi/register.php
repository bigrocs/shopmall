<?php
	
	## 传递参数列表
	## user【用户名】, password【密码】,email【邮箱】,ip【注册IP】

	## 返回状态参数列表 
	## 0【关闭注册】】,2【IP被禁止注册】,3【IP已经注册过账户号】,4【用户名已存在】，5【未知错误】,6【注册成功】

	include_once("../includes/global.php");
	include_once("../includes/smarty_config.php");
	include_once("../config/reg_config.php");

	if($reg_config)
	{
		$config = array_merge($config,$reg_config);
	}
	if(file_get_contents("php://input"))
		$_POST = json_decode(file_get_contents("php://input"),true);


	if(is_array($stop_reg))
	{
		stop_ip($stop_reg);
		unset($stop_reg);
	}
	//==============================================
	$t = "";

	if(!empty($_POST['user']))
	{
		if($config['closetype']==2)
		{	//关闭注册
			$t = 0;
		}
		if($config['user_reg_verf'])
		{	//验证码不对
			if(trim($_POST['ckyzwt'])!=trim($_SESSION['YZWT']))
				$t = -1;
		}
		if($config['inhibit_ip']==1)
		{	//ip禁止注册
			$ip=getip();
			if(empty($ip))
				$t = 2;
			else
			{	
				$config['exception_ip']=explode("\r\n",$config['exception_ip']);
				if(!in_array($ip,$config['exception_ip']))
				{
					$sql="select ip from ".MEMBER." where ip='$ip'";
					$db->query($sql);
					if($db->num_rows())
						$t = 3;
					unset($sql);
				}
			}
		}
		if($config['openbbs']==2)
		{
			//关联UCHENTER
			include_once('../uc_client/client.php');
			
			$user=trim($_POST['user']);
			$pass=trim($_POST['password']);
			$email=trim($_POST['email']);
			$regtime=time();
			// $uid = uc_user_register($user, $pass, $email);
			$uid = 10;
			if($uid < 0){
				echo $uid;
				exit; 
			}
			if($uid>0)
			{
				$t = doreg($uid);
			}
		}
		else
			$t = doreg();
	}

	echo $t;

	
	function doreg($guid=NULL)
	{
		global $db,$config,$ip;
		$user=$_POST['user'];
		$pass=$_POST['password'];
		$email=$_POST['user'];
		$ip=$_POST['ip'];

		$ip=empty($ip)?NULL:$ip;
		$lastLoginTime=time();
		$regtime=date("Y-m-d H:i:s");
		
		// 防止DB冲突  //

		$user_reg=$config['user_reg']==3?"1":"2";
		
		$sql="select * from ".MEMBER." where user = '$user'";
	    $db->query($sql);
	    if($db->num_rows())
			return 4;


		$sql="insert into ".MEMBER." (user,password,ip,lastLoginTime,email,regtime,statu) values ('$user','".md5($pass)."','$ip','$lastLoginTime','$user','$regtime','$user_reg')";
		$re=$db->query($sql);
		$userid=$db->lastid();
		
		if($userid)
		{	
			$sql="INSERT INTO ".MEMBERINFO." (member_id) VALUES ('$userid')";
			$re=$db->query($sql);
			
			if($re)
			{
				$post['userid'] = $userid;
				$post['email'] = $email;
				$pay_id = member_get_url($post,true);	
				
				if($pay_id)
				{
					$sql="update ".MEMBER." set pay_id='$pay_id' where userid='$userid'";
					$re=$db->query($sql);	
				}
			
				//-------------绑定一键连接
				if(!empty($_POST['connect_id']))
				{
					$sql="update ".USERCOON." set userid='$userid' where id='$_POST[connect_id]'";
					$db->query($sql);
				}
				//---------------设置加密的cookie
				bsetcookie("USERID","$userid\t$user",NULL,"/",$config['baseurl']);
				setcookie("USER",$user,NULL,"/",$config['baseurl']);
				return 6;
				//header("Location: main.php?cg_u_type=1");
				//header("Location: ".$config["weburl"]."/?m=member&s=new_email_reg_two");
			}
		 }
		 else
			 return 5;
	}
?>