<?php
/**
 * Created by PhpStorm.
 * User: 006
 * Date: 14-9-9
 * Time: 下午1:56
 */
## 传递参数列表
## uid【用户】,shopId【店铺id】,productList【商品列表】,cityid【城市id】

## 返回状态参数列表
## 0【成功】,1【店铺id为空】,2【商品列表为空】,3【用户id为空】
## -1【用户id错误】,-2【城市不存在】,-3【商品不存在】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
    $_POST = json_decode(file_get_contents("php://input"),true);
function get_price($pde,$lgid,$city,$type,$num)
{
    global $db;

    $sql="select price_type from ".LGSTEMP." where id='$lgid'";
    $db->query($sql);
    $unit=$db->fetchField('price_type');

    if($city){
        $sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='$type'";
        $db->query($sql);
        $re=$db->fetchRow();
        if(empty($re['id']))
        {	//没有为城市定价
            $sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys = 'default' and logistics_type='$type'";
            $db->query($sql);
            $re=$db->fetchRow();
        }
    }else{	//没有为城市定价
    $sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys = 'default' and logistics_type='$type'";
    $db->query($sql);
    $re=$db->fetchRow();
    }
    if($re)
    {
        if($num<=$re['default_num'])
            $price = $re['default_price'];
        else
        {
            if($unit=='件')
                $price=$re['default_price']+ceil(($num-$re['default_num'])/$re['add_num'])*$re['add_price'];
            if($unit=='kg')
                $price=$re['default_price']+ceil(($num*$pde['weight']-$re['default_num'])/$re['add_num'])*$re['add_price'];
            if($unit=='m³')
                $price=$re['default_price']+ceil(($num*$pde['cubage']-$re['default_num'])/$re['add_num'])*$re['add_price'];
        }
    }
    return $price?$price:'0';
}
/**
 * 获取商品平邮、快递、EMS、总邮费
 * @param $area 收货地址
 * @param $pde 商品信息
 * @param $num 数量
 * return array
 */
if(!empty($_POST['shopId'])){
    $shopid = $_POST['shopId'];
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
if($_POST['cityid']){
    $cityid = $_POST['cityid'];
    $area = getdistrictname($cityid);
    if(empty($area)){
        $re['result'] = -2;
        echo json_encode($re);
        exit;
    }
}else{
    $area = "";
}
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
$sql1="select * from mallbuilder_member where user = '".$uname."'";
$db->query($sql1);
$mem = $db->fetchRow();
if(empty($mem)){
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
$uid = $mem['userid'];
foreach($productlist as $key => $val)
{
    $sql="select freight_type,freight_id as freight,weight,cubage from ".PRODUCT." where id=".$val['productId'];
    $db->query($sql);
    $p = $db->fetchRow();
    if(empty($p)){
        $re['result'] = -3;
        echo json_encode($re);
        exit;
    }
    if(empty($p['freight_type']))
    {   //卖家承担运费
        $mail = $ems = $express = 0;
    }
    else
    {   //运费模板
        $mail=get_price($p,$p['freight'],$area,'mail',$val['productVolume']);
        $ems=get_price($p,$p['freight'],$area,'ems',$val['productVolume']);
        $express=get_price($p,$p['freight'],$area,'express',$val['productVolume']);
    }
    $pro['mail'] += $mail;
    $pro['ems'] += $ems;
    $pro['express'] += $express;
    if($mail>0||$ems>0||$express>0)
    {
        if ($mail<=0) $pro['mails'] = 1;
        if ($ems<=0) $pro['emss'] = 1;
        if ($express<=0) $pro['expresss'] = 1;
    }
    $re['mail'] = $pro['mails'] == 1 ? 0 : $pro['mail'];
    $re['ems'] = $pro['emss'] == 1 ? 0 : $pro['ems'];
    $re['express'] = $pro['expresss'] == 1 ? 0:$pro['express'];
}
$re['result'] = 0;
$re['shopId'] = $shopid;
$re['cityid'] = $cityid;
echo json_encode($re);
?>