<?php
//-----------------------------------------------------------------
$fn=basename($_SERVER['SCRIPT_FILENAME']);
if($fn!='main.php'&&$fn!='shop.php')
	@include_once($config['webroot'].'/lang/cn/front.php');
if(!empty($_GET['m']))
	@include('module/'.$_GET['m'].'/lang/cn.php');

$tpl->assign("lang",$lang);
$tpl->assign("sname",$fn);
//-----------------------------------------------------------------

$sql="select * from `$config[dbname]`. ".WEBCON." where con_statu=1 and type=0 and lang='$config[language]' order by con_no asc";
$db->query($sql);
while($v=$db->fetchRow())
{
	if(!empty($v['con_linkaddr']))
	{
		if(substr($v['con_linkaddr'],0,4)=='http')
			$url=$v['con_linkaddr'];
		else
			$url=$config['weburl'].'/'.$v['con_linkaddr'];
	}
	else
		$url=$config['weburl']."/aboutus.php?type=".$v['con_id'];
	$li[]="<a href='$url'>".$v['con_title']."</a>";
}
if(isset($li))
	$tpl->assign("web_con",implode("<em>|</em>",$li));
//------------------------------------------------------------------
if(!empty($config['copyright']))
{
	$config['copyright'].='<br />Powered by <a href="http://www.mall-builder.com">'.$config['version'].'</a>';
	$tpl->assign("bt",$config['copyright']);
}
$pathinfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
if($pathinfo['basename'] ==  'shop.php'){
    $tpl->assign('no_taoplus',1);
}

//获取用户头像 $buid
if ($buid)
{
	$sql = 'select logo from ' . MEMBER . ' where userid=' . $buid;
	$db->query($sql);
	$logo = $db->fetchField('logo');

	$tpl->assign("buid",$buid);
	$tpl->assign("logo", $logo);
}

$tpl->assign("config",$config);
$tpl->assign("buid",$buid);
//==================================================================
//
$rs = $PluginManager->trigger('end');

if (isset($_REQUEST['dist_id']))
{
	$PluginManager = Yf_Plugin_Manager::getInstance();
	$PluginManager->trigger('point_analyse');
}

$tpl->assign("chat_open_flag", $chat_open_flag);

if (true == $chat_open_flag)
{
	if ($config['temp']!='wap')
	{
		include_once("$config[webroot]/module/chat/includes/plugin_chat_class.php");
		$chat = new chat();

		$chat_html = $chat->getChatHtml();

		$tpl->assign("chat_html", $chat_html);
	}

	if (isset($config['weburl']))
	{
		$domain_root = $config['weburl'] . '/';;
	}
	else
	{
		$domain_root = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'] . '/';
	}

	$tpl->assign("domain_root", $domain_root);
}


if($config['rewrite']>0&&$config['temp']=='default')     //防止手机版无法使用
{
	if($config['rewrite']==1)
	{
		$searcharray[] = "/\/\?m=(\w+)&s=(\w+)&id=(\w+)/";
		$searcharray[] = "/\/\?m=(\w+)&s=(\w+)/";
		$searcharray[] = "/\/\?m=(\w+)/";
		$searcharray[] ="/shop\.php\?uid=(\w+)&action=(\w+)&id=(\w+)&m=(\w+)/";
		$searcharray[] ="/shop\.php\?uid=(\w+)&action=(\w+)&m=(\w+)/";
		$searcharray[] ="/shop\.php\?uid=(\w+)/";

		$replacearray[] = "/\\1-\\2-\\3.html";
		$replacearray[] = "/\\1-\\2.html";
		$replacearray[] = "/\\1.html";
		$replacearray[] = "shop-\\1-\\2-\\3-\\4.html";
		$replacearray[] = "shop-\\1-\\2-\\3.html";
		$replacearray[] = "shop-\\1.html";
	}
	function rewrite($output, &$smarty)
	{
		global $searcharray,$replacearray;
		return preg_replace($searcharray, $replacearray, $output);
	}
	$tpl->register_outputfilter("rewrite");
}
if(isset($mlang))
	$tpl->register_outputfilter("translate");
?>