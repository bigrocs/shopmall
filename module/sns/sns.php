<?php

	include_once("$config[webroot]/module/sns/includes/plugin_sns_class.php");
	$sns=new sns();
	if($_GET['curpage'])
	{
		echo "<div id='module'>".$sns->get_sns()."</div>";die;
	}
	
	if(!empty($_POST['bid'])&&is_numeric($_POST['bid'])&&$_POST['op']=='del')
	{
		if($_POST['act']=='comment')
		{
			$sns->del_sns_comment($_POST['bid']);
		}
		else
		{
			$sns->del_sns($_POST['bid']);
		}
	}
	if(!empty($_POST['act']))
	{
		if($_POST['act']=='comment')
		{

			$sns->add_sns_comment($_POST['act']);
		}
		else
		{
			$sns->add_sns($_POST['act']);
		}
	}
	
	if($_GET['op']=="sharegoods")
	{
		include_once("$config[webroot]/module/sns/includes/plugin_share_class.php");
		$share = new share();

		if ($_GET['pid'])
		{
			$tpl->assign("de", $share->GetProduct($_GET['pid']));
		}
		else
		{
			//购买后的产品也能评论
			include_once("$config[webroot]/includes/page_utf_class.php");
			include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
			$order = new order();

			$status = isset($_GET['status']) ? $_GET['status'] : "";
			$re     = $order->buyorder($status);

			$share_pro = array();
			$share_pro = $share->GetShareGoods();

			if ($re && $re['list'])
			{
				foreach ($re['list'] as $order_rows)
				{
					foreach ($order_rows['product'] as $product_row)
					{
						$row = array();
						$row['pid'] = $product_row['pid'];
						$row['image'] = $product_row['pic'];
						$row['pname'] = $product_row['name'];
						$share_pro[] = $row;
					}
				}
			}

			$tpl->assign("re", $share_pro);
		}
		
		$page="admin_sharegoods.htm";
	}
	
	if($_GET['op']=="shareshop")
	{
		include_once("$config[webroot]/module/sns/includes/plugin_share_class.php");
		$share=new share();
		$tpl->assign("re",$share->GetShareShop());
		$page="admin_shareshop.htm";
	}
	
	if($_GET['op']=="forward")
	{
		$page="admin_forward.htm";
	}
	
	if($_GET['op']=="comment")
	{
		$page="admin_comment.htm";
	}
	if(!empty($page))
	{
		$tpl->assign("config",$config);
		$tpl->assign("lang",$lang);
		$output=tplfetch($page,'$flag',true);
	}
?>