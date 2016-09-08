<?php
if(!empty($_GET["action"])&&!empty($_GET["id"]))
{
	$id = $_GET["id"] * 1;
	$status = $_GET["action"] == 'open' ? "1" : "0";	
	$sql="update ".FASTMAIL." set status='$status' where id='$id'";
	$db->query($sql);
	msg("module.php?m=logistics&s=fast_mail.php");
}
$sql="select * from ".FASTMAIL."  order by status desc,id";
$db->query($sql);
$re=$db->getRows();
//=======================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../script/my_lightbox.js" language="javascript"></script>
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
<div class="bigboxhead">预置物流公司</div>
<div class="bigboxbody">
<form action="" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr class="theader">
    <td>物流公司名</td>
    <td width="80" align="center">状态</td>
    <td width="80" align="center">操作</td>
</tr>
<?php
foreach ($re as $v)
{
?>
    <tr>
        <td><?php echo $v['company']." (".$v['pinyin'].")";?></td>
        <td align="center"><?php if($v['status']==1) echo "开启"; else echo "关闭"; ?></td>
        <td align="center">
        <a href="module.php?m=logistics&s=fast_mail.php&action=<?php if($v['status']==1) echo "close"; else echo "open"; ?>&id=<?php echo $v['id'];?>"><?php if($v['status']==1) echo "点击关闭"; else echo "点击开启"; ?></a>
       </td>
    </tr>
<?php
}
?>
    <tr>
    	<td colspan="2" align="left"><input class="btn" type="submit" name="delsel" id="delsel" value="<?php echo lang_show('delete');?>"></td>
    </tr>
</table>
 </form>
</div>
</div>
</body>
</html>

