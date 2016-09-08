<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_product_class.php");
@include_once($config['webroot']."/config/taobao_config.php");
$pro=new product();
//============================================================================
if(!empty($deid))
{
	$pro->del_pro($deid);
}
if(isset($_GET['cstatu'])&&!empty($_GET['pid']))
	$pro->set_pro_statu($_GET['pid'],$_GET['cstatu']);
$tpl->assign("re",$pro->pro_list(1));

if(is_numeric($_GET['id']) && $_GET['act'] == 'taobao')
{
	$id = $_GET['id']*1;
	$appKey = $taobao_config['appkey'];
	$appSecret = $taobao_config['secretKey'];
	$sessions = $_SESSION['topsession'];
	if(!$_SESSION['topsession'])
	{
		$url = "https://oauth.taobao.com/authorize?response_type=code&client_id=$appKey&redirect_uri=".$config['weburl']."/module/product/login.php";
		header("location: $url");
		die;
	}
	$de = $pro -> product_detail($id);
	if($de['id'])
	{	
		$sql = "select taobao_type_id,ext_table from ".PCAT." a left join ".TYPE." b on a.ext_field_cat = b.id where catid = '$de[classid]'";
		$db->query($sql);
		$re = $db->fetchRow();
		$ext_table = $re['ext_table'];
		$taobao_type_id = $re['taobao_type_id'];
		if(!$taobao_type_id)
		{
			msg("main.php?m=product&s=admin_product_list","此商品不支持淘宝同步");die;
		}
		$amount = $de['stock']*1;
		$price = $de['price']*1;
		$title = $de['name'];
		$detail = $de['detail'] ? $de['detail'] : $config['company'];
		$area = explode(' ',$de['area']);
		$province = $area[0];
		$city = $area[1];
		$pic = $de['pic_more'];
			
		if($de['porperty'])
		{
			foreach($de['porperty'] as $val)
			{
				$spec_1 = "";
				foreach($val['setmeal'] as $k => $v)
				{
					$spec_value_id = key($v);
					$sql = "select spec_id , taobao_spec_id , name from ".SPECVALUE." where id = '$spec_value_id'";
					$db->query($sql);
					$re = $db->fetchRow();
					$taobao_spec_value_id = $re['taobao_spec_id'];
					
					$sql = "select taobao_spec_id from ".SPEC." where id = '$re[spec_id]'";
					$db->query($sql);
					$taobao_spec_id = $db->fetchField('taobao_spec_id');
					$spec_1[] = $taobao_spec_id.":".$taobao_spec_value_id;
					
					$property_select[] = $taobao_spec_id.":".$taobao_spec_value_id;
						
					if($re['name'] != $v[$spec_value_id])
						$property_alias[] = $taobao_spec_id.":".$taobao_spec_value_id.":".$v[$spec_value_id];
					if($property_alias)
						$property_alias = array_unique($property_alias);
				}
				$spec[] = $spec_1 ? implode(";",$spec_1) : $spec_1;
				$spec_stock[] = $val['stock'];
				$spec_price[] = $val['price'];
				$spec_sku[] = $val['sku'];
			}
			
			$property_alias = $property_alias ? implode(';',$property_alias) : "";
			$spec_stock = $spec_stock ? implode(',',$spec_stock) : "";
			$spec_price = $spec_price ? implode(',',$spec_price) : "";
			$spec_sku = $spec_sku ? implode(',',$spec_sku) : "";
		}

		$sql = "select * from $ext_table where product_id = '$de[id]'";
		$db -> query($sql);
		$re = $db -> fetchRow();
		if($re)
		{
			foreach($re as $key => $val)
			{
				$field = explode('_',$key);
				if($field['0'] == 'property')
				{
					$property_id = $field[1];
					
					$sql = "select taobao_property_id,format from ".PROPERTY." where id = '$property_id'";
					$db->query($sql);
					$re = $db->fetchRow();
					$taobao_property_id = $re['taobao_property_id'];
					
					if($taobao_property_id)
					{
						if($re['format'] == 'text')
						{
							//$property_text[] = $taobao_property_id;
						}
						if($re['format'] == 'select')
						{	
							$sql = "select taobao_property_id from ".PROPERTYVALUE." where id = '$val'";
							$db->query($sql);
							$taobao_property_value_id = $db->fetchField('taobao_property_id');
							$property_select[] = $taobao_property_id.":".$taobao_property_value_id;
						}
						if($re['format'] == 'checkbox')
						{	
							$val = $val ? explode(",",$val) : "";
							foreach($val as $l)
							{
								$sql = "select taobao_property_id from ".PROPERTYVALUE." where id = '$l'";
								$db->query($sql);
								$arr[] = $db->fetchField('taobao_property_id');
							}
							$taobao_property_value_id = $arr ? implode(',',$arr) : "";
							$property_select[] = $taobao_property_id.":".$taobao_property_value_id;
						}
					}
				}
			}
		}

		$property_select = $property_select ? implode(';',$property_select) : "";
		$property_text = $property_text ? implode(',',$property_text) : "";
		$spec = $spec ? implode(',',$spec) : "";
	
		include_once($config['webroot']."/lib/taobao/TopSdk.php");
		$c = new TopClient;		
		$c->appkey = $appKey;
		$c->secretKey = $appSecret;
		$sessionKey = $sessions;
		$req = new ItemAddRequest;
		$req->setNum($amount);					//商品数量
		$req->setPrice($price);					//商品价格
		$req->setType("fixed");					//发布类型 一口价
		$req->setStuffStatus("new");			//新旧程度
		$req->setTitle($title);					//宝贝标题
		$req->setDesc($detail);					//宝贝描述
		$req->setLocationState($province);		//所在地省份
		$req->setLocationCity($city);			//所在地城市
		$req->setApproveStatus("instock");		//商品上传后的状态 onsale instock
		$req->setCid($taobao_type_id);			//叶子类目ID
		$req->setProps($property_select);		//商品属性列表 pid:vid;pid:vid
		$req->setInputPids($Pids);				//用户自行输入的类目属性ID串 pid1,pid2,pid3
		$req->setPropertyAlias($property_alias);//属性值别名
		$req->setSkuProperties($spec);			//更新的Sku的属性串 pid1:vid;pid2:vid
		$req->setSkuQuantities($spec_stock);	//Sku的数量串 num1,num2,num3
		$req->setSkuPrices($spec_price);		//Sku的价格串 10.00,5.00
		$req->setSkuOuterIds($spec_sku);		//Sku的外部id串 1234,1342
		$req->setInputStr($property_text);
		$resp = $c->execute($req, $sessionKey);
		$resp = (array)$resp;
		if($resp['item'])
		{
			$pic = $pic ? explode(',',$pic) : "";
			if($pic)
			{
				$arr = (array)$resp['item'];
				$iid = $arr['iid'];
			
				$req = new ItemImgUploadRequest;
				foreach($pic as $k => $v)
				{
					$v = str_replace($config['weburl'], $config['webroot'], $v);
					$req->setImage("@".$v);
					$req->setNumIid($iid);
					if($k == 0)
						$req->setIsMajor("true");
					$resp = $c->execute($req, $sessionKey);	
				}
			}
		}
		if($resp['code'])
		{
			if($resp['code'] == '53')
			{
				$url = "http://container.api.taobao.com/container?appkey=$appKey&ref=main.php";
				header("location: $url");
			}
			else
			{
				$admin->msg("main.php?m=product&s=admin_product_list",$resp['sub_msg']? $resp['sub_msg'] : $resp['msg']);	
			}
		}
		else
		{
			$sql="insert into ".TAOBAO." (member_id,product_id) values ('$buid','$id')";
			$db->query($sql);
			$admin->msg("main.php?m=product&s=admin_product_list","同步成功");		
		}
	}
	else
	{
		$admin->msg("404.php","此商品不存在");	
	}	
	
}
//==================================
$nocheck=true;
$tpl->assign("taobao_config",$taobao_config);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_product_list.htm");
?>