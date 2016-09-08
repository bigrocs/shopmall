<?php
include_once($config["webroot"]."/lib/smarty/Smarty.class.php");
include_once($config['webroot']."/config/seo_config.php");
//=================================================
$UID=bgetcookie("USERID");
$UID=$UID['0'];
$tpl =  new Smarty();
$tpl -> left_delimiter  = "<{";
$tpl -> right_delimiter = "}>";
$tpl -> template_dir    = $config["webroot"] . "/templates/".$config['temp']."/";
$tpl -> compile_dir     = $config["webroot"] . "/templates_c/".$config['temp']."/";
?>