<?php
if(empty($buid))
{
	if (isset($_SERVER['HTTP_REFERER']))
	{
		$forward = $_SERVER['HTTP_REFERER'];
		msg($config['weburl']."/login.php?forward=".urlencode($forward)); //如果没有登录
	}
	else
	{
		msg($config['weburl']."/login.php"); //如果没有登录
	}
}
else
{	
	include_once("module/member/includes/plugin_orderadder_class.php");
	include_once("module/product/includes/plugin_cart_class.php");
	$cart = new cart();

	if(!empty($_POST['id'])&&!empty($_POST['nums']))
	{
		if(isset($_POST['is_virtual']) && $_POST['is_virtual'] == 1)
		{
			$_SESSION['pid'] = $_POST['id'] * 1;
			$_SESSION['sid'] = $_POST['sid'] * 1;
			$_SESSION['is_virtual'] = 1;
		}
		else
		{
			$flag = $cart->add_cart($_POST['id'],$_POST['nums'],$_POST['sid'], null, $_REQUEST['dist_user_id']);
			$_SESSION['product_id'] = $flag;
			$_SESSION['dist_user_id'] = $_REQUEST['dist_user_id'];
			header("Location: ?m=product&s=confirm_order");
		}
	}
	
	$orderadder = new orderadder();
	//============================获取数据
	if($config['temp']=='wap'||$config['temp']=='wap_app')
	{
		$addr = $orderadder -> get_default_orderadder();
	}
	else
	{
		$addr = $orderadder -> get_orderadderlist();
	}
	$tpl -> assign("consignee",$addr);
	//============================读出购物车的数据
	if($_GET['id']&&is_numeric($_GET['id']))
	{
		$re = $orderadder -> get_orderadder($_GET['id']);
		$tpl -> assign("re",$re['name']);
		$tpl -> assign("consignee_id",$re['id']);
		if($re) $on_city = getdistrictname($re['cityid']);
	}
	else
	{
		$on_city = getdistrictname($addr[0]['cityid']);
		$tpl -> assign("re",$addr[0]['name']);
	}

	$product_id = $_POST['product_id'] ? implode(",",$_POST['product_id']) : "";
	unset($_POST['product_id']);
	$_SESSION['product_id'] = $product_id ? $product_id : $_SESSION['product_id'];
	$_SESSION['dist_user_id'] = $_REQUEST['dist_user_id'] ? $_REQUEST['dist_user_id'] : $_SESSION['dist_user_id'];

	//修正订单店铺信息
	$cartlist = $cart -> get_cart_list($on_city,$_SESSION['product_id']);

	//-----------如果为空,返回至购物车
	if(empty($cartlist['sumprice'])) msg($config['weburl']."/?m=product&s=cart");
	//=============================提交订单
	if($_POST['act']=='order')
	{  
		$re = $orderadder->get_orderadder($_POST['hidden_consignee_id']); 
		//----------循环店铺,生成多个订单

		// 合并付款
		$uprice = 0;
		$buyer = $buid;
		$inorder = "";

		////订单号生成规则
		//订单号生成前缀
		$order_id_prefix = date("ymdhis");

		$cache_dir = $config['webroot'] . '/cache/order_id/';
		make_dir_path($cache_dir);

		//设置cache 参数
		//cacheType 1:file  2:memcache   3：redis
		$config_cache['order_id'] = array(
				'cacheType' => 1,
				'cacheDir' => $cache_dir,
				'memoryCaching' => true,
				'automaticSerialization' => true,
				'hashedDirectoryLevel' => 3,
				'hashedDirectoryUmask' => 0777,
				'cacheFileMode' => 0777,
				'lifeTime' => 10
		);

		$order_id_cache = new Cache_Lite_Output($config_cache['order_id']);

		foreach($cartlist['cart'] as $key => $val)
		{
			$sell_userid=$val['seller_id'];
			if($_POST['logistics_type_'.$sell_userid] == '快递')
			{
				$logistics_price = $val['express'];
			}
			elseif($_POST['logistics_type_'.$sell_userid] == '平邮')
			{
				$logistics_price = $val['mail'];
			}
			elseif($_POST['logistics_type_'.$sell_userid] == 'EMS')
			{
				$logistics_price = $val['express'];
			}
			else
			{
				//die;
				header("location:".getenv("HTTP_REFERER"));die;
			}

			if($val['prolist'])
			{
				if(!empty($sell_userid))
				{
					//$logistics_type = $_POST['logistics_type_'.$sell_userid];//物流方式
					//$logistics_price = $_POST['logistics_price_'.$sell_userid]*1;//物流价格
					
					$invoice_title = $_POST['invoice_'.$sell_userid] > 0 ? ( $_POST['invoice_title_'.$sell_userid] ? $_POST['invoice_title_'.$sell_userid] : $re['name'] ) : "";
					$product_price = $val['sumprice'];//购物总价



					$order_num_index = $order_id_cache->get($order_id_prefix);

					if ($order_num_index)
					{
						$order_num_index = $order_num_index + 1;
					}
					else
					{
						$order_num_index = 1;
					}

					$rs = $order_id_cache->save($order_num_index);

					$number_str = $order_id_prefix . str_pad($order_num_index, 3, 0 ,STR_PAD_LEFT);
					$order_id = $number_str;//订单号


					//$order_id = date("Ymdhis").rand(0,9);//订单号
					$msg = htmlspecialchars($_POST['msg_'.$sell_userid]);
					$time = time();
					$vou_price = 0; //优惠价格
                    $discounts = $val['discounts']; //会员折扣
                    $inorder .= $order_id.",";
                                        
                    /*** 是否使用代金券 ****/
                    if(isset($_POST['voucher_'.$sell_userid]) && $_POST['voucher_'.$sell_userid] >0 )
                    {
                       $id = $_POST['voucher_'.$sell_userid]*1;
                       $sql = "select * from ".VOUCHER." where member_id = $buid and id = $id and shop_id = $sell_userid and status =1 and end_time >".time()." and `limit` <= $product_price";
                       $db->query($sql);
                       if($db -> num_rows())
                        {
                           $vo = $db -> fetchRow();
                           $vou_price = ($product_price - $vo['price'] >0)?$vo['price']:$product_price;
                           $vprice = $product_price - $vo['price'];
                           $product_price = $vprice > 0?$vprice:0;
                           
                           /*** 更新优惠券状态 和 绑定 order_id ****/
                           $sql = "update ".VOUCHER."  set `status` = 2 ,`order_id` = 'order_id' where id = ".$vo['id'];
                           $db->query($sql);
                           
                           /*** 更新优惠券模板中的使用次数 ****/
                           $sql = "update ".VOUTEMO."  set `used` = used + 1 where id = ".$vo['temp_id'];
                           $db->query($sql);
                        }
                    }

					$dist_user_id = $val['dist_user_id'];

					/***生成买家订单****/
					$sql = "INSERT INTO ".ORDER." (`userid`,`order_id`,`buyer_id`,`seller_id`,`consignee`,`consignee_address`,`consignee_tel`,`consignee_mobile`,`product_price`,`logistics_type`,`logistics_price`,`status`,`des`,`create_time`,`uptime`,`invoice_title`,`voucher_price`,`discounts`, `dist_user_id`) VALUES ($buid,$order_id,'0',$sell_userid,'$re[name]','$re[area] $re[address]','$re[tel]','$re[mobile]','$product_price','$logistics_type','$logistics_price',1,'$msg','$time','$time','$invoice_title','$vou_price','$discounts', '$dist_user_id')";
					$db->query($sql);
					
					/***生成卖家订单****/
					$sql = "INSERT INTO ".ORDER."	(`userid`,`order_id`,`buyer_id`,`seller_id`,`consignee`,`consignee_address`,`consignee_tel`,`consignee_mobile`,`product_price`,`logistics_type`,`logistics_price`,`status`,`des`,`create_time`,`uptime`,`invoice_title`,`voucher_price`,`discounts`, `dist_user_id`) VALUES ($sell_userid,$order_id,'$buid','0','$re[name]','$re[area] $re[address]','$re[tel]','$re[mobile]','$product_price','$logistics_type','$logistics_price',1,'$msg','$time','$time','$invoice_title','$vou_price','$discounts', '$dist_user_id')";
					$db->query($sql);
					
                                        
					foreach($val['prolist'] as $key=>$val)
					{    
						$val['spec_id'] = $val['spec_id']?$val['spec_id']:"0"; 
						$sql = "INSERT INTO ".ORPRO." (`order_id`,`buyer_id`,`pid`,`pcatid`,`name`,`pic`,`price`,`num`,`time`,`setmeal`,`is_tg`,`spec_name`,`spec_value`) 
						VALUES 
						($order_id,$buid,$val[product_id],$val[catid],'$val[pname]','".$val['pic']."','".$val['price']."','".$val['quantity']."','".time()."','$val[spec_id]','$val[is_tg]','$val[spec_name]','$val[setmealname]')"; 
						$db->query($sql);
						
						$sql="select detail from ".PRODETAIL." where proid='$val[product_id]'";
						$db->query($sql);
						$detail=$db->fetchField('detail');
			
						$detail = addslashes($detail);
				
						$sql = "insert into ".SNAPSHOT." (`order_id`,`product_id`,`spec_id`,`member_id`,`shop_id`,`catid`,`type`,`name`,`subhead`,`brand`,`price`,`freight`,`pic`,`uptime`,`detail`,`spec_name`,`spec_value`) values ('$order_id','$val[product_id]','$val[spec_id]','$sell_userid','$sell_userid','$val[catid]','$val[type]','$val[pname]','$val[subhead]','$val[brand]','$val[price]','0','$val[pic]','".time()."','$detail','$val[spec_name]','$val[setmealname]')";
						$db->query($sql);
					}
					if($value["giftlist"])
					{
						foreach($value["giftlist"] as $key=>$va)
						{
							$sql = "INSERT INTO ".ORPRO." (`order_id`,`buyer_id`,`pid`,`name`,`pic`,`price`,`num`,`time`,`is_gift`) 
							VALUES 
							($order_id,$buid,$va[pid],'$va[pname]','".$va['pic']."','".$va['price']."',1,'".time()."',1)"; 
							$db->query($sql);
						}
					}
					
					$post['action']='add';//填加流水
					$post['type']=2;//担保接口
					$post['seller_email'] = $sell_userid;//卖家账号
					$post['buyer_email'] = $buid;//卖家账号
					$post['order_id'] = $order_id;//外部订单号
					$post['price'] = $product_price*1 + $logistics_price*1;//订单总价，单价元
					$post['extra_param'] = '';//自定义参数，可存放任何内容
					$post['return_url'] = $config['weburl'].'/api/order.php?id='.$order_id;//返回地址
					$post['notify_url'] = $config['weburl'].'/api/order.php?id='.$order_id;//异步返回地址
					$post['name']="订单【".$order_id."】消费";

					$uprice += $post['price'];

					$res=pay_get_url($post,true);//跳转至订单生成页面
					if($res<0)
					{
						if($res==-2)
							msg('main.php?m=payment&s=admin_info','您的支付账户还没有开通');
						if($res==-1)
							msg("$config[weburl]/?m=product&s=confirm_order",'卖家没有开通支付功能，暂不能购买');	
					}
				}	
			}
		}

		// 插入到合并支付表
		$uorder = "U".date("Ymdhis",time()).rand(100,999); // 18位
		$inorder = substr($inorder, 0,-1);

		$sql = "insert into ".UORDER."  (`order_id`,`inorder`,`price`,`create_time`) values ('$uorder','$inorder','$uprice','".time()."')";
		$db->query($sql);

		$post['action']='add';//填加流水
		$post['type']=2;//担保接口
		$post['seller_email'] = "admin@systerm.com";//卖家账号
		$post['buyer_email'] = $buid;//卖家账号
		$post['order_id'] = $uorder;//外部订单号
		$post['price'] = $uprice;//订单总价，单价元
		$post['extra_param'] = $inorder;//自定义参数，可存放任何内容
		$post['return_url'] = $config['weburl'].'/api/order.php?id='.$uorder;//返回地址
		$post['notify_url'] = $config['weburl'].'/api/order.php?id='.$uorder;//异步返回地址
		$post['name']="订单【".$order_id."】合并消费";
		$res=pay_get_url($post,true);//跳转至订单生成页面			

		//------------清空购物车
		$cart -> clear_cart($_SESSION['product_id']);
		unset($_SESSION['product_id']);
		unset($_SESSION['dist_user_id']);
		//msg($config['weburl']."/main.php?cg_u_type=1&m=product&s=admin_buyorder");//订单提交成功
		msg($config['pay_url']."/?m=payment&s=pay&tradeNo=".$uorder."&temp=".$config['temp']);//直接跳转到支付页面进行支付选择
		die;
	}
}
//=================================================
$tpl->assign("config",$config);
$tpl->assign("cart",$cartlist['cart']);
$tpl->assign("sumprice",$cartlist['sumprice']);
include_once("footer.php");
if($config['temp']=='wap'||$config['temp']=='wap_app')
{
	$out=tplfetch("confirm_order.htm",$flag);
}
else
{	
	if($_GET['ajax']=='ajax')
	{
		$url = $_SERVER['HTTP_REFERER']?base64_encode($_SERVER['HTTP_REFERER']):1;
		$tpl -> assign("url",$url);
		echo $out=tplfetch("order_ajax.htm",$flag,true);die;
	}
	else
		$out=tplfetch("confirm_order.htm",$flag,true);
}
?>