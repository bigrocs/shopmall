<?php
	include_once("../includes/global.php");
	include_once("../module/sms/includes/plugin_sms_class.php");
	$sms = new sms();
	if(isset($_GET['mobile']) && !empty($_GET['mobile']))
	{
		$cache_dir = $config['webroot'] . '/cache/sms/';
		make_dir_path($cache_dir);

		//设置cache 参数
		//cacheType 1:file  2:memcache   3：redis
		$config_cache['sms'] = array(
			'cacheType' => 1,
			'cacheDir' => $cache_dir,
			'memoryCaching' => false,
			'automaticSerialization' => true,
			'hashedDirectoryLevel' => 3,
			'hashedDirectoryUmask' => 0777,
			'cacheFileMode' => 0777,
			'lifeTime' =>  86400
		);

		$sms_cache = new Cache_Lite_Output($config_cache['sms']);
		//Yf_Registry::set('sms_cache', $sms_cache);
		$ip = $_GET['mobile'];

		$rs = $sms_cache->get($ip);

		if ($rs >= 5)
		{
			$num = $rs + 1;

			$rs = $sms_cache->save($num);

			echo '发送失败,单一手机号一天最多接收5条!';
		}
		else
		{
			$num = $rs + 1;

			$rs = $sms_cache->save($num);

			//计算分佣
			$str = $sms->send($_GET['mobile'],$_GET['me']); //发送短信
			$str = iconv("gb2312","utf-8//IGNORE",urldecode($str));
			echo $str;
		}

	}
	else if(isset($_GET['msg_id']) && $_GET['msg_id'] > 0 && $_GET['key'] == md5($config['authkey'])) //短信重发 传递短信ID和uathkey
	{
		$id = $_GET['msg_id'] * 1;
		$sql = "select * from ".MSGCORD." where `id` = ".$id;
		$db -> query($sql);
		$re = $db -> fetchRow();
		if($re['receive'])
		{
			$str = $sms->send($re['receive'],$re['msg']); //发送短信
			$str = iconv("gb2312","utf-8//IGNORE",urldecode($str));

			$sql = "update ".MSGCORD." set `count` = `count`+1 where  `id` = ".$id;
			$db -> query($sql);

			echo $str;
		}
		else
		{
			echo 0;
		}
	}
	else
	{
		echo 0;
	}
?>