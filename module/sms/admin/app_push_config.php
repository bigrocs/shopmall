<?php
$type='app_push';
if($_POST["act"]=='send' and !empty($_POST['msg_title']) and !empty($_POST['msg_content']))
{
	include_once("../lib/jpush/jpush.php");

	//$str=$sms->send($_POST['msg_title'],$_POST['msg_content']);

	$user = array();
	$msg_title = $_POST['msg_title'];
	$msg_content = $_POST['msg_content'];

	$extras = array();

	$alert = $msg_content;

	$rs = send_notification($user, $msg_title, $msg_content, $extras, $alert);

	if ($rs && $rs->isOk)
	{
		$str = '推送成功！';
	}
	else
	{
		$str = '推送失败！';
	}

	msg("?m=sms&s=app_push_config.php&operation=test",$str);

	exit;
}

if($_POST["act"]=="save")
{
	unset($_POST['submit']);
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
	$write_config_con_str='<?php $'.$type.'_config = unserialize(\''.$write_config_con_str.'\');$config = array_merge($config, $'.$type.'_config);?>';//生成要写的内容
	$fp=fopen('../config/'.$type.'_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	msg("?m=sms&s=app_push_config.php");
	exit;
}

//===读库函数，生成config形式的数组====
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

$config=read_config($type);

$tpl->assign("reg_config",$config);
$tpl->display("app_push_config.htm");
?>