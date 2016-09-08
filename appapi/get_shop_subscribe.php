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
	$where = " and uname='".$uname."'";
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
$sql="select * from mallbuilder_sns_shareshop where 1 ";
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
    $list[$k]['shopId'] = $sub['shopid'];
    $list[$k]['shopName'] = $sub['shopname'];
    $sql="select * from mallbuilder_shop where userid=".$sub['shopid'];
    $db->query($sql);
    $shop=$db->fetchRow();
    $list[$k]['logoUrl'] = $shop['logo'];
    $list[$k]['shopStatus'] = $shop['shop_statu'];
    $list[$k]['area'] = $shop['area'];
    $list[$k]['grade'] = $shop['grade'];
    $list[$k]['mainProduct'] = $shop['main_pro'];
    $list[$k]['createTime'] = $sub['addtime'];
}
$re['list'] = $list;
$re['result'] = 0;
echo json_encode($re);
?>
