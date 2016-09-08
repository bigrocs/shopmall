<?php
include_once("module/shop/includes/plugin_temp_class.php");
$temp=new temp();
//===================================================================

if($_POST['submit']=="add")
{	
    $flag=$temp->add_cs();
    $time = $_POST['working_time'];
    $sql="select id from ".CUSTOMTIME." where userid=$buid";
    $numRows=mysql_num_rows($db->query($sql));
    if($numRows){
        $sql="update ".CUSTOMTIME." `time` set `time`='$time' where userid=$buid";
        $db->query($sql);
    }else{
        $sql="insert into ".CUSTOMTIME." (`time`,`userid`)values('$time','$buid')";
        $db->query($sql);
    }
	$admin->msg('main.php?m=shop&s=admin_custom_service');
}

if($_POST['op']=="del")
{	
	$flag=$temp->del_cs();die;
}
$sql="select time from ".CUSTOMTIME." where userid=$buid";
$db->query($sql);
$working_time = $db->fetch_Row();
$tpl->assign("working_time",$working_time);
$tpl->assign("cs",$temp->get_cs());
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_custom_service.htm");
?>
