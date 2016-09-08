<?php
include_once("../includes/page_utf_class.php");
include_once("../lang/$langs/company_type_config.php");
//====================================
if(!empty($_POST['de'])&&empty($_POST['passall']))
{
	del_user($_POST['de']);
}

if(!empty($_POST['act']))
{	
	$statu="2";
	if($_POST['act']=="s2") $statu="1";
	if($_POST['act']=="s3") $statu="-2";
		
	if($statu&&$_POST['chk'])
	{
		$chk = implode(',',$_POST['chk']);
		$sql="update ".MEMBER." set statu = $statu where userid in ($chk)";
		$db->query($sql);
		unset($sql);
	}
}

//添加会员卡

//需要用户中心....
//暂时屏蔽
if(false && !empty($_POST['card'])&&$_POST['card']=='add')
{
	$amount = $_POST['amount'];
	$number = $_POST['number'];
	$today = date("Ymd");
	//$pass = md5("123456");默认密码
	$ip = getip();
	$ip = empty($ip)?NULL:$ip;
	$lastLoginTime = time();
	$regtime = date("Y-m-d H:i:s");
	$user_reg = "1";
	
	//查询当天的最大的流水号
	$sql = "SELECT MAX(key_value) as key_value FROM ".MEMBERFLOW." WHERE `date_time`='$today'";
	$db->query($sql);
	$max_flow = $db->fetchField('key_value');
	$max_flow = $max_flow?$max_flow:0;
	
	if(!empty($number)){
		for($i=1;$i<=$number;$i++)
		{	
			$id = $i+$max_flow;
			$flow = str_pad($id,6,"0",STR_PAD_LEFT);
			
			//插入会员流水表
			$sql = "insert into ".MEMBERFLOW." (`date_time`,`key_value`,`create_time`) values ('$today','$flow','".time()."')";
			$db->query($sql);

			$str="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPKRSTUVWXYZ0123456789";
			$rand_pwd='';
			for($j=0;$j<8;$j++) $rand_pwd.=$str[rand(0,61)];
			$pass = md5($rand_pwd);
			
			$flow_id = $today.$flow;
			//插入会员表
			$sql="insert into ".MEMBER." (`user`,`card_num`,`password`,`ip`,`regtime`,`statu`,`rand_pwd`) values ('$flow_id','$flow_id','$pass','$ip','$regtime','$user_reg','$rand_pwd')";
			$re=$db->query($sql);
			$userid=$db->lastid();
	
			if($userid)
			{	
				//会员信息表
				$user = $flow_id;
				$sql="INSERT INTO ".MEMBERINFO." (member_id) VALUES ('$userid')";
				$re=$db->query($sql);
				
				if($re)
				{	
					$post['userid'] = $userid;
					$post['email'] = $user;
					$post['cash'] = $amount;
					$pay_id = member_get_url($post,true);	
					if($pay_id)
					{	
						//增加支付账户
						$sql="update ".MEMBER." set pay_id='$pay_id' where userid='$userid'";
						$re=$db->query($sql);	
					}
				}		
			}
		}
	}
}

//根据条件筛选
if($_SESSION['province'])
	$scl.=" and provinceid='".getdistrictid($_SESSION['province'])."'";
if($_SESSION['city'])
	$scl.=" and cityid='".getdistrictid($_SESSION['city'])."'";
if($_SESSION['area'])
	$scl.=" and areaid='".getdistrictid($_SESSION['area'])."'";	
if($_SESSION['street'])
	$scl.=" and streetid='".getdistrictid($_SESSION['street'])."'";
		
if(!empty($_GET['name']))
	$scl.=" and `card_num` like '%".trim($_GET['name'])."%'";
if(!empty($_GET['type']))
	$scl.=" and `statu`='".$_GET['type']."'";
if(!empty($_GET['stime']))
	$scl.=" and regtime>='".trim($_GET['stime'])."'";
if(!empty($_GET['etime']))
	$scl.=" and regtime<='".trim($_GET['etime'])."'";

$sql="
		SELECT
			`card_num`,
			`regtime`,
			`statu`,
			`rand_pwd`,
			`userid`
		FROM
			".MEMBER."
		WHERE
			1
		AND card_num != ''  $scl
		ORDER BY
			regtime DESC
		";
//=============================
$page = new Page;
$page->listRows=10;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",".$page->listRows;
$db->query($sql);
$de['page'] = $page->prompt();
$de['list'] = $db->getRows();

unset($_GET['m']);
unset($_GET['s']);
$getstr=implode('&',convert($_GET));
$tpl->assign("de",$de);
$tpl->assign("member_group",$member_group);

$arr = array("1"=>"未激活","2"=>"已激活"); 
$tpl->assign("arr",$arr);
$tpl->display("generate_member.htm");
?>