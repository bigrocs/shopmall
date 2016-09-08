<?php
include_once("../includes/page_utf_class.php");
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
//====================================

	$get=$_GET;
	unset($get['editid']);
	unset($get['s']);
	unset($get['m']);
	unset($get['grade']);
	unset($get['catid']);
	unset($get['operation']);
	$getstr=implode('&',convert($get));
	$tpl->assign("getstr",$getstr);
	
	if($_GET['operation']=='list')
	{
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				foreach($_POST['chk'] as $key=>$userid)
				{
					$db->query("delete from ".SHOP." where userid='$userid'");
					$db->query("delete from ".SSET." where shop_id='$userid'");
					$db->query("delete from ".CUSTOM_CAT." where userid='$userid'");
					$db->query("delete from ".FEED." where userid='$userid'");
					$db->query("delete from ".FEEDBACK." where touserid='$userid' or fromuserid='$userid'");
					$db->query("delete from ".READREC." where userid='$userid'");
					$db->query("delete from ".SHOPEARNEST." where shop_id='$userid'");
				}
			}
			if($_POST['rank'])
			{
				foreach($_POST['rank'] as $key=>$list)
				{
					$db->query("update ".SHOP." set rank='$list' where userid='$key'");		
				}
			}
			msg("?m=shop&s=shop.php&operation=list",'');
		}	
		$sql = " and shop_statu != '0' and shop_statu != '-2'  and shop_statu != '-4' and shop_statu != '-5' ";
		$tpl->assign("de",$shop->GetShopList($sql));
	}
	elseif($_GET['operation']=='edit')
	{		
		if($_POST["act"]=='edit' and is_numeric($_POST['id']))
		{
			$shop->EditShop($_POST['id']);

			$PluginManager = Yf_Plugin_Manager::getInstance();
			$PluginManager->trigger('edit_shop_commission', intval($_POST['id']), $_POST['commission_shop_rate_0'], $_POST['commission_shop_rate_1'], $_POST['commission_shop_rate_2'], $_POST['commission_shop_rate_plantform']);

			//自动更新认证状态
			//判断用户是否提交认证图片

			$shop_statu = $_POST['shop_statu'] ? $_POST['shop_statu'] : "0";

			if (1 == $shop_statu)
			{
				$user_id    = intval($_POST['id']);

				$shop_row = $shop->GetShop($user_id);

				if ($shop_row['shopkeeper_auth_pic'] || $shop_row['shop_auth_pic'])
				{
					$sql        = "";

					$cond_row = array();

					if ($shop_row['shopkeeper_auth_pic'])
					{
						$cond_row[] = 'shopkeeper_auth=1';
					}

					if ($shop_row['shop_auth_pic'])
					{
						$cond_row[] = 'shop_auth=1';
					}


					$sql        = "UPDATE " . SHOP . " SET " . implode(',', $cond_row) . " WHERE userid in ($user_id)";

					$db->query($sql);
				}
			}


			if($_GET['type'])
				msg("?m=shop&s=shop_$_GET[type].php&operation=list",'');
			else
				msg("?m=shop&s=shop.php&operation=list&$getstr");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$de=$shop->GetShop($_GET['editid']);
			
			if($de['stime'])
				$de['stime']=date("Y-m-d",$de['stime']);
			else
				$de['stime']=date("Y-m-d");
			if($de['etime'])
				$de['etime']=date("Y-m-d",$de['etime']);
			else
				$de['etime']=(date("Y")+1).'-'.date("m-d");
			
			//获取店铺商品数量
			$de['product_num']=$shop->GetProductNum($_GET['editid']);
			//获取店铺分类名
			$de['cat']=$shop->GetShopCatName($de['catid']);

			if (in_array($de['shop_statu'], array(0, 1, -2, -1)))
			{
				$shop_type = 1;
				$tpl->assign("shop_type", $shop_type);

				include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");
				$distribution = new distribution();
				$dist_user_row = $distribution->getDistributionUser(intval($_GET['editid']));

				if (!$dist_user_row)
				{
					$dist_user_row['user_id'] = intval($_GET['editid']);
					$dist_user_row['distribution_user_state'] = 0;
				}

				$distribution_user_state = $dist_user_row['distribution_user_state'];

				$tpl->assign("distribution_user_state", $distribution_user_state);

				//获取分销店铺佣金比率
				include_once("$config[webroot]/module/distribution/includes/plugin_distribution_class.php");

				$distribution = new distribution();
				$commission_shop_row = $distribution->getDistributionCommissionShop(intval($_REQUEST['editid']));

				if (!$commission_shop_row)
				{
					$commission_shop_row['commission_shop_rate_0'] = $distribution_config['commission_product_rate_0'];
					$commission_shop_row['commission_shop_rate_1'] = $distribution_config['commission_product_rate_1'];
					$commission_shop_row['commission_shop_rate_2'] = $distribution_config['commission_product_rate_2'];
					$commission_shop_row['commission_shop_rate_plantform'] = $distribution_config['commission_product_rate_plantform'];
				}

				$de = array_merge($de, $commission_shop_row);
			}
			else
			{
				$shop_type = 0;
				$tpl->assign("shop_type", $shop_type);
			}

			$tpl->assign("de",$de);
			
			//-------------推荐
			$rc_member = array(
				'0'=>$lang['norec'],
				'1'=>$lang['reccom'],
				'2'=>$lang['startcom'],
			);
			$tpl->assign("rc_member",$rc_member);
		}
	}
	//获取店铺类型
	$tpl->assign("grade",$shop->GetShopGradeList());
	//获取店铺分类
	$tpl->assign("catlist",$shop->GetShopCatList());
	//获取地区
	$tpl->assign("prov",GetDistrict());
	
	$tpl->assign("config",$config);
	$tpl->display("shop.htm");
?>