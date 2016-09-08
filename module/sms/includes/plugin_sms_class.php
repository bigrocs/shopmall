<?php
class sms
{
	var $db;
	var $tpl;
	var $page;
	function sms()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function send($mob,$content)
	{
		global $config;
		include_once($config['webroot']."/config/sms_config.php");
		
		$name=$sms_config['sms_account'];
		$password=md5($sms_config['sms_pass']);
		$mob=$_POST['mob']?$_POST['mob']:$mob;
		$content=urlencode($_POST['con']?$_POST['con']:$content);
		$content = iconv("utf-8","gb2312//IGNORE",$content);
		
		$url="http://sms.b2b-builder.com/sms.php?name=".$name."&password=".$password."&mob=".$mob."&content=".$content;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result =  curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
?>
