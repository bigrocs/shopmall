<?php
/**
 * 积分插件
 *
 *
 * @category   Framework
 * @package    Plugin
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Plugin_Point implements Yf_Plugin_Interface
{
	//解析函数的参数是pluginManager的引用
	public function __construct()
	{
		Yf_Plugin_Manager::getInstance()->register('reg_done', $this, 'regDone');
		Yf_Plugin_Manager::getInstance()->register('end',  $this, 'end');
		Yf_Plugin_Manager::getInstance()->register('point_analyse',  $this, 'addAnalysePoint');
	}
	
	public function desc()
	{
		echo 'Hello World';
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

		//注册者送积分


		$dist_id = 0;

		//检测是否有推广用户id，Cookie记录
		if (isset($_COOKIE['dist_id']))
		{
			//上线送积分
		}
		else
		{
		}
	}

	/**
	 * 程序尾部触发事件
	 *
	 * @return mixed
	 */
	public function end()
	{
		//add_dist_user_id();
	}


	/**
	 * 程序尾部触发事件
	 *
	 * @return mixed
	 */
	public function addAnalysePoint()
	{
		if (isset($_REQUEST['dist_id']))
		{

		}
	}
}

?>
