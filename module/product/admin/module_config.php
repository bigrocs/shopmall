<?php
$type='module_product';
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
	admin_msg("module.php?m=product&s=module_config.php","设置成功");
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
if(!$reg_config)
{
	@include('config/'.$type.'_config.php');
	$con=$type.'_config';
	$reg_config=$$con;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo lang_show('admin_system');?></title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script src="../script/jquery-1.4.4.min.js" type=text/javascript></script>
</head>
<body>
<div class="bigbox">
  <div class="bigboxhead">模块配置</div>
  <div class="bigboxbody">
    <form name="sysconfig" action="" method="post">
      <table width="100%" cellspacing="0">
        <tr class="theader">
          <td colspan="4">SEO设置</td>
        </tr>
        <tr>
          <td  align="left" >&nbsp;</td>
          <td align="left" >Title</td>
          <td align="left" >Keyword</td>
          <td align="left" >Description</td>
        </tr>
        <tr>
          <td width="17%"  align="left" >频道首页</td>
          <td align="left" ><input class="text" name="title" type="text" id="title" value="<?php echo $reg_config['title'];?>" size="10"></td>
          <td align="left" ><input class="text" name="keyword" type="text" id="keyword" size="10" value="<?php echo $reg_config['keyword'];?>" /></td>
          <td align="left" ><input class="text" name="description" type="text" id="description" size="10" value="<?php echo $reg_config['description'];?>" /></td>
        </tr>
        <tr>
          <td height="21" align="left">产品列表</td>
          <td align="left" ><input class="text" name="title2" type="text" id="title2" value="<?php echo $reg_config['title2'];?>" size="10" /></td>
          <td align="left" ><input class="text" name="keyword2" type="text" id="keyword2" size="10" value="<?php echo $reg_config['keyword2'];?>" /></td>
          <td align="left" ><input class="text" name="description2" type="text" id="description2" size="10" value="<?php echo $reg_config['description2'];?>" /></td>
        </tr>
       
        <tr class="theader">
          <td height="23" colspan="4" >信息发布</td>
        </tr>
        <tr>
          <td height="40" align="left">有效期</td>
          <td colspan="3" align="left" ><?php echo $reg_config['validTime'];?></td>
        </tr>
        <tr>
          <td height="40" align="left">产品类型</td>
          <td colspan="3" align="left" ><input class="text" name="ptype" type="text" id="ptype" value="<?php echo $reg_config['ptype'];?>" /></td>
        </tr>
        <tr>
          <td width="17%" height="40" align="right">&nbsp;</td>
          <td colspan="3" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
