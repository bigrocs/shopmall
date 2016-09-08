<?php
// 0 关闭 1 退货中 2卖家同意发送退货地址 3买家发货 卖家确认 4卖家拒绝退货 5退货成功

class refund
{
	var $db;
	var $tpl;
	var $page;
	
	function refund()
	{
		global $db;
		
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function order_detail($order_id,$id,$type='buyer')
	{
		global $buid;
		if($type=='buyer')
		{
			$str = " and a.userid = '$buid'";	
			$str1 = " and member_id = '$buid'";	
		}
		else
		{
			$str = " and a.seller_id = '$buid'";
			$str1 = " and seller_id = '$buid'";
		}
		
		$sql = "select		a.userid,a.order_id,a.buyer_id,a.seller_id,a.status,a.product_price, a.voucher_price, b.price,b.num,b.name,b.pic,b.id as pid,user,company from ".ORDER." a left join ".ORPRO." b on a.order_id = b.order_id left join ".SHOP." c on a.seller_id = c.userid where a.order_id = '$order_id' $str and b.id = '$id'";
		$this -> db -> query($sql);
        $re = $this -> db -> fetchRow();
		
		$sql = "select * , status as refund_status from ".REFUND." where order_id = '$order_id' $str1 and status > 0 and product_id = '$id'";
		$this -> db -> query($sql);
        $de = $this -> db -> fetchRow();

		unset($de['status']);
		if($de) @$re = array_merge($re,$de);

		$re['refund_price'] = ($re['product_price'] - $re['voucher_price']) * ($re['price'] * $re['num']) / $re['product_price'];

		return $re;
	}	
	
	function refund_detail($refund_id,$type='buyer')
	{
		global $buid;
		if($type=='buyer')
		{
			$str = " and a.member_id = '$buid'";	
		}
		else
		{
			$str = " and a.seller_id = '$buid'";
		}
		$sql = "select a.*, a.status as refund_status,d.status as order_status , d.product_price, b.status as product_status,b.name,b.pic,b.price,b.num,b.pid,user,company from ".REFUND." a left join ".ORPRO." b on a.product_id = b.id left join ".ORDER." d on b.order_id = d.order_id left join ".SHOP." c on a.seller_id = c.userid where a.refund_id = '$refund_id' $str";
		$this -> db -> query($sql);
        $re = $this -> db -> fetchRow();
		return $re;
	}
		
	function operate_refund($type)
	{
		global $buid;	
		$re = $this -> order_detail($_POST['order_id'],$_POST['id']);
		
		if($type=='add')
		{
			$T = time();
			$R = "R".$T;
			
			if($re['status'] == 2)
			{
				$goods_status = '0';
			}
			elseif($re['status'] == 4)
			{
				$goods_status = '1';
			}
			else
			{
				$goods_status = $_POST['goods_status'] ? $_POST['goods_status'] :"0";
			}
			
			$types = $re['status'] == 2 ? "1" : "2";
			
			$sql="insert into ".REFUND." (order_id,refund_id,product_id,seller_id,member_id,refund_price,create_time,reason,status,goods_status,type) values ('$_POST[order_id]','".$R."','$_POST[id]','$_POST[seller_id]','$buid','$_POST[price]','".time()."','$_POST[reason]','1','$goods_status','$types')";
			$this->db->query($sql);
						
			$type_name = $types == 2 ? "退货退款":"仅退款"; 
			$goods_status_name = $goods_status == 1 ? "买家已收到货":"买家未收到货"; 
			
			$msg = "买家（".$_COOKIE['USER']."）于 ".date("Y-m-d H:i:s",$T)." 创建了退款申请。买家要求：".$type_name."，货物状态：".$goods_status_name."，退款金额：$_POST[price]元，退款原因：$_POST[reason]";
			
		}
		else if($type=='edit')
		{
			$sql="update ".REFUND." set  status='1',refund_price='$_POST[price]',reason='$_POST[reason]' where order_id = '$re[order_id]' and product_id = '$re[pid]' and member_id = '$buid' ";
			$this->db->query($sql);	
			
			$this->add_talk($de['refund_id'],$de['order_id'],$_POST['msg'],$_POST['pic']);
			$R = $re['refund_id'];
			
			$msg = "买家（".$_COOKIE['USER']."）于 ".date("Y-m-d H:i:s")." 修改了退款申请。";
		}
		$pic = $_POST['pic'];
		$this->add_talk($R,$re['order_id'],$msg,$pic);
		return $R;
	}
	
	function agree_refund($refund_id)
	{
		global $config;
		$sql = "select b.logistics_price,a.refund_price,b.order_id,b.status,a.product_id,b.seller_id,b.userid, b.dist_user_id from ".REFUND." a left join ".ORDER." b on a.order_id = b.order_id where a.refund_id = '$refund_id' and a.status > 0 ";
		$this->db->query($sql);	
		$re = $this -> db -> fetchRow();


		$sql = "select * from ".ORPRO." where status != '5' and order_id = '$re[order_id]'";
		$this->db->query($sql);	
		$count = $this -> db -> num_rows();
				
		$post['action'] = 'update';
		$post['seller_email'] = $re['seller_id']; //卖家账号
		$post['buyer_email'] = $re['userid']; 	  //买家账号
		$post['order_id'] = $re['order_id'];      //订单号
		$post['price'] = $re['refund_price'];	  //退款金额
		
		if($re['status'] == 4) 					  //确定收货
		{
			$post['statu'] = 5;
		}
		else									  //未收货
		{
			if($count==1)
			{
				if($re['status'] == 2)
				{
					$post['price'] = $post['price'] + $re['logistics_price'];
				}
				$post['statu'] = 6;
			}
			else
			{
				$post['statu'] = 7;
			}
		}
		$res=pay_get_url($post,true);
		if(!empty($res))
		{
			$res=json_decode($res);
			if($res->statu=='true'&&$res->auth==md5($config['authkey']))
			{
				//修改退货状态
				$sql="update ".REFUND." set status = '5' where refund_id = '$refund_id'";
				$this->db->query($sql);
				//修改商品状态
				$sql="update ".ORPRO." set status = '5' where id = '$re[product_id]'";
				$this->db->query($sql);
				//修改订单
				if($count == 1 && $re['status'] != 4)
				{
					$sql="update ".ORDER." set status = '0' where order_id = '$re[order_id]'";
					$this->db->query($sql);
				}

				//退还分销分佣佣金
				if ($re['dist_user_id'])
				{
					//$re[product_id], 其实是商品订单id
					$sql = "select * from ".ORPRO." where id = '$re[product_id]'";
					$this->db->query($sql);
					$order_product_row = $this -> db -> fetchRow();

					$PluginManager = Yf_Plugin_Manager::getInstance();
					$PluginManager->trigger('confirm_return_product', $refund_id, $re['order_id'], $order_product_row['pid'], $re['dist_user_id']);
				}
			}
		}
	}
	
	function close_refund($refund_id)
	{
		$close_reason = '因您取消退款申请，退款已关闭，交易将正常进行。';
		$sql = "select b.order_id,b.status,a.product_id from ".REFUND." a left join ".ORDER." b on a.order_id = b.order_id where a.refund_id = '$refund_id' and a.status > 0 ";
		$this->db->query($sql);	
		$re = $this -> db -> fetchRow();
		
		$sql="update ".ORPRO." set status = '".($re['status']-1)."' where id = '$re[product_id]'";
		$this->db->query($sql);	
		
		$sql="update ".REFUND." set close_reason = '$close_reason',status = '0' where refund_id = '$refund_id'";
		$this->db->query($sql);	
	}
	
	function refuse($refund_id,$refuse_reason)
	{
		$sql="update ".REFUND." set refuse_reason='$refuse_reason' , status = '4' where refund_id = '$refund_id'";
		$this->db->query($sql);	
	}
	
	function add_talk($refund_id,$order_id,$msg,$pic)
	{
		global $buid;	
		$msg = $msg;
		$pic = $_POST['pic'] ? $_POST['pic'] : "";
		$sql="insert into ".TALK." (refund_id,order_id,member_id,type,content,pic,create_time) values ('$refund_id','$order_id','$buid','1','$msg','$pic','".time()."')";
		$this->db->query($sql);
	}
	
	function get_talk()
	{
		$sql="select * from ".TALK." a left join ".MEMBER." b on a.member_id = b.userid where refund_id ='$_GET[id]' order by create_time desc ";
		$this->db->query($sql);
		$de=$this->db->getRows();
		return $de;
	}
}
?>
