<?php
if(!empty($_GET["username"])&&!empty($_GET["password"]))
{
	include_once("../includes/global.php");
	include_once("../includes/smarty_config.php");
	$sql="select * from ".MEMBER." where user='$_GET[username]' or email='$_GET[username]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re["userid"])
	{
		if(substr($re['password'],0,4)=='lock')
			{
				$loginResult=array(
					'loginResult'=>"找回密码中。。。",
				);
			}
		if($re['password']!=md5($_GET['password']))
			{
				$loginResult=array(
					'loginResult'=>"密码错误",
				);
			}
		if($re["password"]==md5($_GET['password']))
		{
			login($re['userid']);
			$loginResult=array(
				'loginResult'=>"success",
			);
		}
	}else{
		$loginResult=array(
			'loginResult'=>"用户不存在",
		);
	}
	$itemsSearchRel=json_encode($loginResult);
	$callback=$_GET['callback'];
	echo $callback."($itemsSearchRel)";
}
//========================================================
function login($uid)
{
	global $config;
	$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
	if($uid)
		$sql="select pay_id,userid,user,statu from ".MEMBER." a where a.userid='$uid'";
	$db->query($sql);
	$re=$db->fetchRow();
	$time=NULL;
	//bsetcookie("USERID","$uid\t$re[user]\t$pid",$time,"/",$config['baseurl']);
	//setcookie("USER",$re['user'],$time,"/",$config['baseurl']);
	//$_SESSION["STATU"]=$re['statu'];
	/*$str = "" ;
	if(!$re['pay_id'])
	{
		$post['userid'] = $re['userid'];
		$post['email'] = $re['user'];
		$pay_id = member_get_url($post,true);	
		if($pay_id)
		{
			$str = " , pay_id='$pay_id'" ;
		}
	}*/
	$sql="update ".MEMBER." set lastLoginTime='".time()."' WHERE userid='$uid'";
	$db->query($sql);
}
?>