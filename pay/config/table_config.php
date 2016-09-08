<?php
define("CONFIG",$config['table_pre']."web_config");//网站配置表
define("WEBCON",$config['table_pre']."web_con");//网站初始化内容设置
define("WEBCONGROUP",$config['table_pre']."web_con_group");//网站初始化内容分组

define("ADMIN",$config['table_pre']."admin");//网站管理员
define("ADMINMENU",$config['table_pre']."admin_menu");//
define("OPLOG",$config['table_pre']."admin_operation_log"); //后台管理员操作日志
define("DISTRICT",$config['table_pre']."district");
define("SESSION",$config['table_pre']."session");
//======================================加载模块表配置。
$dir=$config['webroot'].'/module/';
$handle = opendir($dir); 
while ($filename = readdir($handle))
{ 
	if($filename!="."&&$filename!="..")
	{
	  if(file_exists($dir.$filename.'/table_config.php'))
	  { 
		 include("$dir/$filename/table_config.php");
	  }
   }
}
?>