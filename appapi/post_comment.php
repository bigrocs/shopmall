<?php
## 传递参数列表
## orderId【订单id】,productList【商品列表】,productId【商品id】,comboId【组合id】,type【评价类型】,message【评价内容】,uid【用户】

## 返回状态参数列表
## 0【成功】,1【订单id为空】,2【商品列表为空】,3【商品id为空】,4【用户为空】
## -1【用户和订单不匹配】,-2【订单中没有该商品】,-3【评论商品失败】,-4【商品数量错误】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['orderId'])){
    $orderid = $_POST['orderId'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['productList'])){
    $productlist = $_POST['productList'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql);
$user=$db->fetchRow();
$uid=$user['userid'];
$selete="select * from ".ORDER." where order_id=".$orderid." and buyer_id=".$uid;
$db->query($selete);
$by=$db->fetchRow();
var_dump($by);die;
if(empty($by)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$flag=0;
foreach($productlist as $key=>$pro){
    if(!empty($pro['productId'])){
        $pid = $pro['productId'];
    }else{
        $re['result'] = 3;
        echo json_encode($re);
        exit;
    }
    $cpid = $pro['comboId'];
    if(!empty($pro['type'])){
        $type = $pro['type'];
    }else{
        $type=1;
    }
    if(!empty($pro['message'])){
        $message = $pro['message'];
    }else{
        $message = "";
    }
    $sql="select * from mallbuilder_product_order_pro where order_id=".$orderid." and pid=".$pid." and setmeal=".$cpid;
    //echo $sql;die;
    $db->query($sql);
    $pre=$db->fetchRow();
    if(empty($pre)){
        $re['result'] = -2;
        echo json_encode($re);
        exit;
    }else{
        $pname = $pre['name'];
        $price = $pre['price'];
    }
    $uptime=time();
    /*$sql="select * from ".PCOMMENT." where user=".$uname;
    $db->query($sql);
    $user=$db->fetchRow();*/
    $sql="insert into ".PCOMMENT." (user,userid,fromid,pid,puid,pname,price,goodbad,con,uptime) value('$uname','$uid','$uid','$pid','".$by['userid']."','$pname','$price','$type','$message','$uptime')";
    $res = $db->query($sql);
    if(!$res){
        $re['result'] = -3;
        echo json_encode($re);
        exit;
    }else{
        $flag+= 1;
    }
}
if(!empty($_POST['asdiscribe'])){//描述相符
    $asdiscribe = $_POST['asdiscribe'];
}else{
    $asdiscribe = 5;
}
if(!empty($_POST['attitude'])){//服务态度
    $attitude = $_POST['attitude'];
}else{
    $attitude = 5;
}
if(!empty($_POST['sendspeed'])){//发货速度
    $sendspeed = $_POST['sendspeed'];
}else{
    $sendspeed = 5;
}
if(!empty($_POST['lospeed'])){//物流速度
    $lospeed = $_POST['lospeed'];
}else{
    $lospeed = 5;
}
if($flag==count($productlist)){
    $sql="insert into ".UCOMMENT." (user,userid,byid,item1,item2,item3,item4,uptime) value('$uname','$uid','".$by['userid']."','$asdiscribe','$attitude','$sendspeed','$lospeed','$uptime')";
    $res = $db->query($sql);
    $iscom="update ".ORDER." set buyer_comment=1 where order_id=".$orderid;
    $db->query($iscom);
    $re['result'] = 0;
    $re['orderId'] = $orderid;
    echo json_encode($re);
}else{
    $re['result'] = -4;
    echo json_encode($re);
    exit;
}
?>
