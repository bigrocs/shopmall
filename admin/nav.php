<?php
	
	include_once("config.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['s']);
			unset($_GET['m']);
			unset($_GET['operation']);
			
			if($_POST["act"]=='save')
			{
				$sql="insert into ".WEBCON." (con_no,con_statu,con_title,con_group,con_linkaddr,lang,con_desc,template,type,title,keywords,description) values ('255','$_POST[con_statu]','$_POST[con_title]','$_POST[con_group]','$_POST[con_linkaddr]','$config[language]','$_POST[con_desc]','$_POST[template]','1','$_POST[title]','$_POST[keywords]','$_POST[description]')";
				$db->query($sql);
			}
		
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql=" update ".WEBCON." SET  title='$_POST[title]',keywords='$_POST[keywords]',description='$_POST[description]',con_title='$_POST[con_title]',con_linkaddr='$_POST[con_linkaddr]',con_desc='$_POST[con_desc]',template='$_POST[template]',con_group='$_POST[con_group]',con_statu='$_POST[con_statu]',msg_online='0' where con_id='$_POST[id]'";	
				$db->query($sql);
			}
			$getstr=implode('&',convert($_GET));
			msg("help.php");
		}
	
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".WEBCON." where con_id ='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		
		$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
		$db->query($sql);
		$re =$db->getRows();
		$tpl->assign("re",$re);
	}
	elseif($_GET['operation']=="cat")
	{
		if($_GET['delid'])
		{
			$sql="delete from ".WEBCONGROUP."  where id='$_GET[delid]'";
			$db->query($sql);
			msg("nav.php?operation=cat");
		}
		if($_POST['act']=='op')
		{
			if($_POST['name'])
			{
				foreach($_POST['name'] as $key=>$list)
				{
					if(!empty($list))
					{
						$displayorder=$_POST['displayorder'][$key];
						$displayorder=$displayorder?$displayorder*1:"255";
						$db->query("update ".WEBCONGROUP." set title='$list',lang='$config[language]',`sort`='$displayorder' where id='$key'");		
					}
				}
			}
			if(!empty($_POST['newname1']))
			{
				$inserts=array();
				foreach($_POST['newname1'] as $key=>$list)
				{
					foreach($list as $k=>$l)
					{
						if(!empty($l))
						{
							$displayorder=$_POST['neworder'][$key][$k];
							$displayorder=$displayorder?$displayorder*1:"255";
							$inserts[]="('$l','$key','$displayorder')";	
						}
					}
				}
				if(!empty($inserts))
				{
					$sql="insert into ".SHOPCAT." (`name`,`parent_id`,`displayorder`) values ".implode(",",$inserts);
					$db->query($sql);
				}
			}
			if(!empty($_POST['newname']))
			{
				$inserts=array();
				foreach($_POST['newname'] as $key=>$list)
				{
					if(!empty($list))
					{
						$displayorder=$_POST['newdisplayorder'][$key];
						$displayorder=$displayorder?$displayorder*1:"255";
						$inserts[]="('$list','$displayorder','$config[language]')";	
					}
				}
				if(!empty($inserts))
				{
					$sql="insert into ".WEBCONGROUP." (`title`,`sort`,`lang`) values ".implode(",",$inserts);
					$db->query($sql);
				}
			}
			msg("help.php?operation=cat");
		}
		$sql="select * from ".WEBCONGROUP." order by sort ,id ";
		$db->query($sql);
		$de=$db->getRows();
	}
	else
	{
		if($_GET['delid'])
		{
			$sql="delete from ".NAV."  where id = '$_GET[delid]'";
			$db->query($sql);
			$sql="delete from ".NAV."  where parent_id = '$_GET[delid]'";
			$db->query($sql);
            $write_str=serialize(getipdata());//将数组序列化后生成字符串
            $write_str='<?php $nav_menu = unserialize(\''.$write_str.'\');?>';//生成要写的内容
            $fp=fopen('../config/nav_menu.php','w');
            fwrite($fp,$write_str,strlen($write_str));//将内容写入文件．
            fclose($fp);
            unset($_GET["delid"]);
			$getstr=implode('&',convert($_GET));
			msg("nav.php?$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['name'])
			{
				foreach($_POST['name'] as $key=>$list)
				{
					if(!empty($list))
					{
						$displayorder = $_POST['displayorder'][$key]*1;											
						$url = $_POST['url'][$key];
						$identifier = $_POST['identifier'][$key];
						$available = $_POST['available'][$key];
						$db->query("update ".NAV." set name='$list',url='$url',`displayorder`='$displayorder',`identifier`='$identifier',`available`='$available' where id='$key'");		
					}
				}
			}
			if(!empty($_POST['newname1']))
			{
				$inserts=array();
				foreach($_POST['newname1'] as $key=>$list)
				{
					foreach($list as $k=>$l)
					{
						if(!empty($l))
						{
							$displayorder=$_POST['newdisplayorder1'][$key][$k];										
							$url = $_POST['newurl1'][$key][$k];	
							$identifier = $_POST['newidentifier1'][$key][$k];	
							$navtype = $_POST['navtype']*1;
							$inserts[]="('$key','$l','$url','$identifier','1','1','$displayorder','$navtype')";	
						}
					}
				}
				if(!empty($inserts))
				{
					$sql="insert into ".NAV." (`parent_id`,`name`,`url`,`identifier`,`type`,`available`,`displayorder`,`navtype`) values ".implode(",",$inserts);
					$db->query($sql);
				}
			}
			if(!empty($_POST['newname']))
			{
				$inserts=array();
				foreach($_POST['newname'] as $key=>$list)
				{
					if(!empty($list))
					{
						$displayorder = $_POST['newdisplayorder'][$key]*1;											
						$url = $_POST['newurl'][$key];
						$identifier = $_POST['newidentifier'][$key];
						$navtype = $_POST['navtype']*1;
						$inserts[]="('0','$list','$url','$identifier','1','1','$displayorder','$navtype')";	
					}
				}
				if(!empty($inserts))
				{
					$sql="insert into ".NAV." (`parent_id`,`name`,`url`,`identifier`,`type`,`available`,`displayorder`,`navtype`) values ".implode(",",$inserts);
					$db->query($sql);
				}
			}
			$write_str=serialize(getipdata());//将数组序列化后生成字符串
			$write_str='<?php $nav_menu = unserialize(\''.$write_str.'\');?>';//生成要写的内容
			$fp=fopen('../config/nav_menu.php','w');
			fwrite($fp,$write_str,strlen($write_str));//将内容写入文件．
			fclose($fp);
			msg("nav.php?operation=$_POST[navtype]");
		}		
		
		$navtype = $_GET['operation']?$_GET['operation']:"1";
		$sql="select * from ".NAV." where parent_id = '0' and navtype = '$navtype' order by displayorder,id";
		$db->query($sql);
		$de=$db->getRows();
		foreach($de as $k=>$v)
		{
			$sql="select * from ".NAV." where parent_id='$v[id]'";
			$db->query($sql);
			$de[$k]['scat']=$db->getRows();	
		}
	}
	/*********************/
	function getipdata()
	{
		global $db,$config;
		$sql="select id,name,url,identifier from ".NAV." where parent_id = 0 and navtype = 1 and available = 1 order by displayorder,id";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $key => $val)
		{
			$sql="select name,url,identifier from ".NAV." where parent_id = '$val[id]' and available = 1 order by displayorder,id";
			$db->query($sql);
			$re[$key]['scat']=$db->getRows();
		}
		return $re;
	}
	$tpl->assign("de",$de);
	$tpl->display("nav.htm");

?>