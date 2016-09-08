<?php
include_once("../includes/page_utf_class.php");
include_once("../lang/$langs/company_type_config.php");
//====================================

	//验证，该会员是否已经存在
	if(isset($_POST["card_num"])&&!$_POST['card'])
	{	
		$card_num = $_POST['card_num'];
		$sql="select * from ".MEMBER." where card_num='$card_num' or `user`='$card_num'";
		$db->query($sql);
		$result = $db->fetchRow();
 
		if(!empty($result))
			echo "false";
		else
			echo "true";
		die;
	}
	
	//添加会员卡
	if(!empty($_POST['card'])&&$_POST['card']=='add')
	{	
		$amount = $_POST['amount'];
		$card_num = $_POST['card_num'];
		$today = date("Ymd");
		//$pass = md5("123456");默认密码
		$ip = getip();
		$ip = empty($ip)?NULL:$ip;
		$lastLoginTime = time();
		$regtime = date("Y-m-d H:i:s");
		$user_reg = "1";
		
		$sql="select * from ".MEMBER." where card_num='$card_num' or `user`='$card_num'";
		$db->query($sql);
		$temp = $db->fetchRow();
 
		if(!empty($card_num)&&empty($temp)){
			 
			$str="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPKRSTUVWXYZ0123456789";
			$rand_pwd='';
			for($j=0;$j<8;$j++) $rand_pwd.=$str[rand(0,61)];
			$pass = md5($rand_pwd);

			//插入会员表
			$sql="insert into ".MEMBER." (`user`,`card_num`,`password`,`ip`,`regtime`,`statu`,`rand_pwd`) values ('$card_num','$card_num','$pass','$ip','$regtime','$user_reg','$rand_pwd')";
			$re=$db->query($sql);
			$userid=$db->lastid();
		
			if($userid)
			{	
				//会员信息表
				$user = $card_num;
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
			msg("?m=member&s=generate_member.php");
		}else{
			msg("?m=member&s=generate_member.php","该会员卡号已经存在！");
		}
	}
	$tpl->display("create_member.htm");
?>