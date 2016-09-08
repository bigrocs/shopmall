<?php

/*
* @Auth:bruce
* @Uptime:2014-11-26
* @Desc:卖家在订单状态改变过，在期页面上还可以修改价格
*/

//-1删除的订单
//0取消的订单
//1新订单，等待买家付款
//2买家已付款，等待卖家发货
//3卖家已发货，等待买家确认收货
//4订单完成
//5退货中的订单
//6退货成功;

class order
{
	var $db;
	var $tpl;
	var $page;
	
	function order()
	{
		global $db;
		
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	/**
	 * 修改订单状态
	 * @param $order_id 订单ID
	 * @param $trade_no 外部订单ID
	 * @param $status 状态 默认值 1
	 * return 结果字符串
	 */
	function set_order_status($order_id,$out_trade_no,$status = '1')
	{
		global $buid;
		$sql="select status from ".ORDER." where order_id='$order_id' and userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchField('status');
		if($status!=$re)
		{
			$sql="update ".ORDER." set out_trade_no = '$out_trade_no', status = '$status', uptime = ".time()." where order_id = '$order_id'";
			$this->db->query($sql);
		}
	}
	
	/**
	 * 买家订单状态
	 * @param $status 状态 默认值 NULL
	 * return 结果字符串
	 */
	function get_order_status($status=NULL)
	{	
		global $config;
		include($config['webroot']."/lang/cn/company_type_config.php");
		if($status!='')
			return $order_status[$status];
		else
			return $order_status;
	}
	
	/**
	 * 买家订单商品状态
	 * @param $status 状态 默认值 NULL
	 * return 结果字符串
	 */
	function get_order_product_status($status=NULL)
	{	
		global $config;
		include($config['webroot']."/lang/cn/company_type_config.php");
		if($status!='')
			return $order_product_status[$status];
		else
			return $order_product_status;
	}
	
	/**
	 * 订单商品
	 * @param $order_id 状态
	 * return array
	 */
	function get_order_product($order_id, $status=null)
	{
		global $buid;

		//分销问题
		if (null != $status)
		{
			if (4 == $status)
			{
				$sql = "select o.*, p.* from ".ORPRO." o left join " . DISTRIBUTION_PRODUCT_ORDER . " p  ON o.order_id = p.order_id AND o.pid = p.product_id   where o.order_id = '$order_id'";
			}
			else
			{
				$sql = "select o.*, p.* from ".ORPRO." o left join " . DISTRIBUTION_PRODUCT . " p  ON o.pid = p.product_id where o.order_id = '$order_id'";
			}
		}
		else
		{
			$sql = "select * from ".ORPRO." where order_id = '$order_id'";
		}

		$this -> db -> query($sql);
		$re = $this -> db -> getRows();
		foreach($re as $key => $val)
		{ 	
			$spec_value = $val['spec_value'] ? explode(',',$val['spec_value']) : "";
			$spec_name = $val['spec_name'] ? explode(',',$val['spec_name']) : "";
			
			if($spec_name && $spec_value)
			{
				foreach($spec_value as $k => $v)
				{
					$re[$key]['spec'][] = $spec_name[$k].":".$v;	
				}	
			}
			$re[$key]['product_status'] = $this -> get_order_product_status($val['status']);

		}
		return $re;
	}
	
	/**
	 * 买家订单列表
	 * @param $status 状态 默认值 NULL
	 * return array
	 */
	function buyorder($status = NULL,$flag = 0)
	{
		global $buid;

		if($_GET['key'])
		{
			$keys = trim($_GET['key']);
			$table = " left join ".ORPRO." c on a.order_id = c.order_id";
			//$str .= " and (c.name like '%$keys%' or a.order_id like '%$keys%')";            订单搜索响应时间太长
			$str .= " and a.order_id like '%$keys%'";
		}
		if(isset($_GET['type']) && $_GET['type'] == 1)
		{
			$table = " left join ".ORPRO." c on a.order_id = c.order_id";
			$str .= " and c.is_tg = 'true' ";
			$str .= " and `is_virtual` =".$flag; 
		}
		if(isset($_GET['type']) && $_GET['type'] == 2)
		{
			$flag = 1;
			// 开启虚拟订单
			$str .= " and `is_virtual` =".$flag; 
		}
		
		if($_GET['zt'])
		{
			$zt = $_GET['zt']*1-1;
			$str .= " and a.status = '$zt'"; 
		}
		else
		{
			if($status!=NULL && $status >= -1 && $status<=6)
				$str .= " and a.status = '$status'"; 
			else
				$str .= " and a.status >= 0 ";		
		}
		if($_GET['stime'])
		{
			$stime = strtotime(trim($_GET['stime']));
			$str .= " and a.create_time >= '$stime'"; 
		}
		if($_GET['etime'])
		{
			$etime = strtotime(trim($_GET['etime']));
			$str .= " and a.create_time <= '$etime'";
		}
		if($_GET['seller'])
		{
			$seller = trim($_GET['seller']);
			$str .= " and b.company like '%$seller%' ";
		}
		if($_GET['rate'])
		{
			switch($_GET['rate'])
			{
				case "1":
				{
					$str .= " and status = '4' and buyer_comment='0' and seller_comment = '0'"; 
					break;
				}
				case "2":
				{
					$str .= " and buyer_comment='1' and seller_comment ='0' "; 
					break;
				}
				case "3":
				{
					$str .= " and buyer_comment='0' and seller_comment ='1' "; 
					break;
				}
				case "4":
				{
					$str .= " and buyer_comment='1' and seller_comment ='1' "; 
					break;
				}
				default:
				{
					break;
				}
			}
		}
		if($_GET['sh'])
		{
			
		}
		
		
		
		$sql = "select a.*,b.company from ".ORDER." a left join ".SHOP." b on a.seller_id=b.userid $table where a.userid = '".$buid."' $str and seller_id != '' order by  FROM_UNIXTIME(a.`create_time`, '%Y-%m-%d') desc,field(a.status,'1','3','2','4','5','6','0'),buyer_comment,seller_comment,a.id desc";

		//=============================
	  	$page = new Page;
		$page -> listRows = 10;
		if (!$page -> __get('totalRows')){
			$this -> db -> query($sql);
			$page -> totalRows = $this -> db -> num_rows();
		}
        $sql .= "  limit " . $page -> firstRow . "," . $page -> listRows;
		//==============================
		$this -> db -> query($sql);
		$ore = $this -> db -> getRows();

		foreach($ore as $k)
		{
			if($k['status']=='3')
			{
				$time = $k['deliver_time'] + $k['time_expand']*86400-1 - time();
				$d = floor($time / 86400);   
				$h = floor(($time % 86400) / 3600);
				$k['time_expand']= $d."天".$h."时";
			}

			// 开启虚拟订单
			if($k['is_virtual'])
			{
				$sql = "select `end_time`,`recate`,`start_time` from ".PROVIR." a left join ".ORPRO." b on a.pid = b.pid left join ".PRODUCT." c on b.pid=c.id  where b.order_id =".$k['order_id'];
				$this -> db -> query($sql);
				$me = $this -> db -> fetchRow();
				$k['recate'] = $me['recate'];
				$k['end_time'] = $me['end_time'];
			}

			$product = $this -> get_order_product($k['order_id']);
			if($keys)
			{
				foreach($product as $key=>$val)
				{
					$product[$key]['name'] = str_replace($keys,"<font color='red'>$keys</font>",$val['name']);	
				}
			}
			$k['product'] = $product;
			$k['statu_text'] = $this -> get_order_status($k['status']);
			$list[] = $k;
		}
		$re["list"] = $list;
		$re["page"] = $page -> prompt();
		return $re;
	}
	
	/**
	 * 卖家订单列表
	 * @param $status 状态 默认值 NULL
	 * return array
	 */
	function sellorder($status='',$flag = 0, $dist_user_id=0)
	{
		global $buid;
		
		if($status!='' && $status >= -1 && $status<=6)
			$str = " and a.status = '$status'"; 
		else
			$str = " and a.status >= 0 ";	

		if($_GET['key'])
		{
			$keys = trim($_GET['key']);
			if($flag)
			{
				$sql = "select order_id from ".MSGCORD." where `serial` = '".$_GET['key']."' ";
				$this -> db -> query($sql);
				$order_id = $this -> db -> fetchField("order_id");

				$str .= " and a.order_id ='$order_id' ";
			}
			else
			{
				$str .= " and a.order_id like '%$keys%' ";
			}
		}
		/*
		//$dist_user_id
		if ($dist_user_id)
		{
			//$sql="select a.*,b.company,b.user from ".ORDER." a left join ".SHOP." b on a.buyer_id = b.userid where a.dist_user_id = '".$dist_user_id."' and buyer_id != '' $str order by a.id desc";

			//修正读取分佣价格数据
			if (4 == $status)
			{
				$sql="select a.*,b.company,b.user, p.commission_product_price_0, p.commission_product_price_1, p.commission_product_price_2 from ".ORDER." a left join ".SHOP." b  on a.buyer_id = b.userid  left join " . DISTRIBUTION_PRODUCT_ORDER . " p  ON a.order_id = p.order_id where a.dist_user_id = '".$dist_user_id."' and a.buyer_id != '' $str order by a.id desc";
			}
			else
			{
				$sql="select a.*,b.company,b.user, p.commission_product_price_0, p.commission_product_price_1, p.commission_product_price_2  from ".ORDER." a left join ".SHOP." b  on a.buyer_id = b.userid left join " . ORPRO . " c  ON a.order_id = c.order_id left join " . DISTRIBUTION_PRODUCT . " p  ON c.pid = p.product_id where a.dist_user_id = '".$dist_user_id."' and a.buyer_id != '' $str order by a.id desc";
			}
		}
		else
		{
			// 开启虚拟商品
			$str .= " and a.is_virtual = ".$flag;

			$sql="select a.*,b.company,b.user from ".ORDER." a left join ".SHOP." b on a.buyer_id = b.userid where a.userid = '".$buid."' and buyer_id != '' $str order by a.id desc";
		}
		*/

		//分销商读取
		$str .= " and a.is_virtual = ".$flag;

		if ($dist_user_id)
		{
			if(!$flag)
				$sql="select a.*,b.company,b.user,m.user from ".ORDER." a left join ".SHOP." b on a.buyer_id = b.userid left join ".MEMBER." m on a.buyer_id=m.userid  where a.dist_user_id = '".$dist_user_id."' and buyer_id != '' $str order by a.id desc";
			else
				$sql="select a.*,b.company,b.user,m.user from ".ORDER." a left join ".SHOP." b on a.buyer_id = b.userid left join ".MEMBER." m on a.buyer_id=m.userid  where a.dist_user_id = '".$dist_user_id."' and buyer_id != '' $str order by a.id desc";
		}
		else
		{
			if(!$flag)
				$sql="select a.*,b.company,m.user from ".ORDER." a left join ".SHOP." b on a.buyer_id = b.userid left join ".MEMBER." m on a.buyer_id=m.userid where a.userid = '".$buid."' and buyer_id != '' $str order by a.id desc";
			else
				$sql="select a.*,b.company,m.user from ".ORDER." a left join ".SHOP." b on a.buyer_id = b.userid  left join ".MEMBER." m on a.buyer_id=m.userid  where a.userid = '".$buid."' and buyer_id != '' $str order by a.id desc";
		}



		//=============================
	  	$page = new Page;
		$page -> listRows = 10;
		if (!$page -> __get('totalRows'))
		{
			$this -> db -> query($sql);
			$page -> totalRows = $this -> db -> num_rows();
		}
        $sql .= "  limit ".$page -> firstRow.",".$page -> listRows;
		//=============================
		$this -> db -> query($sql);
		$ore = $this -> db -> getRows();
		foreach($ore as $k)
		{
			if($k['status']=='3')
			{
				$time = $k['deliver_time'] + $k['time_expand']*86400-1 - time();
				$d = floor($time / 86400);
				$h = floor(($time % 86400) / 3600);
				$k['time_expand']= $d."天".$h."时";
			}

			if ($k['dist_user_id'])
			{
				$k['product'] = $this -> get_order_product($k['order_id'], $k['status']);

				if (4 != $k['status'])
				{
					foreach ($k['product'] as $p)
					{
						$k['commission_product_price_0'] += $p['commission_product_price_0'] * $p['num'];
						$k['commission_product_price_1'] += $p['commission_product_price_1'] * $p['num'];
						$k['commission_product_price_2'] += $p['commission_product_price_2'] * $p['num'];
						$k['commission_product_price_plantform'] += $p['commission_product_price_plantform'] * $p['num'];
					}
				}
				else
				{
					foreach ($k['product'] as $p)
					{
						$k['commission_product_price_0'] += $p['commission_product_price_0'];
						$k['commission_product_price_1'] += $p['commission_product_price_1'];
						$k['commission_product_price_2'] += $p['commission_product_price_2'];
						$k['commission_product_price_plantform'] += $p['commission_product_price_plantform'];
						//break; //为订单汇总信息，不能累加
					}
				}
			}
			else
			{
				$k['product'] = $this -> get_order_product($k['order_id']);
			}

			$k['statu_text'] = $this -> get_order_status($k['status']);
			$re["list"][] = $k;
		}
		$re["page"] = $page->prompt();
		return $re;
	}
	
	/**
	 * 延长收货时间
	 * @param $order_id 订单ID
	 * @param $days 天数
	 * return 结果字符串
	 */
	function expand_time($order_id,$days)
	{
		global $buid;
		$days *= 1;
		$sql="select status from ".ORDER." where order_id='$order_id' and userid='$buid'";
		$this->db->query($sql);
		$status=$this->db->fetchField('status');
		if($status == '3')
		{
			$sql="update ".ORDER." set time_expand= time_expand + $days where order_id = '$order_id'";
			$this->db->query($sql);
		}
	}
	
	function orderdetail($id)
	{
		global $buid;

		if (isset($_REQUEST['dist_order']))
		{
			$sql = "select * from ".ORDER." where order_id = '$id' and dist_user_id = '$buid' AND buyer_id!=0";
		}
		else
		{
			$sql = "select * from ".ORDER." where order_id = '$id' and userid = '$buid'";
		}

		$this -> db -> query($sql);
        $re = $this -> db -> fetchRow();



		//分销商信息
		global $distribution_open_flag;

		if ($distribution_open_flag)
		{
			$re['product'] = $this -> get_order_product($id, $re['status']);

			if (4 != $re['status'])
			{
				foreach ($re['product'] as $p)
				{
					$re['commission_product_price_0'] += $p['commission_product_price_0'] * $p['num'];
					$re['commission_product_price_1'] += $p['commission_product_price_1'] * $p['num'];
					$re['commission_product_price_2'] += $p['commission_product_price_2'] * $p['num'];
					$re['commission_product_price_plantform'] += $p['commission_product_price_plantform'] * $p['num'];
				}
			}
			else
			{

				foreach ($re['product'] as $p)
				{
					$re['commission_product_price_0'] += $p['commission_product_price_0'];
					$re['commission_product_price_1'] += $p['commission_product_price_1'];
					$re['commission_product_price_2'] += $p['commission_product_price_2'];
					$re['commission_product_price_plantform'] += $p['commission_product_price_plantform'];
					//break; //为订单汇总信息，不能累加
				}
			}

		}
		else
		{
			$re['product'] = $this -> get_order_product($id);
		}


		$re['statu_text'] = $this -> get_order_status($re['status']);

		// 开启虚拟商品
		if($re['is_virtual'])
		{
			// 获取兑换序列号
			$sql = "select `serial` from ".MSGCORD." where `order_id` = '$id' limit 1 ";
			$this->db->query($sql);
			$re['serial'] =  $this -> db -> fetchField("serial");

			// 获取兑换码的有效期
			$sql = "select `end_time`,`recate`,`start_time` from ".PROVIR." a left join ".ORPRO." b on a.pid = b.pid left join ".PRODUCT." c on b.pid=c.id  where b.order_id =".$id;
			$this -> db -> query($sql);
			$me = $this -> db -> fetchRow();

			$re['end_time'] = $me['end_time'];
			$re['recate'] = $me['recate'];
			$re['start_time'] = $me['start_time'];
		}

		if($re['seller_id'])
		{
			$sql="select * from ".SHOP." where userid='$re[seller_id]'";
			$this->db->query($sql);
			$re['sellerinfo']=$this->db->fetchRow();
		}
		
		if($re['buyer_id'])
		{
			$sql="select * from ".MEMBER." where userid='$re[buyer_id]'";
			$this->db->query($sql);
			$re['buyerinfo']=$this->db->fetchRow();
		}

		return $re;
	}
		
	function get_addr()
	{	
		global $buid;
		$sql="select * from  ".SHIPPINGADDR." where `userid`='$buid'";
		$this->db->query($sql);
		return $this->db->getRows($sql);
	}
	
	function get_fastmail()
	{	
		$sql="select * from ".FASTMAIL." where status = '1' order by id";
		$this->db->query($sql);
	    return $this->db->getRows();
	}
	
	function set_order_product_statu($order_id,$status,$product_id="")
	{
		$status = $status -1;
		if($product_id)
		{
			$str = " and id = '$product_id' ";	
		}
	
		$sql = "select id,status from ".ORPRO." where order_id = '$order_id' $str";
		$this->db->query($sql);
		$re = $this->db->getRows();
		foreach($re as $val)
		{
			if($val['status'] != 5)
			{
				$sql="update ".ORPRO." set status = '$status' where id='$val[id]'";
				$this->db->query($sql);
			}
		}
		switch ($status)
		{
			case "3":
			{
				$close_reason = '因您确认收货，退款关闭。';
				break;
			}
			case "2":
			{
				$close_reason = '因卖家发货，导致退款关闭，如仍需退款，您可以重新发起申请。';
				break;
			}
			default:
			{	
				$close_reason = '';
				break;
			}
		}
		if($close_reason)
		{
			$sql="update ".REFUND." set close_reason = '$close_reason',status = '0' where order_id = '$order_id' and status ='1' and status ='4' ";
			$this->db->query($sql);	
		}
	}
	
	function set_order_statu($oid="",$status="",$member_id="")
	{
		global $buid,$config;
		$buid = $member_id ? $member_id : $buid;
		if($status==0)
		{
			$sql="select seller_id,status,product_price,logistics_price from ".ORDER." where order_id='$oid' and userid='$buid'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			/*if($de['status']>=2)
			{
				include_once("module/member/includes/plugin_member_class.php");
				$member = new member();				
				$member->add_points(($de['product_price']+$de['logistics_price'])*-1,'3',$oid,$buid);
			}*/
			$post['action']='update';
			$post['seller_email']=$de['seller_id'];
			$post['buyer_email']=$buid;//卖家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=0;//取消的订单
			$res=pay_get_url($post,true);
		}
		if($status==3)
		{
			$sql="select buyer_id from ".ORDER." where order_id='$oid' and userid='$buid' and buyer_id!=''";
			$this->db->query($sql);
			//===========发货
			$post['action']='update';
			$post['buyer_email']=$this->db->fetchField('buyer_id');
			$post['seller_email']=$buid;//买家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=3;//
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		if($status==4)
		{	
			//===========成功，反回结果给支付中心。
			$sql = "select is_virtual from  ".ORDER." where  order_id='$oid' limit 1";
			$this->db->query($sql);
			$is_virtual = $this -> db -> fetchField("is_virtual");
			if($is_virtual)
			{
				$str = " `seller_id` = '".$buid."'";
			}
			else
			{
				$str = " `userid` = '".$buid."'";
			}
			$sql="select seller_id,status,product_price,logistics_price,is_virtual, order_id, dist_user_id from ".ORDER." where order_id='$oid' and $str";
			$this->db->query($sql);
			$de=$this->db->fetchRow();

			if ($de['dist_user_id'])
			{
				global $distribution;
				$commission_row = $distribution->getProductOrderCommission($oid, $de);

				$commission_str = array_sum($commission_row);

				$post['dist_user_id']   = $de['dist_user_id'];
				$post['commission_str'] = $commission_str;
			}

			include_once("module/member/includes/plugin_member_class.php");
			$member = new member();				
			$member->add_points(($de['product_price']+$de['logistics_price'])*1,'1',$oid,$buid);
			$post['action']='update';
			$post['seller_email']=$de['seller_id'];
			$post['buyer_email']=$buid;
			$post['order_id']=$oid;//外部订单号
			$post['statu']=4;
			$post['is_virtual']=$de['is_virtual'];


			$res=pay_get_url($post,true);//跳转至订单生成页面

			fb($res);
		}
		
		if($status==5)
		{
			//提交退货审请
			$sql="select seller_id from ".ORDER." where order_id='$oid' and userid='$buid'";
			$this->db->query($sql);
			
			$post['action']='update';
			$post['seller_email']=$this->db->fetchField('seller_id');
			$post['buyer_email']=$buid;//卖家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=5;
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		if($status==6)
		{	
			//退款，由买家发起，管理员进行退款操作。
			$sql="select userid,seller_id from ".ORDER." where order_id='$oid' and seller_id!=''";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			
			$post['action']='update';
			$post['seller_email']=$re['seller_id'];//卖家账号
			$post['buyer_email']=$re['userid'];//买家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=6;
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		
		if(!empty($res))
		{
			$res=json_decode($res);

			fb($res);
			if($res->statu=='true'&&$res->auth==md5($config['authkey']))
			{
				//------------如果结果正常就对订单进行取消操作。
				$sql="update ".ORDER." set status='$status',uptime=".time()." where order_id='$oid'";
				$this->db->query($sql);
				//------------提取佣金
				$this->set_order_product_statu($oid,$status);
				if($status==4)
				{
					$this->add_commission($oid,$post['seller_email']);

					//分销分佣结算
					$PluginManager = Yf_Plugin_Manager::getInstance();
					$PluginManager->trigger('confirm_received_product', $oid, $de);
				}
			}
			return true;
		}
		else
			return false;
	}
	//===========佣金计算。
	/**
	* burce update:2015-01-14 一件商品购买多个时计算的佣金计没有 X 数量
	*/
	function add_commission($order_id,$seller)
	{
		global $config;
		$re = $this -> get_order_product($order_id);
		foreach($re as $k=>$v)
		{
			$sql="select commission from ".PCAT." where catid='$v[pcatid]'";
			$this->db->query($sql);
			$cmi=$this->db->fetchField('commission');
			$one_price+=$v['price']*$cmi*$v['num'];
		}
		if($one_price>0)
		{
			//--------------写入流水账。卖家扣相关的费用=总站佣金+分站佣金。
			$post['type']=1;//直接到账
			$post['action']='add';//
			$post['buyer_email']=$seller;//
			$post['seller_email']='0';//
			$post['order_id']='C'.time();//外部订单号
			$post['extra_param'] = 'Commission';
			$post['price'] = $one_price;//订单总价，单价元
			$post['name1'] = '订单'.$order_id.'拥金收入';
			$post['name'] = '订单'.$order_id.'拥金支出';
			pay_get_url($post,true);//跳转至订单生成页面
			//--------------
		}
		
	}
	
	//修改订单价格，需要请求支付中心
	function update_price()
	{	
		global $buid,$config;
		$order_id = $_POST['order_id'];
		$logistics_price = $_POST['logistics_price']*1;
		
		$sql = "select buyer_id,product_price,logistics_price,status from ".ORDER." where order_id = '$order_id' and userid = '$buid' and seller_id = '0'";
		$this->db->query($sql);
		$re = $this->db->fetchRow();

		$sum = $re['product_price'] + $re['logistics_price'];

		//更改的变动价格
		foreach($_POST["price"] as $key => $val)
		{
			$sql = "select num from ".ORPRO." where id = '$key'";
			$this->db->query($sql);
			$quantity = $this->db->fetchField('num');
			$count += $val*1*$quantity;
		}

		$num = $re['product_price'] + $count;
		$price = $num + $logistics_price; 
		if(!empty($price)&&$price!=$sum&&$num>0&&$re['status']==1)
		{
			$post['action']='reprice';
			$post['buyer_email'] = $re['buyer_id'];
			$post['seller_email'] = $buid;   
			$post['order_id'] = $order_id;        
			$post['price'] = $price;  
			$res=pay_get_url($post,true);

			if(!empty($res))
			{
                $res = trim($res, "\xEF\xBB\xBF");
				$res=json_decode($res);
				if($res->statu=='true'&&$res->auth==md5($config['authkey']))
				{
					$sql="update ".ORDER." set product_price = '$num' , logistics_price = '$logistics_price' where order_id = '$order_id'";
					$this->db->query($sql);
					if($count!=0)
					{
						foreach($_POST["price"] as $key => $val)
						{
							$val= $val*1;
							$sql="update ".ORPRO." set price = price + $val where id = '$key'";
							$this->db->query($sql);
						}
					}
				}
			}
		}
		else
		{
			return 88;
		}
	}
	
	function send_product()
	{
		$sql="update ".ORDER." set invoice_no='$_POST[deliver_code]',logistics_name='$_POST[deliver_name]',deliver_time=".time()." where order_id='$_POST[id]'";
		$this->db->query($sql);
		//----------------------- 更新订单状态
		$this->set_order_statu($_POST['id'],3);
	}


	function get_logistics_order_status($order_id)
	{
		$sql="select * from  ".LOGISTICS_ORDER."  where order_id='$order_id'";
		$this->db->query($sql);

		$res = $this->db->getRows();
		if ($res)
		{
			$re = $res[0];
		}
		return $re;
	}

	function set_logistics_order_status($order_id, $status=1)
	{
		$sql="update ".LOGISTICS_ORDER." set order_logistics_status='$status'  where order_id='$order_id'";

		return $this->db->query($sql);
	}



	function add_logistics_order_status($order_id, $logistics_code, $status=1)
	{
		$sql="INSERT INTO  ".LOGISTICS_ORDER." set order_id='$order_id', order_logistics_status='$status', order_logistics_code='$logistics_code' ";

		return $this->db->query($sql);
	}
}
?>
