<?php
/*
* Dsc : 虚拟商品不加入购物车直接跳转到订单确认页面，直接下单
*
* Auth : Bruce zhang
*
* Date : 2014-12-05
*/
if(empty($buid))
	msg($config['weburl']."/login.php");
else
{
	include_once("module/member/includes/plugin_orderadder_class.php");	
	
	// 第三步 提交订单，直接跳转支付页面
	if(isset($_POST['act']) && $_POST['act'] == "step3")
	{
		if(!$_POST['buyer_phone'])
		{
			die("why are you here ?");
		}

		$id =  $_POST['id'] * 1;
		$sid = $_POST['sid'] * 1;
		$_POST['nums'] = $_POST['nums'] ? $_POST['nums']*1 : 1;
		$is_virtual = 1;

		if(!$sid)
		{
			// 获取商家信息 价格信息
			$sql = "select company,member_id as seller_id,price,a.*,maxbuy from ".PRODUCT." a left join ".SHOP." b on a.member_id = b.userid left join ".PROVIR." c on c.pid = a.id where a.id = ".$id;
		}
		else
		{
			$sql = "select company,b.member_id as seller_id,a.price,b.*,setmeal,maxbuy,a.stock,a.spec_name,a.setmeal from ".SETMEAL." a left join  ".PRODUCT." b on a.pid = b.id  left join ".SHOP." c on c.userid = b.member_id left join ".PROVIR." d on d.pid = b.id where a.id=".$sid;
		}

		$db -> query($sql);
		$re = $db -> fetchRow();
		$org_price = $re['price'];

		// 获取会员折扣信息
		$sql = "select discounts from ".MECART." where `shop_id` = (select `member_id` from ".PRODUCT." where id = '$id' ) and `blind_member_id` = $buid order by discounts desc limit 1";
        $db->query($sql);
        if($db -> num_rows())
        {
            $discounts = $db -> fetchField("discounts");
        }
        if($discounts > 0){$re['price'] = ($re['price'] * $discounts)/10;}

        $sell_userid = $re['seller_id'];
		$is_virtual = 1;
		$logistics_type = 0;//物流方式
		$logistics_price = 0;//物流价格
		$invoice_title = "";
		$uproduct_price = ($re['price'] * 100 * $_POST['nums'] )/100;//购物总价
		
		$msg = htmlspecialchars($_POST['msg_'.$sell_userid]);
		$time = time();
		$vou_price = 0; //优惠价格
        $buyer_phone = $_POST['buyer_phone'];
        $inorder = "";
        // 每一张电影票或其它 生成一个订单
        for($i=0;$i<$_POST['nums'];$i++)
        {
        	$order_id = time().rand(100,999).$i;
        	$product_price = $re['price'];
        	$inorder .= $order_id.",";
        	$nums = 1;

        	 /***生成买家订单****/
			$sql = "INSERT INTO ".ORDER." (`userid`,`order_id`,`buyer_id`,`seller_id`,`consignee`,`consignee_address`,`consignee_tel`,`consignee_mobile`,`product_price`,`logistics_type`,`logistics_price`,`status`,`des`,`create_time`,`uptime`,`invoice_title`,`voucher_price`,`discounts`,`is_virtual`) VALUES 
			($buid,'$order_id','0',$sell_userid,'','','','$buyer_phone','$product_price','$logistics_type','$logistics_price',1,'$msg','$time','$time','$invoice_title','$vou_price','$discounts',1)"; 
			$db->query($sql);
			
			/***生成卖家订单****/
			$sql = "INSERT INTO ".ORDER."	(`userid`,`order_id`,`buyer_id`,`seller_id`,`consignee`,`consignee_address`,`consignee_tel`,`consignee_mobile`,`product_price`,`logistics_type`,`logistics_price`,`status`,`des`,`create_time`,`uptime`,`invoice_title`,`voucher_price`,`discounts`,`is_virtual`) VALUES 
			($sell_userid,'$order_id','$buid','0','','','','$buyer_phone','$product_price','$logistics_type','$logistics_price',1,'$msg','$time','$time','$invoice_title','$vou_price','$discounts',1)"; 
			$db->query($sql);

			$sql = "INSERT INTO ".ORPRO." (`order_id`,`buyer_id`,`pid`,`pcatid`,`name`,`pic`,`price`,`num`,`time`,`setmeal`,`is_tg`,`spec_name`,`spec_value`) 
			VALUES 
			('$order_id',$buid,'$re[id]',$re[catid],'$re[name]','".$re['pic']."','".$org_price."','".$nums."','".time()."','$sid',0,'$re[spec_name]','$re[setmeal]')"; 
			$db->query($sql);

			$sql="select detail from ".PRODETAIL." where proid='$id'";
			$db->query($sql);
			$detail=$db->fetchField('detail');

			$detail = addslashes($detail);

			$sql = "insert into ".SNAPSHOT." (`order_id`,`product_id`,`spec_id`,`member_id`,`shop_id`,`catid`,`type`,`name`,`subhead`,`brand`,`price`,`freight`,`pic`,`uptime`,`detail`,`spec_name`,`spec_value`) values 
			('$order_id','$id','$sid','$sell_userid','$sell_userid','$re[catid]','0','$re[name]','$re[subhead]','$re[brand]','$org_price','0','$re[pic]','".time()."','$detail','$re[spec_name]','$re[setmeal]')";
			$db->query($sql);

			$post['action']='add';//填加流水
			$post['type']=2;//担保接口
			$post['seller_email'] = $sell_userid;//卖家账号
			$post['buyer_email'] = $buid;//卖家账号
			$post['order_id'] = $order_id;//外部订单号
			$post['price'] = $product_price*1;//订单总价，单价元
			$post['extra_param'] = '';//自定义参数，可存放任何内容
			$post['return_url'] = $config['weburl'].'/api/order.php?id='.$order_id;//返回地址
			$post['notify_url'] = $config['weburl'].'/api/order.php?id='.$order_id;//异步返回地址
			$post['name']="虚拟订单【".$order_id."】消费";
			$res=pay_get_url($post,true);//跳转至订单生成页面
			if($res<0)
			{
				if($res==-2)
					msg('main.php?m=payment&s=admin_info','您的支付账户还没有开通');
				if($res==-1)
					msg("$config[weburl]/?m=product&s=confirm_order",'卖家没有开通支付功能，暂不能购买');	
			}

        }

        $inorder = substr($inorder, 0, -1);

        $uorder = "U".date("Ymdhis",time()).rand(100,999); // 18位

		$sql = "insert into ".UORDER."  (`order_id`,`inorder`,`price`,`create_time`) values ('$uorder','$inorder','$uprice','".time()."')";
		$db->query($sql);

		$post['action']='add';//填加流水
		$post['type']=2;//担保接口
		$post['seller_email'] = "admin@systerm.com";//卖家账号
		$post['buyer_email'] = $buid;//卖家账号
		$post['order_id'] = $uorder;//外部订单号
		$post['price'] = $uproduct_price;//订单总价，单价元
		$post['extra_param'] = $inorder;//自定义参数，可存放任何内容
		$post['return_url'] = $config['weburl'].'/api/order.php?id='.$uorder;//返回地址
		$post['notify_url'] = $config['weburl'].'/api/order.php?id='.$uorder;//异步返回地址
		$post['name']="订单【".$order_id."】合并消费";
		$res=pay_get_url($post,true);//跳转至订单生成页面


		msg($config['pay_url']."/?m=payment&s=pay&tradeNo=".$uorder);//直接跳转到支付页面进行支付选择
		die;
	}

	// 第一步 确定购买数量
	if(!empty($_POST['id'])&&!empty($_POST['nums']) && $_POST['act'] != "step2" && $_POST['act'] != "step3")
	{
		if(isset($_POST['is_virtual']) && $_POST['is_virtual'] == 1)
		{
			$id = $_SESSION['pid'] = $_POST['id'] * 1;
			$sid = $_POST['sid'] * 1;
			$is_virtual = 1;

			if(!$sid)
			{
				// 获取商家信息 价格信息
				$sql = "select company,member_id as seller_id,price,a.*,maxbuy from ".PRODUCT." a left join ".SHOP." b on a.member_id = b.userid left join ".PROVIR." c on c.pid = a.id where a.id = ".$id;
			}
			else
			{
				$sql = "select company,b.member_id as seller_id,a.price,b.*,setmeal,maxbuy,a.stock from ".SETMEAL." a left join  ".PRODUCT." b on a.pid = b.id  left join ".SHOP." c on c.userid = b.member_id left join ".PROVIR." d on d.pid = b.id where a.id=".$sid;
			}

			$db -> query($sql);
			$re = $db -> fetchRow();

			// 获取会员折扣信息
			$sql = "select discounts from ".MECART." where `shop_id` = (select `member_id` from ".PRODUCT." where id = '$id' ) and `blind_member_id` = $buid order by discounts desc limit 1";
            $db->query($sql);
            if($db -> num_rows())
            {
                $discounts = $db -> fetchField("discounts");
                $tpl->assign("discounts",$discounts);
            }
            if($discounts > 0){$re['price'] = ($re['price'] * $discounts)/10;}
		}
	}
	$tpl->assign("config",$config);
	include_once("footer.php");

	// 第二步 确定订单信息
	if(isset($_POST['act']) && $_POST['act'] == "step2")
	{
			$id =  $_POST['id'] * 1;
			$sid = $_POST['sid'] * 1;
			$is_virtual = 1;

			if(!$sid)
			{
				// 获取商家信息 价格信息
				$sql = "select company,member_id as seller_id,price,a.*,maxbuy from ".PRODUCT." a left join ".SHOP." b on a.member_id = b.userid left join ".PROVIR." c on c.pid = a.id where a.id = ".$id;
			}
			else
			{
				$sql = "select company,b.member_id as seller_id,a.price,b.*,setmeal,maxbuy,a.stock from ".SETMEAL." a left join  ".PRODUCT." b on a.pid = b.id  left join ".SHOP." c on c.userid = b.member_id left join ".PROVIR." d on d.pid = b.id where a.id=".$sid;
			}

			$db -> query($sql);
			$re = $db -> fetchRow();

			// 获取会员折扣信息
			$sql = "select discounts from ".MECART." where `shop_id` = (select `member_id` from ".PRODUCT." where id = '$id' ) and `blind_member_id` = $buid order by discounts desc limit 1";
            $db->query($sql);
            if($db -> num_rows())
            {
                $discounts = $db -> fetchField("discounts");
                $tpl->assign("discounts",$discounts);
            }
            if($discounts > 0){$re['price'] = ($re['price'] * $discounts)/10;}

            // 获取手机号码
            $sql = "SELECT `mobile` from ".MEMBER." where `userid` =".$buid;
            $db -> query($sql);
            $mobile = $db -> fetchField("mobile");

            $tpl->assign("mobile",$mobile);
            $tpl->assign("re",$re);
			$out=tplfetch("confirm_virtual_order_step2.htm",$flag,true);
	}
	else
	{
		$tpl->assign("re",$re);
		$out=tplfetch("confirm_virtual_order_step1.htm",$flag,true);
	}
}
?>