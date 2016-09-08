<?php
header("Content-type: text/html; charset=utf-8");
include_once("pkcs7/boc.class.php");
$pay = new boc("1111111a");
$pay->cert = dirname(__FILE__).'/pkcs7/cert/cert1.pem';
$pay->privateKey = dirname(__FILE__).'/pkcs7/cert/key1.pem';

if($_POST){
	//签名数据格式
	//merchantNo|orderNo|orderSeq|cardTyp|payTime|orderStatus|payAmount
	$unsignData = $_POST['merchantNo']."|".$_POST['orderNo']."|".$_POST['orderSeq'];
	$unsignData .= "|".$_POST['cardTyp']."|".$_POST['payTime']."|".$_POST['orderStatus']."|".$_POST['payAmount'];

	if($pay->verifyFormStr($_POST['signData'],$unsignData)){
		//验证成功,
		//请编写支付后业务流程
		print "OK";
	}else{
		//非法数据
		print_r($pay->dnData);
		print "ERROR";
	}
}
?>
<br />
<a href="RecvOrder.php">返回支付测试页</a>
<textarea ID="signData"  NAME="signData" cols=120 rows=30 ><?php print_r($_POST);?></textarea>