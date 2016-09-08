<?php 
 if(!isset($lang))
	$lang=array();
 global $_LANG_PAYMENT; 
 $_LANG_PAYMENT = array (
  'alipay' => '支付宝',
  'alipay_desc' => '支付宝 即时到账',
  'alipay_interface' => '选择接口类型',
  'alipay_interface_options' => 
  array (
    0 => '使用即时到帐交易接口',
  ),
  'seller_email' => '支付宝帐户',
  'key' => '交易安全校验码',
  'partner' => '合作者身份ID',
  
  'cbp' => '网银在线',
  'cbp_account' => '商户编号',
  'cbp_desc' => '网银在线',
  'cbp_key' => 'MD5 密钥',

  'account' => '预存款支付',
  'account_desc' => '预存款支付',
  
  'cards' => '充值卡',
  'cards_desc' => '充值卡',
  
  'tenpay' => '财付通',
  'tenpay_account' => '财付通商户号',
  'tenpay_desc' => '财付通 即时到账',
  'tenpay_key' => '财付通密钥',
  'tenpay_magic_key' => '自定义签名',
  
  'ccb' => '建设银行',
  'ccb_desc' => '建设银行',
  'ccb_account' => '商户号',
  'ccb_posid' => '柜台号',
  'ccb_branchid' => '分行号',
  'ccb_type' => '接口类型',
  'ccb_key1' => '公钥前30位',
  'ccb_key2' => '公钥后30位',
  'ccb_key' => '公钥',
  
  'icbc' => '工商银行',
  'icbc_account' => '商户帐号',
  'icbc_desc' => '工商银行',
  'icbc_id' => '商户代码',
  
  'bc' => '中国银行',
  'bc_account' => '商户号',
  'bc_desc' => '中国银行',
  'bc_key' => '证书密码',
  
  'wap_alipay' => '手机支付宝',
  'wap_alipay_seller_email' => '支付宝帐户',
  'wap_alipay_desc' => '手机支付宝',
  'wap_alipay_partner' => '合作者身份ID',
  'wap_alipay_key' => '交易安全校验码',

  'wx_pay' => '微信支付',
  'APPID' => '微信公众号身份',
  'MCHID' => '受理商ID',
  'KEY' => '商户支付密钥',
  'APPSECRET' => '开发模式下的秘钥',
  
  //新增开始
  'anion_pay' => '通联支付',
  'key1' => '支付秘钥',
  'merchantId' => '商户号',
  //新增结束
  
); 
  $lang = array_merge($lang, $_LANG_PAYMENT); 
?>