<?php
## 传递参数列表
## uid【用户id】,shopId【店铺id】,subscribeId【收藏id】

## 返回状态参数列表
## 0【查询成功】,1【shopId为空】,2【收藏id为空】,3【uid为空】,4【已收藏】
## -1【店铺不存在】, -2【未收藏】, -3【取消收藏失败】

include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and shopid = ".$shopid ;
}else{
    $re['result']=1;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_shop where userid=".$shopid;
$db->query($sql);
$re1=$db->fetchRow();
if(empty($re1)){
    $re['result']=-1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['subscribeId'])){
    $subscribeid = $_POST['subscribeId'];
	$where .= " and id = ".$subscribeid;
}else{
    $re['result']=2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uid = $_POST['uid'];
    $where .= " and uname = '".$uid."'";
}else{
    $re['result']=3;
    echo json_encode($re);
    exit;
}
$sql1="select * from mallbuilder_sns_shareshop where 1".$where;
$db->query($sql1);
$flag = $db -> num_rows();
$sql2="delete from mallbuilder_sns_shareshop where 1 ".$where;
$re1=$db->query($sql2);
if($flag && $re1){
    $sql4="update mallbuilder_shop set shop_collect=shop_collect-1 where userid=".$shopid;
    $db->query($sql4);
    $re['result']=0;
    $re['subscribeId']=$subscribeid;
    $re['shopId']=$shopid;
    echo json_encode($re);
}else{
    if($flag){
        $re['result']=-3;
        echo json_encode($re);
        exit;
    }else{
        $re['result']=-2;
        echo json_encode($re);
        exit;
    }
}
?>
