<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");


if(!empty($_GET['statu'])&&$_GET['statu']==1)
{
	if($_GET['auth']!=md5($config['authkey']))
		die('参数错误');
	
	function order_oprate($id)
	{
		global $db,$config;

		$sql = "select `status`,`pid` from ".ORPRO." where order_id='$id'";
		$db -> query($sql);
		$t_re = $db -> fetchRow();
		$status = $t_re['status'];
		$pid = $t_re['pid'];

		if($status < 2)
		{
			//---------------------付款成功减库存，
			$sql="select pid,num,setmeal from ".ORPRO." where order_id='$id'";
			$db->query($sql);
			$re=$db->getRows();
			foreach($re as $val)
			{
				if(!empty($val['num']))
				{
					$sql="update ".PRODUCT." set sales= sales + $val[num] where id=$val[pid]";
					$db->query($sql);
							
					if($val['setmeal'])
					{
						$sql="update ".SETMEAL." set stock = stock - $val[num] where id = '$val[setmeal]'";
						$db->query($sql);
					}		
				
					$sql="update ".PRODUCT." set stock = stock - $val[num] where id = '$val[pid]'";
					$db->query($sql);
					
					$sql="select stock from ".PRODUCT." where id='$val[pid]'";
					$db->query($sql);
					
					if($db->fetchField('stock')<=0)
					{
						$sql="update ".PRODUCT." set is_shelves = '0' where id=$val[pid]";
						$db->query($sql);
					}
				}
			}
			$sql="update ".ORDER." set status='2',payment_name='$_GET[type]',payment_time=".time()." where order_id='$id'";
			$db->query($sql);
			
			$sql="update ".ORPRO." set status='1' where order_id='$id'";
			$db->query($sql);

		}
	}

	$order_id = $_GET['id'];

	$Ostatus = 0;
	if(substr($order_id, 0,1) == "U")
	{
		// 获取订单状态，如果已经执行过本脚本，则直接跳转成功页面
		$sql = "select `status` from ".UORDER." where  `order_id` = '".$order_id."' ";
		$db->query($sql);
		$Ostatus = $db->fetchField('status');
	}
	

	if($Ostatus != 2)
	{
		// 如果是合并支付
		if(substr($order_id, 0,1) == "U")
		{
			
			$sql = "select `inorder` from ".UORDER." where `order_id` = '$_GET[id]'  ";
			$db -> query($sql);

			$re = $db -> fetchField("inorder");
			$de = explode(",", $re);
			
			$flag = false; // 是否为虚拟订单

			// 更新合并订单状态
			$sql = "update ".UORDER." set `status` = 2 where `order_id` = '".$order_id."' ";
			$db->query($sql);

			foreach ($de as $key => $val)
			{
				$sql = "select `is_virtual`,`consignee_mobile` from ".ORDER." where  order_id='$val'";
				$db -> query($sql);
				$t_res = $db -> fetchRow();
				$is_virtual = $t_res['is_virtual'];

				if($is_virtual == 1){ $flag = true;}

				order_oprate($val);
				@file_get_contents($config["weburl"]."/pay/api/change_status.php?id=".$val);
			}

			$url = $config['weburl']."/main.php?cg_u_type=1&m=product&s=admin_buyorder&zt=3";

			if($flag) // 虚拟商品订单
			{
				// 短信模板
				$sql = "select message from ".MAILMOD." where `flag` ='v-order-msg' and type=2 ";
				$db -> query($sql);
				$me0 = $me = $db -> fetchField("message");

				// 获取虚拟商品信息
				$sql = "select `status`,`pid` from ".ORPRO." where order_id='$de[0]'";
				$db -> query($sql);
				$t_re = $db -> fetchRow();
				$status = $t_re['status'];
				$pid = $t_re['pid'];

				$sql = "select a.`start_time`,b.`end_time`,a.name from ".PRODUCT." a left join ".PROVIR." b on a.id = b.pid where a.id =".$pid;
				$db -> query($sql);
				$ttime = $db -> fetchRow();

				// 获取用户手机号
				$sql = "select `consignee_mobile` from ".ORDER." where  order_id='$de[0]'";
				$db -> query($sql);
				$mobile = $db -> fetchField("consignee_mobile");

				$userial = "";
				foreach ($de as $key => $val)
				{
					$serial = randomkeys();

					$me = str_replace("[webname]", $config['company'], $me);
					$me = str_replace("[serial]", $serial, $me);
					$me = str_replace("[stime]", date("Y-m-d",$ttime['start_time']), $me);
					$me = str_replace("[etime]", date("Y-m-d",$ttime['end_time']), $me);
					$me = str_replace("[order_id]", $val, $me);
					$me = str_replace("[time]", date("Y-m-d"), $me);
					$me = str_replace("[product_name]", $ttime['name'], $me);

					$sql = "insert into ".MSGCORD." (`msg`,`count`,`create_time`,`update_time`,`serial`,`order_id`,`type`) values ('$me',1,'".time()."','".time()."','$serial','$val',1) ";
					$db -> query($sql);

					$userial .= $serial.",";
				}

				$userial = substr($userial, 0, -1);
				$me0 = str_replace("[serial]", $userial, $me);

				$me0 = str_replace("[webname]", $config['company'], $me);
				$me0 = str_replace("[serial]", $userial, $me);
				$me0 = str_replace("[stime]", date("Y-m-d",$ttime['start_time']), $me);
				$me0 = str_replace("[etime]", date("Y-m-d",$ttime['end_time']), $me);
				//$me0 = str_replace("[order_id]", $val, $me); // 屏蔽订单编号
				$me0 = str_replace("[time]", date("Y-m-d"), $me);
				$me0 = str_replace("[product_name]", $ttime['name'], $me);

				$url = $config['weburl']."/api/msg.php?mobile=".$mobile."&me=".urlencode($me);
				$str = file_get_contents($url);

				$str = "短信将于10分钟之内发至您的手机，请注意查收，如果没有收到短信请在个人中心，我的虚拟订单中点击再次发送按钮。";

				//$url=$config["weburl"]."/main.php?cg_u_type=1&m=product&s=admin_virtual_buyorder";
				$url=$config["weburl"]."/main.php?cg_u_type=1&m=product&s=admin_buyorder";

			}

		}
		else
		{
			order_oprate($_GET['id']);
			$url = $config['weburl']."/main.php?cg_u_type=1&m=product&s=admin_buyorder&zt=3";
		}
	}
	else
	{
		$flag = true;
		
		if (substr($order_id, 0, 1) == "U")
		{
			//$url = $config["weburl"] . "/main.php?cg_u_type=1&m=product&s=admin_virtual_buyorder";
			$url = $config["weburl"] . "/main.php?cg_u_type=1&m=product&s=admin_buyorder";
		}
		else
		{
			$url = $config['weburl'] . "/main.php?cg_u_type=1&m=product&s=admin_buyorder&zt=3";
		}
	}


	//启用微信通知
	if (file_exists("../config/wechat_push_config.php"))
	{

		@include_once("../config/wechat_push_config.php");

		if ($wechat_push_config['wechat_statu'])
		{
			//
			// 如果是合并支付
			if (substr($order_id, 0, 1) == "U")
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
				$sql = "select `status`,`pid`, `name`, `price`, `num` from " . ORPRO . " where order_id='$order_id'";
				$db->query($sql);
				$t_re              = $db->fetchRow();
				$status            = $t_re['status'];
				$pid               = $t_re['pid'];
				$product_name_desc = $t_re['name'] . ' x ' . $t_re['num'];
				$product_num       = $t_re['num'];

				if ($status < 2) //付款成功
				{
					//获取卖家信息
					$sql = "select `seller_id`, `product_price`  from " . ORDER . " where order_id='$order_id' AND seller_id!=0";
					$db->query($sql);
					$t_re          = $db->fetchRow();
					$seller_id     = $t_re['seller_id'];
					$product_price = $t_re['product_price'];


					$sql = "select `userid`, open_id, `user`  from " . MEMBER . " where userid='$seller_id'";
					$db->query($sql);
					$t_re   = $db->fetchRow();
					$openid = $t_re['open_id'];

					if ($openid)
					{
						include_once './wechat_mp.php';

						$touser      = $openid;
						$template_id = 'HPy33qqx1ucYhx28QUm3fUPYNJjm48dO6HLBgih18r0';

						$data = array(
							'first' => array('value' => urlencode("您好,买家已付款"), 'color' => "#743A3A"),
							'keyword1' => array('value' => urlencode($order_id), 'color' => '#EEEEEE'),
							'keyword2' => array('value' => urlencode($product_name_desc), 'color' => '#EEEEEE'),
							'keyword3' => array('value' => urlencode($product_price), 'color' => '#EEEEEE'),
							'remark' => array('value' => urlencode('请确认发货！'), 'color' => '#FFFFFF')
						);

						$url = $config['weburl'] . "/main.php?cg_u_type=1&m=product&s=admin_buyorder&zt=3";
						$wechatPush->doSend($touser, $template_id, $url, $data);
					}
				}
			}

		}
	}

	if($flag)
	{
		$tpl -> assign("turn_url",$url);
		include_once("../footer.php");
		$tpl -> assign("str",$str);
		$tpl -> display("virtual_success.htm");
	}
	else
	{
		msg($url);
	}
	
	exit;
}
else
{
	$url=$config["weburl"]."/main.php?m=product&s=admin_orderdetail&id=$_GET[id]";
	msg($url);
}

?>