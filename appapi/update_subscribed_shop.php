<?php
## 传递参数列表
## subscribeId【收藏id】,shopId【店铺id】,uid【用户】

## 返回状态参数列表
## 0【成功】,1【店铺id为空】,2【收藏id为空】,3【用户为空】
## -1【店铺不存在】,-2【信息错误查询失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where1 .= " and userid = ".$shopid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$where = " and shopid = ".$shopid ;
if(!empty($_POST['subscribeId'])){
    $subscribeid = $_POST['subscribeId'];
	$where.= " and id = ".$subscribeid ;
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
    $where.= " and uname = '".$uname."'";
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_shop where 1 ".$where1;
$db->query($sql);
$shop=$db->fetchRow();
if(empty($shop)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$ssql="select * from mallbuilder_sns_shareshop where 1 ".$where;
$db->query($ssql);
$subscribe=$db->fetchRow();
if(empty($subscribe)){
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
$re['result'] = 0;
$re['subscribeId']=$subscribe['id'];//收藏id
$re['shopId']=$shop['userid'];//店铺id
$re['shopName']=$shop['company'];//店铺名
$re['grade'] = $shop['grade'];
$re['mainProduct'] = $shop['main_pro'];
$re['shopStatus']=$shop['shop_statu'];//店铺状态
$re['logoUrl']=$shop['logo']."_60X60.jpg";//店铺logo
$re['area']=$shop['area'];//店铺地址
$re['createtime']=$subscribe['addtime'];//收藏时间
echo json_encode($re);
?>
