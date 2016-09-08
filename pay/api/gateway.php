<?php
//支付网关
include_once("../includes/global.php");
include_once("../../pay/config/table_config.php");
//============================================================

if($_GET['auth']!=md5($config['authkey']))
	die('非法请求');

//----------获取post数据，进行处理
$order_id       =    $_GET['order_id'];                                  //外部订单号
$price          =    $_GET['price'];									    //订单价格
$return_url     =    $_GET['return_url'];		
$notify_url     =    $_GET['notify_url'];                                //异步处理返回请求地址
$name           =    $_GET['name']; 									    //订单内容描述
$name1          =    $_GET['name1']?$_GET['name1']:$_GET['name'];         //订单内容描述
$extra_param    =    $_GET['extra_param'];                                //自定义扩展参数
$action         =    $_GET['action'];									    //动作指令
$statu          =    $_GET['statu'];									    //担保接口状态值
$type           =    $_GET['type'];                                       //支付类型 2担保
$mold           =    $_GET['mold']?$_GET['mold']:"0";                     //类型 0 1 2..   0取消,1待处理,2已付款,3.发货中,4.成功,5.退货中,6.退货成功  9.佣金结算
$earnest        =	 $_GET['earnest']?$_GET['earnest']:"0";	
$seller_email   =    $_GET['seller_email'];								//卖家信息获取
$buyer_email    =    $_GET['buyer_email'];                           	    //买家信息获取				
$is_virtual		=	 $_GET['is_virtual'];								// 是否为虚拟订单操作			
$flag='T';

//不合理！
if(isset($seller_email))
{
	if (9 != $mold)
	{
		$sql="select pay_email,pay_id from ".MEMBER." where userid='$seller_email' or pay_email='$seller_email'";
	}
	else
	{
		$sql="select pay_email,pay_id from ".MEMBER." where userid='$seller_email'";
	}

	$db->query($sql);
	$re=$db->fetchRow();

	if($re)
	{
		$seller_email = $re['pay_email'];
		$seller_id    = $re['pay_id'];
	}
	else
	{
		echo -1;die;
	}
}
if(isset($buyer_email))
{
	if (9 != $mold)
	{
		$sql="select pay_email,pay_id from ".MEMBER." where userid='$buyer_email' or pay_email='$buyer_email'";
	}
	else
	{
		$sql="select pay_email,pay_id from ".MEMBER." where userid='$buyer_email'";
	}

	$db->query($sql);
	$re=$db->fetchRow();

	if($re)
	{
		$buyer_email=$re['pay_email'];
		$buyer_id=$re['pay_id'];
	}
	else
	{
		echo -2;die;
	}
}
if(empty($seller_email))
	die('卖家信息不完整');
if(empty($buyer_email))
	die('买家信息不完整');
//----------------------------------------------------------------
switch ($action)
{
	case "add":
	{
		//-----------------写入流水表，仅写入一次
		$sql="select order_id from ".CASHFLOW." where order_id='$order_id'";
		$db->query($sql);

		if(!$db->fetchField('order_id'))
		{
			$num=3;
			if($extra_param=='Commission')
			{
				$num=$mold='4';
			}
			$num=$mold=='5'?$mold:$num;
			$num=$mold=='6'?$mold:$num;
			$num=$mold=='9'?$mold:$num;
			$num=$mold=='10'?$mold:$num;
			$flow_id=date("Ymdhis").rand(0,9);
			$time=time();

			//分佣结算，无卖家
			if (9 != $mold)
			{
				//写入买家流水信息
				$sql="insert into ".CASHFLOW." (pay_uid,buyer_email,seller_email,flow_id,order_id,price,time,note,statu,return_url,notify_url,extra_param,type,mold) values ('$buyer_id','','$seller_email','$flow_id','$order_id','".($price*-1)."','$time','$name','1','$return_url','$notify_url','$extra_param','$type','$mold')";

				$re=$db->query($sql);
			}

			if (9 == $mold)
			{
				$sql = "update ".MEMBER." set cash=cash+$price where pay_email='$buyer_email'";

				echo $sql;
				$re = $db -> query($sql);

				if($earnest!=2)
				{
					//写入卖家流水信息
					$sql="insert into ".CASHFLOW." 				(pay_uid,buyer_email,seller_email,flow_id,order_id,price,time,note,statu,return_url,notify_url,extra_param,type,mold) values ('$seller_id','$buyer_email','','$flow_id','$order_id','$price','$time','$name1','4','$return_url','$notify_url','$extra_param','$type','$num')";
					$re=$db->query($sql);

				}
			}
			else
			{
				if($earnest!=2)
				{
					//写入卖家流水信息
					$sql="insert into ".CASHFLOW." 				(pay_uid,buyer_email,seller_email,flow_id,order_id,price,time,note,statu,return_url,notify_url,extra_param,type,mold) values ('$seller_id','$buyer_email','','$flow_id','$order_id','$price','$time','$name1','1','$return_url','$notify_url','$extra_param','$type','$num')";
					$re=$db->query($sql);

				}
			}
		}

		if($extra_param=='Commission' || $mold=='5' || $mold=='6')
		{
			$sql="update ".CASHFLOW." set statu='4' where order_id='$order_id'";
			$db->query($sql);
			
			if($mold!='6')
			{
				$sql = "update ".MEMBER." set cash=cash-$price where pay_email='$buyer_email'";
				$db->query($sql);
				
				$sql = "update ".MEMBER." set cash=cash+$price where pay_email='$seller_email'";
				$db->query($sql);
			}
			break;
		}
		break;
	}
	case "update":
	{
		if($statu==0)
		{
			//=======如果已付款的情况下，要对买家加钱。
			$sql="select price,statu from ".CASHFLOW." where pay_uid='$buyer_id' and order_id='$order_id'";
			$db->query($sql);
			$ss=$db->fetchRow();
			$str = "";
			if($ss['statu']>=2)
			{
				$price=$ss['price'];
				if($price<0) $price*=-1;
				
				$sql = "update ".MEMBER." set cash=cash+$price where pay_email='$buyer_email'";
				$re = $db -> query($sql);
				$str = ",refund_amount = '$price' ,is_refund = 'true'";
			}
			//=======取消定单
			$sql="update ".CASHFLOW." set statu='0' $str where order_id='$order_id'";
			$re=$db->query($sql);
		}
		if($statu==3)
		{	//发货，更改订单状态
			$sql="update ".CASHFLOW." set statu='3' where order_id='$order_id'";
			$re=$db->query($sql);
		}
		if($statu == 4)
		{
			//卖家加钱
			$sql = "select price,statu,refund_amount from ".CASHFLOW." where pay_uid='$seller_id' and order_id='$order_id'";
			$db -> query($sql);
			$ss = $db -> fetchRow();
			
			$price = $ss['price'];
			$refund_amount = $ss['refund_amount'];
			$status = $ss['statu'];
			
			$flag = 'F';
			if($status == 3 || $is_virtual)
			{
				$flag = 'T';

				if($price<0) $price *= -1;
				$price =  $price - $refund_amount;

				//dist_commission_out
				//判断是否有分销分佣，对数据进行修正,将用户分佣放入另外地方，分佣额度，通过平台来
				if (isset($_REQUEST['dist_user_id']) && isset($_REQUEST['commission_str']))
				{
					$dist_user_id = $_REQUEST['dist_user_id'];
					//$commission_str = json_decode($_REQUEST['commission_str'], true);
					$commission_str = $_REQUEST['commission_str'];

					$price = $price - $commission_str;


					//确认收货，更改状态
					$sql = "update ".CASHFLOW." set statu='4', price = price - $commission_str, dist_commission_out = dist_commission_out+$commission_str  where order_id='$order_id' AND seller_email=''";
					$re = $db->query($sql);


					$sql = "update ".CASHFLOW." set statu='4' where order_id='$order_id' AND seller_email!=''";
					$re = $db->query($sql);


					$sql = "update ".MEMBER." set cash = cash + $price, dist_commission_out = dist_commission_out+$commission_str  where pay_email='$seller_email'";
				}
				else
				{
					//确认收货，更改状态
					$sql = "update ".CASHFLOW." set statu='4' where order_id='$order_id'";
					$re = $db->query($sql);

					$sql = "update ".MEMBER." set cash = cash + $price where pay_email='$seller_email'";
				}

				$re = $db -> query($sql);

			}
		}
		if($statu==5 || $statu==6 || $statu==7)
		{
			$status = "";
			if($statu == 6)//未收货 1
			{
				$status = "statu = '0', ";
			}


			$sql = "SELECT * FROM  " . CASHFLOW . " WHERE order_id='$order_id'  AND is_refund = 'false' ";

			$db->query($sql);
			$re = $db->fetchRow();

			//判断是否已经退款，不能完全解决重复问题，大部分可解决，必须事务才可以！
			if ($re['id'])
			{
				//退货 更改状态
				$sql="update ".CASHFLOW." set $status refund_amount = refund_amount + $price , is_refund = 'true' where order_id='$order_id'  AND is_refund = 'false' ";
				$re=$db->query($sql);

				/*//买家加钱
				$sql = "select price from ".CASHFLOW." where pay_uid='$buyer_id' and order_id='$order_id'";
				$re = $db -> query($sql);
				$price = $db -> fetchField('price');
				$price = $price<0 ? ($price*-1) : $price;*/

				$sql = "update ".MEMBER." set cash = cash + $price where pay_email='$buyer_email'";
				$re = $db->query($sql);

				if($statu==5)//确定收货
				{
					$sql = "update ".MEMBER." set cash = cash - $price where pay_email='$seller_email'";
					$re = $db->query($sql);
				}
			}
		}
		//========返回执行结果
		if($re&&$flag=='T')
		{
			$return['statu']='true';
			$return['auth']=md5($config['authkey']);
		}
		else
		{
			$return['statu']='false';
			$return['auth']=md5($config['authkey']);
		}
		echo json_encode($return);
		break;
	}
	case "reprice":
	{
		//买家流水
		$sql="update ".CASHFLOW." set price = '".$price*(-1)."' where order_id='$order_id' and seller_email='$seller_email'";
		$re1=$db->query($sql);
		
		//卖家流水
		$sql="update ".CASHFLOW." set price = '$price' where order_id='$order_id' and buyer_email='$buyer_email'";
		$re2=$db->query($sql);
		
		//========返回执行结果
		if($re1&&$re2)
		{
			$return['statu']='true';
			$return['auth']=md5($config['authkey']);
		}
		else
		{
			$return['statu']='false';
			$return['auth']=md5($config['authkey']);
		}
		echo json_encode($return);
		break;
	}
	default:
	{
		break;
	}
}
?>