<?php
## 传递参数列表
## affairId【事务Id】,keyWord【关键字】,page【页码】,number【每页数量】

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
//$psql = "select a.*,b.jwd as jwd from ".PRODUCT." as a left join mallbuilder_shop as b on a.member_id = b.userid ";
$psql = "select * from mallbuilder_shop ";
//按关键字查找
if($_POST['keyWord']){
    $where .=" and company like '%".$_POST['keyWord']."%' ";
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
$limit ="limit ".$firstRow.",".$lastRow;
$sql1= $psql.$where.$limit;
//echo $sql1;die;
$db->query($sql1);
$shops=$db->getRows();
$re['count'] = $db->num_rows();
$sql2= $psql.$where;
$db->query($sql2);
$recount=$db->num_rows();
$re['pageTotal'] = ceil($recount/$num);
$re['pageIndex'] = $page;
foreach($shops as $k=>$s){
    $shop[$k]['shopId'] = $s['userid'];
    $shop[$k]['shopName'] = $s['company'];
    $shop[$k]['logoUrl'] = $s['logo'];
    $shop[$k]['area'] = $s['area'];
    $shop[$k]['longitude'] = $data['lng'];
    $shop[$k]['latitude'] = $data['lat'];
    $shop[$k]['grade'] = $s['grade'];
    $shop[$k]['mainProduct'] = $s['main_pro'];
}
$re['list'] = $shop;
$re['result']=0;
echo json_encode($re);


?>