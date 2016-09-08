<?php
include_once("includes/news_function.php");
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=========================================
$id=is_numeric($_GET["id"])?$_GET['id']:die('Error');
$pagecurrent=!empty($_GET['page'])&&is_numeric($_GET['page'])?$_GET['page']:1;
//=========================================
session_start();

$db->query("update ".NEWSD." set onclick=onclick+1 where nid='$id'");


useCahe("news_detail/",true);
$flag=md5($dpid.$dcid.$config["temp"].$id.$pagecurrent);
if(!$tpl->is_cached($page,$flag))
{
	$sql="SELECT a.*,b.con FROM ".NEWSD." a left join ".NEWSDATA." b on a.nid=b.nid WHERE a.nid='$id'";
	$db->query($sql);
	$news=$db->fetchRow();

	//-------------------------------------------
	$ar1=array('[catname]','[title]','[keyword]','[des]');
	$ar2=array($news['cat'],$news['ftitle'],$news['keyboard'],strip_tags($prode['con']),$news['smalltext']);
	
	$config['title']= $news['title'];
	$config['keyword']=str_replace($ar1,$ar2,$config['keyword3']);
	$config['description']=str_replace($ar1,$ar2,$config['description3']);
	//--------------------------------------------
	
	if($news['vote']!=',')
	{
		$sql="select * from ".NEWSVOTE." where id in ($news[vote]0)";
		$db->query($sql);
		$vote=$db->getRows();
		foreach($vote as $key=>$val)
		{
			if(strtotime($val['time'])-time()<0 and strtotime($val['time']))
			{
				$vote[$key]['end']='1';	
			}
			if($_COOKIE['vote'.$buid.$val['id']])
			{
				$vote[$key]['ip']='1';	
			}
			$str=explode('|',$val['votetext']);
			for($i=0;$i<count($str);$i++)
			{
				$vote[$key]['item'][$i]=explode(',',$str[$i]);
			}
		}
	}
	$tpl->assign("vote",$vote);
	$tpl->assign("de",$news);
	$tpl->assign("current","news");
	include_once("footer.php");
	$out=tplfetch("newsd.htm",$flag);
	unset($news);
}
	

?>