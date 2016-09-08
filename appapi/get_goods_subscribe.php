<?php
## 传递参数列表
## uid【用户】,page【页码】,number【每页数量】

## 返回状态参数列表
## 0【成功】,1【用户为空】
include_once("../includes/global.php");

if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if(isset($_POST['uid']) && !empty($_POST['uid'])){
    $uname = $_POST['uid'];
	$where .= " and uname = '".$uname."'";
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
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
$limit ="limit ".$firstRow.",".$lastRow;
$sql="select * from mallbuilder_sns_shareproduct where 1 ";
$sql1= $sql.$where." order by addtime desc,id asc ".$limit;
$db->query($sql1);
$subscribe=$db->getRows();
$re['count'] = $db->num_rows();
$sql2= $sql.$where;
$db->query($sql2);
$recount = $db->num_rows();
$re['pageTotal'] =ceil($recount/$num);
$re['pageIndex'] =$page;

foreach($subscribe as $k=>$sub){
    $list[$k]['subscribeId'] = $sub['id'];
    $list[$k]['productId'] = $sub['pid'];

    $sql3="select * from ".PRODUCT." where id=".$sub['pid'];
    $db->query($sql3);
    $pro=$db->fetchRow();
    $list[$k]['productName'] = $pro['name'];
    $list[$k]['shopId'] = $pro['member_id'];

    $list[$k]['picUrl'] = $pro['pic']."_120X120.jpg";
    $list[$k]['price'] = $pro['price'];
    $list[$k]['createTime'] = $sub['addtime'];
}
$re['list'] = $list;
$re['result'] = 0;
echo json_encode($re);
?>
