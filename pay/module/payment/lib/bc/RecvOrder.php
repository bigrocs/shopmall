<?php
header("Content-type: text/html; charset=utf-8");
include_once("pkcs7/boc.class.php");
$pay = new boc("1111111a");
$pay->cert = dirname(__FILE__).'/pkcs7/cert/cert1.pem';
$pay->privateKey = dirname(__FILE__).'/pkcs7/cert/key1.pem';

if($_POST && $_POST['orderNo']) $orderNo = $_POST['orderNo'];
else $orderNo = "";

if($orderNo){
//签名数据格式
//orderNo|orderTime|curCode|orderAmount|merchantNo
$unsignData = $orderNo."|20120413111212|001|0.01|104430149000002";

print "<b>未签名数据：".$unsignData."</b>";
$signData = $pay->signFromStr($unsignData);
?>
<FORM METHOD="POST" ACTION="http://180.168.146.75:81/PGWPortal/RecvOrder.do">
<!--01.商户号-->
商户号：<INPUT TYPE="text" SIZE="25" ID="merchantNo" NAME="merchantNo" VALUE="104430149000002"><BR/>
<!--02.支付类型-->
支付类型：<INPUT TYPE="text" SIZE="10" ID="payType" NAME="payType" VALUE="1"><BR/>
<!--03.商户订单号-->
商户订单号：<INPUT TYPE="text" SIZE="19" ID="orderNo" NAME="orderNo" VALUE="<?php echo $orderNo;?>"><BR/>
<!--04.订单币种-->
订单币种：<INPUT TYPE="text" SIZE="3" ID="curCode" NAME="curCode" VALUE="001"><BR/>
<!--05.订单金额-->
订单金额：<INPUT TYPE="text" SIZE="13" ID="orderAmount" NAME="orderAmount" VALUE="0.01"><BR/>
<!--06.订单时间-->
订单时间：<INPUT TYPE="text" SIZE="14" ID="orderTime" NAME="orderTime" VALUE="20120413111212"><BR/>
<!--07.订单说明-->
订单说明：<INPUT TYPE="text" SIZE="30" ID="orderNote" NAME="orderNote" VALUE="buy goods"><BR/>
<!--08.商户接收通知URL-->
商户接收通知URL：<INPUT TYPE="text" SIZE="100" ID="orderUrl" NAME="orderUrl" VALUE="http://www.cwebs.com.cn/pkcs7/RecvOrderResp.php"><BR/>
<!--09.商户签名数据-->
商户签名数据：<textarea ID="signData"  NAME="signData" cols=100 rows=12 ><?php echo $signData;?></textarea><BR/>
<input type="submit">
<BR/>
</FORM><br />
用户名：Cherry11   密码：ceshi333 
<?php
}else{
?>
<FORM METHOD="POST" ACTION="RecvOrder.php">
<!--03.商户订单号-->
商户订单号：<INPUT TYPE="text" SIZE="19" ID="orderNo" NAME="orderNo" VALUE=""><BR/>
<input type="submit">
<BR/>
</FORM><br />
<?php
}
?>
