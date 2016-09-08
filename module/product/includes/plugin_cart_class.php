<?php
/**
 * 获取运费模板平邮、快递、EMS
 * @param $pde 商品信息
 * @param $lgid 物流模版ID
 * @param $city 收货地址
 * @param $type 物流类型 EMS,平邮,快递
 * @param $num 数量
 * @param $weight 体积
 * @param $cubage 重量
 * return array
 */
function get_price($lgid,$city,$type,$num,$weight,$cubage)
{
	global $db;

	$sql = "select price_type from " . LGSTEMP . " where id='$lgid'";
	$db->query($sql);
	$unit = $db->fetchField('price_type');

	$sql = "select * from " . LGSTEMPCON . " where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='$type'";
	$db->query($sql);
	$re = $db->fetchRow();
	if (empty($re['id']))
	{    //没有为城市定价
		$sql = "select * from " . LGSTEMPCON . " where temp_id='$lgid' and define_citys = 'default' and logistics_type='$type'";
		$db->query($sql);
		$re = $db->fetchRow();
	}
	
	if ($re)
	{
		if ($unit == '件')
		{
			if ($num <= $re['default_num'])
			{
				$price = $re['default_price'];
			}
			else
			{
				$price = $re['default_price'] + ceil(($num - $re['default_num']) / $re['add_num']) * $re['add_price'];
			}
		}

		if ($unit == 'm³')
		{
			if ($weight <= $re['default_num'])
			{
				$price = $re['default_price'];
			}
			else
			{
				$price = $re['default_price'] + ceil(($weight - $re['default_num']) / $re['add_num']) * $re['add_price'];
			}
		}

		if ($unit == 'kg')
		{
			if ($cubage <= $re['default_num'])
			{
				$price = $re['default_price'];
			}
			else
			{
				$price = $re['default_price'] + ceil(($cubage - $re['default_num']) / $re['add_num']) * $re['add_price'];
			}
		}
		
	}
	return $price ? $price : '0';
}
/**
 * 获取商品平邮、快递、EMS、总邮费
 * @param $area 收货地址
 * @param $pde 商品信息
 * @param $num 数量
 * return array
 */
function get_log_price($area,$product)
{   
	$mail = $ems = $express = 0;
	$logistics = array();
	foreach($product as $key => $val)
	{
		$logistics[$val['freight']]['freight_id'] = $val['freight'];
		$logistics[$val['freight']]['freight_type'] = $val['freight_type'];
		$logistics[$val['freight']]['num'] += $val['num'];
		$logistics[$val['freight']]['weight'] += $val['weight']*$val['num'];
		$logistics[$val['freight']]['cubage'] += $val['cubage']*$val['num'];
	}

	foreach($logistics as $key => $val)
	{
		if(intval($val['freight_type']))
		{
			$express += get_price($val['freight_id'],$area,'express',$val['num'],$val['weight'],$val['cubage']);	
			$mail += get_price($val['freight_id'],$area,'mail',$val['num'],$val['weight'],$val['cubage']);
			$ems += get_price($val['freight_id'],$area,'ems',$val['num'],$val['weight'],$val['cubage']);
		}	
	}
	$re['mail'] = $mail;
 	$re['ems'] = $ems;
	$re['express'] = $express;

	
	return $re;
}
//================================================
class cart
{
	var $db;
	
	function cart()
	{
		global $db;	
		$this -> db = & $db;
	}
	
	/**
	 * 获取单个店铺商品及商品总价格、平邮、快递、EMS、总邮费
	 * @param $sell_id 卖家ID
	 * @param $area 收货地址
	 * @param $product_id 商品ID
	 * return array
	 */
	function get_prolist($seller_id=NULL,$area,$product_id = '',$provinceid = '')
	{
		global $buid;
		if($product_id)
		{
			$str = " and a.id in ($product_id) ";
		}
		$sql="select 
		a.*,a.price * a.quantity as sumprice,a.quantity as num,b.weight,b.cubage,
		b.subhead,b.brand,b.type,b.promotion_id,b.ship_free_id,b.is_shelves,b.status,b.market_price,b.price as pprice,b.stock as amount,b.name as pname,b.pic,b.catid,b.id as pid,b.freight_id as freight,b.freight_type,b.is_invoice,
		c.setmeal as setmealname,c.spec_name,c.stock,c.price as sprice from 
		".CART." a left join 
		".PRODUCT." b on a.product_id = b.id left join 
		".SETMEAL." c on a.spec_id = c.id 
		where a.seller_id = $seller_id and a.buyer_id = $buid $str order by b.is_shelves desc, b.status desc, b.stock desc, c.stock desc,a.create_time desc";
		$this->db->query($sql);	 	
		$re = $this->db->getRows();	
		$invalid_count = "0";
		$time=time();
		
		$activity_arr=array();			//同一订单中所有商品参加的满减活动
		$gifts_arr=array();				//同一订单中所有商品参加的满赠活动
		foreach($re as $k=>$value)
		{
			if($value["promotion_id"])
			{
				$sql="select a.activity_rule,a.start_time,a.end_time from ".ACTIVITY." a left join ".ACTIVITYPRODUCT." b on a.id=b.activity_id where a.id='$value[promotion_id]' and b.status=2 and a.start_time <= $time and a.end_time >= $time";
				$this->db->query($sql);
				$activities_re=$this->db->fetchRow();
				$re[$k]["activity_rule"]=$activities_re["activity_rule"];
			}
		}
	
		foreach($re as $key => $val)
		{   
			$setmealname = $val['setmealname'] ? explode(',',$val['setmealname']) : "";
			$spec_name = $val['spec_name'] ? explode(',',$val['spec_name']) : "";

			if($spec_name && $setmealname)
			{
				foreach($setmealname as $k => $v)
				{
					$re[$key]['spec'][] = $spec_name[$k].":".$v;	
				}	
			}
			//产品库存数量,用套餐的替换
			$re[$key]['stock'] = $stock = $val['spec_id'] ? $val['stock']:$val['amount'];
			//$re[$key]['price'] = $price = $val['spec_id'] ? $val['sprice']:$val['pprice'];
			//$re[$key]['sumprice'] = $price * $val['num'];
			unset($re[$key]['sprice']);
			unset($re[$key]['pprice']);
			unset($re[$key]['amount']);	
			if($stock < 1 || $val['status'] < 1 || $val['is_shelves'] < 1)
			{				
				$sql = "delete from ".CART." where id = '$val[id]'";
				$flag = $this->db->query($sql);	   
				unset($re[$key]);
			}	
			else
			{	
				if($val["promotion_id"])
				{
					if($val["activity_rule"]==11 || $val["activity_rule"]==12)
						$activity_arr[$val["promotion_id"]][]=array("pid"=>$val["id"],"promotion_id"=>$val["promotion_id"],"sumprice"=>$val["sumprice"],"num"=>$val["num"]);
					else if($val["activity_rule"]==21 || $val["activity_rule"]==22)
						$gifts_arr[$val["promotion_id"]][]=array("pid"=>$val["id"],"promotion_id"=>$val["promotion_id"],"sumprice"=>$val["sumprice"],"num"=>$val["num"]);
				}

				$sumprice += $val['sumprice'];//单店总价
				if($val['is_invoice']=='true')
				{
					$list['is_invoice']++;	
				}
				unset($re[$key]['is_invoice']);
			}
		}
		
		//免运费设置
		$sql = "select shop_free_shipping, shop_free_price from ".SHOP." where userid = '$seller_id'";
		$this -> db -> query($sql);	 	
		$shop_free_row = $this -> db -> fetchRow();
		$shop_free_shipping = $shop_free_row['shop_free_shipping'];
		$shop_free_price_str = $shop_free_row['shop_free_price'];

		$shop_free_price_row = json_decode($shop_free_price_str);

		$shop_free_shipping = $shop_free_shipping ? $shop_free_shipping : "0";
		if($sumprice >= $shop_free_shipping)
		{
			$list['mail'] = $list['ems'] = $list['express'] = 0;
			//end 免运费设置
		}
		else
		{
			$fprice = get_log_price($area,$re);
			$list['mail'] = $fprice['mail'];
			$list['ems'] = $fprice['ems'];
			$list['express'] = $fprice['express'];
			if($fprice['mail']>0||$fprice['ems']>0||$fprice['express']>0)
			{
				if ($fprice['mail']<=0) $list['mails'] = 1;
				if ($fprice['ems']<=0) $list['emss'] = 1;
				if ($fprice['express']<=0) $list['expresss'] = 1;
			}
		}

		$orig_sum_price=$sumprice;//商品原价
		$discount_price=$orig_sum_price-$sumprice;	//会员打折减少金额
		
		//优惠，满减活动，满额减，满件减
		$reduce_price=0;
		if($activity_arr)
		{
			$reduce_price=$this->reduce_price($activity_arr);
		}
		if($gifts_arr)  //满赠，是否满足满赠，满足，显示赠送的商品
		{
			$gift_pid=$this->full_gift($gifts_arr);
			if(!empty($gift_pid))
			{
				$gift_id=implode(",",$gift_pid);
				$sql="select id as pid,price,name as pname,pic from ".PRODUCT." where id in ($gift_id)";
				$this->db->query($sql);
				$res_row=$this->db->getRows();
				$list["giftlist"]=$res_row;
			}
		}
		$sumprice-=$reduce_price;
		
		$list['reduce_price']=$reduce_price; //活动减免的金额
		$list['discount_price']=$discount_price; //会员折扣金额
		
		$list['orig_sum_price']=$orig_sum_price;//商品活动、折扣前的总价
		$list['sumprice'] = $sumprice;//单个卖家的商品总价
		$list['prolist'] = $re;//单个店铺的产品列表
		
		return $list;
	}
	/**
	 * 计算满减活动商品应扣除的金额
	 * @param $arr 满减活动
	 * return  活动扣除的金额
	*/
	function reduce_price($arr=array())
	{
		global $buid;
		$reduce_price=0;
		foreach($arr as $key=>$value)
		{
			$sumprice=0;
			$sumnum=0;
			$sql="select * from ".ACTIVITY." where id='$key'";
			$this->db->query($sql);
			$rule_row=$this->db->fetchRow();
			foreach($value as $k=>$val)
			{
				$sumprice+=$val["sumprice"];
				$sumnum+=$val["num"];
			}
			if(($rule_row["activity_rule"]==11 && $sumprice >= $rule_row["meet_money"]) || ($rule_row["activity_rule"]==12 && $sumnum >= $rule_row["meet_num"]))
				$reduce_price+=$rule_row["cut_money"];
		}
		return $reduce_price;
	}
	/**
	 * 检查是否满足满赠活动
	 * @param $arr 满赠活动
	 * return array 赠送商品id
	 */
	function full_gift($arr=array())
	{
		global $buid;
		$gift_pid=array();
		foreach($arr as $key=>$value)
		{
			$sumprice=0;
			$sumnum=0;
			$sql="select a.*,b.activity_id,b.product_id,b.gift_pid from ".ACTIVITY." a left join ".ACTIVITYPRODUCT."
			b on a.id = b.activity_id where a.id='$key'";
			$this->db->query($sql);
			$rule_row=$this->db->fetchRow();
			foreach($value as $k=>$val)
			{
				$sumprice+=$val["sumprice"];
				$sumnum+=$val["num"];
			}
			if(($rule_row["activity_rule"]==21 && $sumprice >= $rule_row["meet_money"]) || ($rule_row["activity_rule"]==22 && $sumnum >= $rule_row["meet_num"]))
			{
				$gift_pid[]=$rule_row["gift_pid"];
			}
		}
		return $gift_pid;
	}

	/**
	 * 获取购物车商品信息及商品总价格
	 * @param $area 收货地址格
	 * @param $product_id 商品ID
	 * return array
	 */
	function get_cart_list($area,$product_id = '',$provinceid = '')
	{
		global $buid;  
		$sumprice = 0;

		//判断是否分销店
		$sql = "select a.id, GROUP_CONCAT(a.id) as cart_id, seller_id,spec_id,company,a.discounts, a.dist_user_id from
			".CART." a left join
			".SHOP." b on seller_id=b.userid
			where buyer_id = $buid and  a.dist_user_id=0 group by seller_id

			union

			select a.id, GROUP_CONCAT(a.id) as cart_id, seller_id,spec_id,company,a.discounts, a.dist_user_id from
			".CART." a left join
			".SHOP." b on a.dist_user_id=b.userid
			where buyer_id = $buid and  a.dist_user_id!=0 group by seller_id
			";
// var_dump($sql);die();
		$this->db->query($sql);
		$re = $this->db->getRows();

		foreach($re as $key => $v)
		{	
		    //获取单个店铺商品及商品总价格、平邮、快递、EMS、总邮费
			$pro = $this->get_prolist($v['seller_id'],$area,$product_id,$provinceid);
            $voucher = $this->get_voucher($v['seller_id'],$pro['sumprice']);
			if($pro['prolist'])
			{
				$re[$key]['orig_sum_price'] = $pro['orig_sum_price']*1;
				$re[$key]['sumprice'] = $pro['sumprice'];
				$re[$key]['reduce_price'] = $pro['reduce_price']*1;//订单活动减免
				$re[$key]['discount_price'] = $pro['discount_price']*1;//会员等级折扣
                $re[$key]['voucher'] = $voucher;
				$pro['mail'] = $pro['mails'] == 1 ? 0 : $pro['mail'];
				$pro['ems'] = $pro['emss'] == 1 ? 0 : $pro['ems'];
				$pro['express'] = $pro['expresss'] == 1 ? 0 : $pro['express'];
				$re[$key]['mail'] = $pro['mail'];
				$re[$key]['ems'] = $pro['ems'];
				$re[$key]['express'] = $pro['express']; 
				$re[$key]['free_ship_flag'] = $pro['activity_shipping_free_flag']; 
				$re[$key]['prolist'] = $pro['prolist'];
				$re[$key]['is_invoice'] = $pro['is_invoice'];
				$sumprice += $pro['sumprice'];
				if($pro['giftlist'])
				{
					$re[$key]["giftlist"]=$pro['giftlist'];
				}
				$de[$key] = $re[$key];
			}
			
		}
		$res['cart'] = $de;
		$res['sumprice'] = $sumprice;
		return $res;
	}
	
        /**
         * 获取当前店铺下可用的代金券
         */
        function get_voucher($id,$v_price=0)
        {
            global $buid; // 判断条件 状态 结束时间 最低金额限制
            $v_price = $v_price?$v_price:0;
            $sql = "select * from ".VOUCHER." where member_id = $buid and shop_id = $id and status =1 and end_time >".time()." and `limit` <= ".$v_price;
            $this -> db -> query($sql);
            return $this -> db -> getRows();
        }
        
	/**
	 * 商品加入购物车
	 * @param $product_id 商品ID
	 * @param $quantity 数量 默认值：1 
	 * @param $spec_id 规格ID  默认值：0
	 * return 结果字符串
	 */
	function add_cart($product_id, $quantity = 1,$spec_id = 0, $sku=null, $dist_id=null)
	{
		global $buid;  
		$quantity *= 1;
		$spec_id *= 1;
		$str = "";
		
		$sql = "select member_id,price,stock,is_tg from ".PRODUCT." where id = '$product_id' and status >0 and is_shelves = 1";
		$this->db->query($sql);
		$pro = $this->db->fetchRow(); 
		
		if(!$pro) return "1"; //商品不存在或已下架
		
		if(!empty($spec_id))
		{
			$sql = "select price,stock from ".SETMEAL." where id = '$spec_id'";
			$this->db->query($sql);
			$de = $this->db->fetchRow();
			$str =" and spec_id = '$spec_id' ";	
		}
		
		$sql="select id,quantity from ".CART." where product_id = '$product_id' and buyer_id = '$buid '$str limit 1";
		$this->db->query($sql);
		$re=$this->db->fetchRow();

		$re['quantity'] *= 1;
		$stock = $spec_id?$de['stock']:$pro['stock'];
		$price = $spec_id?$de['price']:$pro['price'];
                
		if($re['quantity']+$quantity>$stock)
		{
			$sql = "update ".CART." set quantity = '$stock' where id = '$re[id]'";	
			$this->db->query($sql);	
			return $re['id'];
		}
		else
		{
			if(!empty($re['id']))
			{
				$sql = "update ".CART." set quantity = quantity + $quantity where id = '$re[id]'";	
				$this->db->query($sql);	
				return $re['id'];
			}
			else
			{
                $sql = "select discounts from ".MECART." where `shop_id` = (select `member_id` from ".PRODUCT." where id = '$product_id' ) and `blind_member_id` = $buid and status = '1' order by discounts desc limit 1";
                $this->db->query($sql);
                if($this -> db -> num_rows())
                {
                    $discounts = $this -> db -> fetchField("discounts");
                }
                if($discounts > 0){$price = ($price * $discounts)/10;}

				if ($dist_id)
				{
					$sql = "insert into ".CART."(`buyer_id`,`product_id`,`seller_id`,`price`,`quantity`,`create_time` ,`spec_id`,`is_tg`,`discounts`, dist_user_id) VALUES ('$buid','$product_id','$pro[member_id]','$price','$quantity',".time().",'$spec_id','$pro[is_tg]','$discounts', $dist_id)";

				}
				else
				{
					$sql = "insert into ".CART."(`buyer_id`,`product_id`,`seller_id`,`price`,`quantity`,`create_time` ,`spec_id`,`is_tg`,`discounts`) VALUES ('$buid','$product_id','$pro[member_id]','$price','$quantity',".time().",'$spec_id','$pro[is_tg]','$discounts')";
				}

				$this->db->query($sql);	
				$id=$this->db->lastid();
				return $id;
			}
		}
		
	}
	
	/**
	 * 删除购物车商品
	 * @param $id 购物车商品ID
	 * return 结果字符串
	 */
	function del_cart($id=NULL)
	{
		if(is_array($id))
		{
			$id = implode(',',$id);
			$sql = "delete from ".CART." where id in ($id)";
		}
		else
		{
			$id *= 1;
			$sql = "delete from ".CART." where id = '$id'";
		}
		$flag = $this->db->query($sql);	   
		return $flag;
	}
	
	/**
	 * 清空购物车
	 * return 结果字符串
	 */
	function clear_cart($product_id='')
	{
		global $buid; 
		if($product_id)
		{
			$str = " and id in ($product_id)";	
		} 
		$sql = "delete from ".CART." where buyer_id='$buid' $str";
		$flag = $this->db->query($sql);	   
		return $flag;
	}
	
	/**
	 * 修改购物车商品数量
	 * @param $id 购物车商品ID
	 * @param $quantity 数量 默认值：NULL 
	 * return 结果字符串
	 */
	function edit_cart($id,$quantity=NULL)
	{
		global $buid;  

		if($quantity < 1 && !empty($id))//如果数量小于1就删除
			$this->del_cart($id);
		
		$sql = "select 
		a.quantity,a.spec_id,a.price as cprice,
		b.stock as amount,b.price as pprice,
		c.stock ,c.price from 
		".CART." a left join 
		".PRODUCT." b on a.product_id = b.id left join 
		".SETMEAL." c on a.spec_id = c.id 
		where a.id = $id and a.buyer_id = $buid";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		
		$stock=$re['spec_id']>0?$re['stock']:$re['amount'];
		$price=$re['cprice']?$re['cprice']:($re['spec_id']>0?$re['price']:$re['pprice']);
		
		if($quantity > $stock)
		{
			$sql="update ".CART." set quantity = '$stock' where id = $id";	
			$this->db->query($sql);			
			return ($price*1)*($stock*1);	
		}
		else
		{  
			$sql="update ".CART." set quantity = '$quantity' where id = '$id'";
			$flag=$this->db->query($sql);	   
			return ($price*1)*($quantity*1);
		}
	}
	
}
?>
