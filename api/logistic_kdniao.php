<?php
include_once("../includes/global.php");
@include_once("../config/logistics_config.php");
$logistics_kdniao_connect = @$logistics_config['logistics_kdniao_connect'];


$kdniao_logistics_config = @include_once("../config/kdniao_logistics_config.php");
$e_business_id = $logistics_config['e_business_id'];
$app_key = $logistics_config['logistics_kdniao_app_key'];



$com  = $_GET["com"];
$nu   = $_GET["nu"];
$shipper_code = '';

if ($kdniao_logistics_config)
{
	foreach ($kdniao_logistics_config as $key=>$item)
	{
		if ($item == $com)
		{
			$shipper_code = $key;
			break;
		}
	}
}

$order_id = $_GET['order_id'];

$api = new Api_KdNiao($e_business_id, $app_key);

$request_rows =
	array (
		'OrderCode' =>   $order_id,  //订单编号
		'ShipperCode' => $shipper_code, //物流公司编码
		'LogisticCode' => $nu            //物流单号
	);

$rs_str =  $api->getOrderTracesByJson($request_rows);
$rs_row = array();

if ($rs_str)
{
	$rs_row = json_decode($rs_str, true);
}
if (isset($rs_row['Success']) && $rs_row['Success'])
{
	if ($rs_row['Traces'])
	{
		foreach ($rs_row['Traces'] as $trace)
		{
			//$trace['Remark']

			$msg = sprintf('<p>%s - %s</p>', $trace['AcceptTime'], $trace['AcceptStation']);
			echo "document.write('" . addslashes($msg) . "');";
		}

	}
	else
	{
		$msg = sprintf('<font color="red">%s</font>', $rs_row['Reason']);
		echo "document.write('" . addslashes($msg) . "');";
	}
}
else
{
	if (isset($rs_row["Reason"]))
	{
		$msg = $rs_row["Reason"];
	}
	else
	{
		$msg = $rs_row["Message"];
	}

	$msg = sprintf('<font color="red">%s</font>', $msg);

	echo "document.write('" . addslashes($msg) . "');";
}

?>