<?php
include_once("includes/global.php");
//==================
//print_r($_GET['nid']);

if (isset($_POST['submit1']) && !empty($_POST['fnickname'])){
    $sql = "UPDATE " . FRIEND . " SET fnickname='$_POST[fnickname]' WHERE id='$_GET[nid]' ";
    $db->query($sql);
    $tpl->assign("result",1);
}
//==============ÐÞ¸ÄêÇ³Æ
if ($_GET['ope'] =='upda'){
    $sql = "SELECT * FROM " . FRIEND . " WHERE id = '$_GET[editid]'";
    $db->query($sql);
    $re =$db->getRows();
    $tpl->assign("nick",$re[0]);

    if (!empty($_POST['submit'])){
        $sql = "UPDATE " . FRIEND . " SET fnickname='$_POST[fnickname]' WHERE id='$_GET[editid]' ";
        $db->query($sql);
        $tpl->assign("result",2);
    }
}
/*if (!empty($_POST['sid']) && !empty($_POST['name'])){
    $sql = "UPDATE " . FRIEND . " SET nickname1='$_POST[name]' WHERE id='$_POST[sid]' ";
    //$db ->query($sql);
    // msg("main.php?m=sns&s=admin_friends.php&cg_u_type=1");
}*/
tplfetch("admin_nick.htm",'',true);
?>
