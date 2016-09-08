<?php
include_once("../includes/global.php");
include_once("../config/table_config.php");
//===================Lemonx 20150527 核对原始密码功能=========
if($_POST['action']=="check_oldpass"){
    $oldpass = $_POST['pass']?$_POST['pass']:NULL;
    $pay_id = $_POST['pay_id']?$_POST['pay_id']:NULL;
    $sql = "select pay_pass from ".MEMBER." where pay_id='$pay_id'";
    $db -> query($sql);
    $re = $db->fetchRow();
    if(md5($oldpass)!=$re['pay_pass']){
        echo 0;die;
    }
    else
        echo 1;die;
}


//============================================================

$email = $_GET['email']?$_GET['email']:NULL;
$userid = $_GET['userid']?$_GET['userid']:NULL;
$cash = $_GET['cash']?$_GET['cash']:NULL;
if(empty($userid)&&empty($email))
	die('参数错误');

$sql = "select pay_id from ".MEMBER." where userid='$userid'";
$db -> query($sql);
$re = $db->fetchRow();

if(!$re['pay_id'])
{	
	if($cash){
		$sql = "insert into ".MEMBER." (userid,pay_email,lastLoginTime,regtime,cash) values ('$userid','$email','".time()."','".time()."',$cash)";
		$db -> query($sql);
		$pay_id = $db -> lastid();
		
		$flow_id=date("Ymdhis").rand(0,9);
		$note = '会员卡初始充值';
		$add_time = time();
		$sql = "insert into ".CASHFLOW." (pay_uid,price,time,note,censor,flow_id,statu,type,mold) values
				('$pay_id',$cash,'$add_time','$note','$_SESSION[ADMIN_USER]','$flow_id','4','1','1')";
		$db->query($sql);
		
	}else{
		$sql = "insert into ".MEMBER." (userid,pay_email,lastLoginTime,regtime) values ('$userid','$email','".time()."','".time()."')";
		$db -> query($sql);
		$pay_id = $db -> lastid();
	}
	
	echo $pay_id;
	die;
}
else
{	
	echo $re['pay_id'];die;
}
?>