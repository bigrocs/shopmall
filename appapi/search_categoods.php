<?php
## 传递参数列表
## keyWord【关键字】,page【页码】,number【每页数量】

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
$psql = "select catid from ".PCAT;
//按关键字查找分类
if($_POST['keyWord']){
    $where .=" and cat like '%".$_POST['keyWord']."%' ";
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
$sql1= $psql.$where.$or.$limit;
$db->query($sql1);
$cates=$db->getRows();
foreach($cates as $k=>$c){
    $url = "http://zgxxg.18bc.com/api/search_goods.php";
    $post = array(
        "cid" => $c['catid']);
    $data = curl_post($url,$post);
    $catelist[$k]['products'] = json_decode($data,true);
}
$re['list'] = $catelist;
$re['result'] = 0;
//综合返产品列表
echo json_encode($re);
?>