<?php
include_once("../includes/global.php");
if(file_get_contents("php://input"))
	$_POST = json_encode(file_get_contents("php://input"));
//控制产品查询个数 假设传来的limit是“limit=1,19”的样式
if($_POST['limit']){
    $lim = array();
    $lim = explode(',',$_POST['limit']);
    $limit='';
    if(count($lim)==1){
        $limit="limit 0,".$lim[0];
    }
    if(count($lim)==2){
        $limit="limit ".$lim[0].",".$lim[1];
    }
}

$sql = "
select a.*,b.* from mallbuilder_member as a 
left join 
mallbuilder_member_count as b on a.userid = b.member_id ".$where.$limit." order by a.userid desc  " ;

$re = mysql_query($sql);

$arr =  array();
while($row = mysql_fetch_array($re)){
    $arr[] =  $row; 
}
//现在数组就是二维数组了 想输出json 的话就
echo json_encode($arr);

?>
