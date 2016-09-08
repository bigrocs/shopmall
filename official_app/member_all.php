<?php
include_once("../includes/global.php");



$sql = "
select a.*,b.* from mallbuilder_member as a 
left join 
mallbuilder_member_count as b on a.userid = b.member_id ".$where." order by a.userid desc  " ;


$re = mysql_query($sql);

$arr =  array();
while($row = mysql_fetch_array($re)){
    $arr[] =  $row; 
}
//现在数组就是二维数组了 想输出json 的话就
echo json_encode($arr);

?>
