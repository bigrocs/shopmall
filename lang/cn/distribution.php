<?php
global $lang;

if (!isset($lang))
{
	$lang = array();
}

global $_LANG_DIST;
$_LANG_DIST = array(
	'distribution_level_all' => '所有',
	'distribution_level_1' => '一级用户',
	'distribution_level_2' => '二级用户',


	'distribution_list'=>'分销商品',
	'distribution_user_next_level'=>'下级分销',

	'distribution_settlement_all'=>'全部',
	'distribution_settlement_done'=>'已经算',
	'distribution_settlement_apply'=>'审核中'
	);

$lang       = array_merge($lang, $_LANG_DIST);
?>