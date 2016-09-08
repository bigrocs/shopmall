<?php
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=会员卡.xls');
header('Pragma: no-cache');
header('Expires: 0');
	
	$scl='';	
	if(!empty($_GET['name']))
	$scl.=" and `card_num` like '%".trim($_GET['name'])."%'";
	if(!empty($_GET['type']))
		$scl.=" and `statu`='".$_GET['type']."'";
	if(!empty($_GET['stime']))
		$scl.=" and regtime>='".trim($_GET['stime'])."'";
	if(!empty($_GET['etime']))
		$scl.=" and regtime<='".trim($_GET['etime'])."'";

	$sql="
		SELECT
			`card_num`,
			`regtime`,
			`statu`,
			`rand_pwd`
		FROM
			".MEMBER."
		WHERE
			1
		AND card_num != ''  $scl
		ORDER BY
			regtime DESC
		";
	$db->query($sql);
	$rw=$db->getRows();

	$exe_title=array('NO','会员卡号','初始密码','是否激活','生成时间');
	$exe_data='';

	foreach($rw as $k=>$v)
	{	
		$i = $k+1;
		if($v['statu']==2){
			$statu="已激活";
		}
		if($v['statu']==1){
			$statu="未激活";
		}
		$exe_data[]=array("$i","'$v[card_num]'","$v[rand_pwd]","$statu","$v[regtime]");
	}
	 
	echo iconv('utf-8', 'gbk', implode("\t", $exe_title)), "\n";

	if(!empty($exe_data)){
		foreach ($exe_data as $value){
			echo iconv('utf-8', 'gbk', implode("\t", $value)), "\n";
		}
	}
 
	die;
?>
