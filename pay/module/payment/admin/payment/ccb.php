<?php

if (isset($modules_loading) && $modules_loading == true)
{
	$i = isset($modules_list) ? count($modules_list) : 0;
	$modules_list[$i]['payment_name'] = basename(__FILE__, '.php');
	$modules_list[$i]['payment_commission'] = '0';
	$modules_list[$i]['author'] = 'MaLL-builder';
	$modules_list[$i]['site'] = 'http://www.ccb.com';
	$modules_list[$i]['version'] = '1.0.0';
	$modules_list[$i]['payment_config'] = array(
		array('name' => 'ccb_account', 'type' => 'text', 'value' => '', 'option' => '', 'option_value' =>''),
		array('name' => 'ccb_posid', 'type' => 'text', 'value' => '', 'option' => '', 'option_value' =>''),
		array('name' => 'ccb_branchid', 'type' => 'text', 'value' => '', 'option' => '', 'option_value' =>''),
		array('name' => 'ccb_type', 'type' => 'select', 'value' =>'', 'option' =>'MD5接口|密钥接口|防钩鱼接口','option_value'=>'|ccb_key1|ccb_key2'),
		array('name' => 'ccb_key', 'type' => 'text', 'value' =>'', 'option' =>'', 'option_value' =>'')
    );
	
}
?>