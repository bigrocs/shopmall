<?php
//$val = $_REQUEST?$_REQUEST:$_GET;
if(!empty($_REQUEST["uname"])&&!empty($_REQUEST["password"]))
{
	include_once("../includes/global.php");
	include_once("../includes/smarty_config.php");
	$sql="select * from ".MEMBER." where user='$_REQUEST[uname]' or email='$_REQUEST[uname]' or mobile='$_REQUEST[uname]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re["userid"])
	{
		if(substr($re['password'],0,4)=='lock')
        {
            $loginResult = array(
                array("cmd_id"=>-1, 'b'=>array('msg'=>'找回密码中。。。'))
            );
        }
		if($re['password']!=md5($_REQUEST['password']))
        {
            $loginResult = array(
                array("cmd_id"=>-1, 'b'=>array('msg'=>'密码错误'))
            );

        }
		if($re["password"]==md5($_REQUEST['password']))
		{
			login($re['userid']);
			$loginResult=array(
				'result'=>"success",
				'uid'=>$re['userid'],
				'user'=>$re['user'],
				'name'=>$re['name'],
				'logo'=>$re['logo'],
			);

		    $time = time() + 3600*24*70;

            $userid = $re['userid'];
            $user   = $re['user'];
            $name   = $re['name'];

 			bsetcookie("USERID", "$userid\t$user", $time, "/", $config['baseurl']);
			setcookie("USER", $user, $time, "/", $config['baseurl']);
            $_SESSION["STATU"] = $re['statu'];

            $loginResult = array(
                array("cmd_id"=>-100, 'b'=>$loginResult)
            );
		}
	}else{
        $loginResult = array(
            array("cmd_id"=>-1, 'b'=>array('msg'=>'用户不存在'))
        );
	}
}else{
    $loginResult = array(
        array("cmd_id"=>-1, 'b'=>array('msg'=>'请输入用户名和密码'))
    );
}

if (!empty($_REQUEST["callback"]))
{
    $url = $config['baseurl'];
    if (isset($loginResult[0]['b']['uid']))
    {
        echo $_REQUEST["callback"] . '(true)';
    }
    else
    {
        echo $_REQUEST["callback"] . '(false)';
    }
}
else
{
    print_r( json_encode($loginResult));
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
	$sql="update ".MEMBER." set lastLoginTime='".time()."' WHERE userid='$uid'";
	$db->query($sql);
}
?>