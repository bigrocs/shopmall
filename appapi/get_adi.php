<?php
include_once("../includes/global.php");

$ssql = " select * from mallbuilder_advs_con";
$db->query($ssql);
$advs=$db->getRows();
$adv['count'] = $db->num_rows();
foreach($advs as $k=>$a){
    $advv[$k]['advType'] = $a['type'];
    $advv[$k]['index'] = $a['sort_num'];
    $advv[$k]['advId'] = $a['advid'];//对应店铺或商品id
    $advv[$k]['mallId'] = $a['mallid'];//商城id
    $advv[$k]['url'] = $a['url'];
}
$adv['list'] = $advv;
$adv['result'] = 0;
echo json_encode($adv);
?>
