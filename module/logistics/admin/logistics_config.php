<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
@include_once("auth.php");
//=========================================
$type='logistics';
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
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
	$write_config_con_str='<?php $'.$type.'_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/'.$type.'_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("module.php?m=logistics&s=logistics_config.php","设置成功");
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
$reg_config=read_config($type);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('sysconfig');?></div>
<div class="bigbox">
	<div class="bigboxhead">物流设置</div>
	<div class="bigboxbody">
	<form name="sysconfig" action="" method="post" style="margin-top:0px;">
	<table width="100%" cellspacing="0">
		<!--
        <tr class="theader">
          <td colspan="2"  align="left" >爱查快递</td>
        </tr>
        <tr>
          <td  align="left" >是否开启</td>
          <td align="left" >
          <input type="radio" class="radio" name="logistics_connect" value="1" <?php if($reg_config['logistics_connect']==1) echo 'checked="checked"';?> />是
          <input type="radio" class="radio" name="logistics_connect" value="0" <?php if($reg_config['logistics_connect']==0) echo 'checked="checked"';?> />否&nbsp;
          [<a href="http://api.ickd.cn/users/login.html">审请API</a>]</td>
        </tr>
        <tr>
          <td width="16%"  align="left" >APP ID</td>
          <td width="84%" align="left" >
            <input class="text" name="logistic_app_id" type="text" id="logistic_app_id" value="<?php echo $reg_config['logistic_app_id'];?>"></td>
        </tr>
        <tr>
          <td align="left">APP Sceret</td>
          <td align="left" ><input class="text" name="logistic_api_sceret" type="text" id="logistic_api_sceret" value="<?php echo $reg_config['logistic_api_sceret'];?>"></td>
        </tr>
		-->


		<tr class="theader">
			<td colspan="2"  align="left" >快递鸟</td>
		</tr>
		<tr>
			<td  align="left" >是否开启</td>
			<td align="left" >
				<input type="radio" class="radio" name="logistics_kdniao_connect" value="1" <?php if($reg_config['logistics_kdniao_connect']==1) echo 'checked="checked"';?> />是
				<input type="radio" class="radio" name="logistics_kdniao_connect" value="0" <?php if($reg_config['logistics_kdniao_connect']==0) echo 'checked="checked"';?> />否&nbsp;
				[<a href="http://www.kdniao.com/ServiceApply.aspx">审请API</a>]</td>
		</tr>
		<tr>
			<td width="16%"  align="left" >电商ID</td>
			<td width="84%" align="left" >
				<input class="text" name="e_business_id" type="text" id="e_business_id" value="<?php echo $reg_config['e_business_id'];?>"></td>
		</tr>
		<tr>
			<td width="16%"  align="left" >APP Key</td>
			<td width="84%" align="left" >
				<input class="text" name="logistics_kdniao_app_key" type="text" id="logistics_kdniao_app_key" value="<?php echo $reg_config['logistics_kdniao_app_key'];?>"></td>
		</tr>

		<tr class="theader">
			<td colspan="2"  align="left" >快递100</td>
		</tr>
		<tr>
			<td  align="left" >是否开启</td>
			<td align="left" >
				<input type="radio" class="radio" name="logistics_connect" value="1" <?php if($reg_config['logistics_connect']==1) echo 'checked="checked"';?> />是
				<input type="radio" class="radio" name="logistics_connect" value="0" <?php if($reg_config['logistics_connect']==0) echo 'checked="checked"';?> />否&nbsp;
				[<a href="http://www.kuaidi100.com/openapi/applyapi.shtml">审请API</a>]</td>
		</tr>
		<tr>
			<td width="16%"  align="left" >APP Key</td>
			<td width="84%" align="left" >
				<input class="text" name="logistic_app_id" type="text" id="logistic_app_id" value="<?php echo $reg_config['logistic_app_id'];?>"></td>
		</tr>
		<!--
		<tr>
			<td align="left">APP Sceret</td>
			<td align="left" ><input class="text" name="logistic_api_sceret" type="text" id="logistic_api_sceret" value="<?php echo $reg_config['logistic_api_sceret'];?>"></td>
		</tr>
		-->


        <tr>
          <td width="16%" align="right">&nbsp;</td>
          <td width="84%" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
	</form>
	</div>
</div>
</body>
</HTML>