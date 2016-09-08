<?php
## 传递参数列表
## uid【用户】,connectName【收货人】,area【收货省市区】,address【收货地址】,telephone【收货人电话】,mobilephone【收货人手机】,fromCart【是否购物车商品】,,shopList【店铺列表】
## shopId【店铺id】,shopProductV【该店铺商品数】,postType【运送方式】,postPrice【该店铺运费】,priceTotal【该店铺商品总价】
## productList【商品列表】,productId【商品id】,comboId【所选商品型号id】,price【商品单价】,productVolume【数量】

## 返回状态参数列表
## 0【成功】,1【收货人为空】,2【省市区为空】,3【收货地址为空】,4【联系方式为空】,5【店铺列表为空】，6【产品列表为空】，7【用户为空】
## -1【订单生成失败】，-2【插入订单商品失败】，-3【购物车没有该商品】，-4【商品不存在或已下架】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['connectName'])){
    $connectname = $_POST['connectName'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['area'])){
    $area = $_POST['area'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['address'])){
    $address = $_POST['address'];
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}if(!empty($_POST['telephone'])||!empty($_POST['mobilephone'])){
    $telephone = $_POST['telephone']?$_POST['telephone']:'0';
    $mobilephone = $_POST['mobilephone']?$_POST['mobilephone']:'0';
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['shopList'])){
    $shopList = $_POST['shopList'];
}else{
    $re['result'] = 5;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 7;
    echo json_encode($re);
    exit;
}
$sql1="select * from mallbuilder_member where user = '".$uname."'";
$db->query($sql1);
$mem = $db->fetchRow();
$uid = $mem['userid'];
$fromCart = $_POST['fromCart'];
$re['orderTotal'] = 0;
foreach($shopList as $key=>$shopl){
    $time = time();
    $orderid = date("Ymdhis").rand(0,9);//订单号
    if(!empty($shopl['productList'])){
        /***生成买家订单****/
        $sql="insert into ".ORDER." (userid,order_id,buyer_id,seller_id,consignee,consignee_address,consignee_tel,consignee_mobile,product_price,logistics_type,logistics_price,paytype,status,des,create_time,uptime)
      value('$uid','$orderid','0','".$shopl['shopId']."','".$connectname."','".$area.''.$address."','".$telephone."','".$mobilephone."','".$shopl['priceTotal']."','".$shopl['postType']."','".$shopl['postPrice']."','2','2','".$shopl['additionWord']."','$time','$time')";
        $ire = $db->query($sql);
        /***生成卖家订单****/
        $sql="insert into ".ORDER." (userid,order_id,buyer_id,seller_id,consignee,consignee_address,consignee_tel,consignee_mobile,product_price,logistics_type,logistics_price,paytype,status,des,create_time,uptime)
      value('".$shopl['shopId']."','$orderid','$uid','0','".$connectname."','".$area.''.$address."','".$telephone."','".$mobilephone."','".$shopl['priceTotal']."','".$shopl['postType']."','".$shopl['postPrice']."','2','2','".$shopl['additionWord']."','$time','$time')";
        $ire = $db->query($sql);
        if($ire){
            $productList = $shopl['productList'];
            foreach($productList as $kky=>$prol){
                if($fromCart==1){
                    $csql = "select * from ".CART." where product_id=".$prol['productId']." and spec_id =".$prol['comboId']." and buyer_id=".$uid;
                    $db->query($csql);
                    $cart = $db->fetchRow();
                    if(empty($cart)){
                        $re['result'] = -3;
                        echo json_encode($re);
                        exit;
                    }
                    $sql = "delete from ".CART." where product_id=".$prol['productId']." and spec_id =".$prol['comboId']." and buyer_id=".$uid;
                    $db->query($sql);
                }
                $sql2="select * from ".PRODUCT." where id=".$prol['productId']." and is_shelves=1";
                $db->query($sql2);
                $pro = $db->fetchRow();
                if(empty($pro)){
                    continue;
                }
                $sql3="select * from mallbuilder_product_setmeal where id=".$prol['comboId'];
                $db->query($sql3);
                $combo = $db->fetchRow();
                $insql="insert into ".ORPRO." (order_id,buyer_id,pid,pcatid,name,pic,price,num,time,setmeal,paytype,status,is_tg,spec_name,spec_value)
           value('$orderid','$uid','".$prol['productId']."','".$pro['catid']."','".$pro['name']."','".$pro['pic']."','".$pro['price']."','".$prol['productVolume']."','$time','".$prol['comboId']."','2','1','false','".$combo['spec_name']."','".$combo['setmeal']."')";
                $ire2 = $db->query($insql);
                if(!$ire2){
                    $re['result'] = -2;
                    echo json_encode($re);
                    exit;
                }
                $sql="select detail from ".PRODETAIL." where proid='$val[product_id]'";
                $db->query($sql);
                $detail=$db->fetchField('detail');
                $sql = "insert into mallbuilder_product_snapshot (`order_id`,`product_id`,`spec_id`,`member_id`,`shop_id`,`catid`,`type`,`name`,`subhead`,`brand`,`price`,`freight`,`pic`,`uptime`,`detail`,`spec_name`,`spec_value`)
                values ('$orderid','".$prol['productId']."','".$prol['comboId']."','$uid','".$shopl['shopId']."','".$pro['catid']."','".$pro['type']."','".$pro['name']."','".$pro['name']."','".$pro['brand']."','".$pro['price']."','0','".$pro['pic']."','".time()."','$detail','".$combo['spec_name']."','".$combo['setmeal']."')";
                $db->query($sql);
            }
        }else{
            $re['result'] = -1;
            echo json_encode($re);
            exit;
        }
    }else{
        $re['result'] = 6;
        echo json_encode($re);
        exit;
    }
    $order[$key]['orderResult'] = 0;//下单成功
    $order[$key]['shopId'] = $shopl['shopId'];
    $order[$key]['orderId'] = $orderid;
    $order[$key]['orderStatus'] = 2;
    $order[$key]['createTime'] = $time;
    $re['orderTotal'] = $re['orderTotal'] + 1;
}
//返回处理
$re['result'] = 0;
$re['orderList'] = $order;
echo json_encode($re);
?>
