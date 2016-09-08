<?php
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_encode(file_get_contents("php://input"));
$where =" where 1 ";
$ssql = " select a.*,b.name as catname from mallbuilder_shop as a left join mallbuilder_shop_cat as b on a.catid=b.id ";

if(isset($_POST['catid']) && !empty($_POST['catid'])){
	$catid = $_POST['catid'];
	$where .= " and a.catid = ".$catid ;
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
//按公司名查找
if($_POST['key']){
    $where .=" and company like %".$_POST['key']."%";
}

//按公司经纬度查找
if($_POST['jwd']){
    $jwd = explode(',',$_POST['jwd']);
    if(count($lim)==2){
        $where .=" and Longitude between".($jwd[0]-200)." and ".( $jwd[0]+200);
        $where .=" and Latitude between".($jwd[1]-200)." and ".( $jwd[1]+200);
    }
}

//排序
$o = $_POST['order'];
if($o=='1')
    $or.=" order by rank DESC ,userid desc ";//按排名排序
elseif($o=='2')
    $or.=" order by grade desc,userid desc";//按店铺等级排序
elseif($o=='3')
    $or.=" order by shop_collect desc,userid desc";//按收藏量由高到低排序
elseif($o=='4')
    $or.=" order by credit desc,userid desc";//按信用服务排序
else
    $or=" order by a.uptime DESC,userid desc";//按时间排序


$ssql= $ssql.$where.$limit.$or;

$db->query($ssql);
$shop=$db->getRows();

//分类
	
$catsql = " select id,name from mallbuilder_shop_cat ";
$db->query($catsql);
$cat=$db->getRows();

//综合返回
$list = array();
$list[0] =$shop;
$list[1] = $cat;

echo json_encode($list);

?>
