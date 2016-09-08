<?php
	include_once("config.php");

	$name="sphinx_config";

	@include '../config/'.$name.'.php';

	if($_POST["act"]=='save' || $_POST["act"]=='item')
	{
		$wmark_type='0';
		foreach($_POST as $pname=>$pvalue)
		{
			if ($pname!="act")
			{
				
				$sql="select * from ".CONFIG." where `index`='$pname'";
				$db->query($sql);
				if($db->num_rows())
				{
				   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname'";
				}
				else
				{
				   $sql1="insert into ".CONFIG." (`index`,value) values ('$pname','$pvalue')";
				}
				$db->query($sql1);
				$sphinx_config[$pname]=$pvalue;
			}
		}


		/****更新缓存文件*********/
		$write_config_con_str=serialize($sphinx_config);//将数组序列化后生成字符串
		$write_config_con_str='<?php $'.$name.' = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
		$fp=fopen('../config/'.$name.'.php','w');
		fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
		fclose($fp);
		/*********************/

		admin_msg("sphinx_config.php",'Sphinx设置更新成功');

	}

	if (extension_loaded("sphinx"))
	{
		$tpl->assign("sphinx_ext", true);
	}

	if (extension_loaded("scws"))
	{
		$tpl->assign("scws_ext", true);
	}

	@include_once("../config/sphinx_config.php");
	$tpl->assign("sphinx_config",$sphinx_config);
	$tpl->assign("config",$config);
	$tpl->display("sphinx_config.htm");
?>