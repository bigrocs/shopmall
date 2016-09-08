<?php
## 传递参数列表
## uid【用户】,connectName【收货人姓名】,provinceid【省id】,cityid【市id】,areaid【区id】,area【省市区】
## address【街道地址】postCode【邮编】,telephone【固话】,mobilephone【手机】,isDefault【是否默认地址】

## 返回状态参数列表
## 0【成功】,1【用户为空】,2【收货人为空】,3【省id为空】,4【市id为空】,5【区id为空】,6【省市区为空】,7【街道地址为空】,8【联系方式为空】
## -1【地址数量超过上限】，-2【添加地址失败】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
		$_POST = json_decode(file_get_contents("php://input"),true);
if(!empty($_POST['uid'])){
    $uname = $_POST['uid'];
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$sql0="select * from mallbuilder_member where user='".$uname."'";
$db->query($sql0);
$mem = $db->fetchRow();
$uid = $mem['userid'];
if(!empty($_POST['connectName'])){
    $connectName = $_POST['connectName'];
}else{
    $re['result'] = 2;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['provinceid'])){
    $provinceid = $_POST['provinceid'];
}else{
    $re['result'] = 3;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['cityid'])){
    $cityid = $_POST['cityid'];
}else{
    $re['result'] = 4;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['areaid'])){
    $areaid = $_POST['areaid'];
}else{
    $re['result'] = 5;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['area'])){
    $area = $_POST['area'];
}else{
    $re['result'] = 6;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['address'])){
    $address = $_POST['address'];
}else{
    $re['result'] = 7;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['postCode'])){
    $postCode = $_POST['postCode'];
}else{
    $postCode = 0;
}
if(!empty($_POST['telephone'])||!empty($_POST['mobilephone'])){
    $telephone = $_POST['telephone']?$_POST['telephone']:'0';
    $mobilephone = $_POST['mobilephone']?$_POST['mobilephone']:'0';
}else{
    $re['result'] = 8;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['isDefault'])){
    $isDefault = $_POST['isDefault'];
}else{
    $isDefault = 1;
}
if($isDefault==2){
    $sql="select * from mallbuilder_delivery_address where userid = ".$uid." and `default`=2";
    $db->query($sql);
    if($db->fetchRow()){
        $upsql="update mallbuilder_delivery_address set `default`=1 where userid = ".$uid." and `default`=2";
        $db->query($upsql);
    }
}
$sql="insert into mallbuilder_delivery_address (userid,name,provinceid,cityid,areaid,area,address,zip,tel,mobile,`default`) value ('$uid','$connectName','$provinceid','$cityid','".$areaid."','".$area."','".$address."','".$postCode."','".$telephone."','".$mobilephone."','".$isDefault."')";
$ire = $db->query($sql);
if($ire){
    $re['result']=0;
    echo json_encode($re);
}else{
    $re['result'] = -2;
    echo json_encode($re);
    exit;
}
?>
