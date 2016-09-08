<?php
/*
*	会员进入个人中首页时提醒绑定邮箱！
*/

$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
tplfetch("bind_email_tips.htm",'',true);
?>