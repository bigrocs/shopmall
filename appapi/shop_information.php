<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【uid为空】
## -1【店铺不存在】

include_once("../includes/global.php");
include_once("includes/user_shop_class.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
$where =" where 1 ";
$ssql = " select * from mallbuilder_shop";
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and userid = ".$shopid ;
}else{
    $re['result']=1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uid = $_POST['uid'];
}else{
    $re['result']=2;
    echo json_encode($re);
    exit;
}
$ssql= $ssql.$where;
$db->query($ssql);
$shop=$db->fetchRow();
if(empty($shop)){
    $re['result']=-1;
    echo json_encode($re);
    exit;
}
$re['shopId'] = $shopid;
$re['shopName'] = $shop['company'];//这边shop表没有name字段，假设店铺名在name字段
$re['depositCash'] = $shop['earnest'];//质保金
$re['shopStatus'] = $shop['shop_statu'];
$re['logoUrl'] = $shop['logo'];
$re['company'] = $shop['company'];
//$re['provinceid'] = $shop['provinceid'];
//$re['cityid'] = $shop['cityid'];
//$re['areaid'] = $shop['areaid'];
$re['area'] = $shop['area'];
$re['telephone'] = $shop['tel'];
$re['createTime'] = $shop['create_time'];
$re['shopCollect'] = $shop['shop_collect'];
$re['grade'] = $shop['grade'];
$sql1 = "select * from ".PRODUCT." where member_id=".$shopid;
$db->query($sql1);
$re['productTotal'] = $db->num_rows();
$re['username'] = $shop['user'];
$sql = "select * from mallbuilder_shop_setting where shop_id=".$shopid;
$db->query($sql);
$s=$db->fetchRow();
$re['abstract'] = $s['shop_description'];
//获取用户是否收藏（根据用户名）
$sql = "select * from mallbuilder_sns_shareshop where shopid=".$shopid." and uname='".$_POST['uid']."'";
$db->query($sql);
$list=$db->getRows();
if(!empty($list)){
    $re['subscribe'] = 1;
}else{
    $re['subscribe'] = 0;
}
$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRODUCT." b on a.pid=b.id where b.member_id='".$shopid."' and a.userid <> '".$shopid."'";
$db->query($sql);
$count=$db->fetchField('count');
if($count!=0)
{
    $sql="select count(*) as count  from ".PCOMMENT." a left join ".PRODUCT." b on a.pid=b.id where b.member_id='".$shopid."' and a.userid <> '".$shopid."' and a.goodbad=1";
    $db->query($sql);
    $re["favrate"]=($db->fetchField('count')/$count)*100;
}else{
    $re["favrate"]="100";//好评率
}
$sql="select avg(item1) as a,avg(item2) as b,avg(item3) as c,avg(item4) as d from ".UCOMMENT." where byid='$shopid'";
$db->query($sql);
$u=$db->fetchRow();
$re['asdiscribe']=number_format($u['a'],1);//描述相符
$re['attitude']=number_format($u['b'],1);//服务态度
$re['sendspeed']=number_format($u['c'],1);//发货速度
$re['lospeed']=number_format($u['d'],1);//物流速度
$re['result']=0;
echo json_encode($re);
return 0;
?>
