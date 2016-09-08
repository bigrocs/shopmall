<?php
//------------单项物流价格----------------
function get_log_price($lgid,$area)
{
	global $db;
	if(strlen($area)>6) $city=substr($area,6,strlen($area)-6);
	else $city=$area;
	$city=$city?','.$city:$city;

	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='mail'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys='default' and logistics_type='mail'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str=$re['default_price']?"平邮:$re[default_price]元 ":"";

$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='express'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys='default' and logistics_type='express'";
		$db->query($sql);
		$re=$db->fetchRow();

	}
	$str.=$re['default_price']?"快递:$re[default_price]元 ":"";

	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='ems'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys='default' and logistics_type='ems'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str.=$re['default_price']?"EMS:$re[default_price]元 ":"";
	return $str;
}
//-----------------------------------产品详情
include_once($config['webroot']."/module/product/includes/plugin_product_class.php");
$id=$_GET["id"]*1;
//-----------------------------------
user_read_rec($buid,$id,1);//记录会员查看商品
//-----------------------------------    20150624 lemons 商品被删除后从订单点进去，进入快照
if(is_numeric($_GET["order_id"])&& $id &&is_numeric($_GET["pid"]))
{
    $sql="select * from ".SNAPSHOT." where order_id = '$_GET[order_id]' and id = '$_GET[pid]'";
    $db->query($sql);
    $de = $db->fetchRow();
    $de['extfiled'] = $prode['extfiled'];
    $de['time'] = date("Y年m月d日 H点i分s秒",$prode['uptime']);
    if(!empty($de['member_id']))
    {
        $_GET['uid']=$de['member_id'];
        $_GET['action']='snapshot';
        include($config['webroot'].'/shop.php');
        die;
    }
}
//-----------------------------------
//---如果商品不存在跳转回首页或商家自己商品
$sql = "select p.*,s.user from ".PRODUCT." p, ".SHOP." s where s.userid=p.member_id and p.id =".$id;
$db -> query($sql);
if(!$db -> num_rows())
{
	msg($config['weburl'],"商品不存在");
	exit;
}
$ress=$db->fetchRow();
$tpl->assign("shop_user",$ress['user']);

$prodetail=new product();
$prode=$prodetail->detail($id);

if (isset($_REQUEST['dist_id']))
{
	$shop_id = $prode['member_id'];

	if ($shop_id)
	{
		$PluginManager = Yf_Plugin_Manager::getInstance();
		$PluginManager->trigger('analyse', $shop_id, $id, $prode['name']);
	}
}

//-----------------------------------
$sql="select isbuy,ext_table from ".PCAT." where catid='$prode[catid]'";
$db->query($sql);
$current_cat=$db->fetchRow();
//-----------------------------------扩展字段
include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
$addfield = new AddField('product');
$prode['extfiled']=$addfield->addfieldinput($id,$current_cat['ext_table'],true);
//----------------------------------用户区获取
$prode['user_ip']=convertip(getip());
if($prode['user_ip']=='- LAN') $prode['user_ip']='上海';
//----------------------------------跟据所在地自动算出的运费
$prode['freight_count']=get_log_price($prode['freight_id'],$prode['user_ip']);
//----------------------------------
if(!empty($prode['member_id']))
{
	$_GET['uid'] = $prode['member_id'];
	$_GET['action'] = 'product_detail';
	include($config['webroot'].'/shop.php');
}
else
{
	msg($config['weburl'].'/404.php');
}
?>