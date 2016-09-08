<?php
include_once("../includes/global.php");

$dir = '../install/templates/mail';
$subject = array('find_pwd'=>'找回登录密码','register'=>'注册欢迎邮件','active'=>'邮箱验证');
$mailTitle = array('find_pwd'=>'[weburl_name]找回登录密码','register'=>'感谢您注册[weburl_name]','active'=>'请激活您在[weburl_name]账户');

//导入数据库
$fileArray = $_POST['file'];
if(is_array($fileArray))
{
	$table = MAILMOD;
	$fileValue = join('\',\'', array_values($fileArray));

	$db->query("SELECT `id` FROM $table WHERE `flag` in('$fileValue')");
	$rows = $db->getRows();
	if($rows)
	{
		admin_msg("mailmod_import.php","模板已经存在，不能重复导入");
	}
	else
	{
		foreach($_POST['file'] as $fv)
		{
			$message = file_get_contents($dir.'/'.$fv.'.html');
			$result = $db->query("INSERT INTO $table(`subject`, `title`, `message`, `flag`) VALUES('{$subject[$fv]}', '{$mailTitle[$fv]}', '{$message}', '{$fv}')");
		}
	}
	
	admin_msg("mailmod.php","导入成功");
}



function getFile($dir)
{
$fileArray[]=NULL;
if (false != ($handle = opendir ( $dir ))) {
	$i=0;
	while ( false !== ($file = readdir ( $handle )) ) {
		if ($file != "." && $file != ".."&&strpos($file,".")) {
			$fileArray[$i] = $file;
			if($i==100){
				break;
			}
			$i++;
		}
	}
	closedir ( $handle );
}
return $fileArray;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>

<body>
<div class="bigbox">
<form action="" method="post">
  <div class="bigboxhead">导入邮件模板</div>
  <?php
  foreach(getFile($dir) as $v){
  $tplName = explode('.', $v);
  ?>
  <div class="bigboxbody"><label><input name="file[]" type="checkbox" value="<?php echo $tplName[0];?>" />&nbsp;<?php echo $v .'&nbsp;&nbsp;('. $subject[$tplName[0]] .')';?></label></div>
  <?php }?>
  <div style="clear:both;"><button class="btn" type="submit">导入</button></div>
</form>
</div>
</body>
</html>
