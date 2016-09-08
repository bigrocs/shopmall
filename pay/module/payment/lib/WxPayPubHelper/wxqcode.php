<?php
/**
 * 微信支付帮助库
 * ====================================================
 * 接口分三种类型：
 * 【请求型接口】--Wxpay_client_
 * 		统一支付接口类--UnifiedOrder
 * 		订单查询接口--OrderQuery
 * 		退款申请接口--Refund
 * 		退款查询接口--RefundQuery
 * 		对账单接口--DownloadBill
 * 		短链接转换接口--ShortUrl
 * 【响应型接口】--Wxpay_server_
 * 		通用通知接口--Notify
 * 		Native支付——请求商家获取商品信息接口--NativeCall
 * 【其他】
 * 		静态链接二维码--NativeLink
 * 		JSAPI支付--JsApi
 * =====================================================
 * 【CommonUtil】常用工具：
 * 		trimString()，设置参数时需要用到的字符处理函数
 * 		createNoncestr()，产生随机字符串，不长于32位
 * 		formatBizQueryParaMap(),格式化参数，签名过程需要用到
 * 		getSign(),生成签名
 * 		arrayToXml(),array转xml
 * 		xmlToArray(),xml转 array
 * 		postXmlCurl(),以post方式提交xml到对应的接口url
 * 		postXmlSSLCurl(),使用证书，以post方式提交xml到对应的接口url
*/
@include_once("WxPay.pub.config.php");
/**
 * 统一支付接口类
 */
class   wxqcode 
{	
   
    var $appid ;  //应用ID
	var $secret ; //应用密钥 
	var $curl_timeout = 30 ;//curl超时时间




	function wxqcode() 
	{
       //获取配置参数
		$this->appid = WxPayConf_pub::APPID;
		$this->secret = WxPayConf_pub::APPSECRET;
		$this->curl_timeout = 30 ;
	}
	
    //获取accsee_token

	function  get_access_token(){
         
		 $appid = $this->appid ;
		 $secret = $this->secret;
	     $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
         $data = $this -> getcur($url);
		 $access_token =  $data["access_token"];
		 return  $access_token;
	}
    //获取 临时 ticket
	function  get_lishi_ticket($access_token,$parama){
	 
	  $url ="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
	  
	  //post joson数据
      //填写传递的数据
	  $dataarray=array("expire_seconds"=>604800,"action_name"=>"QR_SCENE","action_info"=>array("scene"=>array("scene_id"=>$parama)));
	  $data=json_encode($dataarray);
	  //$data='{"expire_seconds":604800,"action_name":"QR_SCENE","action_info":{"scene":{"scene_id":'.$parama.'}}}';
      $data=$this -> postcurl($data,$url);
	  //  expire_seconds   url
      $ticket=$data["ticket"];
	  return   $ticket;

	}
   //永久二维码
   function  get_yongjiu_ticket($access_token,$parama){
	  $url ="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
	  //post joson数据
      //填写传递的数据
      
      $dataarray=array("action_name"=>"QR_LIMIT_SCENE","action_info"=>array("scene"=>array("scene_id"=>$parama)));
	  $data=json_encode($dataarray);
	 // $data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$parama.'}}}';
      $data=$this -> postcurl($data,$url);
	  //  expire_seconds   url
      $ticket=$data["ticket"];
	  return   $ticket;
	}

	//换取二维码

	function get_qrcode_url($ticket){
	$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
	return  $url;
	}
   
	
	//创建二维码函数，传递参数；例如传递的是 buid ，和保存图片的路径地址 返回的是二维码的地址链接

	function  createqrd($userid,$filepath=NULL){

		$file_die = dirname($filepath);

		if (!file_exists($file_die))
		{
			mkdir($file_die, 0777, true);
		}

		$access_token=$this->get_access_token();
        
		$ticket=$this->get_yongjiu_ticket($access_token,$userid);
		$picurl=$this->get_qrcode_url($ticket);
	    //保存图片到指定的路径下面
		$file =  @file_get_contents($picurl);
        $newFile = fopen($filepath,"w"); //打开文件准备写入
        fwrite($newFile,$file); //写入二进制流到文件
        fclose($newFile); //关闭文件
        
	}

    // post发送请求
	function  postcurl($postdata,$url=null,$second=30){
	
             //初始化curl        
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		//运行curl
        $res = curl_exec($ch);
        $data=json_decode($res,true);
		curl_close($ch);
		//返回结果
		if($data)
		{
			return $data;
		}
		else 
		{ 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	
	}

	//get的方式发送求情

	function getcur($url){

        //初始化curl
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
        $res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		return $data;

	
	}





}
?>
