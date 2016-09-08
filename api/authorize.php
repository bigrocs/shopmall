<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
include_once("config/reg_config.php");

$tpl->assign("url",$_GET['redirect_uri']."&met=".$_GET['met']."&typ=".$_GET['typ']."&sid=".$_GET['sid']);
$tpl->assign('user', $_GET['user']);
//======================================================================
$tpl->display("authorize.htm");
?>