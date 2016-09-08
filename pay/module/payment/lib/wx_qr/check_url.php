<?php
error_reporting(E_ERROR);

include_once("../../../../includes/global.php");

$cache_dir = $config['webroot'] . '/cache/wx_qr/';


if (!file_exists($cache_dir))
{
	mkdir($cache_dir, 0777, true);
}

//设置cache 参数
//cacheType 1:file  2:memcache   3：redis
$config_cache['wx_qr'] = array(
	'cacheType' => 1,
	'cacheDir' => $cache_dir,
	'memoryCaching' => false,
	'automaticSerialization' => true,
	'hashedDirectoryLevel' => 3,
	'hashedDirectoryUmask' => 0777,
	'cacheFileMode' => 0777,
	'lifeTime' =>  86400
);

$qr_cache = new Cache_Lite_Output($config_cache['wx_qr']);


$id = $_REQUEST['code'];

$rs = $qr_cache->get($id);

$data = array();

if ($rs)
{
	$data['status'] = 200;
}
else
{
	$data['status'] = 250;
}


echo json_encode($data);
die();