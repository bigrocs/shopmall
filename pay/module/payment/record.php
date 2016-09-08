<?php
include_once("includes/page_utf_class.php");
$page = new Page;
$page->listRows=10;
if($_GET['mold']==1)
{
	$s=" and (mold = '2' or mold = '1')";
}
elseif($_GET['mold']==2)
{
	$s=" and mold = '8'";
}
else
{
	$s=" and mold != '8' and mold != '2' and mold != '1'";
}
if($_GET['type']==1)
{
	$s=" and price < 0";
}
if($_GET['type']==2)
{
	$s=" and price > 0";
}
if($_GET['k'])
{
	$s=" and note like '%$_GET[k]%' ";
}
if($_GET['status']&&is_numeric($_GET['status']))
{
	$statu=$_GET['status']*1;
	$s.=" and statu= '$statu' ";	
}
$sql="select * from ".RECORD." where pay_uid='$buid' and (isnull(`order_id`) or LEFT(`order_id`,1) != 'U') $s order by id desc ";
if(!$page->__get('totalRows'))
{
	$db->query($sql);
	$page->totalRows =$db->num_rows();
}
$sql.=" limit ".$page->firstRow.",".$page->listRows;
//--------------------------------------------------
$db->query($sql);
$re['list']=$db->getRows();
$re['page']=$page->prompt();
foreach($re['list'] as $key=>$val)
{		
	$name=$val['seller_email']?$val['seller_email']:($val['buyer_email']?$val['buyer_email']:NULL);
	$sql="select pay_id,real_name from ".MEMBER." where pay_email='$name'";
	$db->query($sql);
	$re['list'][$key]['name']=$db->fetchRow();	
	$re['list'][$key]['minus']=($val['price']<0)?"T":"F";	
}
$tpl->assign("re",$re);	
$tpl->assign("current","record");
$tpl->assign("config",$config);
$output=tplfetch("record.htm");
?>