<?php

	include_once("config.php");

	$name="wechat_config";

	include '../config/'.$name.'.php';


	function https_request($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;

	}

if($_POST["act"]=='save' || $_POST["act"]=='item')
	{
		$wmark_type='0';
		foreach($_POST as $pname=>$pvalue)
		{
			if ($pname!="act")
			{
				
				$sql="select * from ".CONFIG." where `index`='$pname'";
				$db->query($sql);
				if($db->num_rows())
				{
				   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname'";
				}
				else
				{
				   $sql1="insert into ".CONFIG." (`index`,value) values ('$pname','$pvalue')";
				}
				$db->query($sql1);
				$wechat_config[$pname]=$pvalue;
			}
		}



		/****更新缓存文件*********/
		$write_config_con_str=serialize($wechat_config);//将数组序列化后生成字符串
		$write_config_con_str='<?php $'.$name.' = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
		$fp=fopen('../config/'.$name.'.php','w');
		fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
		fclose($fp);
		/*********************/
		if ($_POST["act"]=='item')
		{
			//更改菜单操作...
			$appid     = $_POST["wechat_app_id"];
			$appsecret = $_POST["wechat_app_secret"];

			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);

			//var_dump($output);

			$jsoninfo = json_decode($output, true);
			$access_token = $jsoninfo["access_token"];

			$jsonmenu = $_POST["wechat_app_item"];

			$data = stripslashes($jsonmenu);
			$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
			$result = https_request($url, $data);

			$rs = json_decode($result, true);

			if (0 == $rs['errcode'])
			{
				admin_msg("wechat_config.php?operation=item",'更新成功');
			}
			else
			{
				admin_msg("wechat_config.php?operation=item",'更新失败');
			}
		}
		else
		{
			admin_msg("wechat_config.php",'更新成功');
		}
	}
	@include_once("../config/wechat_config.php");
	$tpl->assign("wechat_config",$wechat_config);
	$tpl->assign("config",$config);
	$tpl->display("wechat_config.htm");
?>