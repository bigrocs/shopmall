<?php
include_once($config['webroot']."/module/product/includes/plugin_product_class.php");
$id=$_GET["id"]*1;
//-----------------------------------
user_read_rec($buid,$id,1);//记录会员查看商品
//-----------------------------------
$prodetail=new product();
$prode=$prodetail->detail($id); 
$tpl->assign("detail",$prode);
$ar1=array('[catname]','[title]');
$ar2=array($cat,$re['downname']);
$config['title']=str_replace($ar1,$ar2,$config['title3']);
$config['keyword']=str_replace($ar1,$ar2,$config['keyword3']);
$config['description']=str_replace($ar1,$ar2,$config['description3']);
//====================================================
include_once("footer.php");
$tpl->assign("current","tg");
$out=tplfetch("tg_detail.htm");
?>