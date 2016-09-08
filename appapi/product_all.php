<?php
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_encode(file_get_contents("php://input"));
//产品
$where =" where 1 ";
$psql = "select a.*,b.cat as cat from mallbuilder_product as a left join mallbuilder_product_cat as b on a.catid = b.catid  ";
if(isset($_POST['catid']) && $_POST['catid'] != 0){
	$where .=" and a.catid = ".$_POST['catid'];
}

//控制产品查询个数 假设传来的limit是“limit=1,19”的样式
if($_POST['limit']){
    $lim = array();
    $lim = explode(',',$_POST['limit']);
    $limit='';
    if(count($lim)==1){
        $limit="limit 0,".$lim[0];
    }
    if(count($lim)==2){
        $limit="limit ".$lim[0].",".$lim[1];
    }
}
//按关键字查找
if($_POST['key']){
    $where .=" and name like %".$_POST['key']."%";
}

//排序
$o = $_POST['order'];
if($o=='1')
    $or.=" order by sales DESC ,id desc ";//按销量排序
elseif($o=='2')
    $or.=" order by price asc,id desc";//按价格由低到高排序
elseif($o=='3')
    $or.=" order by price desc,id desc";//按价格由高到低排序
elseif($o=='4')
    $or.=" order by clicks desc,id desc";//按点击率排序
elseif($o=='5')
    $or.=" order by goodbad desc,id desc";//按评价排序
else
    $or=" order by a.uptime DESC,id desc";//按时间排序

$psql= $psql.$where.$limit.$or;

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