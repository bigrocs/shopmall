<?php
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//=====================================================================
$id = is_numeric($_GET['order_id'])?$_GET['order_id']:"0";
$de = $order -> orderdetail($id);
if(!$de['id'] || $de['status'] != '2' )
{
	header("Location: 404.php");
}


@include_once("./config/logistics_config.php");
$logistics_kdniao_connect = @$logistics_config['logistics_kdniao_connect'];


$kdniao_logistics_config = @include_once("./config/kdniao_logistics_config.php");

$fastmail = $order->get_fastmail();
$fastmail_company = array();

foreach ($fastmail as $item)
{
	$fastmail_company[] = $item['company'];
}

if ($kdniao_logistics_config)
{
	foreach ($kdniao_logistics_config as $key=>$item)
	{
		if (!in_array($item, $fastmail_company))
		{
			unset($kdniao_logistics_config[$key]);
		}
	}
}

if ('create_kdniao_order' == $_POST['act'])
{
	$seller_row = $shop->get_shop_info($buid);
	$sender_company = $seller_row['company'];
	$sender_mobile = $seller_row['mobile'] ? $seller_row['mobile'] : $seller_row['tel'];
	$sender_user = $seller_row['user'];

	list($province_sender, $city_sender, $exparea_sender, $address_sender) = explode(' ', $seller_row['addr']);

	list($province_receiver, $city_receiver, $exparea_receiver, $address_receiver) = explode(' ', $de['consignee_address']);
	$e_business_id = $logistics_config['e_business_id'];
	$app_key = $logistics_config['logistics_kdniao_app_key'];

	$api = new Api_KdNiao($e_business_id, $app_key);


	$commodity = array();

	foreach ($de['product'] as $key => $item)
	{
		$commodity[$key]['GoodsName'] = $item['name'];
		$commodity[$key]['Goodsquantity'] = $item['num'];
		$commodity[$key]['GoodsWeight'] = $item['freight'];
	}

	$shipper_code = $_POST['logistics_code'];
	$request_rows =
		array (
			'OrderCode' => $id,
			'ShipperCode' => $shipper_code,
			'PayType' => 1,
			'MonthCode' => '7553045845',
			'ExpType' => 1,
			'Cost' => 1,
			'OtherCost' => 1,
			'Sender' =>
				array (
					'Company' => $sender_company,
					'Name' => $sender_user,
					'Mobile' => $sender_mobile,
					'ProvinceName' => $province_sender,
					'CityName' => $city_sender,
					'ExpAreaName' => $exparea_sender,
					'Address' => $address_sender,
				),
			'Receiver' =>
				array (
					'Company' => '',
					'Name' => $de['consignee'],
					'Mobile' => $de['consignee_mobile'],
					'ProvinceName' => $province_receiver,
					'CityName' => $city_receiver,
					'ExpAreaName' => $exparea_receiver,
					'Address' => $address_receiver,
				),
			'Commodity' =>
				$commodity,
			'AddService' =>
				array (
				),
			'Weight' => 1,
			'Quantity' => 1,
			'Volume' => 0,
			'Remark' => '小心轻放',
		);


	 $logistics_rs_row_str = $api->createOrderOnlineByJson($request_rows);
	$logistics_rs_row = json_decode($logistics_rs_row_str, true);

	if ($logistics_rs_row['Success'])
	{
		$logistics_code = $_POST['logistics_code'];

		$order->add_logistics_order_status($id, $logistics_code, 1);

		header('location:./main.php?m=product&s=admin_sellorder&status=2');
		die();
	}
	else
	{
		$msg = $logistics_rs_row['Reason'] ?  $logistics_rs_row['Reason'] : '在线下单失败';
		$msg = addslashes($msg);


		$search = array(" ","\t","\n","\r", "\0", "\x0B");
		$replace = array('', '', '', '', '', '');

		$msg = str_replace($search, $replace, $msg);

		msg("./main.php?m=product&s=admin_sellorder&status=2", $msg);
		die();
	}
}
else if ('kdniao' == $_GET['act'])
{

	$tpl->assign("kdniao_logistics_config", $kdniao_logistics_config);

	$tpl->assign("logistics_kdniao_connect", $logistics_kdniao_connect);

//=====================================================================
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_deliver_kdniao.htm");
}
else
{

	if($_POST['status']=='send')
	{
		$order->send_product();
		msg("main.php?m=product&s=admin_sellorder&status=3");
	}

	//判断是否为快递鸟,根据订单判断
	$kdniao_flag = false;
	if ($logistics_kdniao_connect)
	{
		$logistics_row = $order->get_logistics_order_status($id);

		if ($logistics_row)
		{
			$order_logistics_code = $logistics_row['order_logistics_code'];
			$logistics_row['order_logistics_name'] = $kdniao_logistics_config[$order_logistics_code];

			//取得的fastmail id
			foreach ($fastmail as $item)
			{
				if ($logistics_row['order_logistics_name'] == $item['company'])
				{
					$logistics_row['id'] = $item['id'];
					break;
				}
			}


			$kdniao_flag = true;

			$tpl->assign("logistics_row", $logistics_row);
		}

	}

	if ($kdniao_flag)
	{

		$tpl->assign("fastmail", array());
	}
	else
	{

		$tpl->assign("fastmail", $fastmail);
	}

	$tpl->assign("kdniao_flag", $kdniao_flag);
	$tpl->assign("logistics_kdniao_connect", $logistics_kdniao_connect);


//=====================================================================
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_deliver.htm");
}
?>