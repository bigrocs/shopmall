<?php
## 传递参数列表
## uid【用户】

## 返回状态参数列表
## 0【成功】,1【用户为空】
## -1【获取地址列表失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['uid'])){
    $uname= $_POST['uid'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$sql0="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql0);
$mem = $db->fetchRow();
$uid = $mem['userid'];
$sql="select * from mallbuilder_delivery_address where userid = ".$uid." order by `default` desc,id desc ";
$db->query($sql);
$address = $db->getRows();
if(!empty($address)){
    $re['addressTotal'] = $db->num_rows();
    foreach($address as $key=>$add){
        $list[$key]['addressId'] = $add['id'];
        $list[$key]['connectName'] = $add['name'];
        $list[$key]['provinceid'] = $add['provinceid'];
        $list[$key]['cityid'] = $add['cityid'];
        $list[$key]['areaid'] = $add['areaid'];
        $list[$key]['area'] = $add['area'];
        $list[$key]['address'] = $add['address'];
        $list[$key]['postCode'] = $add['zip'];
        $list[$key]['telephone'] = $add['tel'];
        $list[$key]['mobilephone'] = $add['mobile'];
        $list[$key]['isDefault'] = $add['default'];
    }
    $re['list'] = $list;
    $re['result'] = 0;
    echo json_encode($re);
}else{
    $re['result'] = -1;
    echo json_encode($re);
    exit;
}
?>
