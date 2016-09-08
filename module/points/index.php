<?php

	$sql="select * from ".POINTSCAT." where parent_id=0 order by displayorder ,id";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$sql="select * from ".POINTSCAT." where parent_id='$v[id]'";
		$db->query($sql);
		$de[$k]['scat']=$db->getRows();	
	}
	$tpl->assign("cat",$de);

	$sql="select id,pic,name,points from ".POINTSGOODS." where status>0 order by id desc limit 0,18";
	$db->query($sql);
	$de=$db->getRows();
		
	$sql="select id,pic,name,points from ".POINTSGOODS." where status=2 order by id desc limit 0,12";
	$db->query($sql);
	$re=$db->getRows();	
	$tpl->assign("de",$de);
	$tpl->assign("re",$re);
	
        /*** 热门代金券 ****/
        $sql = "select * from ".VOUTEMO." where end_time >".time()." and isindex=1 order by start_time desc limit 0,6 ";
        $db -> query($sql);
	$voucher = $db -> getRows();	
	$tpl->assign("voucher",$voucher);
        
	include_once("footer.php");
	$tpl->assign("current","points");
	$out=tplfetch("points_index.htm",$flag,true);
?>