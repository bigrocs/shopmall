<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
// ========= 微信支付第一步骤 =========
if($config['bw'] == "weixin" && !isset($_SESSION['openid_f']))
{
	/**
	 * 成功调起支付第一步骤：
	 * 步骤1：网页授权获取用户openid
	*/
	include_once("./pay/module/payment/lib/WxPayPubHelper/WxPayPubHelper.php");
	//使用jsapi接口
	$jsApi = new JsApi_pub();
	//通过code获得openid
	if (!isset($_GET['code']) && !isset($_SESSION['openid_f'])) // && $_GET['m']!="product"
	{
		//$url_temp = WxPayConf_pub::JS_API_CALL_URL;
		$url_temp = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$url_temp = urlencode($url_temp);

		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode($url_temp);

		header("Location: $url");
	}
	else if(isset($_GET['code']))
	{
		//获取code码，以获取openid
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
		$_SESSION['openid_f'] = $openid;

		//自动根据openid登录操作
	}
}
//==========================================
$dre=explode(".",$_SERVER['HTTP_HOST']);
$dir=trim(dirname($_SERVER['SCRIPT_NAME']), '\,/');//view: abc.**.com/a.php

$true_prefix=str_replace($config['baseurl'],'',$_SERVER['HTTP_HOST']);
$original_prefix=str_replace('http://','',str_replace($config['baseurl'],'',$config['weburl']));

//view:abc.abc.com, baserurl:abc.abc.com, weburl:abc.abc.com ----- true_prefix='';original_prefix='';
//view:abc.ocm view, baseurl:abc.com, weburl:www.abc.com true_prefix='',original_prefix='www'
if(empty($true_prefix)&&!empty($original_prefix))
{
	header("Location: ".$config['weburl']);exit();
}
if($true_prefix!=$original_prefix&&empty($dpid)&&empty($dcid)&&empty($dir)&&!empty($config['baseurl'])&&empty($mlang))
{
	if($config['opensuburl'])
	{
		//绑定顶级域名
		$sql="select userid from ".SHOP." where domin='"."http://".$_SERVER['HTTP_HOST']."'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($re['userid'])
		{
			$_GET['uid']=$re['userid'];
			include_once("shop.php");
			exit();
		}
		//二级域名转发
		$sql="select userid from ".MEMBER." where userid='".str_replace('shop-','',$dre['0'])."'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($_GET['uid']=$re["userid"])
		{
			include_once("shop.php");
			exit();
		}
		header("Location: ".$config['weburl']);
		exit();
	}
	else
	{
		header("Location: ".$config['weburl']);
		exit();
	}
}
else
{
	$file=$config['webroot'].'/cache/front/index.htm';
	if(file_exists($file) && (time() - filemtime($file)<$config['cacheTime']*1)&&empty($_GET['m']))
	{
		include($file);
	}
	else
	{
		unset($dre);unset($dir);
		$_GET['s']=!empty($_GET['s'])?$_GET['s']:'index';
		$_GET['m']=!empty($_GET['m'])?$_GET['m']:'product';
		$_GET['m'] = preg_replace('#[^a-z]#iuU', '',$_GET['m']);
		if(!empty($_GET['m']))
		{	
			$s=$_GET['s'];
			$m=$_GET['m'];
			if(file_exists($config['webroot'].'/module/'.$m.'/'.$s.'.php'))
			{
				if(file_exists($config['webroot'].'/config/module_'.$m.'_config.php'))
				{
					@include($config['webroot'].'/config/module_'.$m.'_config.php');
					$mcon='module_'.$m.'_config';
					$$mcon = array_filter($$mcon);
					@$config = array_merge($config,$$mcon);
				}
				if($s=='index'&&$m=='product')
				{
					@include($config['webroot'].'/config/home_config.php');
					if($home_config)
					@$config = array_merge($config,$home_config);
				}
				@include('module/'.$m.'/lang/'.$config['language'].'.php');
				include('module/'.$m.'/'.$s.'.php');
			}
			elseif(file_exists($m.'.html'))
				include($m.'.html');
			else
				header("Location: 404.php");
			if(!empty($out))
			{	
				$tpl->assign("out",$out);unset($out);unset($tpl->statu);
				$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/";
				$tpl->caching = false; //设置缓存方式
				$tpl->display("m.htm");
			}
		}
		else
			header("Location: 404.php");
	}
}
?>