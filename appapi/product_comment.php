<?php
## 传递参数列表
## productId【商品id】,type【评价类型】,page【页码】,number【每页数量】

## 返回状态参数列表
## 0【成功】,1【商品id为空】
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
$where =" where 1 ";
$sql = " select * from ".PCOMMENT;

if(isset($_POST['productId']) && !empty($_POST['productId'])){
    $productid = $_POST['productId'];
	$where .= " and pid = ".$productid ;
}else{
    $re['result'] = 1;
    echo json_encode($re);
    exit;
}
if(!empty($_POST['type'])){
    $type = $_POST['type'];
	$where .= " and goodbad = ".$type ;
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
$limit =" limit ".$firstRow.",".$lastRow;
$sql1=$sql.$where." order by uptime desc,id asc ".$limit;
$db->query($sql1);
$prol=$db->getRows();
$re['count']=$db->num_rows();
$sql2=$sql.$where;
$db->query($sql2);
$re['commentVolume']=$db->num_rows();
$re['pageTotal']=ceil($re['commentVolume']/$num);
$re['pageIndex']=$page;
$re['productId']=$productid;
foreach($prol as $k=>$l){
    $list[$k]['username'] = $l['user'];
    $sql="select * from mallbuilder_member where userid=".$l['userid'];
    $db->query($sql);
    $mem=$db->fetchRow();
    if($mem['logo']=='image/default/avatar.png'){
        $list[$k]['headUrl'] = "";//头像链接
    }else{
        $list[$k]['headUrl'] = $mem['logo'];//头像链接
    }
    $list[$k]['message'] = $l['con'];
    $list[$k]['int'] = $l['goodbad'];
    $list[$k]['postTime'] = $l['uptime'];
}
$re['list']=$list;//评价列表
$re['result']=0;
echo json_encode($re);
?>