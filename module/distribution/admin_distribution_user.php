<?php
include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");

$distribution = new distribution();

$level = null;

if (isset($_REQUEST['level']))
{
	$level = intval($_REQUEST['level']);
}
else
{
	$level = array(1, 2);
}

$page_str = true;
$child_user_rows = $distribution->getDistributionChildUser($buid, $level, $page_str);

$type = 'inbox';
$tpl->assign("re", $child_user_rows);
//============================================
$tpl->assign("config", $config);
$tpl->assign("lang", $lang);
$tpl->assign("page", $page_str);

$output = tplfetch("admin_distribution_user.htm");

