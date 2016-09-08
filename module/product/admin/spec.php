<?php

	include_once("$config[webroot]/includes/page_utf_class.php");

	if($_POST['act'] == 'save' or $_POST['act'] == 'edit')
	{	
		unset($_GET['s']);
		unset($_GET['m']);
		unset($_GET['operation']);
		//添加
		if($_POST['name'])
		{
			$_POST['name'] = array_filter($_POST['name']);
			$item = implode(',',$_POST['name']);
		}
		$_POST['spec_displayorder'] *= 1;
		if($_POST["act"] == 'save')
		{
			$sql = "insert into ".SPEC." (name,format,item,displayorder,taobao_spec_id) values ('$_POST[spec_name]','text','$item','$_POST[spec_displayorder]','0')";
			$db -> query($sql);
			$id = $db->lastid();
		}
		//修改
		if($_POST["act"] == 'edit' and is_numeric($_POST['id']))
		{
			if($_POST['new_name'])
			{
				$_POST['new_name'] = array_filter($_POST['new_name']);
				$items = implode(',',$_POST['new_name']);
				if($item)
					$item = $items.",".$item;
				else
					$item = $items;
			}
			$sql = "update ".SPEC." set `name` = '$_POST[spec_name]', `displayorder` = '$_POST[spec_displayorder]', `item` = '$item' where id = '$_POST[id]'";
			$db -> query($sql);
			
			$id = $_POST['id']*1;
			$sql = "select * from ".SPECVALUE." where spec_id = '$id'";
			$db -> query($sql);
			$re = $db -> getRows();
			foreach($re as $val)
			{
				if($_POST['new_name'][$val['id']])
				{
					$name = $_POST['new_name'][$val['id']];
					$displayorder = $_POST['new_displayorder'][$val['id']];
					$sql = "update ".SPECVALUE." set `name` = '$name', `displayorder` = '$displayorder' where id = '$val[id]'";
					$db -> query($sql);
				}
				else
				{
					$sql = "delete from ".SPECVALUE." where id = '$val[id]'";
					$db -> query($sql);
				}	
			}
			unset($_GET['editid']);
		}
		if($_POST['name'])
		{
			foreach($_POST['name'] as $key => $val)
			{
				$displayorder = $_POST['displayorder'][$key]*1;
				$sql = "insert into ".SPECVALUE." (name,spec_id,image,displayorder,taobao_spec_id) values ('$val','$id','','$displayorder','0')";
				$db -> query($sql);	
			}
		}
		$getstr = implode('&',convert($_GET));
		msg("?m=product&s=spec.php&$getstr");
	}
	//信息
	if($_GET['editid'] and is_numeric($_GET['editid']))
	{
		$sql = "select * from ".SPEC." where id = '$_GET[editid]'";
		$db -> query($sql);
		$de = $db -> fetchRow();
		
		$sql = "select * from ".SPECVALUE." where spec_id = '$de[id]' order by displayorder";
		$db -> query($sql);
		$de['item'] = $db -> getRows();
	}
	
	else
	{		
		if($_POST['act'] == 'op')
		{
			if(is_array($_POST['chk']))
			{
				foreach($_POST["chk"] as $v)
				{
					$sql = "delete from ".SPEC." where id = '$v'";
					$db -> query($sql);
					$sql = "delete from ".SPECVALUE." where spec_id = '$v'";
					$db -> query($sql);
				}
				msg("?m=product&s=spec.php");
			}						
		}

		if(!empty($_GET['key']))
		{
			$str = " and name like '%$_GET[key]%' ";
		}
		
		$sql = "select * from ".SPEC." where 1 $str order by id desc ";
		$page = new Page;
		$page -> listRows = 12;
		//分页
		if (!$page -> __get('totalRows'))
		{
			$db -> query($sql);
			$page -> totalRows = $db -> num_rows();
		}
		$count = $page -> totalRows;
		$tpl -> assign("count",$count);
		$sql .=  " limit ".$page -> firstRow.",".$page -> listRows;
		$db -> query($sql);
		$de['list'] = $db -> getRows();
		$de['page'] = $page -> prompt();
	}
	$tpl -> assign("config",$config);
	$tpl -> assign("de",$de);
	$tpl -> display("spec.htm");
?>
