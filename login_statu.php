<?php
include_once("includes/global.php");
//=================================
function showUser()
{
	global $config,$db,$buid;
	
	if(!empty($_COOKIE['USER']))
	{
		if($config['temp']=='wap')
		{
			$new='<a href=\'main.php?cg_u_type=1\'>'.$_COOKIE['USER']."</a> &nbsp;&nbsp;<a href='$config[weburl]/main.php?action=logout'>退出</a>";
		}
		else if($_GET['m'] == 'index')
		{
			
			$sql="select b.pic from ".MEMBER." a left join ".MEMBERGRADE." b on a.grade_id = b.id where user = '$_COOKIE[USER]' ";
			$db->query($sql);
			$pic = $db->fetchField('pic');
			$new = "<div class='nav-fore1'><a class='name' href='main.php?cg_u_type=1'>".$_COOKIE['USER']."</a><img align='absmiddle' src='$pic'><i><em></em></i></div><div class='nav-fore2'><ul><li><a href='main.php?m=member&s=admin_member'>账号管理</a></li><li><a href='main.php?action=logout'>退出</a></li></ul><div>";
			
			$new.= "<li class='nav msg'><div class='nav-fore1'><a class='name' href='main.php?m=message&s=admin_message_list_inbox&cg_u_type=1'><span>消息</span></a></div></li>";
			
		}
		else
		{
			$new="欢迎来".$config['company'].'！<a class=\'name\' href=\'main.php\'>'.$_COOKIE['USER']."</a> &nbsp;<a href='$config[weburl]/main.php?action=logout'>退出</a>";
		}
	
	}
	else
	{	
		if($config['temp']=='wap')
		{
			$new="<a href='".$config["weburl"]."/login.php'>登录</a> &nbsp;&nbsp;<a href='".$config["weburl"]."/$config[regname]'>注册</a>";
		}
		else
		{
			$new="<a class='login' href='".$config["weburl"]."/login.php'>亲，请登录</a><a class='reg' href='".$config["weburl"]."/$config[regname]'>免费注册</a>";
		}
		if($_GET['m'] == 'index')
		{
			$new="<div class='nav-fore1'>".$new."</div>";
		}
	}
	return $new;
}
echo "document.write(\"".showUser()."\");";
?>