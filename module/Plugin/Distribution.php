<?php
/**
 * 分销模块（推广分佣更准确）
 *
 *
 * @category   Framework
 * @package    Plugin
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Plugin_Distribution implements Yf_Plugin_Interface
{
	//解析函数的参数是pluginManager的引用
	public function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('init', $this, 'init');
		Yf_Plugin_Manager::getInstance()->register('end',  $this, 'end');
		Yf_Plugin_Manager::getInstance()->register('admin_menu',   $this, 'setAdminMenu');
		Yf_Plugin_Manager::getInstance()->register('admin_menu_p', $this, 'setAdminMenuP');
		Yf_Plugin_Manager::getInstance()->register('shop_menu_show', $this, 'setMenuShow');
		Yf_Plugin_Manager::getInstance()->register('reg_done', $this, 'regDone');
		Yf_Plugin_Manager::getInstance()->register('dist_product', $this, 'listDistProduct');

		Yf_Plugin_Manager::getInstance()->register('add_distribution_goods', $this, 'addDistributionProductInfo');
		Yf_Plugin_Manager::getInstance()->register('edit_distribution_goods', $this, 'editDistributionProductInfo');

		Yf_Plugin_Manager::getInstance()->register('confirm_received_product', $this, 'confirmReceivedProduct');
		Yf_Plugin_Manager::getInstance()->register('confirm_return_product', $this, 'confirmReturnProduct');

		Yf_Plugin_Manager::getInstance()->register('edit_shop_commission', $this, 'editShopCommission');

	}

	public function desc()
	{
		echo 'Hello World';
	}

	public function init()
	{
		global $config;

		$buid = Yf_Registry::get('buid');

		//检测是否有推广用户id，Cookie记录
		if (isset($_GET['dist_id']) && intval($_GET['dist_id']))
		{
			setcookie("dist_id", intval($_GET['dist_id']), time()+60*60*24*3, "/");
		}

		if (!Yf_Registry::isRegistered('distribution'))
		{
			include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");

			global $distribution;
			$distribution = new distribution();

			Yf_Registry::set('distribution', $distribution);


			//读取设置用户分销状态
			global $dist_user_row;
			$dist_user_row = $distribution->getDistributionUser($buid);

			if (!$dist_user_row)
			{
				$dist_user_row['user_id'] = $buid;
				$dist_user_row['distribution_user_state'] = 0;
			}

			Yf_Registry::set('dist_user_row', $dist_user_row);
		}

		include_once("lang/".$config['language']."/distribution.php");
	}

	/**
	 * 程序尾部触发事件
	 *
	 * @return mixed
	 */
	public function end()
	{
		add_dist_user_id();
	}

	public function setAdminMenu()
	{
		global $menu;
		global $dist_user_row;

		global $distribution_open_flag;
		global $shop_statu;
		global $cominfo;
		$company = $cominfo;


		if (!$distribution_open_flag)
		{
			return true;
		}


		//if (!((1==$company['shop_type'] && $company['shop_statu']==-3) || 3==$company['shop_type']))
		if (!((1==$company['shop_type']) || 3==$company['shop_type']))
		{
			return true;
		}


		$menu['distribution'] = array(
			'name'=>'分销系统',
			'action'=>'distribution',
			'sub'=>array(
				/*
				array(
					'name'=>'分销首页',
					'type'=>array(2),
					'action'=>array(
						'?m=distribution&s=admin_distribution'=>'分佣详情',
						'?m=distribution&s=admin_distribution_order_list'=>'订单详情'
					)
				),
				array(
					'name'=>'下级分销',
					'type'=>array(2),
					'action'=>array(
						'?m=distribution&s=admin_distribution_user'=>'下级分销'
					)
				),
				array(
					'name'=>'产品管理',
					'type'=>array(2),
					'action'=>array(
						'?m=distribution&s=admin_distribution_product_list'=>'分销产品管理',
						//'?m=distribution&s=admin_distribution_cat'=>'分类管理',
					)
				)
				*/
				array(
					'name'=>'分销商品',
					'type'=>array(2),
					'action'=>array(
						'?m=distribution&s=admin_distribution_product_list'=>'商品管理',
						'?m=distribution&s=admin_distribution_product_find'=>'发现商品',
						'?m=distribution&s=admin_distribution_share_log'=>'分享记录',
					)
				),
				array(
					'name'=>'分销统计',
					'type'=>array(2),
					'action'=>array(
						'?m=distribution&s=admin_distribution'=>'统计概况',
						'?m=distribution&s=admin_distribution_user'=>'我的下级',
						'?m=distribution&s=admin_distribution_order_list'=>'分销业绩',
						'?m=distribution&s=admin_distribution_settlement'=>'结算记录',
					)
				),
			),

		);

		$menu['main']['sub'] = array_merge($menu['main']['sub'], $menu['distribution']['sub']);
		unset($menu['distribution']);

		//fix shop, 不显示商家的上传菜单

		global $lang;
		if (-3 == $shop_statu || -4 == $shop_statu || -5 == $shop_statu || -6 == $shop_statu)
		{
			foreach ($menu['main']['sub'] as $id => $val)
			{
				if ('商品管理' == $val['name']
					|| '交易管理' == $val['name']
					|| '营销中心' == $val['name']
				)
				{
					unset($menu['main']['sub'][$id]);
				}

				if ($lang['shop_setting'] == $val['name'])
				{
					$menu['main']['sub'][$id]['name'] = '店铺管理';
				}
			}

			unset($menu['inquire']);
		}
		else
		{
			foreach ($menu['main']['sub'] as $id => $val)
			{
				if ($lang['shop_setting'] == $val['name'])
				{
					$menu['main']['sub'][$id]['name'] = '店铺管理';
				}

				if ('营销中心' == $val['name'])
				{
					$menu['main']['sub'][$id]['action']['?m=distribution&s=admin_distribution_adv'] = '推广管理';
				}

			}
		}

		//unset($menu['main']);

		return true;
	}

	public function setAdminMenuP()
	{
		global $menu;
		global $lang;


		global $distribution_open_flag;
		global $shop_statu;
		global $cominfo;

		$company = $cominfo;

		if ($distribution_open_flag)
		{
			if (!((2==$company['shop_type'] && $company['shop_statu']==1) || 3==$company['shop_type']))
			{
				$dist_config = array(
					'name'=>'我的分销',
					'type'=>array(1,2),
					'action'=>'?m=distribution&s=admin_distribution&cg_u_type=2'
				);

				array_push($menu['main']['sub'], $dist_config);
			}
		}

		/*
		$menu['distribution'] = array(
			'name'=>'推广赚佣金',
			'sub'=>array(
				array(
					'name'=>'推广赚佣金',
					'type'=>array(1,2),
					'action'=>array(
						'?m=distribution&s=admin_distribution'=>"我的佣金",
						'?m=distribution&s=admin_distribution_user'=>"我的下线",
						'?m=distribution&s=admin_distribution_order'=>"订单信息"
					),
				),
				array(
					'name'=>'下级用户',
					'type'=>array(2),
					'action'=>array(
						'?m=distribution&s=admin_distribution_user'=>'所有下级用户',
						'?m=distribution&s=admin_distribution_user&level=1'=>'一级分销',
						'?m=distribution&s=admin_distribution_user&level=2'=>'二级分销',
					)
				)
			)
		);
		*/

		return true;
	}


	public function setMenuShow()
	{
		global $shopconfig;

		unset($shopconfig['menu'][3]);

		return true;
	}

	/**
	 * 注册完成后，判断是否需要建立分佣关系
	 *
	 * @return mixed
	 */
	public function regDone($userid, $user)
	{
		global $config;
		global $distribution_config;
		global $db;

		global $buid;
		global $buser;

		$buid = $userid;
		$buser = $user;

		if ($distribution_config['distribution_open_flag'])
		{
		}
		fb($distribution_config);
		include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");

		$distribution = new distribution();

		$dist_id = 0;

		//检测是否有推广用户id，Cookie记录
		if (isset($_COOKIE['dist_id']))
		{
			$dist_id = intval($_COOKIE['dist_id']);

			//查找$shop_id
			//取得分销店铺商品
			$distribution_row = $distribution->getDistributionUser($dist_id);
			$shop_id = $distribution_row['shop_id'];
		}
		else
		{
			//自然流量，读取默认店家
			$dist_id = $distribution_config['distribution_default_dist_id'];
			$shop_id = $distribution_config['distribution_default_dist_id'];
		}

		if ($dist_id)
		{
			//判断用户是否存在
			include_once("$config[webroot]/module/member/includes/plugin_member_class.php");

			$member = new member();
			$dist_user_row = $member->get_member_detail($dist_id);

			if ($dist_user_row)
			{
				$dist_user = $dist_user_row['user'];
				$distribution->addDistributionUserRelationship($userid, $user, $dist_id, $dist_user);

				//是否为分销用户
				//$distribution->addDistributionUser($userid, -3);
			}
		}

		//是否为分销用户
		$distribution->addDistributionUser($userid, -3, $shop_id);

		//自动开启分销店铺
		if ($distribution_config['distribution_shop_auto_flag'])
		{
			//自动开通分销店铺功能
			include_once("module/shop/includes/plugin_shop_class.php");
			$shop = new shop();
			$shop_statu = $shop->GetShopStatus($shop_id);

			fb('$shop_statu:');
			fb($shop_statu);

			/*
			if(!($shop_statu==1 || $shop_statu==-3))
			{
				return true;
			}
			*/
			//取得上线店铺信息

			$dist_shop_row = $shop->GetShop($shop_id);

			$shop_row = array (
				'submit' => 'edit',
				'company' => $user . '的小店',
				'main_pro' => @$dist_shop_row['main_pro'],
				'catid' => @$dist_shop_row['catid'],
				't' => @$dist_shop_row['area'],
				'province' => @$dist_shop_row['provinceid'],
				'city' => @$dist_shop_row['cityid'],
				'area' => @$dist_shop_row['areaid'],
				'street' => @$dist_shop_row['streetid'],
				'addr' => '',
				'lng' => '',
				'lat' => '',
				'tel' => '',
				'intro' => '',
				'grade' => '1',
			);

			$_POST = array_merge($_POST, $shop_row);


			$re = $shop->update_user();
			unset($_SESSION['shop_type']);

			//自动将上级商品放入分销店铺
			if($shop_statu==1)
			{
				include_once("$config[webroot]/module/product/includes/plugin_product_class.php");
				$product = new product();
				$product_rows = $product->get_shop_pro($shop_id);

				foreach ($product_rows as $product_row)
				{
					if ($product_row['is_dist'])
					{
						$rs = $distribution->addDistributionProduct($buid, $product_row['id'], 1);
					}
				}
			}
			else
			{
				//如果上级无，则随机放入商品
				$sql = 'SELECT * FROM ' . PRODUCT . ' WHERE is_dist=1  ORDER BY RAND() LIMIT 10';
				$db->query($sql);
				$product_rows = $db->getRows();

				foreach ($product_rows as $product_row)
				{
					$rs = $distribution->addDistributionProduct($buid, $product_row['id']);
				}
			}
		}
		else
		{

		}

		//注册分佣
		if ($distribution_config['distribution_reg_flag'] && $dist_id)
		{
			$distribution->onRegCommission($dist_id);
		}

		if (isset($_COOKIE['dist_id']))
		{
			$dist_id = intval($_COOKIE['dist_id']);

			//上线店铺加入收藏夹
			//添加 共享 商铺

			$uid = $buid;

			//修改收藏人气
			$db->query("update ".SHOP." set shop_collect=shop_collect+1 where userid='".$dist_id."'");

			//判断 当前用户 是否 添加 共享 商铺
			$sql="select id from ".SSHOP." where uid=".$uid." and shopid='".$dist_id."'";
			$db->query($sql);
			if($db->num_rows()<=0)
			{
				$uname=$user;
				$shopid=$dist_id;

				if ($dist_id)
				{
					//判断用户是否存在
					include_once("$config[webroot]/module/member/includes/plugin_member_class.php");

					$member = new member();
					$dist_user_row = $member->get_member_detail($dist_id);

					if ($dist_user_row)
					{
						$dist_user = $dist_user_row['user'];
						$shopname=$dist_user;
					}
				}

				$time=time();
				$sql="insert into ".SSHOP." (shopid,shopname,uid,uname,addtime,content,privacy) VALUES ('$shopid','$shopname','$uid','$uname','$time','','0')";
				$db->query($sql);
			}
			else
			{
			}
		}

		//修改跳转地址
		/*
		if (isset($_COOKIE['dist_id']))
		{
			$dist_id = intval($_COOKIE['dist_id']);

			header("Location:" . $config["weburl"] . "/shop.php?uid=$dist_id");

			die();
		}
		*/
	}

	/**
	 * 分销产品列表
	 *
	 * @return mixed
	 */
	public function listDistProduct($user_id)
	{
		global $config;
		global $distribution;

		global $tpl;

		$tpl->assign("dist_user_row", Yf_Registry::get('dist_user_row'));

		//获取详细产品信息
		$dist_rows = $distribution->getDistributionProduct($user_id);


		//根据商品Id获取商品详情
		$produce_id_row = array_filter_key('product_id', $dist_rows);
		$dist_pro = $distribution->getProductInfoNormal($produce_id_row);
		$tpl->assign("dist_pro", $dist_pro);
	}


	/**
	 * 添加分销产品分成信息
	 *
	 * @return mixed
	 */
	public function addDistributionProductInfo($user_id, $produce_id, $commission_product_price_row)
	{
		global $config;
		global $distribution;

		global $tpl;

		$distribution->addDistributionProductInfo($user_id, $produce_id, $commission_product_price_row);
	}

	/**
	 * 编辑分销产品分成信息
	 *
	 * @return mixed
	 */
	public function editDistributionProductInfo($user_id, $produce_id, $commission_product_price_row)
	{
		global $config;
		global $distribution;

		global $tpl;


		$distribution->editDistributionProductInfo($user_id, $produce_id, $commission_product_price_row);
	}

	/**
	 * 玩家确认收货
	 *
	 * @return mixed
	 */
	public function confirmReceivedProduct($order_id, $order_row)
	{
		global $config;
		global $distribution_config;
		global $distribution;

		global $tpl;

		//用户分销产品分佣
		$distribution->confirmReceivedProduct($order_id, $order_row);

		//用户消费分佣
		$distribution_buy_flag = $distribution_config['distribution_buy_flag'];

		if ($distribution_buy_flag)
		{
			$distribution->confirmReceivedBuyProduct($order_id, $order_row);
		}
	}

	/**
	 * 确认退货货
	 *
	 * @return mixed
	 */
	public function confirmReturnProduct($refund_id, $order_id, $product_id, $dist_user_id)
	{
		global $config;
		global $distribution;

		global $tpl;
		$distribution->confirmReturnProduct($refund_id, $order_id, $product_id, $dist_user_id);
	}




	/**
	 * 修改店家商品分销比例
	 *
	 * @return mixed
	 */
	public function editShopCommission($shop_id, $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform)
	{
		global $config;
		global $distribution;
		global $db;


		$flag = $distribution->editDistributionCommissionShop($shop_id, $commission_shop_rate_0, $commission_shop_rate_1, $commission_shop_rate_2, $commission_shop_rate_plantform);

		//批量更新所有商品的分佣比率，如果不允许商家自定义比率的话

		if (1 == $_POST['shop_statu'])
		{
			//将分销着shop_id 更改为自己店铺
			$sql = 'UPDATE ' . DISTRIBUTION_USER . ' SET shop_id=' . $shop_id . ' WHERE user_id=' . $shop_id;

			$re = $db->query($sql);
		}
		else
		{

		}

		return $flag;
	}
}
?>
