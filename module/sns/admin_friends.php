<?php
include_once("$config[webroot]/module/sns/includes/plugin_friend_class.php");
include_once("$config[webroot]/includes/global.php");
$friend=new friend();
//=================
//=========获取好友总数
$sql = "SELECT * FROM " . FRIEND . " WHERE uid='$buid'";
$db ->query($sql);
$res = $db->getRows();
$cou = count($res);
$tpl->assign("cou",$cou);

//============获取分组

$sql = "SELECT * FROM " . FRIENDG . " ";
$db->query($sql);
$re =$db->getRows();
$tpl->assign("gronmame",$re);
//获取好友
$tpl->assign("re",$friend->GetFriendList());

//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_friends.htm");
?>