<?php

	include_once("../../includes/global.php");
	include_once($config['webroot']."/config/taobao_config.php");
	
	$appSecret = $taobao_config['secretKey'];
	$appkey = $taobao_config['appkey'];
	$code = $_REQUEST['code'];

	$url = 'https://oauth.taobao.com/token';
	$postfields= array('grant_type'=>'authorization_code',
	'client_id'=>$appkey,
	'client_secret'=>$appSecret,
	'code'=>$code,
	'redirect_uri'=> $config['weburl']."/module/product/login.php");
	$post_data = '';
	
	foreach($postfields as $key=>$value){
	$post_data .="$key=".urlencode($value)."&";}
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);  
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//指定post数据
	curl_setopt($ch, CURLOPT_POST, true);
	//添加变量
	curl_setopt($ch, CURLOPT_POSTFIELDS, substr($post_data,0,-1));
	$output = curl_exec($ch);
	$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$re = json_decode($output);
	$_SESSION['topsession'] = $re->access_token;
	curl_close($ch);
	if($_SESSION['topsession'])
	{
		header("location: ".$config['weburl']."/main.php");
	}
	else
	{	
		print_r($output);die;
	}
?>