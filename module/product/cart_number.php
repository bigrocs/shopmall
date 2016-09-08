<?php
if($_GET['act']=='list')
{	
	if($buid)
	{
		@include_once("$config[webroot]/module/product/includes/plugin_cart_class.php");
		$cart = new cart();
		$re = $cart -> get_cart_list('');
		if($re['cart'])
		{
			$str = "<ul>";
			foreach($re['cart'] as $k => $v)
			{
				foreach($v['prolist'] as $key => $val)
				{
					$class = ($key==0)?" first":"";
					$str .= "<li class='fn-clear $class'>
					<div class='mini-cart-img'><a target='_blank' href='$config[weburl]/?m=product&s=detail&id=$val[pid]'><img width='40' src='$val[pic]_60X60.jpg' /></a></div>
					<div class='mini-cart-title'><a target='_blank' href='$config[weburl]/?m=product&s=detail&id=$val[pid]'>".csubstr($val['pname'],0,25)."</a></div>
					<div class='mini-cart-count'>$config[money]<span>$val[price]</span></div>
					</li>";	
				}
			}
			$str .= "</ul>";
			echo $str;
		}
		else
		{
			echo "<ul><li class='first'>您购物车里还没有任何宝贝。</li></ul>";	
		}
	}
	else
	{
		echo "<ul><li class='first'>您购物车里还没有任何宝贝。</li></ul>";	
	}
}
else
{
	if(!empty($_COOKIE['cartnumt']))
	{
		$num=count(explode('|',$_COOKIE['cartnumt']))-1;
	}
	elseif($buid)
	{
		$sql="select sum(quantity) as nums from ".CART." where buyer_id='$buid'";
		$db->query($sql);
		$num=$db->fetchField('nums');
	}
	$num*=1;
	echo "document.write('".$num."')";
}
?>