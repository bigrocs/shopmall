<?php
$buid = 0;

//============================================================
if (isset($_POST['msgcon']))
{
	$_POST[msgtitle] =  $_POST['msgcon'];

	if (!empty($_POST['msgcon']))
	{
		$date = date("Y-m-d H:i:s");

		$sql = "select user,email,userid from " . MEMBER . " where 1";
		$db->query($sql);
		$re = $db->getRows();

		foreach ($re as $v)
		{
			$sql = "insert into " . FEEDBACK . " (uid, touserid,fromuserid,fromInfo,sub,con,date,msgtype, iflook) VALUES
				('$v[userid]', '$v[userid]','$buid','Sys Message','$_POST[msgtitle]','$_POST[msgcon]','$date','1','2')";
			$db->query($sql);

			//$sql = "insert into " . FEEDBACK . " (uid, touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES
				//('$buid','$v[userid]','Sys Message','$_POST[msgtitle]','$_POST[msgcon]','$date','2')";
			//$db->query($sql);
		}
	}

	$notice_msg = '群发成功！';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
	<TITLE><?php echo lang_show('admin_system'); ?></TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="main.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>
<div class="bigbox">
	<div class="bigboxhead">管理员群发消息，所有用户都将受到消息 <b style="color: #008000;"><?php echo $notice_msg ?></b></div>
	<div class="bigboxbody">
		<form method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="5%">消息内容</td>
					<td width="80%"><textarea name="msgcon" class="text" cols="50" rows="20"></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input name="m" class="text" type="text" style="display: none;" value="message">
						<input name="s" class="text" type="text" style="display: none;" value="sys_chat_send.php">
						<input class="btn" type="submit" name="Submit" value="群 发">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

</body>
</html>

