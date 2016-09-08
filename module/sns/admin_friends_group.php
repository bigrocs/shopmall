<?php
include_once("$config[webroot]/includes/global.php");

//===================================================================
//==========修改好友分组名称
//print_r($_GET);
if ($_GET['ope'] =='upda'){
    $sql = "SELECT * FROM " . FRIENDG . " WHERE group_id = '$_GET[editid]'";
    $db->query($sql);
    $re =$db->getRows();
    $tpl->assign("groname",$re[0]);

    if (!empty($_POST['submit'])){
         $sql = "UPDATE " . FRIENDG . " SET name = '$_POST[name]', `describe` = '$_POST[describe]' where group_id='$_GET[editid]' ";
         $db->query($sql);
         $tpl->assign("result",2);
    }
}
//==========删除好友分组
if ($_GET['delid']){
    $sql ="SELECT * FROM " . FRIEND . " WHERE uid='$buid' and group_id='$_GET[delid]' ";
    $db->query($sql);
    $rss = $db->getRows();
    if(count($rss) >0){
        echo "<script language=javascript>alert('该分组下有好友,请移动后再删除分组!');</script>";
        msg("main.php?m=sns&s=admin_friends");
    }else{

    $sql = "DELETE FROM " . FRIENDG . " WHERE group_id='$_GET[delid]' ";
    $db->query($sql);
     msg("main.php?m=sns&s=admin_friends");
    }
}
//==========添加好友分组
if (isset($_POST['submit1']) && !empty($_POST['name'])){
	$sql ="INSERT INTO ".FRIENDG." (name,`describe`)VALUES('$_POST[name]','$_POST[describe]')";
	$db->query($sql);
    $tpl->assign("result",1);
}

//============获取分组

$tpl->assign("config",$config);
tplfetch("admin_friends_group.htm",'',true);
?>


