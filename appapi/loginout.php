<?php
	
	## 退出无传递和返回参数

	include_once("../includes/global.php");
	include_once("../config/reg_config.php");

	$config = array_merge($config,$reg_config);

	bsetcookie("USERID",NULL,time(),"/",$config['baseurl']);
	setcookie("USER",NULL,time(),"/",$config['baseurl']);

	//=====================
	
	include_once("../uc_client/client.php");
	echo uc_user_synlogout();
	$_SESSION['USER_TYPE']=NULL;
?>