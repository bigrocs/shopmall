<?php
	include_once("includes/global.php");
	include_once("$config[webroot]/includes/page_utf_class.php");
	function create_table($id)
	{
		global $db,$config;
		$table_name = $config['table_pre']."defind_".$id;
		$db -> query("DROP TABLE IF EXISTS `".$table_name."`;");
		$csql = "
		CREATE TABLE IF NOT EXISTS `".$table_name."` (
		  `id` int(10) NOT NULL auto_increment,
		  `product_id` int(10) unsigned default NULL,
		  `color_img` text default NULL,
		   PRIMARY KEY  (`id`)
		) ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 0 ;
		";
		$db -> query($csql);
		
		$sql = "ALTER TABLE `$table_name` ADD INDEX (`product_id`)";
		$db -> query($sql);
	}
	
	if($_POST['act'] == "save" || $_POST['act'] == "edit")
	{	
		unset($_GET['s']);
		unset($_GET['m']);
		unset($_GET['operation']);
		if($_POST["act"] == 'save')
		{
			$sql = "insert into ".TYPE." (`name`) values ('$_POST[pname]')";
			$db -> query($sql);
			$id = $db -> lastid();
			
			create_table($id);
			$table_name = $config['table_pre']."defind_".$id;
			
			//添加规格
			if($_POST['spec'])
			{
				foreach($_POST['spec'] as $key => $val)
				{
					$sql = "insert into ".TYPESPEC." (type_id,spec_id) values ('$id','$val')";
					$db -> query($sql);
				}
			}
		}
		if($_POST["act"] == 'edit' and is_numeric($_POST['id']))
		{
			$id = $_POST['id']*1;

			$sql = "update ".TYPE." set name = '$_POST[pname]' where id = '$id'";
			$db -> query($sql);
					
			$table_name = $config['table_pre']."defind_".$id;
			
			if($_POST['spec'])
			{
				$sql = "delete from ".TYPESPEC." where type_id = '$id'";
				$db -> query($sql);
				foreach($_POST['spec'] as $key => $val)
				{
					$sql = "insert into ".TYPESPEC." (type_id,spec_id) values ('$id','$val')";
					$db -> query($sql);
				}
			}
			
			$sql = "select id from ".PROPERTY." where type_id = '$id' order by displayorder";
			$db -> query($sql);
			$re = $db -> getRows();
			foreach($re as $k => $v)
			{
				$name = $_POST['names'][$v['id']];
				if($name)
				{
					$item = trim(trim($_POST['items'][$v['id']]),',');
					$select = $_POST['selects'][$v['id']];
					$displayorder = $_POST['displayorders'][$v['id']];
					
					$display_type = $select == 3 ? "text" : ($select == 4 ? "checkbox" : "select");
					$is_search = $select == 2 ? 1 : 0;
					
					$sql = "update ".PROPERTY." set name = '$name' , item = '$item' ,is_search ='$is_search' ,format = '$display_type' , displayorder = '$displayorder'  where id = '$v[id]'";
					$db -> query($sql);
		
					if($item)
					{
						$item = explode(',',$item);
						$sql = "select id from ".PROPERTYVALUE." where property_id = '$v[id]'";
						$db -> query($sql);
						$re = $db -> getRows();
						if($re)
						{
							foreach($re as $key => $val)
							{
								$property_name = $item[$key];
								if($property_name)
								{
									$sql = "update ".PROPERTYVALUE." set name = '$property_name' where id = '$val[id]'";
									$db -> query($sql);
								}
								else
								{
									$sql = "delete from ".PROPERTYVALUE." where id = '$val[id]'";
									$db -> query($sql);
								} 	
							}
						}
						if(!$re || count($re) < count($item) )
						{
							foreach($item as $key => $val)
							{
								if($re && count($re) > ($key))
								{}
								else
								{
									$sql = "insert into ".PROPERTYVALUE." (name,property_id,displayorder,taobao_property_id) values ('$val','$v[id]','0','0')";
									$db -> query($sql);	
								}
							}
						}
					}
					else
					{
						$sql = "delete from ".PROPERTYVALUE." where property_id = '$v[id]'";
						$db -> query($sql);
					}
					
				}
				else
				{
					$sql = "delete from ".PROPERTY." where id = '$v[id]'";
					$db -> query($sql);
					
					$sql = "delete from ".PROPERTYVALUE." where property_id = '$v[id]'";
					$db -> query($sql);
				}
			}
		}
		//添加扩展属性
		if($_POST['name'])
		{
			foreach($_POST['name'] as $key => $val)
			{
				$select = $_POST["select"][$key]*1;
				$item = trim(trim($_POST["item"][$key]),',');
				$num = $_POST["displayorder"][$key]*1;
				if($val)
				{	
					$display_type = $select == 3 ? "text" : ($select == 4 ? "checkbox" : "select");
					$is_search = $select == 2 ? 1 : 0;
					
					$sql = "insert into ".PROPERTY." (name,type_id,item,is_search,format,displayorder) values ('$val','$id','$item','$is_search','$display_type','$num')";
					$db -> query($sql);
					$property_id = $db -> lastid();
					
					$field = "property_".$property_id;
					$sql = "ALTER TABLE `".$table_name."` ADD `$field` VARCHAR(255) NULL";
					$db -> query($sql);
					
					if($_POST['item'])
					{
						$items = $item ? explode(',',$item) : "";
						foreach($items as $k => $v)
						{
							$sql = "insert into ".PROPERTYVALUE." (name,property_id,displayorder,taobao_property_id) values ('$v','$property_id','0','0')";
							$db -> query($sql);
						}
					}
				}
			}
		}	
		$getstr = implode('&',convert($_GET));
		admin_msg("module.php?m=product&s=property.php&$getstr","操作成功");
	}

	if($_POST['act'] == 'setting')
	{
		unset($_POST['act']);
		$type = 'taobao';
		function read_config($type)
		{
			global $db;
			$sql="select * from ".CONFIG." where type='$type'";
			$db->query($sql);
			$re=$db->getRows();
			foreach($re as $v)
			{
				$index=$v['index'];
				$value=$v['value'];
				$configs[$index]=$value;
			}
			return $configs;
		}
		foreach($_POST as $pname=>$pvalue)
		{
			$sql="select * from ".CONFIG." where `index`='$pname' and type='$type'";
			$db->query($sql);
			if($db->num_rows())
			   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='$type'";
			else
			   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','$type')";
			$db->query($sql1);
		}
		/****更新缓存文件*********/
		$write_config_con_array=read_config($type);//从库里取出数据生成数组
		$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
		$write_config_con_str=str_replace("'","\'",$write_config_con_str);
		$write_config_con_str='<?php $'.$type.'_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
		$fp=fopen('../config/'.$type.'_config.php','w');
		fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
		fclose($fp);
		/*********************/
		admin_msg("module.php?m=product&s=property.php&operation=taobao","设置成功");
		exit;
	}
	
	if($_POST['act'] == 'taobao')
	{
		@include('config/taobao_config.php');
		$appkey = $taobao_config['appkey'];
		$secret = $taobao_config['secretKey'];
		
		include_once("$config[webroot]/lib/taobao/TopSdk.php");
		$c = new TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new ItempropsGetRequest;
		$req->setFields("pid,is_sale_prop,name,must,multi,prop_values");
		$req->setCid($_POST['cid']);
		$resp = $c->execute($req);	
		$resp = (array)($resp);
		if($resp['code'])
		{
			admin_msg("module.php?m=product&s=property.php",$resp['msg']);
		}
		else
		{
			if($resp)
			{
				$sql = "insert into ".TYPE." (`name`,`taobao_type_id`) values ('".addslashes($_POST['pname'])."','$_POST[cid]')";
				$db -> query($sql);
				$id = $db -> lastid();
				
				create_table($id);
				$table_name = $config['table_pre']."defind_".$id;
				
				foreach($resp['item_props'] as $val)
				{
					$val = (array)($val);
					if($val['is_sale_prop'] == 'true')
					{
						
						$sql = "select id from ".SPEC." where taobao_spec_id = '$val[pid]'";
						$db -> query($sql);
						$spec_id = $db -> fetchField('id');
						if(!$spec_id)
						{
							$item = '';
							$sql = "insert into ".SPEC." (name,format,item,displayorder,taobao_spec_id) values ('".addslashes($val['name'])."','text','$item','0','$val[pid]')";
							$db -> query($sql);
							$spec_id = $db -> lastid();
							
							$val['prop_values'] = (array)($val['prop_values']);
							if($val['prop_values']['prop_value'])
							{
								foreach($val['prop_values']['prop_value'] as $v)
								{
									$v = (array)($v);
									$sql = "insert into ".SPECVALUE." (name,spec_id,image,displayorder,taobao_spec_id) values ('".addslashes($v['name'])."','$spec_id','','0','$v[vid]')";
									$db -> query($sql);	
									$item .= addslashes($v['name']).',';
								}
								$item = trim($item,',');
								$sql = "update ".SPEC." set item = '$item' where id = '$spec_id'";
								$db -> query($sql);
							}
						}
						$sql = "insert into ".TYPESPEC." (type_id,spec_id) values ('$id','$spec_id')";
						$db -> query($sql);	
					}
					else
					{
						$val['prop_values'] = (array)($val['prop_values']);
						$format = $val['multi'] == true ? "checkbox" : ($val['prop_values']['prop_value'] ? "select" : "text");
						
						$sql = "insert into ".PROPERTY." (name,type_id,item,is_search,format,displayorder,taobao_property_id) values ('".addslashes($val['name'])."','$id','','0','$format','0','$val[pid]')";
						$db -> query($sql);
						$property_id = $db -> lastid();
						
						$field = "property_".$property_id;
						$sql = "ALTER TABLE `".$table_name."` ADD `$field` VARCHAR(255) NULL";
						$db -> query($sql);
						
						$item = '';
						if($val['prop_values']['prop_value'])
						{
							foreach($val['prop_values']['prop_value'] as $v)
							{
								$v = (array)($v);
								$sql = "insert into ".PROPERTYVALUE." (name,property_id,displayorder,taobao_property_id) values ('".addslashes($v['name'])."','$property_id','0','$v[vid]')";
								$db -> query($sql);	
								$item .= addslashes($v['name']).',';
							}
							$item = trim($item,',');
							$sql = "update ".PROPERTY." set item = '$item' where id = '$property_id'";
							$db -> query($sql);
						}
					}
				}
				admin_msg("module.php?m=product&s=property.php","导入成功");
			}
			else
			{
				admin_msg("module.php?m=product&s=property.php","导入失败");
			}
		}
	}
	if($_GET['operation'] == 'add' or $_GET['operation'] == 'edit')
	{
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql = "select * from ".TYPE." where id = '$_GET[editid]'";
			$db -> query($sql);
			$de = $db -> fetchRow();
			
			$sql = "select spec_id from ".TYPESPEC." where type_id = '$_GET[editid]'";
			$db -> query($sql);
			$re = $db -> getRows();
			foreach($re as $val)
			{
				$de["spec_id"][] = $val['spec_id'];	
			}
			$sql = "select * from ".PROPERTY." where type_id = '$_GET[editid]' order by displayorder";
			$db -> query($sql);
			$de["property"] = $db -> getRows();
		}
		$sql = "select * from ".SPEC." order by displayorder,id desc";
		$db -> query($sql);
		$spec = $db -> getRows();
		$tpl -> assign("spec",$spec);
	}
	else if($_GET['operation'] == 'taobao')
	{
		@include('config/taobao_config.php');
		$appkey = $taobao_config['appkey'];
		$secret = $taobao_config['secretkey'];
		if(!$appkey && !$secret)
		{
			admin_msg("module.php?m=product&s=property.php&operation=setting","请设置淘宝导入配置");	
		}	
	}
	else if($_GET['operation'] == 'setting')
	{
		@include('config/taobao_config.php');
		$tpl -> assign("taobao_config",$taobao_config);
	}
	else
	{	
		if($_POST['act'] == 'op')
		{			
			if(is_array($_POST['chk']))
			{
				$id = implode(",",$_POST['chk']);
				$sql=" update ".PCAT." SET ext_table = '' , ext_field_cat = '' where ext_field_cat in ($id) ";
				$db -> query($sql);
				  
				$sql = "delete from ".TYPE." where id in ($id)";
				$db -> query($sql);
				$sql = "delete from ".TYPESPEC." where type_id in ($id)";
				$db -> query($sql);
				
				$sql = "select * from ".PROPERTY." where type_id in ($id) order by displayorder";
				$db -> query($sql);
				$re = $db -> getRows();
				foreach($re as $key => $val)
				{
					$sql = "delete from ".PROPERTY." where id = '$val[id]'";
					$db -> query($sql);
					
					$sql = "delete from ".PROPERTYVALUE." where property_id = '$val[id]'";
					$db -> query($sql);
				}
				foreach($_POST["chk"] as $val)
				{
					$table_name = $config['table_pre']."defind_".$val;
					$db -> query("DROP TABLE `$table_name`");//删除生成的表
				}
				msg("?m=product&s=property.php");
			}									
		}
		if(!empty($_GET['key']))
		{
			$str = " and name like '%$_GET[key]%' ";
		}
		$sql = "select * from ".TYPE." where 1 $str order by id desc";
		$page = new Page;
		$page -> listRows = 12;
		if (!$page -> __get('totalRows'))
		{
			$db -> query($sql);
			$page -> totalRows = $db -> num_rows();
		}
		$count = $page -> totalRows;
		$tpl -> assign("count",$count);
		$sql .=  "  limit ".$page -> firstRow.",".$page -> listRows;
		$db -> query($sql);
		$de['list'] = $db -> getRows();
		$de['page'] = $page -> prompt();
	}
	$tpl -> assign("de",$de);
	$tpl -> assign("config",$config);
	$tpl -> display("property.htm");
?>
