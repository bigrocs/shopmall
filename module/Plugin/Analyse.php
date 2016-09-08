<?php
/**
 * 访问分析模块（流量分佣）
 *
 *
 * @category   Framework
 * @package    Plugin
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Plugin_Analyse implements Yf_Plugin_Interface
{
	//解析函数的参数是pluginManager的引用
	public function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('init', $this, 'init');
		Yf_Plugin_Manager::getInstance()->register('analyse',  $this, 'analyse');
	}
	
	public function desc()
	{
		echo 'Hello World';
	}

	public function init()
	{
		global $config;
	}

	/**
	 * 程序尾部触发事件，在在店铺访问、商品访问页面触发-》需要有分销者id
	 *
	 * @return mixed
	 */
	public function analyse($shop_id, $product_id, $distribution_analyse_name)
	{
		global $config;

		$buid = Yf_Registry::get('buid');

		//判断是否分销产品，不可以自己点击自己的
		if (isset($_REQUEST['dist_id']))
		{
			$dist_user_id = intval($_REQUEST['dist_id']);

			if ($buid == $dist_user_id)
			{
				return false;
			}
		}

		$cache_dir = $config['webroot'] . '/cache/analyse/';
		make_dir_path($cache_dir);

		//设置cache 参数
		//cacheType 1:file  2:memcache   3：redis
		$config_cache['analyse'] = array(
			'cacheType' => 1,
			'cacheDir' => $cache_dir,
			'memoryCaching' => false,
			'automaticSerialization' => true,
			'hashedDirectoryLevel' => 3,
			'hashedDirectoryUmask' => 0777,
			'cacheFileMode' => 0777,
			'lifeTime' => 1
		);

		$analyse_cache = new Cache_Lite_Output($config_cache['analyse']);
		//Yf_Registry::set('analyse_cache', $analyse_cache);
		$ip = getip();

		$rs = $analyse_cache->get($ip);

		if ($rs)
		{
			$num = $rs + 1;

			$rs = $analyse_cache->save($num);
			fb("无效访问：");
			fb($num);
		}
		else
		{
			$rs = $analyse_cache->save(1);

			//计算分佣
			fb("有效访问");
			fb('$shop_id:' . $shop_id);
			//记录入库一次
			//在在店铺访问、商品访问页面触发-》需要有分销者id
			global $distribution;
			if ($distribution && $shop_id)
			{
				if (isset($_REQUEST['dist_id']) && $dist_user_id=intval($_REQUEST['dist_id']))
				{
					$distribution->editDistributionAnalyseShop($shop_id, $dist_user_id, $product_id, $distribution_analyse_name);
				}
				else
				{
					fb("tongji error");
				}
			}

		}
	}
}
//
/*
ALTER TABLE `mallbuilder_distribution_analyse_shop`
CHANGE COLUMN `shop_id` `distribution_analyse_id`  mediumint(8) NOT NULL COMMENT '用户Id' FIRST;

ALTER TABLE `mallbuilder_distribution_analyse_shop`
ADD COLUMN `distribution_analyse_type`  tinyint(1) NULL DEFAULT 1 COMMENT '分享类型 1:店铺  2:商品' AFTER `distribution_analyse_settlement_flag`,
ADD COLUMN `distribution_analyse_name`  varchar(50) NULL COMMENT '分享的店铺名称或者商品名称' AFTER `distribution_analyse_type`;
*/
?>
