<?php
	if($_GET['operation'] == 'send')
	{
		@include_once("$config[webroot]/config/sms_config.php");
		
		$re = get_member();
		foreach($_GET as $key => $val)
		{
			if($val&&$key!='operation') $arr[$key] = $val;
		}
		$tpl->assign("getstr",http_build_query($arr));
		$tpl->assign("count",count($re));
		$tpl->assign("sms_config",$sms_config);
	}
	elseif($_POST['act'] == "send")
	{
		$re = get_member();
		$mtitle = $_POST['title'];
		$mcontent = $_POST['con'];
		$i = 0;
		if($_POST['type']=='2')
		{
			foreach($re as $val)
			{
				if($val["email"])
				{
					$flag = send_mail($val["email"],$val["user"],$mtitle,$mcontent);
					if($flag == 1) $i++;
					$msg = "成功发送".$i."封邮件";
				}
			}
		}
		else if($_POST['type']=='3')
		{
			foreach($re as $val)
			{
				if($val["mobile"])
				{
					$_POST['mob'] = $val['mobile'];
					$_POST['con'] = $mcontent;

					send_sms($val['mobile'],$mcontent);
					$i++;
					$msg = "成功发送".$i."条短信";
				}
			}
		}
		else 
		{
			foreach($re as $val)
			{
				$sql="insert into ".FEEDBACK." (uid,touserid,fromuserid,fromInfo,sub,con,date,msgtype,iflook) VALUES ('$val[userid]','$val[userid]','0','系统消息','$mtitle','$mcontent','".date("Y-m-d H:i:s")."','3', 2)";
				$db->query($sql);
				$msg = "发送成功";
			}
		}
		admin_msg("module.php?m=sms&s=newsletter.php",$msg);		
	}
	else
	{
		$sql="select id,name from ".MEMBERGRADE." where status = '1'";
		$db -> query($sql);
		$grade = $db -> getRows();
		$tpl->assign("grade",$grade);
		
		$sql = "select name from ".ADMIN." where name!='' order by name";
		$db -> query($sql);
		$invite = $db->getRows();
		$tpl->assign("invite",$invite);
		$tpl->assign("prov",GetDistrict());	
	}

	function get_member()
	{
		global $db;
		
		if(!empty($_GET['name']))
		{
			$name = trim($_GET['name']);
			$str .= " and a.user like '%$name%' ";	
		}
		if(!empty($_GET['id']))
		{
			$id = trim($_GET['id']);
			$str .= " and a.userid like '%$id%' ";	
		}
		if(!empty($_GET['grade']))
		{
			$grade = @implode(',',$_GET['grade']);
			$str .= " and a.grade_id in ($grade) ";	
		}
		if(!empty($_GET['email_verify']))
		{
			$email_verify = $_GET['email_verify'] == 1 ? "1" : "0";
			$str .= " and a.email_verify = '$email_verify' ";	
		}
		if(!empty($_GET['mobile_verify']))
		{
			$mobile_verify = $_GET['mobile_verify'] == 1 ? "1" : "0";
			$str .= " and a.mobile_verify = '$mobile_verify' ";	
		}
		if(!empty($_GET['real_name']))
		{
			$real_name = trim($_GET['real_name']);
			$str .= " and a.name like '%$real_name%' ";	
		}
		if(!empty($_GET['sex']))
		{
			$sex = $_GET['sex'] == 1 ? "1" : "2";
			$str .= " and a.sex = '$sex' ";	
		}
		if(!empty($_GET['province']))
		{
			$str .= " and a.provinceid = '$_GET[province]' ";	
		}
		if(!empty($_GET['city']))
		{
			$str .= " and a.cityid = '$_GET[city]' ";	
		}
		if(!empty($_GET['area']))
		{
			$str .= " and a.areaid = '$_GET[area]' ";	
		}
		if(!empty($_GET['street']))
		{
			$str .= " and a.streetid = '$_GET[street]' ";	
		}
		if(!empty($_GET['invite']))
		{
			$str .= " and a.invite = '$_GET[invite]' ";	
		}
		if(!empty($_GET['email']))
		{
			$email = trim($_GET['email']);
			$str .= " and a.email like '%$email%' ";	
		}
		if(!empty($_GET['mobile']))
		{
			$mobile = trim($_GET['mobile']);
			$str .= " and a.mobile like '%$mobile%' ";	
		}
		if(!empty($_GET['qq']))
		{
			$qq = trim($_GET['qq']);
			$str .= " and a.qq like '%$qq%' ";	
		}
		if(!empty($_GET['ww']))
		{
			$ww = trim($_GET['ww']);
			$str .= " and a.ww like '%$ww%' ";	
		}
		if(!empty($_GET['rstime']))
		{
			$rstime = trim($_GET['rstime']);
			$str .= " and a.regtime >= '$rstime' ";	
		}
		if(!empty($_GET['retime']))
		{
			$retime = trim($_GET['retime']);
			$str .= " and a.regtime <= '$retime' ";	
		}
		if(!empty($_GET['lstime']))
		{
			$lstime = strtotime(trim($_GET['lstime']));
			$str .= " and a.lastLoginTime >= '$lstime' ";	
		}
		if(!empty($_GET['letime']))
		{
			$letime = strtotime(trim($_GET['letime']));
			$str .= " and a.lastLoginTime <= '$letime' ";	
		}
		$sql = "select userid,user,email,mobile from ".MEMBER." a where 1 $str";
		$db -> query($sql);
		return $db -> getRows();
	}
	$tpl->assign("config",$config);
	$tpl->display("newsletter.htm");
?>