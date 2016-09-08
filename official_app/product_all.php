<?php
include_once("../includes/global.php");

//产品
$where =" where 1 ";
$psql = "select a.*,b.cat as cat from mallbuilder_product as a left join mallbuilder_product_cat as b on a.catid = b.catid  ";
if(isset($_GET['catid']) && $_GET['catid'] != 0){
	$where .=" and a.catid = ".$_GET['catid'];
}

$psql .= $where;
$db->query($psql);
$pro=$db->getRows();



//产品分类
$sql="select * from ".PCAT." where 1 and catid<9999 order by nums,catid";
$db->query($sql);
$de=$db->getRows();
foreach($de as $k=>$v)
{
	$sql="select name from ".PROPERTY." where id='$v[ext_field_cat]'";
	$db->query($sql);
	$de[$k]['property']=$db->fetchField('name');
	
	$tsql=" and catid < '".$v['catid']."99' and catid >'".$v['catid']."00' ";
	$sql="select * from ".PCAT." where 1 $tsql order by nums,catid";
	$db->query($sql);
	$a=$db->getRows();
	foreach($a as $ks=>$vs)
	{
		$sql="select name from ".PROPERTY." where id='$vs[ext_field_cat]'";
		$db->query($sql);
		$a[$ks]['property']=$db->fetchField('name');
		
		
		$tsql=" and catid < '".$vs['catid']."99' and catid >'".$vs['catid']."00' ";
		$sql="select * from ".PCAT." where 1 $tsql order by nums,catid";
		$db->query($sql);
		$b=$db->getRows();
		foreach($b as $kss=>$vss)
		{
			$sql="select name from ".PROPERTY." where id='$vss[ext_field_cat]'";
			$db->query($sql);
			$b[$kss]['property']=$db->fetchField('name');

			$tsql=" and catid < '".$vss['catid']."99' and catid >'".$vss['catid']."00' ";
			$sql="select * from ".PCAT." where 1 $tsql order by nums,catid";
			$db->query($sql);
			$c=$db->getRows();
			
			foreach($c as $ksss=>$vsss)
			{
				$sql="select name from ".PROPERTY." where id='$vsss[ext_field_cat]'";
				$db->query($sql);
				$c[$ksss]['property']=$db->fetchField('name');
			}
			$b[$kss]['scat']=$c;
		}
		$a[$ks]['scat']=$b;
	}
	$de[$k]['scat']=$a;
}

//综合返回栏目和产品列表
$list = array();
$list[0] =$pro;
$list[1] = $de;
echo json_encode($list);
	
?>