<?php
/**
 * 聊天功能
 *
 *
 * @category   Framework
 * @package    Plugin
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Plugin_Chat implements Yf_Plugin_Interface
{
	//解析函数的参数是pluginManager的引用
	public function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('init', $this, 'init');
		Yf_Plugin_Manager::getInstance()->register('chat',  $this, 'chat');
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
	 * 聊天调用
	 *
	 * @return mixed
	 */
	public function chat($shop_id, $product_id, $distribution_analyse_name)
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
	}
}
?>
