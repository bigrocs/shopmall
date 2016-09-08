<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
include_once("../includes/user_shop_class.php");


include "../lib/phpqrcode/phpqrcode.php";


if (isset($_REQUEST['type']))
{
	switch ($_REQUEST['type'])
	{
		case 'shop':
			$url = sprintf('%s/shop.php?uid=%d&extension_id=%d&dist_id=%d', $config['weburl'], $_REQUEST['shop_id'], $_REQUEST['extension_id'], $_REQUEST['dist_id']);
			break;
		case 'product':
			$url = sprintf('%s/?m=product&s=detail&id=%d&extension_id=%d&dist_id=%d', $config['weburl'], $_REQUEST['pid'], $_REQUEST['extension_id'], $_REQUEST['dist_id']);
			break;
		case 'index':
			$url=sprintf('%s/index.php?extension_id=%d&dist_id=%d', $config['weburl'],$_REQUEST['extension_id'], $_REQUEST['dist_id']);
			break;
		default:
			break;
	}
}


$errorCorrectionLevel = 'L';
$matrixPointSize = 4;
QRcode::png($url,false, $errorCorrectionLevel, $matrixPointSize);

die();
?>