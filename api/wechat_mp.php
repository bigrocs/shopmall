<?php
class WechatOrderPush
{
	protected $appid;
	protected $secrect;
	protected $accessToken;

	function  __construct($appid, $secrect)
	{
		$this->appid       = $appid;
		$this->secrect     = $secrect;
		$this->accessToken = $this->getToken($appid, $secrect);
	}

	/**
	 * 发送post请求
	 * @param string $url
	 * @param string $param
	 * @return bool|mixed
	 */
	function requestPost($url = '', $param = '')
	{
		if (empty($url) || empty($param))
		{
			return false;
		}
		$postUrl  = $url;
		$curlPost = $param;
		$ch       = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch); //运行curl
		curl_close($ch);
		return $data;
	}


	/**
	 * 发送get请求
	 * @param string $url
	 * @return bool|mixed
	 */
	function requestGet($url = '')
	{
		if (empty($url))
		{
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);

		if (curl_errno($ch))
		{
			echo "Error Occured in Curl\n";
			echo "Error number: " . curl_errno($ch) . "\n";
			echo "Error message: " . curl_error($ch) . "\n";
		}

		curl_close($ch);
		return $data;
	}

	/**
	 * @param $appid
	 * @param $appsecret
	 * @return mixed
	 * 获取token
	 */
	protected function getToken($appid, $appsecret)
	{
		global $config;

		$cache_dir = $config['webroot'] . '/cache/wechat_api_data/';
		make_dir_path($cache_dir);

		//设置cache 参数
		$config_cache['wechat_api_data'] = array(
			'cacheType' => 1,
			'cacheDir' => $cache_dir,
			'memoryCaching' => false,
			'automaticSerialization' => true,
			'hashedDirectoryLevel' => 1,
			'hashedDirectoryUmask' => 0777,
			'cacheFileMode' => 0777,
			'lifeTime' => 720
		);

		$analyse_cache = new Cache_Lite_Output($config_cache['wechat_api_data']);

		$access_token = $analyse_cache->get($appid);

		if ($access_token)
		{
			fb('$access_token :');
			fb($access_token);
		}
		else
		{
			$url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
			$token        = $this->requestGet($url);

			fb('$token str:');
			fb($token);

			$token        = json_decode(stripslashes($token));
			$arr          = json_decode(json_encode($token), true);
			$access_token = $arr['access_token'];

			if ($access_token)
			{
				$rs = $analyse_cache->save($access_token);
			}
		}

		return $access_token;
	}


	/**
	 * 发送自定义的模板消息
	 * @param $touser
	 * @param $template_id
	 * @param $url
	 * @param $data
	 * @param string $topcolor
	 * @return bool
	 */
	public function doSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE')
	{
		/*
		 * data=>array(
				'first'=>array('value'=>urlencode("您好,您已购买成功"),'color'=>"#743A3A"),
				'name'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
				'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#FFFFFF'),
			)
		 */
		$template = array('touser' => $touser, 'template_id' => $template_id, 'url' => $url, 'topcolor' => $topcolor, 'data' => $data);

		$json_template = json_encode($template);
		$url           = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->accessToken;
		$dataRes       = $this->requestPost($url, urldecode($json_template));

		fb('$dataRes:');
		fb($dataRes);
		if ($dataRes['errcode'] == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

include_once("../includes/global.php");
@include_once("../config/wechat_config.php");
@include_once("../config/connect_config.php");
include_once("../pay/module/payment/lib/WxPayPubHelper/WxPay.pub.config.php");

$appid = WxPayConf_pub::APPID;
$appsecret = WxPayConf_pub::APPSECRET;


//wxa1757d6d855d9cb9
//4aa163241960e33583e66e23100299e6

//$appid = 'wx5ae625473eb5ac13';
//$appsecret = 'dbc36683859d2394ffea26cdd1c67c4f';
$wechatPush = new WechatOrderPush($appid, $appsecret);

fb($wechatPush);
/*
$touser = 'oQXqYs69qIVfEG-bezX_-Sd8PM4U';
$template_id = 'OPENTM200444326';

$data = array(
		'first'=>array('value'=>urlencode("您好,买家已付款"),'color'=>"#743A3A")
);

$wechatPush->doSend($touser, $template_id, $url, $data);
*/

/*
$order_id = '201506190915134';

//启用微信通知
if (true)
{
	//
	// 如果是合并支付
	if(substr($order_id, 0,1) == "U")
	{
		$sql = "select `inorder` from " . UORDER . " where `order_id` = '$order_id'  ";
		$db->query($sql);

		$re = $db->fetchField("inorder");
		$de = explode(",", $re);

		$flag = false; // 是否为虚拟订单

		foreach ($de as $key => $val)
		{
			$sql = "select `is_virtual`,`consignee_mobile` from " . ORDER . " where  order_id='$val'";
			$db->query($sql);
			$t_res      = $db->fetchRow();
			$is_virtual = $t_res['is_virtual'];

			if ($is_virtual == 1)
			{
				$flag = true;
			}

			//如下else代码
		}
	}
	else
	{
		$sql = "select `status`,`pid`, `name`, `price`, `num` from ".ORPRO." where order_id='$order_id'";
		$db -> query($sql);
		$t_re = $db -> fetchRow();
		$status = $t_re['status'];
		$pid = $t_re['pid'];
		$product_name_desc = $t_re['name'] . ' x ' . $t_re['num'];
		$product_num = $t_re['num'];

		if($status < 2) //付款成功
		{
			//获取卖家信息
			$sql = "select `seller_id`, `product_price`  from ".ORDER." where order_id='$order_id' AND seller_id!=0";
			$db -> query($sql);
			$t_re = $db -> fetchRow();
			$seller_id = $t_re['seller_id'];
			$product_price = $t_re['product_price'];


			$sql = "select `userid`, open_id, `user`  from ".MEMBER." where userid='$seller_id'";
			$db -> query($sql);
			$t_re = $db -> fetchRow();
			$openid = $t_re['open_id'];

			if ($openid)
			{
				$touser = $openid;
				$template_id = 'HPy33qqx1ucYhx28QUm3fUPYNJjm48dO6HLBgih18r0';

				$data = array(
					'first'=>array('value'=>urlencode("您好,买家已付款"),'color'=>"#743A3A"),
					'keyword1'=>array('value'=>urlencode($order_id),'color'=>'#EEEEEE'),
					'keyword2'=>array('value'=>urlencode($product_name_desc),'color'=>'#EEEEEE'),
					'keyword3'=>array('value'=>urlencode($product_price),'color'=>'#EEEEEE'),
				    'remark'=>array('value'=>urlencode('请确认发货！'),'color'=>'#FFFFFF')
				);

				$url = $config['weburl']."/main.php?cg_u_type=1&m=product&s=admin_buyorder&zt=3";
				$wechatPush->doSend($touser, $template_id, $url, $data);
			}
		}
	}
}
*/
?>