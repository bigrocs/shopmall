<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//====================================================
if(isset($_GET['act'])&&isset($_GET['id']))
{
	$sql="delete from ".ADMIN." WHERE id='$_GET[id]'";
	$db->query($sql);
}
$sql="SELECT id,user FROM ".ADMIN." WHERE id!=1 order by id desc";
$db->query($sql);
$users = $db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo lang_show('admin_system');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('admin_manager');?></div>
	<div class="bigboxbody">
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<tr class="theader"> 
		<td width="47">ID</td>
		<td width="212"><?php echo lang_show('actuser');?></td>
		<td width="247"><?php echo lang_show('username');?></td>
		<td width="121" align="left"><?php echo lang_show('operation');?></td>
	</tr>
	<?php
		while(list($key,$item) = @each($users))
		{
			echo '<tr>
			<td>'.$item['id'].'</td>
			<td>'.$item['user'].'</td>
			<td>'.$item['name'].'</td>
			<td><a href="add_admin_manager.php?act=edit&id='.$item['id'].'">'.$editimg.'</a> <a href="admin_manager.php?act=del&id='.$item['id'].'">'.$delimg.'</a></td>
			</tr>';
		}
	?>
</table>
</div>
</div>
</body>
</html>