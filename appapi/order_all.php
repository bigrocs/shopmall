<?php
## 传递参数列表
## uid【用户】,orderStatus【订单状态】

## 返回状态参数列表
## 0【成功】,1【用户为空】
## -1【用户不存在】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
    $_POST = json_decode(file_get_contents("php://input"),true);
$where =" where 1 ";
$status = '';
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$usql="select * from mallbuilder_member where user='".$uname."'";
$db->query($usql);
$mem=$db->fetchRow();
if(empty($mem)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$where .= " and buyer_id = ".$mem['userid'];
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
if(isset($_POST['orderStatus']) && !empty($_POST['orderStatus'])){
    $status = $_POST['orderStatus'];
    if($_POST['orderStatus']!=6){
        if($_POST['orderStatus']==5){
            $status = 0;
        }
        $where .= " and status = ".$status ;
    }else{
        $re=returnOrder($mem['userid'],$limit,$page,$num);
        echo json_encode($re);
        exit;
    }
}
function returnOrder($uid,$limit,$page,$num){
    global $db;
    $sql="select distinct order_id from mallbuilder_return where member_id=".$uid;
    $ssql = $sql." order by create_time desc,id asc ".$limit;
    $db->query($ssql);
    $ids=$db->getRows();
    $count=$db->num_rows();
    $re['count'] = $count;
    $re['pageIndex'] = $page;
    $db->query($sql);
    $recount=$db->num_rows();
    $re['pageTotal']=ceil($recount/$num);
    foreach($ids as $key=>$id){
        $psql = "select * from mallbuilder_product_order where order_id=".$id['order_id'];
        $db->query($psql);
        $order=$db->fetchRow();
        $orls[$key]['createTime'] = $order['create_time'];
        $orls[$key]['orderStatus'] = $order['status'];
        $orls[$key]['reimburseStatus'] = 1;
        $orls[$key]['buyerComment'] = $order['buyer_comment'];
        $orls[$key]['sellerComment'] = $order['seller_comment'];
        $orls[$key]['orderId'] = $order['order_id'];
        $orls[$key]['shopId'] = $order['userid'];
        $sql="select * from ".SHOP." where userid=".$order['userid'];
        $db->query($sql);
        $s = $db->fetchRow();
        $orls[$key]['shopName'] = $s['company'];
        $orls[$key]['postType'] = $order['logistics_type'];
        $orls[$key]['postPrice'] = $order['logistics_price'];
        $orls[$key]['priceTotal'] = $order['logistics_price']+$order['product_price'];
        $getsql="select * from ".ORPRO." where order_id=".$order['order_id'];
        $db->query($getsql);
        $products=$db->getRows();
        $prol="";
        foreach($products as $kky => $pro){
            $prol[$kky]['productId'] = $pro['pid'];
            $prol[$kky]['picUrl'] = $pro['pic']."_120X120.jpg";
            $prol[$kky]['productName'] = $pro['name'];
            $prol[$kky]['comboId'] = $pro['setmeal'];
            $combosql = "select * from mallbuilder_product_setmeal where id=".$pro['setmeal'];
            $db->query($combosql);
            $combo = $db->fetchRow();
            $prol[$kky]['comboName'] = $combo['setmeal'];
            $prol[$kky]['price'] = $pro['price'];
            $prol[$kky]['productVolume'] = $pro['num'];
            if($orls[$key]['reimburseStatus']){
                $prol[$kky]['reimburseStatus'] = $pro['status'];
            }
        }
        $orls[$key]['productList']=$prol;
    }
    $re['orderList'] = $orls;
    $re['result'] = 0;
    return $re;
}
$psql = "select * from mallbuilder_product_order ";
$sql = $psql.$where." order by create_time desc,id asc ".$limit;
$db->query($sql);
$orders = $db->getRows();
$count=$db->num_rows();
$re['count'] = $count;
$re['pageIndex'] = $page;
$sql2= $psql.$where;
$db->query($sql2);
$recount=$db->num_rows();
$re['pageTotal']=ceil($recount/$num);
//echo($sql);die;
foreach($orders as $key=>$or){
    $orls[$key]['createTime'] = $or['create_time'];
    $orls[$key]['orderStatus'] = $or['status'];
    $rsql="select * from mallbuilder_return where order_id=".$or['order_id'];
    $db->query($rsql);
    $return = $db->getRows();
    if(empty($return)){
        $orls[$key]['reimburseStatus'] = 0;
    }else{
        $orls[$key]['reimburseStatus'] = 1;
    }
    $orls[$key]['buyerComment'] = $or['buyer_comment'];
    $orls[$key]['sellerComment'] = $or['seller_comment'];
    $orls[$key]['orderId'] = $or['order_id'];
    $orls[$key]['shopId'] = $or['userid'];
    $sql="select * from ".SHOP." where userid=".$or['userid'];
    $db->query($sql);
    $s = $db->fetchRow();
    $orls[$key]['shopName'] = $s['company'];
    $orls[$key]['postType'] = $or['logistics_type'];
    $orls[$key]['postPrice'] = $or['logistics_price'];
    $orls[$key]['priceTotal'] = $or['logistics_price']+$or['product_price'];
    $getsql="select * from ".ORPRO." where order_id=".$or['order_id'];
    $db->query($getsql);
    $products=$db->getRows();
    $prol="";
    foreach($products as $kky => $pro){
        $prol[$kky]['productId'] = $pro['pid'];
        $prol[$kky]['picUrl'] = $pro['pic']."_120X120.jpg";
        $prol[$kky]['productName'] = $pro['name'];
        $prol[$kky]['comboId'] = $pro['setmeal'];
        $combosql = "select * from mallbuilder_product_setmeal where id=".$pro['setmeal'];
        $db->query($combosql);
        $combo = $db->fetchRow();
        $prol[$kky]['comboName'] = $combo['setmeal'];
        $prol[$kky]['price'] = $pro['price'];
        $prol[$kky]['productVolume'] = $pro['num'];
        if($orls[$key]['reimburseStatus']){
            $prol[$kky]['reimburseStatus'] = $pro['status'];
        }
    }
    $orls[$key]['productList']=$prol;
}
$re['orderList'] = $orls;
$re['result'] = 0;
//现在数组就是二维数组了 想输出json 的话就
echo json_encode($re);
?>