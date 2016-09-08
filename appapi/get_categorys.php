<?php
## 传递参数列表
## level【分类级数：1，2，3】 cid【父类id】
## 返回状态参数列表
## 0【成功】,1【父类id为空】

include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
include_once("../config/home_config.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);
if($_POST['level']==1){
    //--------类别名--------------
    $sql="select brand,cat,catid,pic from ".PCAT." where catid<9999";
    $db->query($sql);
    $cata=$db->getRows();
    foreach($cata as $key=>$c){
        $cat_pro[$key]['catid'] = $c['catid'];
        $cat_pro[$key]['name'] = $c['cat'];
        if(!empty($c['brand']))
        {
            $brand_id=$c['brand'];
            $sql="select id,name,logo from ".BRAND." where 1 and id in($brand_id) limit 0,10";
            $db->query($sql);
            $cat_pro[$key]['brand']=$db->getRows();
        }
    }
}
if($_POST['level']>1&&!empty($_POST['cid'])){
    $s=$_POST['cid']."00";
    $b=$_POST['cid']."99";
    $sql="select brand,cat,catid,pic from ".PCAT." where catid>$s and catid<$b order by nums asc,char_index asc limit 0,7";
    $db->query($sql);
    $cata = $db->getRows();
    foreach($cata as $key=>$c){
        $cat_pro[$key]['catid'] = $c['catid'];
        $cat_pro[$key]['name'] = $c['cat'];
        if(!empty($c['brand']))
        {
            $brand_id=$c['brand'];
            $sql="select id,name,logo from ".BRAND." where 1 and id in($brand_id) limit 0,10";
            $db->query($sql);
            $cat_pro[$key]['brand']=$db->getRows();
        }
    }
}else{
    if($_POST['level']>1&&empty($_POST['cid'])){
        $re['result'] = 1;
        echo json_encode($re);
        exit;
    }
}
$re['categorys']=$cat_pro;
$re['result'] =0;
echo json_encode($re);
?>
