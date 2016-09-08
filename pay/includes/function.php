<?php

function is_mobile()
{
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	$is_mobile = false;
	foreach ($mobile_agents as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}

function isrobot() 
{
	if(!defined('IS_ROBOT'))
	{
		$kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
		$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
		if(!strexists($_SERVER['HTTP_USER_AGENT'], 'http://') && preg_match("/($kw_browsers)/i", $_SERVER['HTTP_USER_AGENT'])) {
			define('IS_ROBOT', FALSE);
		} elseif(preg_match("/($kw_spiders)/i", $_SERVER['HTTP_USER_AGENT'])) {
			define('IS_ROBOT', TRUE);
		} else {
			define('IS_ROBOT', FALSE);
		}
	}
	return IS_ROBOT;
}
 
function convert($array)
{
	if(is_array($array))
	{
		 @array_walk($array, create_function('&$value, $key', '$value = $key ."=". $value;'));
	}
	return $array;
}

function msg($url,$str="")
{
	if($url&&!$str)
		echo "<script>window.location='$url';</script>";
	if($url&&$str)
		echo "<script>alert('$str');window.location='$url';</script>";
	die;
}

function dateformat($time)
{
	global $config;
	if(!empty($config['date_format']))
		$config['date_format']=str_replace("%",'',$config['date_format']);
	if(is_numeric($time))
		return date($config['date_format'],$time);
	else
		return date($config['date_format'],strtotime($time));
}

function mkdirs($dir)
{    
	return is_dir($dir) or (mkdirs(dirname($dir)) and mkdir($dir, 0777));
}

function authcode($string, $operation = 'DECODE', $expiry = 0)
{
	global $config;
	$ckey_length = 4;
	$key = md5(md5($config['authkey'].$_SERVER['HTTP_USER_AGENT']));
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function bsetcookie($var,$value,$time=NULL,$path=NULL,$dommain)
{
	global $config;
	$value=authcode($value,'ENDODE');
	setcookie("cn".$var,$value,$time,$path,$dommain);
}

function bgetcookie($var)
{
	global $config;
	$nvar=$config['language'].$var;
	if(isset($_COOKIE[$nvar]))
		return explode("\t", authcode($_COOKIE[$nvar], 'DECODE')) ;
	elseif(isset($_COOKIE['cn'.$var]))
		return explode("\t", authcode($_COOKIE['cn'.$var], 'DECODE')) ;
	elseif(isset($_COOKIE['en'.$var]))
		return explode("\t", authcode($_COOKIE['en'.$var], 'DECODE')) ;
}

function lang_show ($langKey, $argument = null)
{
	global $lang;
	$showContent = $lang[$langKey];
	if(empty($lang[$langKey]))
	    return false;
	
	if($argument)
	{
	    while(list($key,$item) = @each($argument))
	    {
			$showContent = str_replace('#'.$key, $item, $showContent);
	    }
	}
	return $showContent;
}

function inject_check($sql)
{ 
	return preg_match("/^select|insert|delete|\.\.\/|\.\/|union|into|load_file|outfile/", $sql);// 进行过滤   
}

function magic()
{
	if(!get_magic_quotes_gpc()&&isset($_POST))
	{
		foreach($_POST as $key=>$v)
		{
			if(!is_array($v))
				$_POST[$key]=addslashes($v);
			else
			{
				foreach($v as $skey=>$sv)
				{
					if(!is_array($sv))
						$_POST[$key][$skey]=addslashes($sv);
					else
					{
						if($sssv)
						{
							foreach($sv as $sskey=>$ssv)
							{
								if(!is_array($ssv))
									$_POST[$key][$skey][$sskey]=addslashes($ssv);
								else	
								{
									if($sssv)
									{
										foreach($ssv as $ssskey=>$sssv)
										{
											if(!is_array($sssv))
												$_POST[$key][$skey][$sskey][$ssskey]=addslashes($sssv);
											else	
											{
												if($sssv)
												{
													foreach($sssv as $sssskey=>$ssssv)
													{
														$_POST[$key][$skey][$sskey][$ssskey][$sssskey]=addslashes($ssssv);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	//===========GET
	if(inject_check(implode('',$_GET)))
	{
		die('Invalid URL !');
	}
	//===========POST
	if(isset($_POST))
	{
		foreach($_POST as $key=>$v)
		{
			if(!is_array($v))
			{
				if(strpos($v,'eval(')or(strpos($v,'$_POST[')))
					die('Invalid POST');
			}
		}
	}
}

function stop_ip($ip)
{
	foreach($ip as $v)
	{	
		if($uip=getip())
		{	
			$pos = strpos($uip,str_replace(".*","",$v));
			if($pos===false)
				;
			else
				die("您的IP被禁止访问");
		}
		else
			die;
	}
}

function strexists($haystack, $needle)
{
	return !(strpos($haystack, $needle) === FALSE);
}

function makethumb($srcFile,$dstFile,$dstW,$dstH,$watermark=true)
{ 
	global $config;
	include_once("$config[webroot]/includes/image_class.php");
	$t=new cls_image();
	$t-> watermark=$watermark;
	$t-> make_thumb($srcFile, $dstFile,$dstW,$dstH);
	unset($t);
}

function csubstr($string, $start, $length, $dot = ' ...')
{   
	if(strlen($string) <= $length) {
		return $string;
	}
	$string = str_replace(array('&nbsp;','&amp;', '&quot;', '&lt;', '&gt;'), array(' ','&', '"', '<', '>'), $string);
	$strcut = '';
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
	return $strcut;
}
##########################################
function useCahe($cachPath=NULL,$lifetime=NULL)
{
	global $tpl,$config;
	if(!empty($config['cacheTime']))
	{
		if(!empty($_GET['m']))
		{
			//如果是 module下面调用，需要事先更改模板路径。
			$dir=$config['webroot'].'/module/'.$_GET['m'].'/templates/'.$config['temp'].'/';
			if(file_exists($dir.$file))
				$tpl->template_dir=$dir;
			else
				$tpl->template_dir=$config['webroot'].'/module/'.$_GET['m'].'/templates/default/';
		}
		
		$tpl->caching = true; //设置缓存方式 
		
		if(!empty($cachPath))
			$tpl->cache_dir = $config['webroot'].'/cache/'.$cachPath;
		else
			$tpl->cache_dir = $config['webroot'].'/cache/front/';
		
		if($lifetime==true)
			$tpl->cache_lifetime = -1 ; //永久有效
		elseif(!is_null($lifetime))
			$tpl->cache_lifetime = $lifetime ; //设置缓存时间
		else
			$tpl->cache_lifetime = $config['cacheTime'] ; //设置缓存时间
	}
}

function send_mail($email,$name,$title,$con,$reply=NULL)
{	
	global $config;
	include($config['webroot'].'/config/mail_config.php');
	if($mail_config['mail_statu']==0)
		return NULL;//邮件功能关闭
	else
	{
		if(empty($reply))
		{
			if(!empty($config['email']))
				$reply=$config['email'];
			else
				$reply=$email;
		}
		if(!empty($mail_config["sent_type"])==2)
		{	
			include_once($config['webroot']."/lib/phpmailer/class.phpmailer.php");
			for($i=1;$i<=6;$i++)
			{	
				$index='smtp'.$i;
				if($mail_config[$index]!='')
				{
					$t++;
					$s[$t]['smtp']=$mail_config['smtp'.$i];
					$s[$t]['email']=$mail_config['email'.$i];
					$s[$t]['emailPass']=$mail_config['emailPass'.$i];
				}
			}
			$get_index=rand(1,$t);
			$m_smtp=$s[$get_index]['smtp'];
			$m_email=$s[$get_index]['email'];
			$m_emailPass=$s[$get_index]['emailPass'];
			
			$mail = new PHPMailer();
			$mail->IsSMTP();                        
			$mail->Host = $m_smtp;  	
			$sm=explode(":",$m_smtp);
			if(count($sm)>=2)
			{
				$mail->Host = $sm[0];
				$mail->Port = $sm[1];
			}  
			$mail->SMTPAuth = true;                
			$mail->Username = $m_email;               
			$mail->Password = $m_emailPass;          
			$mail->From = !empty($from)?$from:$m_email;
			$mail->FromName=$config['company']; 
			$mail->FromEmail=$reply;
			$mail->AddReplyTo($reply,$config['company']);//回复地址
			$mail->WordWrap = 50;           
			$mail->AddAddress($email,$name);
			$mail->IsHTML(true);                
			$mail->CharSet="utf-8";
			$mail->Subject =$title; 
			$mail->Body =$con;
			$re=$mail->send();
			return $re;
		}
		else
		{
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
			$headers .= 'From: '.$reply.'<'.$config['company'].'>' . "\r\n";
			return mail($email,$title,$con,$headers);
		}
	}
}

function tplfetch($file,$flag=NULL,$no_return=false)
{
	global $tpl,$config;
	if(file_exists($tpl->template_dir.$file))
		;//当前主模板下面有，先去主模板下面找，
	else
	{	//如果不存在就去模块目录下面找
		$tpl->template_dir=$config['webroot'].'/module/'.$_GET['m'].'/templates/';
	}
	$tpl->statu=$tpl->template_dir;
	if($no_return)
	{
		$tpl->display($file,$flag);die;
	}
	else
		return $tpl->fetch($file,$flag);
}

function get_mail_template($flag)
{
	global $db;
	$sql="select title,message from ".MAILMOD." where flag='$flag'";
	$db->query($sql);
	return $db->fetchRow();
}
function  replace_outside_link($str)
{
   $str=preg_replace("/<a.*>|<\/a>/isU",'',$str);
   return $str;
}

function admin_msg($url,$str=NULL)
{
	msg('noright.php?str='.urlencode($str).'&url='.urlencode($url));
}

function get_url_content($url)
{
	if(function_exists('file_get_contents'))
	{
		$file_contents = file_get_contents($url);
	}
	else
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	}
	return $file_contents;
}

function hideStr($string, $bengin=0, $len = 4, $type = 0, $glue = "@") {
	if (empty($string))
		return false;
	$array = array();
	if ($type == 0 || $type == 1 || $type == 4) {
		$strlen = $length = mb_strlen($string);
		while ($strlen) {
			$array[] = mb_substr($string, 0, 1, "utf8");
			$string = mb_substr($string, 1, $strlen, "utf8");
			$strlen = mb_strlen($string);
		}
	}
	if ($type == 0) {
		for ($i = $bengin; $i < ($bengin + $len); $i++) {
			if (isset($array[$i]))
				$array[$i] = "*";
		}
		$string = implode("", $array);
	}else if ($type == 1) {
		$array = array_reverse($array);
		for ($i = $bengin; $i < ($bengin + $len); $i++) {
			if (isset($array[$i]))
				$array[$i] = "*";
		}
		$string = implode("", array_reverse($array));
	}else if ($type == 2) {
		$array = explode($glue, $string);
		$array[0] = hideStr($array[0], $bengin, $len, 1);
		$string = implode($glue, $array);
	} else if ($type == 3) {
		$array = explode($glue, $string);
		$array[1] = hideStr($array[1], $bengin, $len, 0);
		$string = implode($glue, $array);
	} else if ($type == 4) {
		$left = $bengin;
		$right = $len;
		$tem = array();
		for ($i = 0; $i < ($length - $right); $i++) {
			if (isset($array[$i]))
				$tem[] = $i >= $left ? "*" : $array[$i];
		}
		$array = array_chunk(array_reverse($array), $right);
		$array = array_reverse($array[0]);
		for ($i = 0; $i < $right; $i++) {
			$tem[] = $array[$i];
		}
		$string = implode("", $tem);
	}
	return $string;
}


/**
 * 转义字符函数
 *
 * @param mixed $content contents should be addslashes
 *
 * @return mixed  $content
 *
 */
function quotes(&$content)
{
	if (is_array($content))
	{
		foreach ($content as $key => $value)
		{
			$content[$key] = quotes($value);
		}
	}
	else
	{
		$content = addslashes($content);
	}

	return $content;
}


//如果使用zend, 则关掉此处
function __autoload($class_name)
{
	if (!class_exists($class_name, false) && !interface_exists($class_name, false))
	{
		if (false !== strpos($class_name, '_'))
		{
			$class_file_suffix = '/' . str_replace('_', '/', $class_name) . '.php';
		}
		else if (false !== strpos($class_name, '\\'))
		{
			$class_file_suffix = '/' . str_replace('\\', '/', $class_name) . '.php';
		}
		else
		{
			$class_file_suffix = '/' . $class_name . '.php';
		}

		global $config;
		$class_file_path = $config['webroot'].'/../lib/' . $class_file_suffix;

		if (is_file($class_file_path))
		{
			include_once $class_file_path;
		}

		if (!class_exists($class_name, false) && !interface_exists($class_name, false))
		{
			throw new Exception('Class ' . $class_name . ' does not exists : ' . $class_file_suffix);
		}
	}
}
?>