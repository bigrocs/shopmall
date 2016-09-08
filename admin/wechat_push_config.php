<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);

@include_once("auth.php");
@include_once("../config/wechat_push_config.php");
//=========================================
if(!empty($_GET['email']))
{
	$res=send_mail($_GET['email'],'test','test','test');
	if($res==1)
		admin_msg("wechat_push_config.php",'成功');
	else
		admin_msg("wechat_push_config.php",'失败');
}
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		$sql="select * from ".CONFIG." where `index`='$pname' and type='mail'";
		$db->query($sql);
		if($db->num_rows())
			$sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='mail'";
		else
			$sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','mail')";
		$db->query($sql1);
	}
	/****更新缓存文件*********/
	$write_config_con_array=read_config();//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);

	$write_config_con_str='<?php $wechat_push_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/wechat_push_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("wechat_push_config.php",'设置成功');
}
//===读库函数，生成config形式的数组====
function read_config()
{
	global $db;
	$sql="select * from ".CONFIG." where type='mail'";
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
	<div class="bigboxhead">微信推送配置-需要开通微信支付后申请</div>
	<div class="bigboxbody">
		<form name="sysconfig" action="wechat_push_config.php" method="post" style="margin-top:0px;">
			<table width="100%" cellspacing="0">
				<tr>
					<td colspan="4" align="left">微信推送总开关
						<input type="radio" class="radio" name="wechat_statu" value="1" <?php
						if ($wechat_push_config['wechat_statu']==1)
							echo "checked";
						?>>
						开启
						<input type="radio" class="radio" name="wechat_statu" value="0" <?php
						if ($wechat_push_config['wechat_statu']==0)
							echo "checked";
						?>>
						关闭
				</tr><!--
				<tr class="theader">
					<td >&nbsp;</td>
					<td >推送名称</td>
					<td >模板Id</td>
					<td >推送注释</td>
				</tr>
				<tr>
					<td width="2%" align="left" >1.</td>
					<td width="27%" align="left" ><input name="wechat_msg_1" type="text" id="wechat_msg_1" class="text" maxlength="60" value="<?php echo $wechat_push_config['wechat_msg_1'];?>"></td>
					<td width="28%" align="left" ><input name="template_id_1" type="text" id="template_id_1" class="text" maxlength="60"	value="<?php echo $wechat_push_config['template_id_1'];?>" /></td>
					<td width="43%" align="left" ><input name="template_id_note_1" type="text" id="template_id_pass_1" class="text" maxlength="60" value="<?php echo $wechat_push_config['template_id_note_1'];?>"/></td>
				</tr>
				<tr>
					<td width="2%" align="left" >2.</td>
					<td align="left" ><input name="wechat_msg_2" type="text" id="wechat_msg_2" class="text" maxlength="60" value="<?php echo $wechat_push_config['wechat_msg_2'];?>" /></td>
					<td align="left" ><input name="template_id_2" type="text" id="template_id_2" class="text" maxlength="60"	value="<?php echo $wechat_push_config['template_id_2'];?>" /></td>
					<td align="left" ><input name="template_id_note_2" type="text" id="template_id_note_2" class="text" maxlength="60" value="<?php echo $wechat_push_config['template_id_note_2'];?>"/></td>
				</tr>
				<tr>
					<td width="2%" align="left">3.</td>
					<td align="left"><input name="wechat_msg_3" type="text" id="wechat_msg_3" class="text" maxlength="60" value="<?php echo $wechat_push_config['wechat_msg_3'];?>" /></td>
					<td align="left"><input name="template_id_3" type="text" id="template_id_3" class="text" maxlength="60"	value="<?php echo $wechat_push_config['template_id_3'];?>" /></td>
					<td align="left"><input name="template_id_note_3" type="text" id="template_id_note_3" class="text" maxlength="60" value="<?php echo $wechat_push_config['template_id_note_3'];?>"/></td>
				</tr>
				<tr>
					<td height="32" align="left">4.</td>
					<td align="left" ><input name="wechat_msg_4" type="text" id="wechat_msg_4" class="text" maxlength="60" value="<?php echo $wechat_push_config['wechat_msg_4'];?>" /></td>
					<td align="left" ><input name="template_id_4" type="text" id="template_id_4" class="text" maxlength="60"	value="<?php echo $wechat_push_config['template_id_4'];?>" /></td>
					<td align="left" ><input name="template_id_note_4" type="text" id="template_id_note_4" class="text" maxlength="60" value="<?php echo $wechat_push_config['template_id_note_4'];?>"/></td>
				</tr>
				<tr>
					<td height="34" align="left">5.</td>
					<td align="left" ><input name="wechat_msg_5" type="text" id="wechat_msg_5" class="text" maxlength="60" value="<?php echo $wechat_push_config['wechat_msg_5'];?>" /></td>
					<td align="left" ><input name="template_id_5" type="text" id="template_id_5" class="text" maxlength="60"	value="<?php echo $wechat_push_config['template_id_5'];?>" /></td>
					<td align="left" ><input name="template_id_note_5" type="text" id="template_id_note_5" class="text" maxlength="60" value="<?php echo $wechat_push_config['template_id_note_5'];?>"/></td>
				</tr>
				<tr>
					<td height="32" align="left">6.</td>
					<td align="left" ><input name="wechat_msg_6" type="text" id="wechat_msg_6" class="text" maxlength="60" value="<?php echo $wechat_push_config['wechat_msg_6'];?>" /></td>
					<td align="left" ><input name="template_id_6" type="text" id="template_id_6" class="text" maxlength="60"	value="<?php echo $wechat_push_config['template_id_6'];?>" /></td>
					<td align="left" ><input name="template_id_note_6" type="text" id="template_id_note_6" class="text" maxlength="60" value="<?php echo $wechat_push_config['template_id_note_6'];?>"/></td>
				</tr>-->
				<tr>
					<td width="2%" height="40" align="right">&nbsp;</td>
					<td colspan="3" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>">
						<span class="bz"></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
</body>
</HTML>
