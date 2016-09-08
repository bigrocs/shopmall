<?php
if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');
//==========================================================

$_GET['keys']=$_GET['keys']?$_GET['keys']:$_GET['key'];
$key = $_GET['keys'];

$_GET['id'] = intval($_GET['id']);

//是否启用Sphinx搜索
if ($sphinx_search_flag && $key && extension_loaded("sphinx") && extension_loaded("scws"))
{
	$b_time = microtime(true);
	//$key = "我是一个测试";
	$index = "shop_name_search";
	//========================================分词

	$so = new Yf_Search_Scws($key);
	$words = $so->getResult();
	fb($words);

	//========================================搜索
	$sc = new SphinxClient();
	$sc->SetServer($sphinx_search_host, $sphinx_search_port);
	#$sc->SetMatchMode(SPH_MATCH_ALL);
	$sc->SetMatchMode(SPH_MATCH_EXTENDED);
	$sc->SetArrayResult(TRUE);
	$sc->setFilter('shop_statu', array(1));

	$order = '';
	$orderby = $_GET['orderby'];


	if($orderby==1)
		$order.="sellerpoints DESC, rank DESC";
	else
	{
		$order.="rank DESC, userid DESC";
	}

	$sc->SetSortMode(SPH_SORT_EXTENDED, $order);

	if ($_GET['firstRow'])
	{
		$start = $_GET['firstRow'];
	}
	else
	{
		$start = 0;
	}

	$sc->SetLimits($start, 16, 1000);    // 最大结果集10000

	$res = $sc->Query($words,$index);
	//print_r($res);
	$e_time = microtime(true);
	$time = $e_time - $b_time;

	fb($time);
	fb($res);

	$prol = array();

	if ($res['matches'])
	{
		foreach ($res['matches'] as $matches)
		{
			$matches['attrs']['id'] = $matches['id'];
			$prol[] = $matches['attrs'];
		}

	}

	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->url=$config['weburl'].'/';
	$page->listRows=16;

	if(!$page->__get('totalRows'))
	{
		$page->totalRows = $res['total'];
	}

	$re['list']=$prol;
	$re['page']=$page->prompt();
	$re['count']=$res['total'];
}
else
{
	if(!empty($_GET['province'])){
		$_GET['province']=$_GET['province']*1;
		$sqls.=" and a.provinceid='$_GET[province]'";
	}
	if(!empty($_GET['city'])){
		$_GET['city'] = $_GET['city']*1;
		$sqls.=" and a.cityid='$_GET[city]'";
	}

	if($_GET['orderby']==1)
		$o=" order by sellerpoints desc,rank,userid desc";
	else
		$o=" order by rank,userid desc";

	if(!empty($_GET['id']) and is_numeric($_GET['id']))
	{
		$str[]=$_GET['id']*1;
		$sql="select id from ".SHOPCAT."  where parent_id='$_GET[id]' ";
		$db->query($sql);
		$ss=$db->getRows();
		if($ss)
		{
			foreach($ss as $val)
			{
				$str[]="$val[id]";
			}
		}
		$ss=implode(',',$str);
		$sqls.=" and a.catid in ($ss)";
	}

	if(!empty($_GET['keys']))
		$sqls.=" and (a.company regexp '".trim($_GET['keys'])."')";

	if($dpid)
		$sqls.=" and a.provinceid='".getdistrictid($dpid)."'";
	if($dcid)
		$sqls.=" and a.cityid='".getdistrictid($dcid)."'";
	if($daid)
		$sqls.=" and a.areaid='".getdistrictid($daid)."'";
	if($dsid)
		$sqls.=" and a.streetid='".getdistrictid($dsid)."'";

	$sql="select grade,a.company,a.main_pro,a.userid,a.user,a.tel,a.area,a.logo,a.addr,b.sellerpoints,b.name from ".SHOP." a left join  ".MEMBER." b on a.userid=b.userid WHERE a.shop_statu=1 $sqls $o";

	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->url=$config['weburl'].'/';
	$page->listRows=10;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$db->query($sql);
	$re["page"]=$page->prompt();
	/**************************************/
	$re["list"]=$db->getRows();
}

	foreach($re["list"] as $key=>$v)
	{
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
	$tpl->assign("info",$re);
	$tpl->assign("province",GetDistrict1());
	
//========================================================================
	$sql="select id,name from ".SHOPCAT."  where parent_id=0 order by displayorder ,id";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("cat",$re);
	
	$url=implode('&',convert($_GET));
	$tpl->assign("url",$url);

$tpl->assign("current","shop");
include_once("footer.php"); 
$out=tplfetch("shop_index.htm",$flag);
?>