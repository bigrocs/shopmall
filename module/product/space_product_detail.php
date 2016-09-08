<?php
//微信分享
if ($config['bw'] == "weixin")
{
	include_once("pay/module/payment/lib/WxPayPubHelper/WxPay.pub.config.php");

	if(!isset($_SESSION['access_token']) || (time()-$_SESSION['tmpTime'])>7200)
	{
		//获取微信票据
		$date = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . WxPayConf_pub::APPID . "&secret=" . WxPayConf_pub::APPSECRET);
		$wobj = json_decode($date);
		$_SESSION['access_token'] = $wobj -> access_token;

		if(isset($wobj -> access_token))
		{
			$tmp = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$wobj -> access_token."&type=jsapi");
			$tobj = json_decode($tmp);
			$_SESSION['ticket'] = $tobj->ticket;
		}

		$_SESSION['tmpTime'] = time();
	}
	{
		$_SESSION['noncestr'] = randomkeys(12);

		$strTmp = "http://".$_SERVER['HTTP_HOST'];
		if(!empty($_SERVER['REQUEST_URI']))
		{
			$strTmp .= $_SERVER['REQUEST_URI'];
		}


		$_SESSION['appid'] = WxPayConf_pub::APPID;


		$str_tmp = "jsapi_ticket=".$_SESSION['ticket']."&noncestr=".$_SESSION['noncestr']."&timestamp=".$_SESSION['tmpTime']."&url=".$strTmp;
		$_SESSION['signature'] = sha1($str_tmp);
	}
}


//====================================产品详情
$tpl->assign("de",$prode);
$tpl->assign("score",$shop->score());

$tpl->assign("chat_open_flag", $chat_open_flag);
//====================================购买记录
if($_GET['ajax'] == 'deal-record')
{
	$product_id = $_GET['id']*1;	//产品ID

	$sql="select a.name,a.price,a.spec_name,a.spec_value,a.num,a.time,b.user,b.buyerpoints,b.name as uname from ".ORPRO." a left join ".MEMBER." b on a.buyer_id=b.userid where status = 3 and a.pid='$product_id' order by a.id desc";

	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page -> url = $config['weburl'].'/';
	$page -> listRows = 20;
	if (!$page -> __get('totalRows')){
		$db -> query($sql);
		$page -> totalRows = $db -> num_rows();
	}
	$sql .= "  limit ".$page -> firstRow.",".$page -> listRows;
	$db -> query($sql);
	$re["total2"] = $page -> __get('totalRows');
	$re["page"] = $page -> prompt();
	$re["list"] = $db -> getRows();
	foreach($re["list"] as $key => $val)
	{
		$re['list'][$key]['user'] = namereplace($val['uname']?$val['uname']:$val['user']);
		$re['list'][$key]['buyerpoints'] = get_buyerpoints($val["buyerpoints"]);
		$val['spec_value'] = explode(",",$val['spec_value']);
		$val['spec_name'] = explode(",",$val['spec_name']); 
		foreach($val['spec_name'] as $k => $v)
		{
			$re['list'][$key]['spec'][] = $v.":".$val['spec_value'][$k];
		}
	}
	$sql="select * from ".ORPRO." where status in (0,1,2) and pid = '$product_id'";
	$db -> query($sql);
	$re["total1"] = $db -> num_rows();
	
	$sql="select * from ".ORPRO." where status in (4,5) and pid = '$product_id'";
	$db -> query($sql);
	$re["total3"] = $db -> num_rows();
		
	$tpl->assign("re",$re);
	$tpl->assign("config",$config);
	echo $output=tplfetch("space_record.htm",$flag);
	die;
}
//====================================评价
if($_GET['ajax'] == 'reviews')
{
	$product_id = $_GET['id']*1;  //产品ID
	$catid = $_GET['catid']*1;	  //分类ID
	
	$sql = "select member_id from ".PRODUCT." where id= ".$product_id;
	$db -> query($sql);
	$delid = $db -> fetchField("member_id");

	if($delid > 0)
	{
		$scl.=" and a.userid != $delid";
	}

	if($catid > 0)
	{
		$catid = $catid -2;
		$scl.=" and goodbad = '$catid'";
	}
	if(!empty($product_id))
		$scl.=" and a.pid = '$product_id'";
	
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page -> url = $config['weburl'].'/';
	$page -> listRows = 20;
	$sql = "select a.*,b.logo,b.name,b.buyerpoints from ".PCOMMENT." a left join ".MEMBER." b on a.userid=b.userid  where 1 $scl order by uptime desc";
	if(!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows =$db->num_rows();
	}
	$sql.=" limit ".$page->firstRow.",".$page->listRows;
	//--------------------------------------------------
	$db->query($sql);
	$re['list'] = $db->getRows();
	$re['page'] = $page->prompt();
	
	foreach($re['list'] as $key=>$val)
	{		
		$user_id=$val['userid'];
		$id = $val['id'];
	    $sql1 = "select * from ".ORPRO." where buyer_id='$user_id' and id='$id' order by time desc";
		$db->query($sql1);
	    $re1['list'] = $db->getRows();      
	    $re['list'][$key] = $re1['list'][0];
		
		$val['spec_value'] = explode(",",$re1['list'][0]['spec_value']);
		$val['spec_name'] = explode(",",$re1['list'][0]['spec_name']); 
		foreach($val['spec_name'] as $k => $v)
		{
			$re['list'][$key]['spec'][] = $v.":".$val['spec_value'][$k];
		}
		$re['list'][$key]['user'] = namereplace($val['name']?$val['name']:$val['user']);
		$re['list'][$key]['uptime'] = date("Y年m月d日 H:i",$val['uptime']);
		$re['list'][$key]['buyerpoints'] = get_buyerpoints($val["buyerpoints"]);
		$re['list'][$key]['logo'] = $val["logo"];
		$re['list'][$key]['con'] = $val["con"];
	}
	$tpl->assign("re",$re);
	$tpl->assign("config",$config);
	echo $output=tplfetch("space_reviews.htm",$flag);
	die;
}
//====================================咨询
$_SESSION['auth'] = $_SESSION['auth'] ? $_SESSION['auth'] : create_password();
if($_POST['act'] == $_SESSION['auth'])
{
	include_once($config['webroot']."/module/product/includes/plugin_consult_class.php");
	$consult = new consult();
	$re['member_id'] = $prode['member_id'];
	$re['id'] = $prode['id'];
	$re['name'] = $prode['name'];
	$consult -> add_question($re);
	header("Location:main.php?cg_u_type=1&m=product&s=admin_consult");
	unset($_SESSION['auth']);
}
if($_GET['ajax'] == 'consult')
{
	$product_id = $_GET['id']*1;//产品ID

	$sql="select question,answer,question_time,user,logo,name from ".CONSULT." a left join ".MEMBER." b on a.member_id = b.userid where a.status = 2 and product_id='$product_id' order by question_time desc,answer_time desc,id desc ";
	
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page -> url = $config['weburl'].'/';
	$page -> listRows = 20;
	if (!$page -> __get('totalRows')){
		$db -> query($sql);
		$page -> totalRows = $db -> num_rows();
	}
	$sql .= "  limit ".$page -> firstRow.",".$page -> listRows;
	$db -> query($sql);
	$re["page"] = $page -> prompt();
	$re["list"] = $db -> getRows();
	foreach($re["list"] as $key => $val)
	{
		$re['list'][$key]['user'] = namereplace($val['name']?$val['name']:$val['user']);
	}
	$tpl->assign("re",$re);
	$tpl->assign("config",$config);
	$tpl->assign("act",$_SESSION['auth']);
	echo $output = tplfetch("space_consult.htm",$flag);
	die;
}
//====================================Function
function get_buyerpoints($points)
{
	global $db;
	$sql="select * from ".POINTS." order by id";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$ar=explode('|',$v['points']);
		if($points <= $ar[1] and $points >= $ar[0])
		{
			return $v['img'];
		}
	}	
}
function namereplace($name, $charset = 'UTF8') {
	$strlen = mb_strlen($name, $charset);
	if($strlen>2) {
		return mb_substr($name, 0, 1, $charset).str_repeat('*',$strlen-2).mb_substr($name, -1, 1, $charset);
	} elseif($strlen>0) {
		return mb_substr($name, 0, 1, $charset).str_repeat('*',$strlen-1);
	}
}
//====================================SEO
$company["shop_title"] = $prode['name'];
$company["shop_keywords"] = $prode['keywords'].','.$shopconfig["homedes"];
$company["shop_description"] = $prode['keywords'].','.$shopconfig["homekeyword"];
$company["logo"] = $prode['pic'] ? $prode['pic'] : $config['weburl'] . "/image/default/nopic.gif";
//====================================
$tpl->assign("config",$config);
$tpl->assign("com",$company);


if ($dist_user_row)
{
	$tpl->assign("dist_user_row", $dist_user_row);

	$product_id = intval($_GET['id']);
	$tpl->assign("is_distribution_product", $distribution->isDistributionProduct($product_id));
}

$output=tplfetch("space_product_detail.htm",$flag);
?>