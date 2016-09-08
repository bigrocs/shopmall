<?php
	
	include_once("module/member/includes/plugin_member_class.php");
	$member=new member();
	
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			//添加
			if($_POST["act"]=='save')
			{
				$member->AddMemberGrade();;
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$member->EditMemberGrade($_POST['id']);
			}
			msg("?m=member&s=member_grade.php");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$de=$member->GetMemberGrade($_GET['editid']);
		}
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".MEMBERGRADE."  where id='$_GET[delid]'";
			$db->query($sql);
			msg("?m=member&s=member_grade.php");
		}
		//批量删除
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".MEMBERGRADE." where id in ($id)";
				$db->query($sql);
			}
			msg("?m=member&s=member_grade.php");
		}
		//获取信息
		$de['list']=$member->GetMemberGradeList();
		if(empty($de['list']))
		{
			$sql="INSERT INTO `mallbuilder_member_grade` (`id`, `name`, `pic`, `pic1`, `desc`, `create_time`, `status`) VALUES
(1, 'VIP0', '".$config['weburl']."/image/grade/vs0.gif', '".$config['weburl']."/image/grade/vb0.gif', '', 1405069757, 1),
(2, 'VIP1', '".$config['weburl']."/image/grade/vs1.gif', '".$config['weburl']."/image/grade/vb1.gif', '', 1405069769, 1),
(3, 'VIP2', '".$config['weburl']."/image/grade/vs2.gif', '".$config['weburl']."/image/grade/vb2.gif', '', 1405069769, 1),
(4, 'VIP3', '".$config['weburl']."/image/grade/vs3.gif', '".$config['weburl']."/image/grade/vb3.gif', '', 1405069769, 1),
(5, 'VIP4', '".$config['weburl']."/image/grade/vs4.gif', '".$config['weburl']."/image/grade/vb4.gif', '', 1405069769, 1),
(6, 'VIP5', '".$config['weburl']."/image/grade/vs5.gif', '".$config['weburl']."/image/grade/vb5.gif', '', 1405069769, 1),
(7, 'VIP6', '".$config['weburl']."/image/grade/vs6.gif', '".$config['weburl']."/image/grade/vb6.gif', '', 1405069769, 1);";
			$db->query($sql);
			admin_msg("module.php?m=member&s=member_grade.php","数据导入成功");
		}
	}
	$tpl->assign("de",$de);
	$tpl->display("member_grade.htm");
?>
