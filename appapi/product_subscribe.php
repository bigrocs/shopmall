<?php
## 传递参数列表
## uid【用户id】,productId【产品id】,shopId【店铺id】

## 返回状态参数列表
## 0【成功】,1【产品id为空】,2【店铺id为空】,3【用户id为空】
## -1【产品不存在】,-2【产品已收藏】,-3【收藏失败】,-4【用户不存在】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
{
	$_POST = json_decode(file_get_contents("php://input"),true);
}
$re['result'] = 0;
if(isset($_POST['productId']) && !empty($_POST['productId'])){
    $productid = $_POST['productId'];
	$where .= " and id = ".$productid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
	$where .= " and member_id = ".$shopid ;
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
$sql="select * from ".PRODUCT." where 1 ".$where;
$db->query($sql);
$pro=$db->fetchRow();
if(empty($pro)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql);
$mem=$db->fetchRow();
if(!empty($mem)){
    $uid = $mem['userid'];
}else{
    $re['result'] = -4;
    echo json_encode($re);
    exit;
}
$createtime=time();
$re['productId']=$pro['id'];
$re['productName']=$pro['name'];
$re['shopId']=$pro['member_id'];
$re['price']=$pro['price'];
$re['picUrl']=$pro['pic']."_60X60.jpg";
$re['createTime']=$createtime;

$sql="select * from mallbuilder_sns_shareproduct where pid=".$productid." and uid=".$uid;
$db->query($sql);
$res=$db->fetchRow();
if(!empty($res)){
    $re['result'] = -2;
    $re['subscribeId']=$res['id'];
    echo json_encode($re);
}else{
    $sql= "insert into mallbuilder_sns_shareproduct (pid,uid,uname,addtime) value('$productid','$uid','$uname','$createtime')";
    $db->query($sql);
    $re1=$db->lastid();
    $re['subscribeId']=$re1;
    if($re1){
        //$where = "where pid=".$productid." and shopid=".$shopid." and uname='".$uname."'";
        $where = "where pid=".$productid." and shopid=".$shopid;
        $finsql = "select * from mallbuilder_sns_shareproduct_info ".$where;
        //echo $finsql;die;
        $db->query($finsql);
        $re2=$db->fetchRow();
        if($re2){
            $sql="update mallbuilder_sns_shareproduct_info set collectnum = collectnum+1 ".$where;
            $re3=$db->query($sql);
            if($re3){
                //echo 1;
                echo json_encode($re);
            }else{
                $re['result'] = -3;
                echo json_encode($re);
                exit;
            }
        }else{
            $sql="insert into mallbuilder_sns_shareproduct_info (pid,pname,image,price,shopid,addtime,collectnum) value('$productid','".$pro['name']."','".$pro['pic']."','".$pro['price']."','$shopid','$createtime',1)";
            $re3=$db->query($sql);
            if($re3){
                //echo 2;
                echo json_encode($re);
            }else{
                $re['result'] = -3;
                echo json_encode($re);
                exit;
            }
        }

    }else{
        $re['result'] = -3;
        echo json_encode($re);
        exit;
    }
}
?>
