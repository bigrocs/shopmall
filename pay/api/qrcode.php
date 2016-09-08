<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");


include "../../lib/phpqrcode/phpqrcode.php";

$data = $_REQUEST["data"];

QRcode::png($data);

die();
?>