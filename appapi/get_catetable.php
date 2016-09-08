<?php
## 传递参数列表

## 返回状态参数列表
## 0【成功】

include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
include_once("../config/home_config.php");
if(file_get_contents("php://input"))
	$_POST = json_decode(file_get_contents("php://input"),true);

$sql="select * from ".PCAT." where 1";
$db->query($sql);
$re['categorys']=$db->getRows();
$re['result'] =0;
echo json_encode($re);
?>
