<?php
/**
* Auth:bruce
*
* Date:2014-12-32
*
* Dsc : 附近的店铺
*/

if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');
//==========================================================
## 经纬度计算距离函数
function getDistance($lat1, $lng1, $lat2, $lng2)
 {
     $earthRadius = 6367000; //approximate radius of earth in meters
 
     $lat1 = ($lat1 * pi() ) / 180;
     $lng1 = ($lng1 * pi() ) / 180;
 
     $lat2 = ($lat2 * pi() ) / 180;
     $lng2 = ($lng2 * pi() ) / 180;
 
     $calcLongitude = $lng2 - $lng1;
     $calcLatitude = $lat2 - $lat1;
     $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
     $calculatedDistance = $earthRadius * $stepTwo;
 
     return round($calculatedDistance);
 }

 //==============================================================
if($_GET['action'] == "ajax")
{
	$x = $y = 0;
	if(isset($_GET['x']) && !empty($_GET['x']))
	{
		$x = $_GET['x']*1;
	}
	if(isset($_GET['y']) && !empty($_GET['y']))
	{
		$y = $_GET['y']*1;
	}

	$limit = (isset($_GET['limit']) && $_GET['limit'] > 0)?$_GET['limit'] * 1:5;

	$sql="select (abs(a.lng-$x)+abs(a.lat-$y)) as paixe,grade,lng,lat,a.company,a.main_pro,a.userid,a.user,a.tel,a.area,a.logo,a.addr,b.sellerpoints,b.name from ".SHOP." a left join  ".MEMBER." b on a.userid=b.userid WHERE 1 and a.shop_statu=1 order by paixe asc limit $limit";
	$db->query($sql);
	$totalRows = $db->num_rows();	
	$db->query($sql);
	
	/**************************************/
	$re["list"]=$db->getRows();
	foreach($re["list"] as $key=>$v)
	{
		if($v['lng'] && $v['lat'])
		{
			$re["list"][$key]['longth'] = getDistance($v['lng'],$v['lat'],$x,$y);
		}
		$sql="select id,name as pname,member_id as userid,market_price,price,pic from ".PRODUCT." where member_id='".$v['userid']."' and status>0 limit 0,3";
		$db->query($sql);
		$re["list"][$key]['pro']=$db->getRows();
		
		$sql="select * from ".POINTS." order by id";
		$db->query($sql);
		$de=$db->getRows();
		foreach($de as $k=>$val)
		{
			$ar=explode('|',$val['points']);
			if($re["list"][$key]['sellerpoints']<=$ar[1] and $re["list"][$key]['sellerpoints']>=$ar[0])
			{
				$re["list"][$key]["sellerpointsimg"]=$val['img'];
			}
		}

		$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRODUCT." b on a.pid=b.id where b.member_id='".$re["list"][$key]['userid']."' and a.userid <> '".$v['userid']."'";
		$db->query($sql);
		$count=$db->fetchField('count');
		if($count!=0)
		{
			$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRODUCT." b on a.pid=b.id where b.member_id='".$re["list"][$key]['userid']."' and a.userid <> '".$v['userid']."' and a.goodbad=1";
			$db->query($sql);
			$re["list"][$key]["favorablerate"]=($db->fetchField('count')/$count)*100;
		}else{
			$re["list"][$key]["favorablerate"]="100";
		}
		
		$sql="select name from ".SHOPGRADE." where id= '$v[grade]' ";
		$db ->query($sql);
		$re["list"][$key]['gradename']=$db->fetchField('name');
		
		$sql="select count(*) as count  from ".PRODUCT." where member_id='".$v['userid']."' ";
		$db->query($sql);
		$re["list"][$key]['count']=$db->fetchField('count');
	}

	$de = $re["list"];
	$str = "";
	foreach($de as $key => $val)
	{
		$logo = $val['logo']?$val['logo']:$config['weburl']."/image/default/nopic.gif";
		//if($val['sellerpointsimg']){$str0="<p class='p-name color' <img align='absmiddle' src='".$config['weburl'].">/image/points/".$val['sellerpointsimg']."'> </p>";}
		//if($val['name']){$str1="<p class='p-name color'>店主：".$val['name']."</p>";}
		
		if($val['longth'])
		{
			if($val['longth']*1/1000 > 10)
			{
				$ll = ">10公里";
			}
			else if($val['longth']*1/1000 < 0.3)
			{
				$ll = $val['longth']."米";
			}
			else
			{
				$ll = ($val['longth']*1/1000)."公里";
			}
		}
		else
		{
			$ll  = "未知";
		}

        
	$string .="<div class='zhoushop lines'><div class='righdhd'><a class='pifesa' href='".$config['weburl']."/shop.php?uid=".$val['userid']."'><img height='78' width='78' alt='".$val['company']."' src='".$logo."'></a><ul class='mides'><li><span class='tidss shopzif'>".$val['company']."</span></li><li>好评率：".$val['favorablerate']."%</li><li><span class='tidss shoptbot'>地址：".$val['addr']."</span></li></ul> <ul class='riggfhs' <li><a class='lianjiea bgfdb' href='#''></a></li><li></li><li><a class='liapo shoptbot' href='#''>".$ll."</a></li></ul></div></div>";
	}
	echo $string;
	die;
}
	
	
	
//========================================================================

// 获取是否刷新
if(isset($_GET['act']) && $_GET['act'] == "renew")
{
	$tpl -> assign("act","renew");
	unset($_GET['act']);
}

$tpl->assign("current","shop");
include_once("footer.php"); 
$out=tplfetch("shop_list.htm",$flag);
?>