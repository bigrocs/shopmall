<?php
$id=!empty($_GET["id"])?$_GET["id"]*1:NULL;
$key=!empty($_GET["key"])?trim($_GET["key"]):NULL;

if (null == $key)
{
	$key=!empty($_GET["keyword"])?trim($_GET["keyword"]):NULL;
}

$firstRow=!empty($_GET["firstRow"])?$_GET["firstRow"]:NULL;
$totalRows=!empty($_GET["totalRows"])?$_GET["totalRows"]:NULL;
$orderby=!empty($_GET["orderby"])?$_GET['orderby']*1:NULL;

//是否启用Sphinx搜索
if ($sphinx_search_flag && $key && extension_loaded("sphinx") && extension_loaded("scws"))
{
	$b_time = microtime(true);
	//$key = "我是一个测试";
	$index = "product_search";

	$so    = new Yf_Search_Scws($key);
	$words = $so->getResult();

	$sc = new SphinxClient();
	$sc->SetServer($sphinx_search_host, $sphinx_search_port);
	#$sc->SetMatchMode(SPH_MATCH_ALL);
	$sc->SetMatchMode(SPH_MATCH_EXTENDED);
	$sc->SetArrayResult(TRUE);


	$sc->setFilter('shop_statu', array(1));
	$sc->setFilter('p_status', array(1));
	$sc->setFilter('is_shelves', array(1));
	//$sc->setFilter('tg', array(crc32('false')));

	if (!empty($_GET['ptype']) and $_GET['ptype'] >= 0 and $_GET['ptype'] < count($ptype))
	{
		$sc->setFilter('type', array($_GET[ptype]));
	}

	if(isset($_GET['province']))
	{
		$sc->setFilter('provinceid', array(intval($_GET['province'])));
	}

	$order = '';

	if ($orderby == 1)
	{
		$order .= "sales DESC";
	}
	elseif ($orderby == 2)
	{
		$order .= "clicks DESC";
	}
	elseif ($orderby == 3)
	{
		$order .= "goodbad DESC";
	}
	elseif ($orderby == 4)
	{
		$order .= "uptime DESC";
	}
	elseif ($orderby == 5)
	{
		$order .= "price ASC";
	}
	elseif ($orderby == 6)
	{
		$order .= "price DESC";
	}
	else
	{
		$order .= "rank DESC, uptime DESC";
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

	$sc->SetLimits($start, 30, 1000);    // 最大结果集10000

	$res = $sc->Query($words, $index);

	$prol = array();

	if ($res['matches'])
	{
		foreach ($res['matches'] as $matches)
		{
			$matches['attrs']['id'] = $matches['id'];
			$prol[]                 = $matches['attrs'];
		}
		
	}


	include_once("includes/page_utf_class.php");
	$page           = new Page;
	$page->url      = $config['weburl'] . '/';
	$page->listRows = 30;

	if (!$page->__get('totalRows'))
	{
		$page->totalRows = $res['total'];
	}

	$prolist['list']  = $prol;
	$prolist['page']  = $page->prompt();
	$prolist['count'] = $res['total'];
	$tpl->assign("info", $prolist);
	unset($prolist);
}
else
{
    //===================================分类
	if(is_numeric($id))
	{
		if(strlen($id)>8)
			$catname[]=substr($id,0,-6);
		if(strlen($id)>6)
			$catname[]=substr($id,0,-4);
		if(strlen($id)>4)
			$catname[]=substr($id,0,-2);
		$catname[]=$id;
		$tpl->assign("catname",$catname);

		$cat=readCat($id);
		//-----------------------------分类关连的品牌
		if(!empty($cat['brand']))
		{
			$sql="select * from ".BRAND." where id in ( $cat[brand] ) order by displayorder asc ";
			$db->query($sql);
			$re=$db->getRows();
			$tpl->assign("brand",$re);
		}
		//-----------------------------子类
		$sql="select cat,catid from ".PCAT." where catid < ".$id."99 and catid > ".$id."00 order by nums asc ";
		$db->query($sql);
		$de=$db->getRows();
		$tpl->assign("cat",$de);

		//-----------------------------获取分类下自定义字段搜索项
		$sql="select id,name from ".PROPERTY." where is_search = '1' and type_id='$cat[ext_field_cat]'";
		$db->query($sql);
		$catfile = $db->getRows();
		foreach($catfile as $fkey => $v)
		{
			$v['field'] = 'property_'.$v["id"];
			$catfile[$fkey]["field"] = $v['field'];
			$sql="select id,name from ".PROPERTYVALUE." where property_id='$v[id]' order by displayorder";
			$db->query($sql);
			$catField = $db->getRows();
			foreach($catField as $skey=>$sv)
			{
				$catfile[$fkey]['catItem'][]= array("id"=>$sv['id'],"name"=>$sv['name']);
			}
			//------组合皖搜索
			if(!empty($_GET[$v['field']]))
				$ext_sql.=' and b.'.$v['field'].'=\''.$_GET[$v['field']].'\'';
		}
		$tpl->assign("catfile",$catfile);
		//---------------------------------按分类搜索
		$scl.=" and LOCATE('".intval(trim($_GET['id']))."',a.catid)=1 ";//按类别搜索
	}
//------------------------------------------------------
	include_once("config/module_product_config.php");
	$tpl->assign("ptype",$ptype=explode('|',$module_product_config['ptype']));
//------------------------------------------------------
// -- 手机端屏蔽虚拟商品 bruce 2015-1-22
	if($config['temp'] == "wap")
	{
		$scl .= " and a.`is_virtual` = 0";
	}
	if(!empty($key))
		$scl.=" and ( a.keywords like '%$key%' or a.name like '%$key%' )";
	if(!empty($_GET['brand']))
		$scl.=" and a.brand='".$_GET['brand']."' ";
	if($dpid)
		$scl.=" and c.provinceid='".getdistrictid($dpid)."'";

	if(isset($_GET['province']))
	{
		$scl.=" and c.provinceid='" . intval($_GET['province']) . "'";
	}

	if($dcid)
		$scl.=" and c.cityid='".getdistrictid($dcid)."'";
	if($daid)
		$scl.=" and c.areaid='".getdistrictid($daid)."'";
	if($dsid)
		$scl.=" and c.streetid='".getdistrictid($dsid)."'";

	if(!empty($_GET['ptype']) and $_GET['ptype']>=0 and $_GET['ptype']<count($ptype))
		$scl.=" and a.type='$_GET[ptype]' ";


	if(!empty($_GET['is_dist']) and $_GET['is_dist']==1)
		$scl.=" and a.is_dist=1 ";

	if($orderby==1)
		$scl.=" order by a.sales desc";
	elseif($orderby==2)
		$scl.=" order by a.clicks desc";
	elseif($orderby==3)
		$scl.=" order by a.goodbad desc";
	elseif($orderby==4)
		$scl.=" order by a.uptime desc";
	elseif($orderby==5)
		$scl.=" order by a.price ASC";
	elseif($orderby==6)
		$scl.=" order by a.price desc";
	elseif($orderby==8)
		$scl.=" order by p.commission_product_price_0 desc";
	else{
		if($config['temp']=='wap'){
			$scl.=" order by p.commission_product_price_0 desc";
		}else{
			$scl.=" order by a.rank desc,a.uptime desc";
		}

	}
//--------------------------------------------------
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->url=$config['weburl'].'/';
	$page->listRows=30;
	if(empty($cat['ext_field_cat']))
		$sql="SELECT a.id,a.name as pname,a.price,a.market_price,a.member_id as userid,a.pic,c.company, p.* FROM ".PRODUCT." a left join ".DISTRIBUTION_PRODUCT." p ON a.id=p.product_id left join ".SHOP." c on a.member_id=c.userid WHERE c.shop_statu=1 and a.status>0 and is_shelves=1  $ext_sql $scl";
	else
		$sql="SELECT a.id,a.name as pname,a.price,a.market_price,a.member_id as userid,a.pic,c.company, p.* FROM ".PRODUCT." a left join ".DISTRIBUTION_PRODUCT." p ON a.id=p.product_id left join ".$cat['ext_table']." b on a.id=b.product_id left join ".SHOP." c on a.member_id=c.userid WHERE c.shop_statu=1 and a.status>0 and is_shelves=1 $ext_sql $scl";
	if(!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows =$db->num_rows();
	}

	$sql.=" limit ".$page->firstRow.",".$page->listRows;
//--------------------------------------------------
	$db->query($sql);
	$prol=$db->getRows();

	fb($prol);



	if ($distribution_open_flag)
	{
		$distribution_product_rows = $distribution->getDistributionProduct($buid);
		$distribution_product_id_row = array();

		if ($distribution_product_rows)
		{
			foreach ($distribution_product_rows as $v)
			{
				array_push($distribution_product_id_row, $v['product_id']);
			}
		}


		//判断卖家是否有广告费
		$distribution_visit_price = $distribution_config['distribution_visit_price'];

		foreach ($prol as $k => $row)
		{
			//是否已经加入分销
			if (in_array($row['id'], $distribution_product_id_row))
			{
				unset($prol[$k]);
				continue;
			}

			//判断卖家是否有广告费
			$sellder_row = $distribution->getDistributionUser($row['user_id']);

			if ($sellder_row && $sellder_row['distribution_adv_money'] >0)
			{
				$prol[$k]['share_money'] = $distribution_visit_price;
			}
			else
			{
				$prol[$k]['share_money'] = 0;
			}
		}
	}

	$prolist['list']=$prol;
	$prolist['page']=$page->prompt();
	$prolist['count']=$page->totalRows;
	$tpl->assign("info",$prolist);
	unset($prolist);
}

$tpl->assign("province",GetDistrict1());

//------------------------------------------------------
$url=implode('&',convert($_GET));
$tpl->assign("url",$url);
//----------------------------SEO
$config['title']=str_replace('[catname]',$cat['cat'],$config['title2']);
$config['keyword']=str_replace('[catname]',$cat['cat'],$config['keyword2']);
$config['description']=str_replace('[catname]',$cat['cat'],$config['description2']);
//=====================================================
if($cat['templates'])
{
	$tpl -> template_dir = $config['webroot'] . "/templates/".$cat['templates']."/";
	$tpl -> compile_dir  = $config["webroot"] . "/templates_c/".$cat['templates']."/";
}

$tpl->assign("current","product");
include_once("footer.php");
$out=tplfetch("product_list.htm");
?>