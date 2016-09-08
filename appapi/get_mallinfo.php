<?php
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_encode(file_get_contents("php://input"));
$where =" where 1 ";
$ssql = " select * from mallbuilder_webname";
if($_POST['searchType']){
    //按mallname查找
    if($_POST['mallName']){
        $where .=" and village like %".$_POST['mallName']."%";
    }
}else{
    //按mallid查找
    if($_POST['mallId']){
        $where .=" and id =".$_POST['mallId'];
    }
}
//-------------------分页-------------------------------
include_once("includes/page_utf_class.php");
$page = new Page;
$page->url=$config['weburl'].'/';
$page->listRows=$_POST['number'];
if(!$page->__get('totalRows'))
{
    $db->query($sql);
    $page->totalRows =$db->num_rows();
}
$limit=" limit ".$page->firstRow.",".$page->listRows;
//-------------------------分页end-------------------------
$ssql= $ssql.$where.$limit;
$db->query($ssql);
$malls=$db->getRows();
$re['page']=$page->prompt();
$re['count'] = $page->totalRows;

foreach($malls as $k=>$m){
    $mall[$k]['mallId'] = $m['id'];
    $mall[$k]['mallName'] = $m['village'];
    $jwd = explode(',',$m['jwd']);
    $mall[$k]['longitude'] = $jwd[0];
    $mall[$k]['latitude'] = $jwd[1];
    $mall[$k]['logoUrl'] = $m['logo'];
    $mall[$k] = $m;
}
$re['list'] = $mall;
$re['result'] = 0;
echo json_encode($re);
?>
