<?php
$mem = array(
	"index"=>array
	(
		'首页',
		array
		(
			array(
				'',
				array(
					'main_index.php,1,,管理中心首页',
					'admin_menu.php,1,,常用操作管理',
					)
				)
			)
	)
	,
	'global'=>array
	(
		'设置',
		array
		(
			array(
				'',
				array(
					'system_config.php,1',
					'seo_config.php,1',
					'aboutus.php,1,,独立页面',
					'help.php,1,,帮助中心',
					'to_login.php,0,,登录会员后台',
				)
			),
			array(
				lang_show('admini'),
				array(
					'add_admin_manager.php,1',
					'admin_manager.php,1',
					'modpass.php,1',
					'operate_log.php,1',
				)
			),	
		)
	),
	"pay"=>array
	(
		lang_show('account_manager'),
	)
	,
	/*"product"=>array
	(
		'产品',
		array
		(
			
		)
	),
	
	"member"=>array
	(
		'会员',
		array
		(
			
		)
	),
	
	"shop"=>array
	(
		'店铺',
		array
		(
			
		)
	)
	,
	"business"=>array
	(
		'交易',
		array
		(
			
		)
	)
	,
	
	"running"=>array
	(
		'运营',
	),
	
	
	"website"=>array
	(
		'网站',
		array
		(
			
		)
	),
	*/
	"tools"=>array
	(
		'工具',
		array
		(
			array(
				'',
				array(
					'clearcahe.php,1',
					'up_db.php,1',
					'db_export.php,1',
					'optimizetable.php,1',
				)
			)
		)
	)
	,

);
$sql="select name,url from ".ADMINMENU." where uid='$_SESSION[ADMIN_USER_ID]' order by displayorder,id ";
$db->query($sql);
$de=$db->getRows();
foreach($de as $key=>$val)
{
	$mem['index'][1][0][1][$key+2]="$val[url],1,,$val[name]";	
}
$dir=$config['webroot'].'/module/';
$handle = opendir($dir); 
while ($filename = readdir($handle))
{ 
	if($filename!="."&&$filename!="..")
	{
	  if(file_exists($dir.$filename.'/config.php'))
	  { 
		include("$dir/$filename/config.php");
	  }
   }
}
foreach($mem as $key=>$v)
{
	if(isset($mem[$key][1]))
		ksort($mem[$key][1]);
}
?>