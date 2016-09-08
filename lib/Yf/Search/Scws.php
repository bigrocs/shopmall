<?php
/**
 * Sphinx Scws中文分词
 *
 * @category   Framework
 * @package    Search
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Yf_Search_Scws
{

	private $so = null;

	/**
	 * 构造函数
	 *
	 * @access    private
	 */
	public function __construct($key)
	{
		$this->so = scws_new();
		$this->so->set_charset('utf-8');
		//默认词库
		$this->so->add_dict(ini_get('scws.default.fpath') . '/dict.utf8.xdb');
		//自定义词库
		// $this->so->add_dict('./dd.txt',SCWS_XDICT_TXT);

		//默认规则
		$this->so->set_rule(ini_get('scws.default.fpath') . '/rules.utf8.ini');

		//设定分词返回结果时是否去除一些特殊的标点符号
		$this->so->set_ignore(true);

		//设定分词返回结果时是否复式分割，如“中国人”返回“中国＋人＋中国人”三个词。
		// 按位异或的 1 | 2 | 4 | 8 分别表示: 短词 | 二元 | 主要单字 | 所有单字
		//1,2,4,8 分别对应常量 SCWS_MULTI_SHORT SCWS_MULTI_DUALITY SCWS_MULTI_ZMAIN SCWS_MULTI_ZALL
		$this->so->set_multi(false);

		//设定是否将闲散文字自动以二字分词法聚合
		$this->so->set_duality(true);

		$this->sendText($key);
	}

	/**
	 *
	 * @access public
	 */
	public function sendText($key)
	{
		$this->so->send_text($key);
	}

	/**
	 *
	 * @access public
	 */
	public function getResult()
	{
		$words_array = $this->so->get_result();;

		$words = "";

		foreach($words_array as $v)
		{
			$words = $words.'|('.$v['word'].')';
		}

		//加入全词
		#$words = '('.$key.')'.$words;
		$words = trim($words,'|');

		//echo '<p>输入：'.$key.'</p>';
		//echo '<p>分词：'.$words.'</p>';
		return $words;
	}

	/**
	 *
	 * @access public
	 */
	public function __destruct()
	{
		$this->so->close();
	}
}
?>