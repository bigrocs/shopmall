<?php
	if($_GET['id']&&is_numeric($_GET['id']))
	{
		$sql="DELETE FROM ".READREC." WHERE `id` = '$_GET[id]' and `userid` = $buid";
		$db->query($sql);
		msg("main.php?m=product&s=admin_footprint");
	}
	
	$sql = "select a.id,a.tid,a.time,b.name,b.pic,b.price from ".READREC." a left join ".PRODUCT." b on a.tid = b.id where a.userid='$buid' and a.type='1' order by FROM_UNIXTIME(`time`, '%Y-%m-%d') desc ";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key => $val)
	{
        if($val['name']){
            $time = date('Y-m-d',$val['time']);
            switch($time)
            {
                case date("Y-m-d",strtotime("-2 day")):
                {
                    $date = "前天";
                    break;
                }
                case date("Y-m-d",strtotime("-1 day")):
                {
                    $date = "昨天";
                    break;
                }
                case date("Y-m-d"):
                {
                    $date = "今天";
                    break;
                }
                default:
                {
                    $date = date("d",strtotime($time))."日";
                    break;
                }
            }
            $de[$time]['date'] = $date;
            $de[$time]['count'] += 1;
            $de[$time]['product'][] = $val;
        }
	}
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$output=tplfetch("admin_footprint.htm");
?>