<?php
include_once("../includes/global.php");
@include_once("../config/wechat_config.php");
include_once("../config/reg_config.php");

if($reg_config)
{
	$config = array_merge($config,$reg_config);
}

$wechat = $wechat_config['wechat']?$wechat_config['wechat']:"";
$wechat = $_GET['uid'] ? "WeiXin" : $wechat ;  
define("TOKEN", $wechat);

Yf_Log::log('Request : ' . json_encode($_REQUEST), Yf_Log::INFO, 'wechat');

$wechatObj = new wechatCallbackapiTest();
if($_GET["echostr"]&&$_GET["signature"]&&$_GET["timestamp"]&&$_GET["nonce"])
{
	$wechatObj->valid();	
}
else
{
	
	$wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
			Yf_Log::log('checkSignature : ' . $echoStr, Yf_Log::INFO, 'wechat');
			echo $echoStr;
        	exit;
        }
    }
    public function responseMsg()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		Yf_Log::log('$postStr : ' . $postStr, Yf_Log::INFO, 'wechat');
		global $db,$config;
		if (!empty($postStr)){
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
				$RX_TYPE = trim($postObj->MsgType);
				$num=0;
				$str="";

				Yf_Log::log('$postObj : ' . json_encode($postObj), Yf_Log::INFO, 'wechat');
				if($RX_TYPE=='text')
				{
					if(!empty($keyword))
					{
						switch($keyword){
							case "注册":
								$content = "欢迎您注册:"."\n"."<a href='".$config['weburl']."/register.php'>普通会员注册</a>"."\n";
								$resultStr =  $this->response_text($postObj,$content);
								echo $resultStr;
								break;
							default:
								$uid = $_GET['uid'];
								if($uid)
								{
									$str = " and member_id ='$uid' ";	
								}								
								$sql="select name as pname,id,pic from ".PRODUCT." where name like '%$keyword%' $str order by id desc limit 0,4";
								$db->query($sql);
								$re=$db->getRows();
								foreach($re as $val)
								{
									$str.="<item>
										 <Title><![CDATA[".$val['pname']."]]></Title> 
										 <Description><![CDATA[]]></Description>
										 <PicUrl><![CDATA[".$val['pic']."]]></PicUrl>
										 <Url><![CDATA[".$config['weburl']."?m=product&s=detail&id=".$val['id']."]]></Url>
									</item>";
									$num++;
								}
								if($num>0)
								{
									$textTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>".$num."</ArticleCount>
													<Articles>".$str."</Articles>
													<FuncFlag>1</FuncFlag>
												</xml>"; 
									$msgType = "text";
									$contentStr = "";
									$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
									echo $resultStr;
								}								
						}	
					}
					else
						echo "Input something...";
				}
				elseif ($RX_TYPE == 'event')
				{
										
					$bStr = $postObj->EventKey;
					$buid = str_replace("qrscene_","",$bStr);
					$wxclose = $config['wxclose'];
					if($wxclose == '2'){
						$sql="select * from ".USERCOON." where type=3 and openid='$fromUsername'";
						$db->query($sql);
						$cre=$db->fetchRow();
						
						if(empty($cre['id'])){
							$sql="insert into ".USERCOON."(type,openid) values('3','$fromUsername')";
							$db->query($sql);
						}
						
						if(empty($cre['userid'])){
							if($buid*1 > 0){
								$sql="select id from ".USERCOON." where type=3 and userid='$buid'";
								$db->query($sql);
								$id=$db->fetchField("id");
								
								if(empty($id)){
									$sql="update ".USERCOON." set userid='".$buid."' where openid='$fromUsername'";
									$db->query($sql);
								}else{
									rega($fromUsername,1);
								}																
							}else{
								rega($fromUsername,1);
							}
						}
						
					}else{
						if($buid*1 > 0){
							
							$sql="select id from ".USERCOON." where type=3 and userid='$buid'";
							$db->query($sql);
							$id=$db->fetchField("id");
							
							if(empty($id)){
								
								$sql="select * from ".USERCOON." where type=3 and openid='$fromUsername'";
								$db->query($sql);
								$cuser=$db->fetchRow();
								
								if(empty($cuser['id'])){
									$sql="insert into ".USERCOON."(type,openid) values('3','$fromUsername')";
									$db->query($sql);
								}
								
								if(empty($cuser['userid'])){
									$sql="update ".USERCOON." set userid='".$buid."' where openid='$fromUsername'";
									$db->query($sql);
								}								
							}						
						}
					}
					
					$content="感谢您关注<a href='".$config['weburl']."'>Mallbuilder商城</a>";
					$resultStr =  $this->response_text($postObj,$content);
					echo $resultStr;
				}
				else
				{
                	echo "Input something...";
                }
        }else{
        	echo "";
        	exit;
        }
    }
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	//文本回复
	private function response_text($object,$content){
	    $textTpl = "<xml>
		                <ToUserName><![CDATA[%s]]></ToUserName>
		                <FromUserName><![CDATA[%s]]></FromUserName>
		                <CreateTime>%s</CreateTime>
		                <MsgType><![CDATA[text]]></MsgType>
		                <Content><![CDATA[%s]]></Content>
		                <FuncFlag>%d</FuncFlag>
	                </xml>";
	    $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content,0);
	    return $resultStr;
	}
}
?>