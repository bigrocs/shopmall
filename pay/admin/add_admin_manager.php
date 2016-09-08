<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");

//============================================
if(!empty($_POST['user'])&&!isset($_GET['id']))
{
	foreach($_POST as $key=>$v)
	{
		$_POST[$key]=trim($_POST[$key]);
	}
	$sql="select * from ".ADMIN." WHERE user='$_POST[user]'";
	$db->query($sql);
	if(!$db->num_rows())
	{
		$_POST['password']=md5($_POST['password']);
		$sql="insert into ".ADMIN."
		 (user,name,password,group_id,`desc`,province,city,area,type,lang)
		  VALUES
('$_POST[user]','$_POST[name]','$_POST[password]','0','$_POST[desc]','','','','1','cn')";
		 $re=$db->query($sql);
		 if($re)
			msg("admin_manager.php");
	}
	else
		msg("add_admin_manager.php?type=1");
}
if(!empty($_GET['id']))
{
	if(!empty($_POST['password']))
	{
		$_POST['password']=md5($_POST['password']);
		$pa="password='$_POST[password]',";
	}
	$sql="update ".ADMIN." set ".$pa."  `desc`='".$_POST['desc']."' , name='$_POST[name]' where id='".$_GET['id']."'";
	$re=$db->query($sql);
	 if($re)
		msg("admin_manager.php");
}


//-----------------------------------------------
if(!empty($_GET['id']))
{
	$sql="select * from ".ADMIN." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
$sql="SELECT id, user FROM ".ADMIN." WHERE user <> 'admin'";
$db->query($sql);
$users = $db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../script/district.js" ></script>
<script>
var weburl="<?php echo $config["weburl"]; ?>";
</script>
<style>.hidden{ display:none;}</style>
</HEAD>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('add_manager');?></div>
	<div class="bigboxbody">
  <form name="form1" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <?php
	 if(!empty($_GET['type'])&&$_GET['type']==1)
	 {p
	 ?>
	 <tr>
        <td>&nbsp;</td>
        <td><?php echo lang_show('repeat_msg');?></td>
      </tr>
	  <?php }?>
      <tr>
        <td  class="body_left"><?php echo lang_show('actuser');?>&nbsp;</td>
        <td><input class="text" type="text" name="user" value="<?php echo $de['user'];?>" <?php if($de['user']){echo "disabled";}?> /></td>
      </tr>
      <tr>
        <td><?php echo lang_show('password');?></td>
        <td><input class="text" type="text" name="password"><?php if(!empty($_GET['id'])){echo lang_show('passmsg');}?></td>
      </tr>
	  <tr>
        <td  class="body_left"><?php echo lang_show('manager_name');?></td>
        <td width="85%"><input class="text" type="text" name="name" value="<?php echo $de['name'];?>" /></td>
      </tr>
      
      
      <tr>
        <td><?php echo lang_show('des');?></td>
        <td>
        <textarea class="text" name="desc" cols="50" rows="7"><?php if(!empty($de['desc'])) echo $de['desc'];?></textarea>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>"></td>
      </tr>
    </table>
    </form>
</div>
</div>
</body>
</html>