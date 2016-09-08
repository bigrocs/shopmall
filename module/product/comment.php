<?php

	if(empty($_GET['m'])||empty($_GET['s'])||empty($_GET['id'])||empty($_GET['uid']))
		die('forbiden;');

	$sql="select a.buyer_id,a.name,a.pic,b.member_id as userid,b.member_name as user,b.id,b.price from ".ORPRO." a left join ".PRODUCT." b on a.pid=b.id where order_id='$_GET[id]'";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("pro",$re);

	if($_POST['submit']=="submit")
	{
		$sql="select user,userid from ".MEMBER." where userid='$_GET[uid]' ";
		$db->query($sql);
		$de=$db->fetchRow();
		foreach($re as $k=>$v)
		{
			if($v['userid'])
			{
				$db->query("update ".MEMBER." set sellerpoints=sellerpoints+". $_POST["g".$k]." where userid='".$v['userid']."'");	
		
				$sql="select id from ".PRODUCT." where id='$v[id]' ";
				$db->query($sql);
				$pro=$db->fetchRow();
				if($pro['id'])
				{
					$db->query("update ".PRODUCT." set goodbad=goodbad+". $_POST["g".$k]." where id='".$v['id']."'");	
				}
				
				include_once($config['webroot'].'/includes/filter_class.php');
				$word=new Text_Filter();
				$_POST["comment_text".$k] = htmlspecialchars($_POST["comment_text".$k]);
				$_POST["comment_text".$k] = $word->wordsFilter($_POST["comment_text".$k], $matche_row);
			
				$sql="INSERT ".PCOMMENT."(userid,user,fromid,pid,puid,pname,price,con,uptime,goodbad) VALUES ('$de[userid]','$de[user]','$v[userid]','$v[id]','$v[userid]','$v[name]','$v[price]','".$_POST["comment_text".$k]."','".time()."','". $_POST["g".$k]."')";
				$db->query($sql);
			}
		
		}
		if($_POST['snuma'] and $_POST['snumb'] and $_POST['snumc'] and $_POST['snumd'] )
		{
			$sql="INSERT ".UCOMMENT."(userid,user,byid,item1,item2,item3,item4,uptime) VALUES ('$de[userid]','$de[user]','".$re[0]['userid']."','$_POST[snuma]','$_POST[snumb]','$_POST[snumc]','$_POST[snumd]','".time()."')";
			$db->query($sql);
		}
		$db->query("update ".ORDER." set buyer_comment=1 where order_id='".$_GET['id']."'");	
		
		msg("main.php?m=product&s=admin_buyorder","评论成功");
	}
	$tpl->assign("current","product");
	$tpl->assign("n","0");
	include_once("footer.php");
	//=============================================
	$out=tplfetch("comment.htm",NULL);
	
?>