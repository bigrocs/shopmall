<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【uid为空】,3【已收藏】
## -1【用户不存在】,-2【收藏失败】,-3【店铺不存在】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and userid = ".$shopid ;
}else{
    $re['result']=1;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_shop where 1 ".$where;
$db->query($sql);
$shop=$db->fetchRow();
if(empty($shop)){
    $re['result']=-3;
    echo json_encode($re);
    exit;
}
$re['shopId'] = $shopid;
$re['shopName'] = $shop['company'];//这边shop表没有name字段，假设店铺名在name字段
$re['shopStatus'] = $shop['shop_statu'];
$re['logoUrl'] = $shop['logo'];
$re['grade'] = $shop['grade'];
$re['mainProduct'] = $shop['main_pro'];
//$re['provinceid'] = $shop['provinceid'];
//$re['cityid'] = $shop['cityid'];
//$re['areaid'] = $shop['areaid'];
$re['area'] = $shop['area'];
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result']=2;
    echo json_encode($re);
    exit;
}
$sql1="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql1);
$mem=$db->fetchRow();
if($mem){
    $uid = $mem['userid'];
}else{
    $re['result']=-1;
    echo json_encode($re);
    exit;
}
$sql2="select * from mallbuilder_sns_shareshop where uid=".$uid." and shopid=".$shopid;
$db->query($sql2);
$re1=$db->fetchRow();
if(empty($re1)){
    $createtime=time();
    $sql3= "insert into mallbuilder_sns_shareshop (shopid,shopname,uid,uname,addtime) value('$shopid','".$shop['company']."','$uid','$uname','$createtime')";
    $db->query($sql3);
    $res=$db->lastid();
    if($res){
        $sql4="update mallbuilder_shop set shop_collect=shop_collect+1 where userid=".$shopid;
        $db->query($sql4);
        $re['result'] = 0;
        $re['subscribeId'] = $res;
        $re['createTime'] = $createtime;
        echo json_encode($re);
    }else{
        $re['result']=-2;
        echo json_encode($re);
        exit;
    }
}else{
    $sql4="update mallbuilder_shop set shop_collect=shop_collect+1 where userid=".$shopid;
    $db->query($sql4);
    $re['result'] = 3;
    $re['subscribeId'] = $re1['id'];
    $re['createTime'] = $re1['addtime'];
    echo json_encode($re);
}
?>
