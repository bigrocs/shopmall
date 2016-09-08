<?php
class pay
{
	var $db;
	var $tpl;
	var $page;
	
	function pay()
	{
		global $db;
		global $config;
		global $tpl;	
		global $buid;
		$this -> db      = & $db;
		$this -> tpl     = & $tpl;
		$this -> pay_uid = & $buid;
		$this->check();
	}
	
	function check()
	{
		global $UID,$config;
		if($_GET['s']!="accounts")
		{
			if($UID)
			{
				$sql = "select pay_id from ".MEMBER." where userid='$UID'";
				$this->db->query($sql);
				$this -> pay_uid = $this->db->fetchField('pay_id');

				if(!$this -> pay_uid)
				{	
					msg($config["web_url"]."/login.php");
				}
				else
				{
                    if($_GET['act']!='logout')
                    {
                        $sql = "select pay_mobile,pay_pass,login_pass from ".MEMBER." where pay_id='".$this -> pay_uid."'";
                        $this->db->query($sql);
                        $de=$this->db->fetchRow();
                        if(($_GET['s']!='settings')&&empty($de['pay_mobile']))
                        {
                            $url = urlencode($config['weburl']."?".http_build_query($_GET));
                            msg("$config[weburl]/?m=payment&s=settings&forword=".$url);
                        }
                        if(($_GET['s']!='settings')&&($_GET['s']!='setpass')&&empty($de['pay_pass']))
                        {
                            $url = urlencode($config['weburl']."?".http_build_query($_GET));
                            msg("$config[weburl]/?m=payment&s=setpass&forword=".$url);
                        }
                    }
				}
			}
			else
			{
				msg($config["web_url"]."/login.php");
			}
		}
	}
	
	function get_member_info($id)
	{
		$sql="select * from ".MEMBER." where pay_id='$id'";
		$this->db->query($sql);
		$de=$this->db->fetchRow();
		$de['email'] = $de['pay_email'];	
		$de['pay_email'] = hideStr($de['pay_email'], 1, 4);
		return $de;
	}
	
	function get_trade_record($id,$limit=5)
	{
		$sql="select * from ".RECORD." where pay_uid='$id'  and LEFT(`order_id`,1) != 'U' order by id desc limit 0, $limit";
		$this->db->query($sql);
		$de=$this->db->getRows();
		foreach($de as $key=>$val)
		{
			$de[$key]['minus']=($val['price']<0)?"T":"F";	
		}
		return $de;
	}

    //-------------------------20150529 Lemons 修改账户信息
	function edit_info($id)
	{
        $pay_mobile=$_POST['pay_mobile']?$_POST['pay_mobile']:"";
        $logo=$_POST['logo']?$_POST['logo']:"";
		if($pay_mobile)
		{
			$str=" ,pay_mobile='$pay_mobile' ";
		}
		if($logo)
		{
			$str.=" ,logo='$logo' ";
		}
		if($str)
		{
			$sql="update ".MEMBER." set pay_id='$id' $str where pay_id='$id'";
			return $this->db->query($sql);
		}
	}
	function edit($id)
	{
		$pass=$_POST['pass']?md5(trim($_POST['pass'])):"";
		$pass1=$_POST['pass1']?md5(trim($_POST['pass1'])):"";
		if($pass)
		{
			$str=" ,pay_pass='$pass' ";
		}
		if($pass1)
		{
			$str.=" ,login_pass='$pass1' ";
		}
		if($str)
		{
			$sql="update ".MEMBER." set email_verify = 'true' $str where pay_id='$id'";
			return $this->db->query($sql);
		}
	}
		
	function edit_name($id)
	{
		$real_name=trim($_POST['real_name']);
		$identity_card=trim($_POST['identity_card']);
		$pic=trim($_POST['pic']);
		$sql="update ".MEMBER." set real_name='$real_name',identity_pic='$pic' ,identity_card='$identity_card' where pay_id='$id'";
		$re=$this->db->query($sql);
	}
	
	function get_payment_type()
	{
		global $config;
		
		if($config['temp'] == 'wap')
		{
			

			if($config['bw'] == 'weixin')
			{
				$str = " and payment_type = 'wap_alipay' or payment_type = 'wx_pay'";
			}
			else
			{
				$str = " and payment_type = 'wap_alipay'";
			}

		}
		else
		{
			$str = " and payment_type != 'wap_alipay' and payment_type != 'wx_pay'";
		}

		

		$sql = "select * from ".PAYMENT." where active=1 $str order by nums , payment_id ";
		$this->db->query($sql);
		return $this->db->getRows();
	}
	
	function get_service_fee()
	{
		$sql = "select * from ".FEE;
		$this->db->query($sql);
		return $this->db->getRows();
	}
	
	
	function online_pay()
	{
		//第一步写入流水记录，状态为处理中。
		global $config;
		$amount=trim($_POST['amount'])*1; 
		
		$time=time();
		$flow_id=date("Ymdhis").rand(0,9);
			
		include("$config[webroot]/module/payment/lang/cn.php");
		include("$config[webroot]/module/payment/lang/payment_cn.php");
		
		if($_POST['payment_type']=='cards')
		{
			if($_POST['card_num'] and $_POST['password'])
			{
				$sql="select id,statu,stime,etime,total_price from ".PAYCARD." where card_num='$_POST[card_num]' and password='$_POST[password]'";
				$this->db->query($sql);
				$re=$this->db->fetchRow();
				if($re)
				{
					if($re['statu']==1)
					{
						msg("$config[weburl]/?m=payment&s=recharge","充值卡已被使用过");
						exit;
					}
					else
					{
						if($re['stime']<=$time&&$re['etime']>=$time)
						{
							$amount=trim($re['total_price'])*1;
						}
						else
						{
							msg("$config[weburl]/?m=payment&s=recharge","充值卡已过期");
							exit;
						}
					}
				}
				else
				{
					msg("$config[weburl]/?m=payment&s=recharge","充值卡不存在");
					exit;	
				}
			}
			else
			{
				msg("$config[weburl]/?m=payment&s=recharge","充值卡不存在");
				exit;	
			}
		}
		
		if($amount)
		{
			$payment_name=$lang[$_POST['payment_type']].($_POST['card_num']?$_POST['card_num']:"");
			$sql="insert into ".CASHFLOW." (`pay_uid`,flow_id,`price`,`time`,`note`,`type`,`mold`,`statu`) values ('".$this->pay_uid."','$flow_id','$amount','$time','$payment_name $note[1]','1','2','1')";
			$this->db->query($sql);
			$id=$this->db->lastid();
		}
		$extra_common_param=$_POST['id'];
		
		if($_POST['payment_type']=='cards'&&$id)
		{
			
			$sql="update ".PAYCARD." set statu=1,use_name='$_COOKIE[USER]' where id='$re[id]' and card_num='$_POST[card_num]'";
			$this->db->query($sql);
			
			$sql="update ".CASHFLOW." set price='$amount',statu='4' where id='$id'";
			$this->db->query($sql);
			
			$sql="update ".MEMBER." set cash=cash+$amount where pay_id='".$this->pay_uid."'";			$this->db->query($sql);
			msg("$config[weburl]/?m=payment&s=record&mold=1","充值成功");	
		}
		if($_POST['payment_type']=='tenpay'&&$id)
		{
			$url=$config['weburl']."/?m=payment&s=accounts&onlinepaytype=tenpay";
			
			require_once($config['webroot']."/module/payment/lib/tenpay/classes/PayRequestHandler.class.php");
			$configs=$this->get_pay_config('tenpay');
			
			$strDate = date("Ymd");
			$strTime = date("His");
			$randNum = rand(1000, 9999);//4位随机数。
			
			$transaction_id = $configs['tenpay_account'] . $strDate . $strTime.$randNum;/* 财付通交易单号*/
			$reqHandler = new PayRequestHandler();
			$reqHandler->init();
			$reqHandler->setKey($configs['tenpay_key']);
			$reqHandler->setParameter("bargainor_id", $configs['tenpay_account']);	//商户号
			$reqHandler->setParameter("sp_billno", $id);						//商户订单号
			$reqHandler->setParameter("transaction_id", $transaction_id);		//财付通交易单号
			$reqHandler->setParameter("total_fee", $amount*100);				//商品总金额,以分为单位
			$reqHandler->setParameter("return_url", $url);						//返回处理地址
			$reqHandler->setParameter("attach",  $extra_common_param); //自定义参数
			$reqHandler->setParameter("desc", iconv('utf-8','gb2312',$config['company']));				//商品名称
			//$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//用户ip
			$link = $reqHandler->getRequestURL();
			msg($link);
		}
		if($_POST['payment_type']=='bc'&&$id)
		{
			$configs=$this->get_pay_config('bc');
			
			include_once($config['webroot']."/module/payment/lib/bc/pkcs7/boc.class.php");
			$pays = new boc($configs["bc_key"]);
			$pays -> cert = $config['webroot']."/module/payment/lib/bc/pkcs7/cert/cert1.pem";
			$pays -> privateKey = $config['webroot']."/module/payment/lib/bc/pkcs7/cert/key1.pem";

			$time = date("Ymdhis");
			$merchantNo = $configs["bc_account"];
			$unsignData = $id."|".$time."|001|".$amount."|".$merchantNo;
			$signData = $pays->signFromStr($unsignData);
			
			$url=$config['weburl']."/?m=payment&s=accounts&onlinepaytype=bc";
			if($extra_common_param) $url .= "&attach=".$extra_common_param;
			
			$parameter = array(
				"merchantNo"		=>	$merchantNo,	//商户号
				"payType"			=>	"1",			//支付类型
				"orderNo"			=>	$id,			//商户订单号
				"curCode"			=>	"001",			//订单币种
				"orderAmount"		=>	$amount,		//订单金额
				"orderTime"			=>	$time,			//订单时间
				"orderNote"			=>	$note[1],		//订单说明
				"orderUrl"			=>	$url,			//商户接收通知URL
				"signData"			=>	$signData,		//商户签名数据
			);
			$gateway = "http://180.168.146.75:81/PGWPortal/RecvOrder.do";
			echo $this->buildForm($parameter,$gateway,"post");
		}
		
		/******************
		*****新增开始******
		*******************/
		if($_POST['payment_type']=='anion_pay'&&$id)
		{
			$configs=$this->get_pay_config('anion_pay');
			$amount = $amount*100;
			$time = date("Ymdhis");
			$merchantNo1 = $configs["key1"];
			$merchantId = $configs["merchantId"];
			$packupurl=$config['weburl']."/api/anion_pickup.php";
			$receiveurl=$config['weburl']."/api/anion_pay_return.php";
			//if($extra_common_param) $url .= "&attach=".$extra_common_param;
			$signMsg='inputCharset='.'1'.'&'.'pickupUrl='.$packupurl.'&'.'receiveUrl='.$receiveurl.'&'.'version='.'v1.0'.'&'.'language='.'1'.'&'.'signType='.'1'.'&'.'merchantId='.$merchantId.'&'.'orderNo='.$id.'&'.'orderAmount='.$amount.'&'.'orderCurrency='.'0'.'&'.'orderDatetime='.$time.'&'.'ext1='.$extra_common_param.'&'.'payType='.'0'.'&'.'key='.$merchantNo1;
			$signMsg=strtoupper(md5($signMsg));
			$parameter = array(
				"inputCharset"		=>	"1",			//字符集
				"pickupUrl"			=>	$packupurl,			//付款客户的取货url地址
				"receiveUrl"		=>	$receiveurl,			//服务器接受支付结果的后台地址
				"version"			=>	"v1.0",			//网关接收支付请求接口版本
				"language"			=>	"1",			//网关页面显示语言种类
				"signType"			=>	"1",			//签名类型
				"merchantId"		=>	$merchantId,	//商户号
				"orderNo"			=>	$id,			//商户订单号
				"orderAmount"		=>	$amount,		//商户订单金额
				"orderCurrency"		=>	"0",			//订单金额币种类型
				"orderDatetime"		=>	$time,			//商户订单提交时间
				"ext1"              => 	$extra_common_param,
				"payType"			=>	"0",			//支付方式
				"signMsg"			=>	$signMsg,		//签名字符串
			);

			/*
				说明：现在配置的是测试环境的通联支付，如要配置生产环境，首先将plugin_pay_class.php中的
				$gateway = "https://service.allinpay.com/gateway/index.do";打开，
				将$gateway = "http://ceshi.allinpay.com/gateway/index.do";注释起来。其次将生产环境demo文件夹中的publickey.txt文件替换demo文件夹中的publickey.txt文件即可。
			 */
			$gateway = "http://ceshi.allinpay.com/gateway/index.do";//测试环境
			$gateway = "https://service.allinpay.com/gateway/index.do";//生产环境
			echo $this->buildForm($parameter,$gateway,"post");
		}
		
		/******************
		*****新增结束******
		*******************/
		
		if($_POST['payment_type']=='cbp'&&$id)
		{
			$sum=$amount*1;
			if($extra_common_param) $u = "&attach=".$extra_common_param;
			msg($config['weburl']."/module/payment/lib/cbp/Send.php?id=$id&sum=$sum".$u);
		}
		if($_POST['payment_type']=='alipay'&&$id)
		{
			require_once($config['webroot']."/module/payment/lib/alipay/lib/alipay_service.class.php");
			$configs=$this->get_pay_config('alipay');
			$parameter = array(
				"service"         => "create_direct_pay_by_user",   //交易类型
				"payment_type"    => "1",               			//默认为1,不需要修改
				"partner"         => $configs['partner'], 			//合作商户号
				"_input_charset"  => 'UTF-8',   					//字符集，默认为GBK
				"seller_email"    => $configs['seller_email'],    	//卖家邮箱，必填
				"return_url"      => $configs['return_url'],       	//同步返回
				"notify_url"      => $config['weburl']."/module/payment/lib/alipay/notify_url.php",       	//异步返回
				"out_trade_no"    => $id,     						//商品外部交易号，必填（保证唯一性）
				"subject"         => $config['company'],  			//商品名称，必填
				"body"            => $config['company'],     		//商品描述，必填
				"total_fee"       => $amount,       				//商品单价，必填（价格不能为0）
				"show_url"        => $config['weburl'], 			//商品相关网站
				"paymethod"			=> $paymethod,//默认支付方式，取值见"即时到帐接口"技术文档中的请求参数列表
				"defaultbank"		=> $defaultbank,//默认网银代号，代号列表见"即时到帐接口"技术文档"附录"→"银行列表"
				"anti_phishing_key"	=> $anti_phishing_key,//防钓鱼时间戳
				"exter_invoke_ip"	=> $exter_invoke_ip,//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
				"extra_common_param"=> $extra_common_param,//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
				"royalty_type"		=> $royalty_type,//提成类型，该值为固定值：10，不需要修改
				"royalty_parameters"=> $royalty_parameters//提成类型，该值为固定值：10，不需要修改
			);
			$alipayService = new AlipayService($configs);
			echo $alipayService->create_direct_pay_by_user($parameter);
		}
		if($_POST['payment_type']=='wap_alipay'&&$id)
		{
			require_once($config['webroot']."/module/payment/lib/wap_alipay/lib/alipay_submit.class.php");
			$configs=$this->get_pay_config('wap_alipay');
			
			$format = "xml";//必填，不需要修改//返回格式
			$v = "2.0";//返回格式//必填，不需要修改
			$req_id = $id;////请求号必填，须保证每次请求都是唯一
			
			//**req_data详细信息**
			$notify_url = $configs['notify_url'];//服务器异步通知页面路径//需http://格式的完整路径，不允许加?id=123这类自定义参数
			$call_back_url = $configs['return_url'];//页面跳转同步通知页面路径//需http://格式的完整路径，不允许加?id=123这类自定义参数
			$merchant_url = $config['web_url']."/main.php";//操作中断返回地址//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
			
			$seller_email = $configs['wap_alipay_seller_email'];//卖家支付宝帐户//必填
			
			////商户订单号商户网站订单系统中唯一订单号，必填
			if($extra_common_param)
				$out_trade_no = $id.'-'.$extra_common_param;
			else
				$out_trade_no = $id;
			$subject = $config['company'];//订单名称//必填
			$total_fee = $amount;//付款金额//必填
			
			//请求业务参数详细
			$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
			//必填
			/************************************************************/
			//构造要请求的参数数组，无需改动
			$para_token = array(
					"service" => "alipay.wap.trade.create.direct",
					"partner" => trim($configs['wap_alipay_partner']),
					"sec_id" => trim($configs['sign_type']),
					"format"	=> $format,
					"v"	=> $v,
					"req_id"	=> $req_id,
					"req_data"	=> $req_data,
					"_input_charset"	=> trim(strtolower($configs['input_charset']))
			);
			//建立请求
			$alipaySubmit = new AlipaySubmit($configs);
			$html_text = $alipaySubmit->buildRequestHttp($para_token);
			//URLDECODE返回的信息
			$html_text = urldecode($html_text);
			//解析远程模拟提交后返回的信息
			$para_html_text = $alipaySubmit->parseResponse($html_text);
			//获取request_token
			$request_token = $para_html_text['request_token'];
			/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
			
			//业务详细
			$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
			//必填
			
			//构造要请求的参数数组，无需改动
			$parameter = array(
					"service" => "alipay.wap.auth.authAndExecute",
					"partner" => trim($configs['wap_alipay_partner']),
					"sec_id" => trim($configs['sign_type']),
					"format" => $format,
					"v"	=> $v,
					"req_id" => $req_id,
					"req_data" => $req_data,
					"_input_charset" => trim(strtolower($configs['input_charset']))
			);
			
			//建立请求
			$alipaySubmit = new AlipaySubmit($configs);
			$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
			echo $html_text;
		}
		if($_POST['payment_type']=='icbc'&&$id)
		{
			$configs = $this -> get_pay_config('icbc');
			$icbc_id 		= 	$configs['icbc_id'];
			$icbc_account	= 	$configs['icbc_account'];
			$url			=	$config['weburl'];//."/?m=payment&s=accounts&onlinepaytype=icbc";
			$icbc			=	"$config[webroot]/module/payment/lib/icbc/ebb2cpublic.crt";
			$cert			= 	"$config[webroot]/module/payment/lib/icbc/test20140729dt.crt";
			$key			= 	"$config[webroot]/module/payment/lib/icbc/test20140729dt.key";
			$keyPass		=	"12345678";  //私钥保护密码


			//-------------------------------------------------
			//-- 根据定单生成 交易数据
			//-------------------------------------------------	
			$interfaceName	 	= 		"ICBC_PERBANK_B2C";			//接口名称
			$interfaceVersion	=		"1.0.0.11";					//接口版本号
			$orderDate 			= 		"20140831";//date("YmdHis",time());		//交易日期时间
			$curType			=		"001";						//支付币种
			$merID				=		$icbc_id;					//商户代码
			$orderid			=		$id;						//订单号
			$amount				=		$amount;					//订单金额
			$installmentTimes	= 		"1";						//分期付款期数
			$merAcct			= 		$icbc_account;				//商户账号
			$goodsID			=       "1";						//商品编号
			$goodsName			=		$config['company'];//$config['company']; 		//商品名称
			$goodsNum			=       "1";						//商品数量
			$carriageAmt		=		"0";						//已含运费金额
			$verifyJoinFlag		=		"0";						//检验联名标志
			$Language			=		"ZH_CN";					//语言版本
			$creditType			=		"2";						//支持订单支付的银行卡种类
			$notifyType			=		"AG";						//通知类型
			$resultType			=		"1";						//结果发送类型
			$merReference		=		"localhost";				//商户reference
			$merCustomIp		=		"127.0.0.1";				//客户端IP
			$goodsType			=		"1";						//虚拟商品/实物商品标志位
			$merCustomID		=		"";							//买家用户号
			$merCustomPhone		=		"";							//买家联系电话
			$goodsAddress		=		"";							//收货地址
			$merOrderRemark		=		"";							//订单备注
			$merHint			=		"";							//商城提示
			$remark1			=		"";							//备注字段1
			$merURL				=		$url;						//返回商户URL
			$merVAR				=		"test";						//返回商户变量

			$TDT="<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"no\"?><B2CReq><interfaceName>ICBC_PERBANK_B2C</interfaceName><interfaceVersion>1.0.0.11</interfaceVersion><orderInfo><orderDate>20140831</orderDate><curType>001</curType><merID>".$merID."</merID><subOrderInfoList><subOrderInfo><orderid>201003081416290</orderid><amount>1</amount><installmentTimes>1</installmentTimes><merAcct>".$merAcct."</merAcct><goodsID>001</goodsID><goodsName>威尼熊</goodsName><goodsNum>2</goodsNum><carriageAmt>20</carriageAmt></subOrderInfo></subOrderInfoList></orderInfo><custom><verifyJoinFlag>0</verifyJoinFlag><Language>ZH_CN</Language></custom><message><creditType>2</creditType><notifyType>AG</notifyType><resultType>1</resultType><merReference>localhost</merReference><merCustomIp>127.0.0.1</merCustomIp><goodsType>1</goodsType><merCustomID>123456</merCustomID><merCustomPhone>13466780886</merCustomPhone><goodsAddress>三里屯</goodsAddress><merOrderRemark>防欺诈接口专用</merOrderRemark><merHint>请保留包装</merHint><remark1>1</remark1><remark2>1</remark2><merURL>http://localhost:80/EbizSimulate/emulator/Newb2c_Pay_Mer.jsp</merURL><merVAR>test</merVAR></message></B2CReq>";

			$TDT = "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"no\"?><B2CReq><interfaceName>".$interfaceName."</interfaceName><interfaceVersion>".$interfaceVersion."</interfaceVersion><orderInfo><orderDate>".$orderDate."</orderDate><curType>".$curType."</curType><merID>".$merID."</merID><subOrderInfoList><subOrderInfo><orderid>".$orderid."</orderid><amount>".$amount."</amount><installmentTimes>$installmentTimes</installmentTimes><merAcct>".$merAcct."</merAcct><goodsID>".$goodsID."</goodsID><goodsName>".$goodsName."</goodsName><goodsNum>".$goodsNum."</goodsNum><carriageAmt>".$carriageAmt."</carriageAmt></subOrderInfo></subOrderInfoList></orderInfo><custom><verifyJoinFlag>".$verifyJoinFlag."</verifyJoinFlag><Language>".$Language."</Language></custom><message><creditType>".$creditType."</creditType><notifyType>".$notifyType."</notifyType><resultType>".$resultType."</resultType><merReference>".$merReference."</merReference><merCustomIp>".$merCustomIp."</merCustomIp><goodsType>".$goodsType."</goodsType><merCustomID>".$merCustomID."</merCustomID><merCustomPhone>".$merCustomPhone."</merCustomPhone><goodsAddress>".$goodsAddress."</goodsAddress><merOrderRemark>".$merOrderRemark."</merOrderRemark><merHint>".$merHint."</merHint><remark1>".$remark1."</remark1><remark2></remark2><merURL>".$merURL."</merURL><merVAR>".$merVAR."</merVAR></message></B2CReq>";


			$tranData = base64_encode($TDT);
			
			//-------------------------------------------------
			//-- 初始化工行支付对象
			//-------------------------------------------------
			$TDT = @iconv( "utf-8", "gb2312//IGNORE",$TDT);
			if (strtoupper(substr(PHP_OS,0,3))=="WIN"){
				$icbcPayObj= new com('ICBCEBANKUTIL.B2CUtil');
				$rc = $icbcPayObj -> init($icbc,$cert,$key,$keyPass);
				if($rc != 0){
					("初始化失败 调试代码:".$icbcPayObj->getRC());
				}
				//----------------------------------------------------------
				//-- 签名
				//----------------------------------------------------------
				$merSignMsg = '';
				$qianMing = $icbcPayObj->signC($TDT, strlen($TDT));
				if($qianMing == ''){
					die("签名失败! 调试代码:".$icbcPayObj->getRC());
				}else{
					$merSignMsg = base64_encode($qianMing);
				}
			}
			else{
				//商户签名数据BASE64编码
				$cmd = "/bin/icbc_sign '{$key}' '{$keyPass}' '{$tranData}'";
				//error_log($cmd,3,__FILE__.".log");
				$handle = popen($cmd, 'r');
				$merSignMsg = fread($handle, 2096);
				pclose($handle);
			}

			//-----------------------------------------------------------
			//-- 验证签名
			//-------------------------------------------------------------
		//	@$qm_ok = $icbcPayObj->verifySignC($TDT, strlen($TDT), $qianMing, strlen($qianMing));
		
			//if($qm_ok != 0){
				//die("签名验证失败! 调试代码:".$icbcPayObj->getRC());
			//}
			
			//-------------------------------------------------------------
			//-- 获取商户证书
			//-------------------------------------------------------------
			//$cert = $icbcPayObj->getCert(1);
			//if($cert == ''){
				//die("获取商户证书失败! 调试代码:".$icbcPayObj->getRC());
			//}
			//--------------------------------------------------------------
			//-- 商城证书公钥
			//--------------------------------------------------------------
			$fp = fopen($cert,"rb");
			$merCert = fread($fp,filesize($cert));
			$merCert = base64_encode($merCert);
			fclose($fp);
			//-------------------------------------------------------------
			//-- 生成支付form串
			//-------------------------------------------------------------
			$parameter = array(
				"interfaceName"		=>	$interfaceName,
				"interfaceVersion"	=>	$interfaceVersion,
				"tranData"			=>	$tranData,
				"merSignMsg"		=>	$merSignMsg,
				"merCert"			=>	$merCert
			);

			//$gateway = "https://210.82.37.103/servlet/ICBCINBSEBusinessServlet";
			$gateway = "https://b2c.icbc.com.cn/servlet/ICBCINBSEBusinessServlet";
			$gateway = "https://mybank3.dccnet.com.cn/servlet/NewB2cMerPayReqServlet";
			echo $this->buildForm($parameter,$gateway,"POST","icbc");
		}
		if($_POST['payment_type']=='ccb'&&$id)
		{
			$configs=$this->get_pay_config('ccb');
			
			$MERCHANTID = $configs['ccb_account']; //商户号
			$POSID = $configs['ccb_posid']; //柜台号
			$BRANCHID = $configs['ccb_branchid'];//分行号
			$GATEWAY = ""; //网关类型
			$CLIENTIP = $_SERVER['REMOTE_ADDR'];//客户端IP
			$REGINFO = "";//注册信息
			$PROINFO = "";//商品信息
			$MER_REFERER = $config["baseurl"];//商户域名
			$REMARK1 = $extra_common_param?$extra_common_param:"";

			$parameter = array(
				"MERCHANTID"		=>	$MERCHANTID,			//商户号
				"POSID"				=>	$POSID,					//柜台号
				"BRANCHID"			=>	$BRANCHID,				//分行号
				"ORDERID"			=>	$id,					//定单号
				"PAYMENT"			=>	$amount,				//付款金额
				"CURCODE"			=>	"01",					//币种
				"TXCODE"			=>	"520100",				//交易码
				"REMARK1"			=>	$REMARK1,				//备注1
				"REMARK2"			=>	"",						//备注2
				//"MAC"				=>	$tmp,					//MAC校验域
			);
			if($configs["ccb_type"]==1)
			{
				$parameter["PUB32"] = $configs["ccb_key1"];
			}
			else if($configs["ccb_type"]==2)
			{
				$parameter1 = array(
					"TYPE"				=>	"1",
					"PUB"				=>	$configs["ccb_key2"],
					"GATEWAY"			=>	$GATEWAY,
					"CLIENTIP"			=>	$CLIENTIP,
					"REGINFO"			=>	$REGINFO,
					"PROINFO"			=>	$PROINFO,
					"REFERER"			=>	$MER_REFERER
				);
				$parameter = array_merge($parameter,$parameter1);
			}
			$parameter = array_merge($parameter,array("MAC"=>http_build_query($parameter)));
			unset($parameter['PUB']);
			$gateway = "https://ibsbjstar.ccb.com.cn/app/ccbMain";
			echo $this->buildForm($parameter,$gateway,"get","ccb");
		}


		if($_POST['payment_type']=='wx_qr'&&$id)
		{
			require_once($config['webroot'] . "/module/payment/lib/WxPayPubHelper/WxPay.Api.php");
			require_once($config['webroot'] . "/module/payment/lib/WxPayPubHelper/WxPay.NativePay.php");

			$notify = new NativePay();

			$input = new WxPayUnifiedOrder();
			$input->SetBody($config['company']);
			$input->SetAttach($extra_common_param);
			$input->SetOut_trade_no($id);
			$input->SetTotal_fee($amount * 100);
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("test");
			$input->SetNotify_url($config['weburl']."/module/payment/lib/wx_qr/notify_url.php");
			$input->SetTrade_type("NATIVE");
			$input->SetProduct_id($id);
			$result = $notify->GetPayUrl($input);
			$code_url  = $result["code_url"];
			$url_data  = urlencode($code_url);

			$check_url = $config['weburl'] . '/module/payment/lib/wx_qr/check_url.php';
			$jump_url = $config['weburl'] . '/../main.php?m=product&s=admin_buyorder';
			print <<<EOT
<!DOCTYPE html>
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<title>微信二维码登陆接口</title>
	<style type="text/css">
	body {
		background:#fff;
		width: 100%;
		z-index: -10;
		padding: 0;
	}
	</style>
</head>
<body>
<div id="content" align="center">
    <div style="margin-left: 10px;margin-top:100px;color:#556B2F;font-size:30px;font-weight: bolder;">扫描支付模式</div><br/>
	<img alt="模式二扫码支付" src="{$config['weburl']}/api/qrcode.php?data={$url_data}" style="width:150px;height:150px;"/>
</div>
<script type="text/javascript">
	var ajaxlock = false;
	var ajaxhandle;
	function synclogin(){
		if (!ajaxlock) {
			ajaxlock = true;
			$.post("{$check_url}",{code:{$id}},function(json){
				//console.log(json);
				if (json.status) {
					console.log(json.status);
					if (json.status==200) {
						alert('付款成功');
						window.location.href = "{$jump_url}";
						clearInterval(ajaxhandle);
					}
				}
				ajaxlock = false;
			},'json');
		}
	}
	$(function(){
		ajaxhandle = setInterval("synclogin()",2000);
	});
</script>
</body>
</html>
EOT;
			die();
		}
	}
	
	/**
     * 构造提交表单HTML数据
     * @param $para_temp 请求参数数组
     * @param $gateway 网关地址
     * @param $method 提交方式。两个值可选：post、get
	 * @param $type 类型 
     * @return 提交表单HTML文本
     */
	function buildForm($para_temp, $gateway, $method, $type = NULL)
	{
		$sHtml = "<form id='form' name='form' action='".$gateway."' method='".$method."'>";
	
		while (list ($key, $val) = each ($para_temp))
		{
			if($key == "MAC" && $type == 'ccb')
			{	
				$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
				$sHtml.= "<script src='script/jquery-1.4.4.min.js' type='text/javascript'></script><script src='module/payment/lib/ccb/md5.js' type='text/javascript'></script><script>$('input[name=\'".$key."\']').val(hex_md5('".$val."'));</script>";		
			}
			else
			{
				$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
			}
	    }
		$sHtml = $sHtml."</form>";
		$sHtml = $sHtml."<script>document.forms['form'].submit();</script>";
		return $sHtml;
	}
	
	
	function get_pay_config($type)
	{
		global $config;
		$sql="select * from ".PAYMENT." where payment_type='$type'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		$re=unserialize($re['payment_config']);
		foreach($re as $v)
		{
			$name=$v['name'];
			$cn[$name]=$v['value'];
		}
		if($type=='alipay')
		{
			$return_url=$config['weburl']."/?m=payment&s=accounts&onlinepaytype=alipay";
			$notify_url=$config['weburl']."/api/pay.php";
			$cn['sign_type']    = 'MD5';
			$cn['input_charset']= 'utf-8';
			$cn['transport']    = 'http';
			$cn['return_url']   = $return_url;
			$cn['notify_url']   = $notify_url;
		}
		elseif($type=='wap_alipay')
		{
			$return_url = $config['weburl']."/module/payment/lib/wap_alipay/call_back_url.php";
			$notify_url = $config['weburl']."/api/wap_alipay.php";
			$cn["private_key_path"] = $config['webroot'].'\module\payment\lib\wap_alipay\key\rsa_private_key.pem';
			$cn["ali_public_key_path"] = $config['webroot'].'\module\payment\lib\wap_alipay\key\alipay_public_key.pem';
			$cn['sign_type']    = 'MD5';
			$cn['input_charset']= 'utf-8';
			$cn['transport']    = 'http';
			$cn["cacert"]       = $config['webroot'].'\module\payment\lib\wapalipay\cacert.pem';
			$cn['return_url']   = $return_url;
			$cn['notify_url']   = $notify_url;
			$cn['key'] = $cn['wap_alipay_key'];
		}
		return $cn;
	}
}
?>
