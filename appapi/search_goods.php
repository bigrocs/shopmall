<?php
## 传递参数列表
## keyWord【关键字】,order【排序】,page【页码】,number【每页数量】,cid【分类id】

## 返回状态参数列表
## 0【成功】
include_once("../includes/global.php");

if($_SERVER['HTTP_HOST'])
    $host = "http://".$_SERVER['HTTP_HOST']."/";
else
    $host = "http://".$_SERVER['SERVER_NAME']."/";
$url = "http://mall.18bc.com/api/gethttp.php";
$post = array(
    "https" => $host);
$data = curl_post($url,$post);
$data = json_decode($data,true);

if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
//产品
$where =" where 1 ";
$psql = "select a.*,b.jwd as jwd from ".PRODUCT." as a left join mallbuilder_shop as b on a.member_id = b.userid ";
//按关键字查找
if($_POST['keyWord']){
    $where .=" and a.name like '%".$_POST['keyWord']."%' ";
}
//按分类查找
if($_POST['cid']){
    $where .=" and a.catid =".$_POST['cid'];
}
if($_POST['page']){
    $page = $_POST['page'];
}else{
    $page = 1;
}
if($_POST['number']){
    $num = $_POST['number'];
}else{
    $num=10;
}
$firstRow = ($page-1)*$num;
$lastRow = $num;
$limit =" limit ".$firstRow.",".$lastRow;
//排序
$o = $_POST['order'];
$or = "";
if($o=='0')
    $or.=" order by clicks desc,id desc";//综合排序（按点击率综合排序）
elseif($o=='1')
    $or="";//距离排序
elseif($o=='2')
    $or.=" order by price asc,id desc";//按价格由低到高排序
elseif($o=='3')
    $or.=" order by price desc,id desc";//按价格由高到低排序
elseif($o=='4')
    $or.=" order by sales DESC ,id desc ";//按销量排序

$sql1= $psql.$where.$or.$limit;
$db->query($sql1);
$products=$db->getRows();
$count=$db->num_rows();
$re['count'] = $count;
$re['pageIndex'] = $page;
$sql2= $psql.$where.$or;
$db->query($sql2);
$recount=$db->num_rows();
$re['pageTotal']=ceil($recount/$num);

foreach($products as $k=>$pro){

    $product[$k]['longitude'] = $data['lng'];
    $product[$k]['latitude'] = $data['lat'];
    $product[$k]['productId'] = $pro['id'];
    $sql="select cat from mallbuilder_product_cat where catid=".$pro['catid'];
    $db->query($sql);
    $cat=$db->fetchRow();
    $product[$k]['cate'] = $cat['cat'];
    $product[$k]['imageUrl'] = $pro['pic']."_120X120.jpg";
    $product[$k]['productName'] = $pro['name'];
    $product[$k]['price'] = $pro['price'];
    $product[$k]['isShelves'] = $pro['is_shelves'];
    //运费
    if(empty($pro['freight_type']))
    {   //卖家承担运费
        $product[$k]['postPrice'] = "0";
    }else{
        $sql="select * from ".LGSTEMPCON." where temp_id=".$pro['freight_id']." and define_citys = 'default'";
        $db->query($sql);
        $lgs=$db->fetchRow();
        if($lgs)
        {
            $product[$k]['postPrice'] = $lgs['default_price'];
        }
    }
    $asql="select * from mallbuilder_shop where userid=".$pro['member_id'];
    $db->query($asql);
    $sh = $db->fetchRow();
    $product[$k]['area'] = $sh['area'];
    $product[$k]['salesVolume'] = $pro['sales'];
    $product[$k]['shopId'] = $pro['member_id'];
    //店铺名
    $sql = "select * from mallbuilder_shop where userid=".$pro['member_id'];
    $db->query($sql);
    $shop=$db->fetchRow();
    $product[$k]['shopName'] = $shop['company'];
}
$re['list'] = $product;
$re['result'] = 0;
//综合返产品列表
echo json_encode($re);
?>