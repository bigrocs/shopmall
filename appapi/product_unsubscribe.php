<?php
## 传递参数列表
## uid【用户id】,subscribeId【收藏id】,productId【产品id】,shopId【店铺id】

## 返回状态参数列表
## 0【成功】,1【收藏id为空】,2【产品id为空】,3【店铺id为空】,4【用户id为空】
## -1【取消收藏失败】,-2【店铺商品不存在】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['subscribeId'])){
    $subscribeid = $_POST['subscribeId'];
    $where .= " and id = ".$subscribeid;
}else{
    $res['result'] = 1;
    echo json_encode($res);
    exit;
}
if(!empty($_POST['productId'])){
    $productid = $_POST['productId'];
	$where .= " and pid = ".$productid;
}else{
    $res['result'] = 2;
    echo json_encode($res);
    exit;
}
if(isset($_POST['shopId']) && !empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
}else{
    $res['result'] = 3;
    echo json_encode($res);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
    $where .= " and uname='".$uname."'";
}else{
    $res['result'] = 4;
    echo json_encode($res);
    exit;
}
$fsql="select * from ".PRODUCT." where id=".$productid." and member_id=".$shopid;
$db->query($fsql);
$p = $db->fetchRow();
if(empty($p)){
    $res['result'] = -2;
    echo json_encode($res);
    exit;
}

$sql2 = "select * from mallbuilder_sns_shareproduct where 1 ".$where;
$db -> query($sql2);
$flag = $db -> num_rows();
$sql="delete from mallbuilder_sns_shareproduct where 1 ".$where;
$re=$db->query($sql);
if($flag && $re){
    $sql1="update mallbuilder_sns_shareproduct_info set collectnum = collectnum-1 where pid=".$productid." and shopid=".$shopid;
    //echo $sql1;die;
    $re1=$db->query($sql1);
    if($re1){
        $res['subscribeid']=$subscribeid;
        $res['shopid']=$shopid;
        $res['productid']=$productid;
        $res['result']=0;
        echo json_encode($res);
    }else{
        $res['result'] = -1;
        echo json_encode($res);
        exit;
    }
}else{
    $res['result'] = -1;
    echo json_encode($res);
    exit;
}
?>
