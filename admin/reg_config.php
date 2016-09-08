<?php
include_once("config.php");
//=========================================
if($_POST['act'] == 'save')
{
	unset($_POST['act']);
	
	foreach($_POST as $pname => $pvalue)
	{
		if(is_array($pvalue))
		{
			array_shift($pvalue);
			$pvalue = $pvalue ? serialize($pvalue) : "";
		}
		$sql="select * from ".CONFIG." where `index`='$pname' and type='reg'";
		$db->query($sql);
		if($db->num_rows())
		   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='reg'";
		else
		   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','reg')";
		$db->query($sql1);
	}
	/****更新缓存文件*********/
	$write_config_con_array=read_config();//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	$write_config_con_str='<?php $reg_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/reg_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("reg_config.php","设置成功");
	exit;
}
//===读库函数，生成config形式的数组====
function read_config()
{
	global $db;
	$sql="select * from ".CONFIG." where type='reg'";
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


@include_once("../config/connect_config.php");
if ($connect_config['ucenter_connect'])
{
	header('location:./connect_config.php');
}
else
{
	@include_once("../config/reg_config.php");
	$tpl->assign("reg_config",$reg_config);
	$tpl->assign("config",$config);
	$tpl->display("reg_config.htm");
}

?>