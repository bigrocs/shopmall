<?php
//设置时区
if (function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('Asia/Shanghai');
	//date_default_timezone_set('UTC');
}

include_once("../includes/global.php");
include_once("../includes/smarty_config.php");

include_once($config['webroot']."/config/app_push_config.php");

require_once '../lib/jpush/jpush.php';


$user = array("u_11");
$msg_title = '消息标题';
$msg_content = '消息内容';

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

echo $str . '-------------' . $br;
?>