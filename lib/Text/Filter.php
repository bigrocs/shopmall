<?php
class Text_Filter
{
	var $words;  //文字
	function Text_Filter()
	{
		$this->words=NULL;
	}
	
	function wordsFilter(&$message=null, &$matche_row=array())
	{
		//$matche_row 非法词组
		if(empty($this->words))
		{

			$config = Yf_Registry::get('config');
			@include_once($config['webroot'].'/config/filter.inc.php');
			$this->words =& $_CACHE['word_filter'];
		}
		else
		{
			
		}

		$message = empty($this->words['filter']) ? $message : @preg_replace($this->words['filter']['find'], $this->words['filter']['replace'], $message);

		if($this->words['banned'] && preg_match($this->words['banned'], $message, $matche_row))
		{
			foreach($matche_row as $v)
			{
				$message = $message . $v .',';
			}

			return false;
		}
		else
		{
			return true;//被替换后的信息
		}
	}
}
?>