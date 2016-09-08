<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【uid为空】
## -1【店铺不存在】

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

$where =" where 1 ";
$ssql = " select * from mallbuilder_shop";
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and userid = ".$shopid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(isset($_POST['uid']) && !empty($_POST['uid'])){
    $uid = $_POST['uid'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
$ssql= $ssql.$where;
$db->query($ssql);
$shop=$db->fetchRow();
if(empty($shop)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$re['shopId'] = $shopid;
$re['shopName'] = $shop['company'];//这边shop表没有name字段，假设店铺名在name字段
$re['username'] = $shop['user'];//这边shop表没有name字段，假设店铺名在name字段
$re['grade'] = $shop['grade'];
$re['logoUrl'] = $shop['logo'];
//获取用户是否收藏（根据用户id）
$sql = "select * from mallbuilder_sns_shareshop where shopid=".$shopid." and uname='".$uid."'";
$db->query($sql);
$subs=$db->getRows();
if(!empty($subs)){
    $re['subscribe'] = 1;
}else{
    $re['subscribe'] = 0;
}
//$re['provinceid'] = $shop['provinceid'];
//$re['cityid'] = $shop['cityid'];
//$re['areaid'] = $shop['areaid'];
$re['area'] = $shop['area'];
$re['longtitude'] = $data['lng'];
$re['latitude'] = $data['lat'];
$re['mainProduct'] = $shop['main_pro'];
$re['shopCollect'] = $shop['shop_collect'];
//获取首页商品列表,限定不超过10个
$sql3 = "select id,pic,pic_more,name,price,member_id from ".PRODUCT." where member_id=".$shopid." limit 0,10";
$db->query($sql3);
$prolist=$db->getRows();
$sql3 = "select id,pic,pic_more,name,price,member_id from ".PRODUCT." where member_id=".$shopid;
$db->query($sql3);
$re['productTotal'] = $db->num_rows();
if($re['productTotal']){
    foreach($prolist as $k=>$pro){
        $list[$k]['productId'] = $pro['id'];
        $list[$k]['imageUrl'] = $pro['pic']."_120X120.jpg";
        $list[$k]['productName'] = $pro['name'];
        $list[$k]['price'] = $pro['price'];
        $list[$k]['shopId'] = $pro['member_id'];
    }
    $re['list'] = $list;
}else{
    $re['list'] = '';
}

$re['result'] = 0;
echo json_encode($re);
?>
