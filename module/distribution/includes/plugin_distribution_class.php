<?php
//类如此写，不是太好，不过考虑兼容结构及使用方便，如此做
class distribution
{
	var $db;
	var $tpl;
	var $page;

	function distribution()
	{
		global $db;
		global $config;
		global $tpl;

		$this->db  = &$db;
		$this->tpl = &$tpl;
	}

	/**
	 * 取得分销用户信息
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function getDistributionUser($user_id)
	{
		global $db;

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER . " WHERE user_id='$user_id'";

		$db->query($sql);
		$user_row = $db->fetchRow();

		return $user_row;
	}


	/**
	 * 添加分销用户
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function addDistributionUser($user_id, $state=0, $shop_id=0)
	{
		global $db;
		$sql = sprintf("INSERT INTO   %s  (user_id, distribution_user_state, distribution_user_apply_time, shop_id) VALUES ( %d, %d, %d, %d)", DISTRIBUTION_USER, $user_id, $state, time(), $shop_id);

		$re = $this->db->query($sql);


		return $re;
	}

	/**
	 * 更新分销用户
	 *
	 * @param int $userid 注册用户
	 * @param float $amount_0
	 * @param float $amount_1
	 * @param float $amount_2
	 * @return bool
	 */
	function editDistributionShopAmount($user_id, $amount_0,  $amount_1,  $amount_2)
	{
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_shop_amount_0=distribution_shop_amount_0+%f, distribution_shop_amount_1=distribution_shop_amount_1+%f, distribution_shop_amount_2=distribution_shop_amount_2+%f WHERE user_id=%d ", DISTRIBUTION_USER, $amount_0, $amount_1, $amount_2, $user_id);

		$re = $this->db->query($sql);


		return $re;
	}


	/**
	 * 更新分销用户
	 *
	 * @param int $userid 注册用户
	 * @param float $settlement
	 * @return bool
	 */
	function editDistributionSettlementAmount($user_id, $settlement, $distribution_user_settlement_amount)
	{
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_user_settlement_amount=distribution_user_settlement_amount+%f WHERE user_id=%d AND distribution_user_settlement_amount=%s ", DISTRIBUTION_USER, $settlement, $user_id, $distribution_user_settlement_amount);

		$re = $this->db->query($sql);


		return $re;
	}

	/**
	 * 更新分销用户点击费用
	 *
	 * @param int $userid 注册用户
	 * @param float $amount_0
	 * @param float $amount_1
	 * @param float $amount_2
	 * @return bool
	 */
	function editDistributionClickAmount($user_id, $amount_0,  $amount_1,  $amount_2)
	{
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_click_amount_0=distribution_click_amount_0+%f, distribution_click_amount_1=distribution_click_amount_1+%f, distribution_click_amount_2=distribution_click_amount_2+%f WHERE user_id=%d ", DISTRIBUTION_USER, $amount_0, $amount_1, $amount_2, $user_id);

		$re = $this->db->query($sql);


		return $re;
	}

	function editDistributionRegAmount($user_id, $amount_0,  $amount_1,  $amount_2)
	{
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_reg_amount_0=distribution_reg_amount_0+%f, distribution_reg_amount_1=distribution_reg_amount_1+%f, distribution_reg_amount_2=distribution_reg_amount_2+%f WHERE user_id=%d ", DISTRIBUTION_USER, $amount_0, $amount_1, $amount_2, $user_id);

		$re = $this->db->query($sql);

		return $re;
	}


	function editDistributionBuyAmount($user_id, $amount_0,  $amount_1,  $amount_2)
	{
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_buy_amount_0=distribution_buy_amount_0+%f, distribution_buy_amount_1=distribution_buy_amount_1+%f, distribution_buy_amount_2=distribution_buy_amount_2+%f WHERE user_id=%d ", DISTRIBUTION_USER, $amount_0, $amount_1, $amount_2, $user_id);

		Yf_Log::log($sql, Yf_Log::INFO, 'buy');
		$re = $this->db->query($sql);

		return $re;
	}

	function editDistributionAdvAmount($user_id, $distribution_adv_money_add)
	{
		fb($distribution_adv_money_add);
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_adv_money=distribution_adv_money+%f WHERE user_id=%d ", DISTRIBUTION_USER, $distribution_adv_money_add, $user_id);

		fb($sql);
		$re = $this->db->query($sql);

		return $re;
	}

	/**
	 * 取得带来的下线
	 *
	 * @param int $buid 注册用户
	 * @param int $level 下线等级
	 * @return bool
	 */
	function getDistributionChildUser($buid, $level=null, &$page_str=null)
	{
		global $db;
		global $config;

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_RELATIONSHIP . " WHERE user_parent_id='$buid'";

		if ($level)
		{
			if (is_array($level))
			{
				$sql = $sql . ' AND user_relationship_level IN(' . implode(',', $level) . ')';
			}
			else
			{
				$sql = $sql . ' AND user_relationship_level = ' . intval($level);
			}
		}


		include_once("$config[webroot]/includes/page_utf_class.php");


		if ($page_str)
		{
			$page           = new Page();
			$page->listRows = 10;

			if (!$page->__get('totalRows'))
			{
				$db->query($sql);
				$page->totalRows = $db->num_rows();
			}

			$sql .= "  LIMIT " . $page->firstRow . ", 10";
			$page_str = $page->prompt();
		}

		$db->query($sql);
		$re = $db->getRows();

		foreach ($re as $v)
		{

		}

		return $re;
	}

	/**
	 * 取得带来的下线统计
	 *
	 * @param int $buid 注册用户
	 * @param int $level 下线等级
	 * @return bool
	 */
	function getDistributionChildUserNum($buid, $level=null)
	{
		global $db;

		$sql = "SELECT user_relationship_level, count(*) num, sum(user_commission_buy_amount) user_commission_buy_amount_total, sum(user_commission_shop_amount) user_commission_shop_amount_total, sum(user_commission_click_amount) user_commission_click_amount_total, sum(user_commission_reg_amount) user_commission_reg_amount_total     FROM " . DISTRIBUTION_USER_RELATIONSHIP . " WHERE user_parent_id='$buid' GROUP BY user_relationship_level";


		$db->query($sql);
		$rows = $db->getRows();

		$re = array();

		foreach ($rows as  $row)
		{
			$re['level_' . $row['user_relationship_level']]['amount'] = $row['user_commission_buy_amount_total']+$row['user_commission_shop_amount_total']+$row['user_commission_click_amount_total']+$row['user_commission_reg_amount_total'];
			$re['level_' . $row['user_relationship_level']]['shop_amount'] = $row['user_commission_shop_amount_total'];
			$re['level_' . $row['user_relationship_level']]['click_amount'] = $row['user_commission_click_amount_total'];
			$re['level_' . $row['user_relationship_level']]['reg_amount'] = $row['user_commission_reg_amount_total'];
			$re['level_' . $row['user_relationship_level']]['buy_amount'] = $row['user_commission_buy_amount_total'];
			$re['level_' . $row['user_relationship_level']]['num'] = $row['num'];

		}
		
		return $re;
	}

	/**
	 * 取得上线
	 *
	 * @param int $buid 注册用户
	 * @param int $level 上线等级
	 * @return bool
	 */
	function getDistributionParentUser($buid, $level=null)
	{
		global $db;

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_RELATIONSHIP . " WHERE user_id='$buid'";

		if ($level)
		{
			if (is_array($level))
			{
				$sql = $sql . ', AND user_relationship_level IN(' . implode(',', $level) . ')';
			}
			else
			{
				$sql = $sql . ', AND user_relationship_level = ' . intval($level);
			}
		}


		$db->query($sql);
		$re = $db->getRows();

		foreach ($re as $v)
		{

		}

		return $re;
	}


	/**
	 * 添加带来的下线
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function addDistributionUserRelationship($userid, $user, $dist_id, $dist_user)
	{
		global $db;

		//判断上线是否存在
		$dist_id = intval($dist_id);

		//取得dist_id上线
		$re = $this->getDistributionParentUser($dist_id);

		$sql = 'INSERT INTO ' . DISTRIBUTION_USER_RELATIONSHIP . ' (user_id, user_name, user_parent_id, user_parent_name, user_relationship_level) VALUES ( ' . $userid . ', "' . $user . '", ' . $dist_id . ', "' . $dist_user . '",  1)';

		$this->db->query($sql);

		foreach ($re as $v)
		{
			//
			if (1 == $v['user_relationship_level'] || 2 == $v['user_relationship_level'])
			{
				$sql = 'INSERT INTO ' . DISTRIBUTION_USER_RELATIONSHIP . ' (user_id, user_name, user_parent_id, user_parent_name, user_relationship_level) VALUES ( ' . $userid . ', "' . $user . '", ' . $v['user_parent_id'] . ', "' . $v['user_parent_name'] . '",  ' . intval($v['user_relationship_level']+1) . ')';

				$this->db->query($sql);
			}
		}

		return $re;
	}


	/**
	 * 更新分销用户-下线消费     佣金
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function editDistributionUserRelationshipUserAmount($user_relationship_id, $user_commission_amount)
	{
		global $db;

		//判断上线是否存在
		//$dist_id = intval($dist_id);

		$sql = 'UPDATE ' . DISTRIBUTION_USER_RELATIONSHIP . ' SET user_commission_shop_amount=user_commission_shop_amount+' . $user_commission_amount . ' WHERE user_relationship_id=' . $user_relationship_id;

		$re = $this->db->query($sql);
		

		return $re;
	}

	/**
	 * 更新分销卖家佣金
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function editDistributionUserRelationshipShopAmount($user_relationship_id, $user_commission_amount=0)
	{
		global $db;

		//判断上线是否存在
		//$dist_id = intval($dist_id);

		$sql = 'UPDATE ' . DISTRIBUTION_USER_RELATIONSHIP . ' SET user_commission_shop_amount=user_commission_shop_amount+' . $user_commission_amount . ' WHERE user_relationship_id=' . $user_relationship_id;

		$re = $this->db->query($sql);


		return $re;
	}

	/**
	 * 更新分销卖家流量佣金
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function editDistributionUserRelationshipClickAmount($user_relationship_id, $user_commission_click_incr=0)
	{
		global $db;

		//判断上线是否存在
		//$dist_id = intval($dist_id);

		$sql = 'UPDATE ' . DISTRIBUTION_USER_RELATIONSHIP . ' SET user_commission_click_amount=user_commission_click_amount+' . $user_commission_click_incr . ' WHERE user_relationship_id=' . $user_relationship_id;
fb($sql);
		$re = $this->db->query($sql);


		return $re;
	}


	/**
	 * 更新分销注册佣金
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function editDistributionUserRelationshipRegAmount($user_relationship_id, $user_commission_reg_incr=0)
	{
		global $db;

		//判断上线是否存在
		//$dist_id = intval($dist_id);

		$sql = 'UPDATE ' . DISTRIBUTION_USER_RELATIONSHIP . ' SET user_commission_reg_amount=user_commission_reg_amount+' . $user_commission_reg_incr . ' WHERE user_relationship_id=' . $user_relationship_id;

		$re = $this->db->query($sql);


		return $re;
	}
	/**
	 * 更新购买佣金
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function editDistributionUserRelationshipBuyAmount($user_relationship_id, $user_commission_amount=0)
	{
		global $db;

		//判断上线是否存在
		//$dist_id = intval($dist_id);

		$sql = 'UPDATE ' . DISTRIBUTION_USER_RELATIONSHIP . ' SET user_commission_buy_amount=user_commission_buy_amount+' . $user_commission_amount . ' WHERE user_relationship_id=' . $user_relationship_id;

		$re = $this->db->query($sql);


		return $re;
	}

	/**
	 * 添加分销产品呢
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function addDistributionProduct($user_id, $product_id, $product_lock_flag=0)
	{
		global $db;

		//判断产品是否存在
		$product_id = intval($product_id);

		$sql = 'INSERT INTO ' . DISTRIBUTION_USER_PRODUCT . ' (user_id, product_id, product_lock_flag) VALUES ( ' . $user_id . ', ' . $product_id . ', ' . $product_lock_flag . ')';

		$re = $this->db->query($sql);


		return $re;
	}

	/**
	 * 删除分销产品呢
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function removeDistributionProduct($user_id, $product_id)
	{
		global $db;

		//判断产品是否存在
		$user_id    = intval($user_id);
		$product_id = intval($product_id);

		$sql = 'DELETE FROM ' . DISTRIBUTION_USER_PRODUCT . ' WHERE user_id =  ' . $user_id . ' AND product_id = ' . $product_id . ' AND  product_lock_flag=0';

		$re = $this->db->query($sql);


		return $re;
	}

	/**
	 * 取得分销产品呢
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function getDistributionProduct($user_id, $product_id=null)
	{
		global $db;


		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_PRODUCT . " WHERE user_id='$user_id'";

		if ($product_id)
		{
			$product_id = intval($product_id);
			$sql = $sql . ' AND product_id = ' . intval($product_id);
		}

		$this->db->query($sql);
		$re = $this->db->getRows($sql);

		return $re;
	}


	/**
	 * 取得有效产品信息
	 *
	 * @param int $userid 注册用户
	 * @param int $dist_id 上线Id
	 * @return bool
	 */
	function getProductInfoNormal($produce_id_row=array())
	{
		global $db;
		global $tpl;

		$rs = array();

		if (is_array($produce_id_row) && $produce_id_row)
		{
			$sql = 'SELECT p.id, p.name as pname, p.uptime, p.pic, p.status, p.price, p.stock as amount, p.code, p.shop_rec, p.is_dist, dp.* FROM ' . PRODUCT . ' p LEFT JOIN ' . DISTRIBUTION_PRODUCT . '  dp ON p.id=dp.product_id    WHERE p.id IN (' . implode(',', $produce_id_row) . ') AND p.is_shelves=1 AND p.status>0 ORDER BY p.id DESC LIMIT 0, 10';

			$db->query($sql);

			$rs = $db->getRows();
		}

		return $rs;
	}


	/**
	 * 取得产品信息
	 *
	 * @param array $produce_id_row 产品Id
	 * @return bool
	 */
	function getProductInfo($produce_id_row=array())
	{
		global $db;
		global $tpl;

		$rs = array();

		if (is_array($produce_id_row) && $produce_id_row)
		{
			$sql = 'SELECT p.id, p.name as pname, p.uptime, p.pic, p.status, p.price, p.stock as amount, p.code, p.shop_rec, p.is_dist, p.member_id, dp.* FROM ' . PRODUCT . ' p LEFT JOIN ' . DISTRIBUTION_PRODUCT . '  dp ON p.id=dp.product_id    WHERE p.id IN (' . implode(',', $produce_id_row) . ')  ORDER BY p.id DESC LIMIT 0, 1000';

			$db->query($sql);

			$rs = $db->getRows();
		}

		return $rs;
	}


	/**
	 * 判断产品是否允许分销
	 *
	 * @param int $produce_id 产品Id
	 * @return mixed
	 */
	public function isDistributionProduct($produce_id)
	{
		global $config;
		global $distribution;

		global $tpl;


		$sql = sprintf("SELECT is_dist FROM  %s  WHERE id=%d", PRODUCT, $produce_id);
		$this->db->query($sql);
		$row = $this->db->fetchRow($sql);

		if ($row && 1==$row['is_dist'])
		{
			$flag = true;
			return $flag;
		}

		$sql = sprintf("SELECT * FROM  %s  WHERE product_id=%d", DISTRIBUTION_PRODUCT, $produce_id);
		$this->db->query($sql);
		$row = $this->db->fetchRow($sql);

		$flag = false;

		if ($row)
		{
			if ($row['commission_product_price_0']!=0 && $row['commission_product_price_0']>=$row['commission_product_price_1'] && $row['commission_product_price_1']>=$row['commission_product_price_2'])
			{
				$flag = true;
			}
		}

		return $flag;
	}

	/**
	 * 去的分销产品分成信息
	 *
	 * @param int $user_id  用户
	 * @param int $produce_id 产品Id
	 * @return mixed
	 */
	public function getDistributionProductInfo($user_id, $produce_id)
	{
		global $config;
		global $distribution;

		global $tpl;

		if (null == $user_id)
		{
			$sql = sprintf("SELECT * FROM  %s  WHERE product_id=%d", DISTRIBUTION_PRODUCT, $produce_id);
		}
		else
		{
			$sql = sprintf("SELECT * FROM  %s  WHERE user_id=%d AND product_id=%d", DISTRIBUTION_PRODUCT, $user_id, $produce_id);
		}

		$this->db->query($sql);
		$re = $this->db->getRows($sql);

		return $re;
	}

	/**
	 * 添加分销产品分成信息
	 *
	 * @param int $user_id  用户
	 * @param int $produce_id 产品Id
	 * @return mixed
	 */
	public function addDistributionProductInfo($user_id, $produce_id, $commission_product_price_row)
	{
		global $config;
		global $distribution;

		global $tpl;

		$sql = sprintf("INSERT INTO  %s (user_id, product_id, commission_product_price_0, commission_product_price_1, commission_product_price_2, commission_product_price_plantform, commission_product_price_settlement) values (%d, %d, %f, %f, %f, %f, %f)", DISTRIBUTION_PRODUCT, $user_id, $produce_id, $commission_product_price_row['commission_product_price_0'], $commission_product_price_row['commission_product_price_1'], $commission_product_price_row['commission_product_price_2'], $commission_product_price_row['commission_product_price_plantform'], $commission_product_price_row['commission_product_price_settlement']);
		$re = $this->db->query($sql);

		return $re;
	}

	/**
	 * 编辑分销产品分成信息
	 *
	 * @param int $user_id  用户
	 * @param int $produce_id 产品Id
	 * @return mixed
	 */
	public function editDistributionProductInfo($user_id, $produce_id, $commission_product_price_row)
	{
		global $config;
		global $distribution;

		global $tpl;

		if (!$this->getDistributionProductInfo($user_id, $produce_id))
		{
			$this->addDistributionProductInfo($user_id, $produce_id, $commission_product_price_row);
		}
		else
		{
			$sql = sprintf("UPDATE %s SET commission_product_price_0=%f, commission_product_price_1=%f, commission_product_price_2=%f, commission_product_price_plantform=%f, commission_product_price_settlement=%f WHERE user_id=%d AND product_id=%d", DISTRIBUTION_PRODUCT,    $commission_product_price_row['commission_product_price_0'], $commission_product_price_row['commission_product_price_1'], $commission_product_price_row['commission_product_price_2'], $commission_product_price_row['commission_product_price_plantform'], $commission_product_price_row['commission_product_price_settlement'],  $user_id, $produce_id);
			$re = $this->db->query($sql);
		}

		return $re;
	}

	/**
	 * 确认收货，分佣结算
	 *
	 * @param int $order_id  订单id
	 * @param int $order_row 订单信息
	 * @return mixed
	 */
	public function  confirmReceivedProduct($order_id, $order_row)
	{

		$dist_user_id = $order_row['dist_user_id'];
		$seller_id    = $order_row['seller_id'];

		//读取订单信息

		$sql = "SELECT  pid, num  FROM " . ORPRO . " WHERE order_id='$order_id'";

		$this->db->query($sql);
		//$order_pro_row = $this->db->fetchRow();
		$order_pro_rows = $this->db->getRows();

		foreach ($order_pro_rows as  $order_pro_row)
		{
			$user_order_commission_add_0 = 0;
			$user_order_commission_add_1 = 0;
			$user_order_commission_add_2 = 0;
			$user_order_commission_add_plantform = 0;

			$product_id = $order_pro_row['pid'];
			$num        = $order_pro_row['num'];

			//读取产品分佣信息
			$produce_rows = $this->getDistributionProductInfo($seller_id, $product_id);

			$produce_row = array();

			if (is_array($produce_rows))
			{
				$produce_row = array_pop($produce_rows);
			}

			//更新佣金
			$user_commission_add_0 = $produce_row['commission_product_price_0'] * $num;
			$user_commission_add_1 = $produce_row['commission_product_price_1'] * $num;
			$user_commission_add_2 = $produce_row['commission_product_price_2'] * $num;
			$user_commission_add_plantform = $produce_row['commission_product_price_plantform'] * $num;


			$user_order_commission_add_0 += $user_commission_add_0;
			$user_order_commission_add_1 += $user_commission_add_1;
			$user_order_commission_add_2 += $user_commission_add_2;
			$user_order_commission_add_plantform += $user_commission_add_plantform;

			//本店
			$this->editDistributionShopAmount($dist_user_id, $user_commission_add_0,  0,  0);

			//上级店铺佣金

			//上上级别 店铺佣金

			//更新上级,显示去获取的佣金
			$parent_dist_user_rel_rows = $this->getDistributionParentUser($dist_user_id);


			$user_commission_add = 0;
			foreach ($parent_dist_user_rel_rows as $parent_dist_user_rel_row)
			{

				if (1 == $parent_dist_user_rel_row['user_relationship_level'])
				{
					$user_commission_add = $user_commission_add_1;

					//上级店铺佣金
					$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
					$this->editDistributionShopAmount($user_parent_id, 0,  $user_commission_add_1,  0);
				}
				else if(2 == $parent_dist_user_rel_row['user_relationship_level'])
				{
					$user_commission_add = $user_commission_add_2;

					//上上级别 店铺佣金
					$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
					$this->editDistributionShopAmount($user_parent_id, 0,  0,  $user_commission_add_2);
				}
				else
				{
					$user_commission_add = 0;
					continue;
				}

				//更新分佣数据
				$this->editDistributionUserRelationshipShopAmount($parent_dist_user_rel_row['user_relationship_id'], $user_commission_add);
			}

			//确认此订单佣金数据
			$this->addDistributionOrderCommission($order_id, $product_id, $user_order_commission_add_0, $user_order_commission_add_1, $user_order_commission_add_2, $user_order_commission_add_plantform);
		}

	}


	/**
	 * 确认收货，消费分佣结算
	 *
	 * @param int $order_id  订单id
	 * @param int $order_row 订单信息
	 * @return mixed
	 */
	public function  confirmReceivedBuyProduct($order_id, $order_row)
	{
		$sql="SELECT buyer_id FROM " . ORDER . " WHERE order_id='$order_id' AND buyer_id!=0";
		$this->db->query($sql);

		$dist_user_id = $this->db->fetchField("buyer_id");

		$seller_id    = $order_row['seller_id'];

		//读取订单信息

		$sql = "SELECT  pid, num  FROM " . ORPRO . " WHERE order_id='$order_id'";

		$this->db->query($sql);
		//$order_pro_row = $this->db->fetchRow();
		$order_pro_rows = $this->db->getRows();


		foreach ($order_pro_rows as  $order_pro_row)
		{
			$user_order_commission_add_0 = 0;
			$user_order_commission_add_1 = 0;
			$user_order_commission_add_2 = 0;
			$user_order_commission_add_plantform = 0;

			$product_id = $order_pro_row['pid'];
			$num        = $order_pro_row['num'];

			//读取产品分佣信息
			$produce_rows = $this->getDistributionProductInfo($seller_id, $product_id);

			$produce_row = array();

			if (is_array($produce_rows))
			{
				$produce_row = array_pop($produce_rows);
			}

			//更新佣金
			$user_commission_add_0 = $produce_row['commission_product_price_0'] * $num;
			$user_commission_add_1 = $produce_row['commission_product_price_1'] * $num;
			$user_commission_add_2 = $produce_row['commission_product_price_2'] * $num;
			$user_commission_add_plantform = $produce_row['commission_product_price_plantform'] * $num;


			$user_order_commission_add_0 += $user_commission_add_0;
			$user_order_commission_add_1 += $user_commission_add_1;
			$user_order_commission_add_2 += $user_commission_add_2;
			$user_order_commission_add_plantform += $user_commission_add_plantform;

			//本店
			//$this->editDistributionShopAmount($dist_user_id, $user_commission_add_0,  0,  0);

			//上级店铺佣金

			//上上级别 店铺佣金

			//更新上级,显示去获取的佣金
			$parent_dist_user_rel_rows = $this->getDistributionParentUser($dist_user_id);

			$user_commission_add = 0;
			foreach ($parent_dist_user_rel_rows as $parent_dist_user_rel_row)
			{

				if (1 == $parent_dist_user_rel_row['user_relationship_level'])
				{
					$user_commission_add = $user_commission_add_1;

					//上级店铺佣金
					$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
					$this->editDistributionBuyAmount($user_parent_id, 0,  $user_commission_add_1,  0);
				}
				else if(2 == $parent_dist_user_rel_row['user_relationship_level'])
				{
					$user_commission_add = $user_commission_add_2;

					//上上级别 店铺佣金
					$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
					$this->editDistributionBuyAmount($user_parent_id, 0,  0,  $user_commission_add_2);
				}
				else
				{
					$user_commission_add = 0;
					continue;
				}

				//更新分佣数据
				$this->editDistributionUserRelationshipBuyAmount($parent_dist_user_rel_row['user_relationship_id'], $user_commission_add);
			}

			//确认此订单佣金数据, 单独记录消费的
			$this->addDistributionOrderBuyCommission($order_id, $product_id, $user_order_commission_add_0, $user_order_commission_add_1, $user_order_commission_add_2, $user_order_commission_add_plantform);
		}

	}

	/**
	 * 确认收货，分佣结算
	 *
	 * @param int $order_id  订单id
	 * @param int $order_row 订单信息
	 * @return mixed
	 */
	public function  getProductOrderCommission($order_id, $order_row)
	{
		$dist_user_id = $order_row['dist_user_id'];
		$seller_id    = $order_row['seller_id'];

		//读取订单信息

		$sql = "SELECT  pid, num  FROM " . ORPRO . " WHERE order_id='$order_id'";

		$this->db->query($sql);
		//$order_pro_row = $this->db->fetchRow();
		$order_pro_rows = $this->db->getRows();


		$user_order_commission_add_0 = 0;
		$user_order_commission_add_1 = 0;
		$user_order_commission_add_2 = 0;
		$user_order_commission_add_plantform = 0;

		foreach ($order_pro_rows as  $order_pro_row)
		{
			$product_id = $order_pro_row['pid'];
			$num        = $order_pro_row['num'];

			//读取产品分佣信息
			$produce_rows = $this->getDistributionProductInfo($seller_id, $product_id);

			$produce_row = array();

			if (is_array($produce_rows))
			{
				$produce_row = array_pop($produce_rows);
			}

			//更新佣金
			$user_commission_add_0 = $produce_row['commission_product_price_0'] * $num;
			$user_commission_add_1 = $produce_row['commission_product_price_1'] * $num;
			$user_commission_add_2 = $produce_row['commission_product_price_2'] * $num;
			$user_commission_add_plantform = $produce_row['commission_product_price_plantform'] * $num;


			$user_order_commission_add_0 += $user_commission_add_0;
			$user_order_commission_add_1 += $user_commission_add_1;
			$user_order_commission_add_2 += $user_commission_add_2;
			$user_order_commission_add_plantform += $user_commission_add_plantform;
		}


		//确认此订单佣金数据
		//
		$commission = array();
		$commission['user_order_commission_add_0'] = $user_order_commission_add_0;
		$commission['user_order_commission_add_1'] = $user_order_commission_add_1;
		$commission['user_order_commission_add_2'] = $user_order_commission_add_2;
		$commission['user_order_commission_add_plantform'] = $user_order_commission_add_plantform;

		return $commission;
	}

	/**
	 * 确认退货，分佣结算 --  直接扣除，可能不符合财务逻辑
	 *
	 * @param int $refund_id  订单退单id
	 * @param int $order_id  订单id
	 * @param int $product_id  产品Id
	 * @param int $dist_user_id  分销用户
	 * @return mixed
	 */
	public function confirmReturnProduct($refund_id, $order_id, $product_id, $dist_user_id)
	{
		//读取订单佣金信息信息
		$order_commission_row = $this->getDistributionOrderCommission($order_id, $product_id);

		//更新佣金
		if ($order_commission_row)
		{
			$user_commission_add_0 = -$order_commission_row['commission_product_price_0'];
			$user_commission_add_1 = -$order_commission_row['commission_product_price_1'];
			$user_commission_add_2 = -$order_commission_row['commission_product_price_2'];

			//本店
			$this->editDistributionShopAmount($dist_user_id, $user_commission_add_0,  0,  0);

			//上级店铺佣金

			//上上级别 店铺佣金

			//更新上级,显示去获取的佣金
			$parent_dist_user_rel_rows = $this->getDistributionParentUser($dist_user_id);


			$user_commission_add = 0;
			foreach ($parent_dist_user_rel_rows as $parent_dist_user_rel_row)
			{

				if (1 == $parent_dist_user_rel_row['user_relationship_level'])
				{
					$user_commission_add = $user_commission_add_1;

					//上级店铺佣金
					$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
					$this->editDistributionShopAmount($user_parent_id, 0,  $user_commission_add_1,  0);
				}
				else if(2 == $parent_dist_user_rel_row['user_relationship_level'])
				{
					$user_commission_add = $user_commission_add_2;

					//上上级别 店铺佣金
					$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
					$this->editDistributionShopAmount($user_parent_id, 0,  0,  $user_commission_add_2);
				}
				else
				{
					$user_commission_add = 0;
					continue;
				}

				//更新分佣数据
				$this->editDistributionUserRelationshipShopAmount($parent_dist_user_rel_row['user_relationship_id'], $user_commission_add);
			}
		}

		return true;
	}


	/**
	 * 取得分销订单详情
	 *
	 * @param int $dist_user_id  分销用户id
	 * @return mixed
	 */
	public function getDistributionOrder($dist_user_id)
	{

		$sql = sprintf("SELECT * FROM %s WHERE dist_user_id = %d  LIMIT 0,100", ORDER, $dist_user_id);
		$this->db->query($sql);

		$rs = $this->db->getRows();

		return $rs;
	}

	/**
	 * 取得分销订单数量
	 *
	 * @param int $dist_user_id  分销用户id
	 * @return mixed
	 */
	public function getDistributionOrderNum($dist_user_id, $time)
	{

		$sql = sprintf("SELECT count(*) num FROM %s WHERE dist_user_id = %d AND buyer_id=0 AND create_time >=%d  LIMIT 0,100", ORDER, $dist_user_id, $time);
		$this->db->query($sql);

		$rs = $this->db->fetchRow();

		return $rs['num'];
	}

	/**
	 * 取得分销订单数量
	 *
	 * @param int $dist_user_id  分销用户id
	 * @return mixed
	 */
	public function getDistributionOrderAmount($dist_user_id, $time)
	{
		$sql = sprintf("SELECT SUM(product_price) amount FROM %s WHERE dist_user_id = %d AND buyer_id=0 AND create_time >=%d  LIMIT 0,100", ORDER, $dist_user_id, $time);
		$this->db->query($sql);

		$rs = $this->db->fetchRow();

		return $rs['amount'];
	}

	/**
	 * 取得分销订单佣金详情
	 *
	 * @param int $order_id  订单id
	 * @return mixed
	 */
	public function getDistributionOrderCommission($order_id, $product_id=null)
	{
		$sql = sprintf("SELECT * FROM %s WHERE order_id = '%s' AND product_id = %d ", DISTRIBUTION_PRODUCT_ORDER, $order_id, $product_id);
		$this->db->query($sql);

		$rs = $this->db->fetchRow();

		return $rs;
	}

	/**
	 * 添加分销订单佣金详情， 结算后真是数据，不随修改变动=>需要修改为订单对应多产品，否则退款时候结算有些问题。
	 *
	 * @param int $dist_user_id  分销用户id
	 * @return mixed
	 */
	public function addDistributionOrderCommission($order_id, $product_id, $commission_product_price_0, $commission_product_price_1, $commission_product_price_2, $user_commission_add_plantform=0)
	{
		$sql = sprintf("INSERT INTO %s (order_id, product_id, commission_product_price_0, commission_product_price_1, commission_product_price_2, commission_product_price_plantform) VALUES ( %s, %d, %f, %f, %f, %f)", DISTRIBUTION_PRODUCT_ORDER, $order_id, $product_id, $commission_product_price_0, $commission_product_price_1, $commission_product_price_2, $user_commission_add_plantform);

		$rs = $this->db->query($sql);
		fb($sql);

		return $rs;
	}

	/**
	 * 添加购买订单佣金详情， 结算后真是数据，不随修改变动=>需要修改为订单对应多产品，否则退款时候结算有些问题。
	 *
	 * @param int $dist_user_id  分销用户id
	 * @return mixed
	 */

	public function addDistributionOrderBuyCommission($order_id, $product_id, $commission_product_price_0, $commission_product_price_1, $commission_product_price_2, $user_commission_add_plantform=0)
	{
		$sql = sprintf("INSERT INTO %s (order_id, product_id, commission_product_price_0, commission_product_price_1, commission_product_price_2, commission_product_price_plantform) VALUES ( %s, %d, %f, %f, %f, %f)", DISTRIBUTION_PRODUCT_BUY_ORDER, $order_id, $product_id, $commission_product_price_0, $commission_product_price_1, $commission_product_price_2, $user_commission_add_plantform);

		$rs = $this->db->query($sql);
		fb($sql);

		return $rs;
	}
	//店铺佣金信息


	/**
	 * 取得分销店铺佣金详情
	 *
	 * @param int $shop_id  分销店铺
	 * @return mixed
	 */
	public function getDistributionCommissionShop($shop_id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE shop_id = '%d' ", DISTRIBUTION_COMMISSION_SHOP, $shop_id);
		$this->db->query($sql);

		$rs = $this->db->fetchRow();

		return $rs;
	}


	/**
	 * 添加分销店铺佣金详情
	 *
	 * @param int $shop_id  分销店铺
	 * @return mixed
	 */
	public function addDistributionCommissionShop($shop_id, $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform)
	{
		$sql = sprintf("INSERT INTO %s (shop_id,  commission_shop_rate_0, commission_shop_rate_1, commission_shop_rate_2, commission_shop_rate_plantform) VALUES ( %s, %f, %f, %f, %f)", DISTRIBUTION_COMMISSION_SHOP, $shop_id, $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform);

		$rs = $this->db->query($sql);
		fb($sql);

		return $rs;
	}

	/**
	 * 更新分销店铺佣金详情
	 *
	 * @param int $shop_id  分销店铺
	 * @return mixed
	 */
	public function editDistributionCommissionShop($shop_id, $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform)
	{
		global $config;

		if (!$this->getDistributionCommissionShop($shop_id))
		{
			$this->addDistributionCommissionShop($shop_id, $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform);
		}
		else
		{
			$sql = sprintf("UPDATE %s SET commission_shop_rate_0=%f, commission_shop_rate_1=%f, commission_shop_rate_2=%f ,commission_shop_rate_plantform=%f WHERE shop_id=%d", DISTRIBUTION_COMMISSION_SHOP,    $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform, $shop_id);
			$re = $this->db->query($sql);
		}

		return $re;
	}




	/**
	 * 分销点击统计
	 *
	 * @param int $shop_id  分销店铺
	 * @return mixed
	 */
	public function getDistributionAnalyseShop($shop_id, $user_id, $product_id=0, $date=null, $order=null)
	{
		$sql = sprintf("SELECT * FROM %s WHERE 1  ", DISTRIBUTION_ANALYSE_SHOP);

        if ($shop_id)
        {
            $sql = $sql . ' AND shop_id = "' . $shop_id . '"';
        }

        if ($product_id)
        {
            $sql = $sql . ' AND product_id = "' . $product_id . '"';
        }

		if ($date)
		{
			$sql = $sql . ' AND distribution_analyse_shop_date = "' . $date . '"';
		}
		if ($user_id)
		{
			$sql = $sql . ' AND user_id = ' . $user_id . '';
		}

        $sql = $sql . ' ORDER BY  distribution_analyse_shop_num DESC';

		$this->db->query($sql);

		$rs = $this->db->getRows();

		return $rs;
	}


	/**
	 * 添加分销点击统计
	 *
	 * @param int $shop_id  分销店铺
	 * @return mixed
	 */
	public function addDistributionAnalyseShop($shop_id, $user_id, $product_id=0, $distribution_analyse_name='', $date=null)
	{
		if (!$date)
		{
			$date = date('Y-m-d');
		}

		$sql = sprintf("INSERT INTO %s (shop_id,  product_id, distribution_analyse_name, distribution_analyse_shop_num, distribution_analyse_shop_date, user_id) VALUES ( %d, %d, '%s', %d, '%s', %d)", DISTRIBUTION_ANALYSE_SHOP, $shop_id, $product_id, $distribution_analyse_name, 1, $date, $user_id);

		$rs = $this->db->query($sql);

		return $rs;
	}

	/**
	 * 更新分销点击统计
	 *
	 * @param int $shop_id  分销店铺
	 * @return mixed
	 */
	public function editDistributionAnalyseShop($shop_id, $user_id, $product_id=0, $distribution_analyse_name='', $date=null, $incr_num=1)
	{
		global $config;

		if (!$date)
		{
			$date = date('Y-m-d');
		}

		$rs = false;
		if (!$this->getDistributionAnalyseShop($shop_id, $user_id, $product_id, $date))
		{
			$this->addDistributionAnalyseShop($shop_id, $user_id, $product_id, $distribution_analyse_name,  $date);
			$rs = true;
		}
		else
		{
			$sql = sprintf("UPDATE %s SET distribution_analyse_shop_num=distribution_analyse_shop_num+%d WHERE shop_id=%d AND distribution_analyse_shop_date='%s' AND product_id=%d AND user_id=%d", DISTRIBUTION_ANALYSE_SHOP, $incr_num, $shop_id, $date, $product_id, $user_id);

            $re = $this->db->query($sql);
			$rs = true;
		}

		//实时处理流量佣金, 自己店铺推广，不扣除流量费用
		if ($rs && $shop_id!=$user_id)
		{
			//判断广告费是否存在， 扣除商检费用
			$dist_user_row = $this->getDistributionUser($shop_id);

			if ($dist_user_row && $dist_user_row['distribution_adv_money'] > 0)
			{
				//流量分佣,真正的实现方式，应该只记录访问，结算每天统一结算
				//客户要求，实时结算
				$this->onClickProduct($shop_id, $user_id, $product_id);
			}
			else
			{
				fb('商家广告费用额度不足！');
			}

		}

		return $re;
	}



	/**
	 * 点击货物
	 *
	 * @param int $shop_id  订单退单id
	 * @param int $user_id  订单id
	 * @param int $product_id  产品Id
	 * @return mixed
	 */
	public function  onClickProduct($shop_id, $user_id, $product_id=0)
	{
		$dist_user_id = $user_id;

		global $distribution_config;


		$user_click_commission_add_0 = 0;
		$user_click_commission_add_1 = 0;
		$user_click_commission_add_2 = 0;
		$user_click_commission_add_plantform = 0;

		$commission_product_rate_total = ($distribution_config['commission_product_rate_0'] + $distribution_config['commission_product_rate_1'] + $distribution_config['commission_product_rate_2'] + $distribution_config['commission_product_rate_plantform']);
		//更新佣金
		$user_commission_add_0 = $distribution_config['commission_product_rate_0'] / $commission_product_rate_total * $distribution_config['distribution_visit_price'];
		$user_commission_add_1 = $distribution_config['commission_product_rate_1'] / $commission_product_rate_total * $distribution_config['distribution_visit_price'];
		$user_commission_add_2 = $distribution_config['commission_product_rate_2'] / $commission_product_rate_total * $distribution_config['distribution_visit_price'];
		$user_commission_add_plantform = $distribution_config['commission_product_rate_plantform'] / $commission_product_rate_total * $distribution_config['distribution_visit_price'];


		$user_click_commission_add_0 += $user_commission_add_0;
		$user_click_commission_add_1 += $user_commission_add_1;
		$user_click_commission_add_2 += $user_commission_add_2;
		$user_click_commission_add_plantform += $user_commission_add_plantform;

		fb($distribution_config['distribution_visit_price']);
		//扣除费用
		$this->editDistributionAdvAmount($shop_id, -$distribution_config['distribution_visit_price']);

		//本店
		$this->editDistributionClickAmount($dist_user_id, $user_commission_add_0,  0,  0);

		//上级店铺佣金

		//上上级别 店铺佣金

		//更新上级,显示去获取的佣金
		$parent_dist_user_rel_rows = $this->getDistributionParentUser($dist_user_id);


		$user_commission_add = 0;
		foreach ($parent_dist_user_rel_rows as $parent_dist_user_rel_row)
		{

			if (1 == $parent_dist_user_rel_row['user_relationship_level'])
			{
				$user_commission_add = $user_commission_add_1;

				//上级店铺佣金
				$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
				$this->editDistributionClickAmount($user_parent_id, 0,  $user_commission_add_1,  0);
			}
			else if(2 == $parent_dist_user_rel_row['user_relationship_level'])
			{
				$user_commission_add = $user_commission_add_2;

				//上上级别 店铺佣金
				$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
				$this->editDistributionClickAmount($user_parent_id, 0,  0,  $user_commission_add_2);
			}
			else
			{
				$user_commission_add = 0;
				continue;
			}

			//更新分佣数据
			$this->editDistributionUserRelationshipClickAmount($parent_dist_user_rel_row['user_relationship_id'], $user_commission_add);
		}
	}


	/**
	 * 注册分佣
	 *
	 * @param int $user_id  用户Id
	 * @return mixed
	 */
	public function onRegCommission($user_id)
	{
		$dist_user_id = $user_id;

		global $distribution_config;


		$user_reg_commission_add_0 = 0;
		$user_reg_commission_add_1 = 0;
		$user_reg_commission_add_2 = 0;
		$user_reg_commission_add_plantform = 0;

		$commission_product_rate_total = ($distribution_config['commission_product_rate_0'] + $distribution_config['commission_product_rate_1'] + $distribution_config['commission_product_rate_2'] + $distribution_config['commission_product_rate_plantform']);

		//更新佣金
		$user_commission_add_0 = $distribution_config['commission_product_rate_0'] / $commission_product_rate_total * $distribution_config['distribution_reg_price'];
		$user_commission_add_1 = $distribution_config['commission_product_rate_1'] / $commission_product_rate_total * $distribution_config['distribution_reg_price'];
		$user_commission_add_2 = $distribution_config['commission_product_rate_2'] / $commission_product_rate_total * $distribution_config['distribution_reg_price'];
		$user_commission_add_plantform = $distribution_config['commission_product_rate_plantform'] / $commission_product_rate_total * $distribution_config['distribution_reg_price'];


		$user_reg_commission_add_0 += $user_commission_add_0;
		$user_reg_commission_add_1 += $user_commission_add_1;
		$user_reg_commission_add_2 += $user_commission_add_2;
		$user_reg_commission_add_plantform += $user_commission_add_plantform;

		fb($distribution_config['distribution_reg_price']);

		//扣除费用
		//$this->editDistributionAdvAmount($shop_id, -$distribution_config['distribution_reg_price']);

		//本店
		$this->editDistributionRegAmount($dist_user_id, $user_commission_add_0,  0,  0);

		//上级店铺佣金

		//上上级别 店铺佣金

		//更新上级,显示去获取的佣金
		$parent_dist_user_rel_rows = $this->getDistributionParentUser($dist_user_id);


		$user_commission_add = 0;
		foreach ($parent_dist_user_rel_rows as $parent_dist_user_rel_row)
		{

			if (1 == $parent_dist_user_rel_row['user_relationship_level'])
			{
				$user_commission_add = $user_commission_add_1;

				//上级店铺佣金
				$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
				$this->editDistributionRegAmount($user_parent_id, 0,  $user_commission_add_1,  0);
			}
			else if(2 == $parent_dist_user_rel_row['user_relationship_level'])
			{
				$user_commission_add = $user_commission_add_2;

				//上上级别 店铺佣金
				$user_parent_id = $parent_dist_user_rel_row['user_parent_id'];
				$this->editDistributionRegAmount($user_parent_id, 0,  0,  $user_commission_add_2);
			}
			else
			{
				$user_commission_add = 0;
				continue;
			}

			//更新分佣数据
			$this->editDistributionUserRelationshipRegAmount($parent_dist_user_rel_row['user_relationship_id'], $user_commission_add);
		}
	}

	//分销佣金结算相关 DISTRIBUTION_USER_SETTLEMENT

	/**
	 * 取得分销佣金结算信息
	 *
	 * @param int $userid 注册用户
	 * @return bool
	 */
	function getDistributionUserSettlementById($distribution_user_settlement_id=null)
	{
		global $db;

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_SETTLEMENT . " WHERE 1";
		$sql .=  " AND distribution_user_settlement_id='$distribution_user_settlement_id'";

		$db->query($sql);
		$re = $db->fetchRow();

		return $re;
	}

	/**
	 * 取得分销佣金结算信息
	 *
	 * @param int $userid 注册用户
	 * @return bool
	 */
	function getDistributionUserSettlement($user_id=null, $state=null, &$page_str=null)
	{
		global $db;

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_SETTLEMENT . " as u LEFT JOIN " . MEMBER . " as m ON u.user_id=m.userid   WHERE 1";

		if (null != $user_id)
		{
			$sql .=  " AND user_id='$user_id'";
		}


		if (null !== $state)
		{
			$sql .=  " AND distribution_user_settlement_state='$state'";
		}

		global $config;

		include_once("$config[webroot]/includes/page_utf_class.php");


		if ($page_str)
		{
			$page           = new Page();
			$page->listRows = 10;

			if (!$page->__get('totalRows'))
			{
				$db->query($sql);
				$page->totalRows = $db->num_rows();
			}

			$sql .= "  LIMIT " . $page->firstRow . ", 10";
			$page_str = $page->prompt();
		}

		$db->query($sql);
		$re = $db->getRows();


		return $re;
	}

	/**
	 * 添加分销佣金结算
	 *
	 * @param int $userid 注册用户
	 * @param float $amount
	 * @param int $state
	 * @return bool
	 */
	function addDistributionUserSettlement($user_id, $amount, $state=0)
	{
		global $db;
		$sql = sprintf("INSERT INTO   %s  (user_id, distribution_user_settlement_amount, distribution_user_settlement_state, distribution_user_settlement_apply_time) VALUES ( %d, %f, %d, %d)", DISTRIBUTION_USER_SETTLEMENT, $user_id, $amount, $state, time());

		$re = $this->db->query($sql);

		return $re;
	}

	/**
	 * 更新分销佣金结算
	 *
	 * @param int $userid 注册用户
	 * @param int $state
	 * @return bool
	 */
	function editDistributionUserSettlement($distribution_user_settlement_id,  $state)
	{
		global $db;
		$sql = sprintf("UPDATE  %s SET distribution_user_settlement_state=%d, distribution_user_settlement_time=%d WHERE distribution_user_settlement_id=%d ", DISTRIBUTION_USER_SETTLEMENT, $state, time(), $distribution_user_settlement_id);

		$re = $this->db->query($sql);

		return $re;
	}

	/**
	 * 更新分销佣金结算
	 *
	 * @param int $userid 注册用户
	 * @param int $state
	 * @return bool
	 */
	function getDistributionUserSettlementState($state)
	{
		$row = array();
		$row['0'] = '审核中';
		$row['1'] = '审核通过';
		$row['2'] = '结算完成';

		if (array_key_exists($state, $row))
		{
			return $row[$state];
		}
		else
		{
			return '状态异常';
		}

	}


	/**
	 * 添加广告充值
	 *
	 * @param int $userid 注册用户
	 * @param float $amount
	 * @param int $state
	 * @return bool
	 */
	function addDistributionUserAdv($user_id, $order_id, $amount, $state=0)
	{
		global $db;
		$sql = sprintf("INSERT INTO   %s  (distribution_user_adv_id, user_id, distribution_user_adv_money, distribution_user_adv_state, distribution_user_adv_time) VALUES ('%s', %d, %f, %d, %d)", DISTRIBUTION_USER_ADV, addslashes($order_id), $user_id, $amount, $state, time());

		$re = $this->db->query($sql);

		return $re;
	}

	/**
	 * 更新广告充值
	 *
	 * @param int $distribution_user_adv_id 订单id
	 * @param int $state
	 * @return bool
	 */
	function editDistributionUserAdv($distribution_user_adv_id,  $state, $state_old)
	{
		global $db;

		$sql = sprintf("UPDATE  %s SET distribution_user_adv_state=%d, distribution_user_adv_time=%d WHERE distribution_user_adv_id='%s' AND distribution_user_adv_state=%d", DISTRIBUTION_USER_ADV, $state, time(), addslashes($distribution_user_adv_id), $state_old);

		$re = $this->db->query($sql);

		return $re;
	}

	/**
	 * 取得广告充值信息
	 *
	 * @param int $userid 注册用户
	 * @return bool
	 */
	function getDistributionUserAdv($user_id=null, $state=null, &$page_str=null)
	{
		global $db;

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_ADV . " WHERE 1";

		if (null != $user_id)
		{
			$sql .=  " AND user_id='$user_id'";
		}


		if (null !== $state)
		{
			$sql .=  " AND distribution_user_adv_state='$state'";
		}


		$sql .=  " ORDER BY distribution_user_adv_state ASC ";

		global $config;

		include_once("$config[webroot]/includes/page_utf_class.php");

		if ($page_str)
		{
			$page           = new Page();
			$page->listRows = 10;

			if (!$page->__get('totalRows'))
			{
				$db->query($sql);
				$page->totalRows = $db->num_rows();
			}

			$sql .= "  LIMIT " . $page->firstRow . ", 10";
			$page_str = $page->prompt();
		}

		$db->query($sql);
		$re = $db->getRows();

		return $re;
	}


	/**
	 * 取得广告充值
	 *
	 * @param int $distribution_user_adv_id 注册用户
	 * @return bool
	 */
	function getDistributionUserAdvById($distribution_user_adv_id=null)
	{
		global $db;

		$distribution_user_adv_id = addslashes($distribution_user_adv_id);

		$sql = "SELECT *  FROM " . DISTRIBUTION_USER_ADV . " WHERE 1";
		$sql .=  " AND distribution_user_adv_id='$distribution_user_adv_id'";

		$db->query($sql);
		$re = $db->fetchRow();

		return $re;
	}

	/**
	 * 获取广告充值
	 *
	 * @param int $userid 注册用户
	 * @param int $state
	 * @return bool
	 */
	function getDistributionUserAdvState($state)
	{
		$row = array();
		$row['0'] = '待支付';
		$row['1'] = '支付完成';
		$row['2'] = '结算完成';

		if (array_key_exists($state, $row))
		{
			return $row[$state];
		}
		else
		{
			return '状态异常';
		}

	}
}
?>
