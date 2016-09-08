<?php
## 传递参数列表
##

##
##

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

$psql = "select a.*,b.company as company from ".PRODUCT." as a left join ".SHOP."  as b on a.member_id = b.userid ";
//按修改时间查找
if($_GET['beginDate'] && $_GET['endDate'])
{
    $start = strtotime($_GET['beginDate']);
    $end = strtotime($_GET['endDate']);
    $time = " and a.uptime > $start and a.uptime <= $end";
}
//按店铺查找
if(!empty($_GET['sid']))
{
    $where =" where a.member_name = '$_GET[sid]' and";
}
else
{
    $where = "where";
}

//按商品是否上架查找
if($_GET['goodsStatus'])
{
    if($_GET['goodsStatus'] == 1)
    {
        $where .=" a.is_shelves=1 ";
    }
    elseif($_GET['goodsStatus'] == 2)
    {
        //ERP中的下架是2，而mall中下架是0
        $where .=" and a.is_shelves=0 ";
    }
}
//按商品ID查找
if($_GET['goodsId'])
{
    if (strstr ($_GET['goodsId'], ','))
    {
        $where .=" and a.id in($_GET[goodsId]) ";
    }
    else
    {
        $where .=" and a.id= $_GET[goodsId] ";
    }
}

//按商品名称查找
if($_GET['goodsName'])
{
    $where .=" and a.name like '%".$_GET['goodsName']."%' ";
}
$sql1= $psql.$where . $time;
$db->query($sql1);
$products=$db->getRows();
$count=$db->num_rows();
$re['count'] = $count;
$sql2= $psql.$where;
$db->query($sql2);
$recount=$db->num_rows();
//$re['pageTotal']=ceil($recount/$num);

foreach($products as $k=>$pro)
{

    $product[$k]['productId'] = $pro['id'];

    $sql="select * from mallbuilder_product_setmeal where pid=$pro[id]";

    $db->query($sql);
    $sku= $db->getRows();
    $product[$k]['sku'] = $sku;
    $str = "";
    if(empty($sku))
    {
        $product[$k]['stock'] = $pro['stock'];

    }
    foreach ($sku as $ks=>$vs)
    {
        $sql="select spec_id from mallbuilder_spec_value where id in($vs[property_value_id])";
        $db->query($sql);
        $spec=$db->getRows();
        foreach ($spec as $kp=>$vp)
        {
            $str.=",".$vp['spec_id'];
        }
        $str = implode(',',array_unique(explode(',',$str)));
        $product[$k]['sku'][$ks]['spec_id']=ltrim($str, ",");
    }
    $sql="select cat from mallbuilder_product_cat where catid=".$pro['catid'];
    $db->query($sql);
    $cat=$db->fetchRow();

    $product[$k]['cate'] = $cat['cat'];
    $product[$k]['imageUrl'] = $pro['pic']."_120X120.jpg";
    $product[$k]['productName'] = $pro['name'];
    $product[$k]['price'] = $pro['price'];
    $product[$k]['member_name'] = $pro['member_name'];
    $product[$k]['code']    = $pro['code'];
    $product[$k]['isShelves'] = $pro['is_shelves'];
    //运费
    if(empty($pro['freight_type']))
    {   //卖家承担运费
        $product[$k]['postPrice'] = "0";
    }
    else
    {
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
$re['data'] = $product;
$re['result'] = 0;
//综合返产品列表

echo json_encode($re);
?>