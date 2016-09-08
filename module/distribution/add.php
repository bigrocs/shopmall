<?php

include_once("../../includes/global.php");

$product_id = intval($_REQUEST['pid']);

$p_rows = $distribution->getDistributionProduct($buid, $product_id);

if ($p_rows)
{
	echo 1;
}
else
{
	//需要判断产品是否为分销产品
	$rs = $distribution->addDistributionProduct($buid, $product_id);

	if ($rs)
	{
		echo 2;
	}
	else
	{
		echo -1;
	}
}



