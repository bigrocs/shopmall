<?php
//$val = $_REQUEST?$_REQUEST:$_GET;
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");

$input_data = trim(file_get_contents("php://input"));

if ($input_data)
{
    parse_str($input_data, $user_request_data);

    if (is_array($user_request_data))
    {
        $_REQUEST = array_merge($_REQUEST, $user_request_data);
    }
}
if(isset($_REQUEST["method"])&&$_REQUEST["method"]=="send_code")//发送验证码
{
    $mobile = trim($_REQUEST["mobile"]);
    $sql = "select message from ".MAILMOD." where `flag` ='register_mobile' and type=2 ";
    $db -> query($sql);
    $me = $db -> fetchField("message");

    $serial = randomkeys();
    $_SESSION['auth'] = $serial;

    /*$me = str_replace("[webname]", $config['company'], $me);
    $me = str_replace("[serial]", $serial, $me);

    $url = $config['weburl']."/api/msg.php?mobile=".$mobile."&me=".urlencode($me);
    $str = file_get_contents($url);*/
    $list["result"]="success";
    $list["auth"]=$_SESSION['auth'];
    print_r(json_encode($list));
}elseif(isset($_REQUEST["method"])&&$_REQUEST["method"]=="ver_code")//判断验证码是否正确
{
	if(strtolower($_REQUEST["yzm"])!=strtolower($_SESSION['auth']))
	{
		$list["result"]="false";
		$list["yzm"]=$_SESSION['auth'];
	}else{
		$list["result"]="success";
	}
	print_r(json_encode($list));
}elseif(isset($_REQUEST["method"])&&$_REQUEST["method"]=="check_user")//判断手机号是否存在
{
    if(valid_mobile($_REQUEST["mobile"]))
    {
        $mobile = $_REQUEST["mobile"];
        $_REQUEST["user"] = $user = "M".$mobile;
        if(is_repeat($user))
        {
            $list["result"]="false";//帐号不存在，无法找回密码
        }else{
            $list["result"]="success";//帐号存在，可以找回密码
        }
    }
    print_r(json_encode($list));
}elseif(isset($_REQUEST["method"])&&$_REQUEST["method"]=="repwd")//重设密码
{
    $sql="UPDATE ".MEMBER." SET password='".md5($_REQUEST['newpass'])."' WHERE mobile='".$_REQUEST['mobile']."'";
    $re=$db->query($sql);
    $sql="select userid,user,name,logo from ".MEMBER." WHERE mobile='".$_REQUEST['mobile']."'";
    $db->query($sql);
    $mem=$db->fetchRow();
    $list["result"]="success";
    $list["uid"]=$mem['userid'];
    $list["user"]=$mem['name']?$mem['name']:$mem['user'];
    $list["logo"]=$mem['logo'];
    print_r(json_encode($list));
}else{//注册
    include_once("../config/reg_config.php");
    if($reg_config)
    {
        $config = array_merge($config,$reg_config);
    }
//----------------------------------------------------
    if(!empty($_REQUEST['user']))
    {
        if($config['closetype']==2)
        {	//关闭注册
			$list["result"]="access dined!";
			print_r(json_encode($list));
        }
        $ip = getip();
        $user = trim($_REQUEST['user']);
        $pass = trim($_REQUEST['password']);
        $time = time();
        if(valid_mobile($user))
        {
            $_REQUEST["mobile"] = $mobile = $user;
            $_REQUEST["user"] = $user = "M".$mobile;
            if(!is_repeat($user))
            {
                $_REQUEST["user"] = $user = $user.substr(md5($time),-5);
            }
        }
        doreg();
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
    return ( ! preg_match("/^13[0-9]{1}[0-9]{8}$|14[57]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$str))?FALSE:TRUE;
}
function doreg($guid=NULL)
{
    global $db,$config,$ip;
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['password'];
    $mobile = $_REQUEST['mobile'];
    $email_verify = "1";
    $mobile_verify = $mobile&&$config['user_reg']==3 ? "1":"0";

    $lastLoginTime = time();
    $regtime = date("Y-m-d H:i:s");
    $user_reg = "2";

    $db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);

    $sql="select * from ".MEMBER." where user = '$user'";
    $db->query($sql);
    if($db->num_rows()){
        $list["result"]="User name is have";
        print_r(json_encode($list));
    }

    $sql="insert into ".MEMBER." (user,password,ip,lastLoginTime,email,mobile,regtime,statu,email_verify,mobile_verify) values ('$user','".md5($pass)."','','$lastLoginTime','','$mobile','$regtime','$user_reg','$email_verify','$mobile_verify')";
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

            //---------------设置加密的cookie
            bsetcookie("USERID","$userid\t$user",NULL,"/",$config['baseurl']);
            setcookie("USER",$user,NULL,"/",$config['baseurl']);
            $list["result"]="success";
            $list["user"]=$user;
            $list["uid"]=$userid;
            print_r(json_encode($list));
        }
    }
    else{
        $list["result"]="Can not register...";
        print_r(json_encode($list));
    }
}

?>