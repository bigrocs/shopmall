<?php
include_once("../includes/page_utf_class.php");
if(file_exists("$config[webroot]/module/product/lang/cn.php"))
	include("$config[webroot]/module/product/lang/cn.php");//#调用模块语言包
//=============================================
if(!empty($_GET['deid']))
{	
	$sql="update ".ORDER." set status='-1' where order_id='$_GET[deid]'";
	$db->query($sql);
}

if(!empty($_GET['username']))
{
    $sqlc="select userid from ".MEMBER." where user='".$_GET['username']."'";
	$db->query($sqlc);
	$us=$db->fetchRow();
    if(!empty($_GET['ordertype'])&&$_GET['ordertype']=='b')
       $subsql.=" and o.buyer_id='".$us['userid']."'";
	elseif (!empty($_GET['ordertype'])&&$_GET['ordertype']=='s')
	   $subsql.=" and o.seller_id='".$us['userid']."'";
	elseif (!empty($_GET['ordertype'])&&$_GET['ordertype']=='d')
		$subsql.=" and o.dist_user_id='".$us['userid']."'";
	else
       $subsql.=" and ( o.dist_user_id='".$us['userid']."')";
       //$subsql.=" and ( buyer_id='".$us['userid']."' or seller_id='".$us['userid']."')";
}

if($_GET['orderstatus']!='')
	$subsql.=" and o.status='".$_GET['orderstatus']."'";
$sql="select o.*, m.user seller_user, d.user dist_user from ".ORDER." o LEFT JOIN ".MEMBER." m ON o.seller_id=m.userid LEFT JOIN  ".MEMBER." d ON o.dist_user_id=d.userid  where o.buyer_id='' and o.userid!='' and o.dist_user_id!=0  $subsql  order by o.id desc";
//=============================

$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$pages = $page->prompt();
//=====================
$db->query($sql);
$re=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('orderm');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('oum');?></div>
  <div class="bigboxbody">
    <form method="get" action="">
      <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="searh_left">分销<?php echo lang_show('ouname');?></td>
          <td><input class="text" type="text" name="username" id="username" value="<?php echo $_GET['username'];?>" />
          </td>
        </tr>
        <tr>
          <td ><?php echo lang_show('ostatu');?></td>
          <td ><select class="select" name="orderstatus" id="orderstatus">
              <option value=""><?php echo lang_show('otypeall');?></option>
              <?php
			 include("../lang/$config[language]/company_type_config.php");
			 foreach($order_status as $key=>$v)
			 {
			 ?>
              <option value="<?php echo $key;?>" <?php if($_GET['orderstatus']==$key&&$_GET['orderstatus']!='') echo "selected";?>> <?php echo $v;?> </option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>" />
            <input name="m" type="hidden" value="distribution" />
            <input name="s" type="hidden" value="distribution_user_order.php" /></td>
        </tr>
      </table>
    </form>
    <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr class="theader">
        <td width="13%" align="left"><?php echo lang_show('ordid');?></td>
        <td width="10%"><?php echo lang_show('ostatu');?></td>
        <td width="11%"><?php echo lang_show('price');?></td>
        <td width="10%"><?php echo lang_show('obuyer');?></td>
        <td width="10%"><?php echo lang_show('oseller');?></td>
        <td width="17%">分销商</td>
        <td width="12%" align="left" ><?php echo lang_show('otime');?></td>
        <td width="12%" align="center" ><?php echo lang_show('option');?></td>
      </tr>
      <?php
	  //----送货方式
	  foreach ($re as $v)
	  {
	 ?>
      <tr>
        <td width="13%" align="left" ><a href="?m=product&s=order_detail.php&oid=<?php echo $v['order_id'];?>"><?php echo $v['order_id']; ?></a></td>
        <td ><?php echo $order_status[$v['status']]?>&nbsp;</td>
        <td ><?php echo number_format($v['product_price']+$v['logistics_price'],2)?></td>
        <td ><?php echo $v['consignee']?>/<?php echo $v['consignee_tel']?><?php echo $v['consignee_mobile']?></td>
        <td ><?php echo $v['seller_user']?></td>
        <td ><?php echo $v['dist_user']?></td>
        <td  align="left"><?php echo date("Y-m-d H:i",$v['create_time']); ?></td>
        <td  align="center"><a href="sendmail.php?userid=<?php echo $v['seller_id']; ?>"><?php echo $mailimg;?></a> <a href="?m=product&s=order_detail.php&oid=<?php echo $v['order_id'];?>"><?php echo $editimg; ?></a>
       
          <?php if($v['status']==0||$v['status']==4||$v['status']==6){?>
          <a onClick="return confirm('确信删除吗？');" href="?m=distribution&s=distribution_user_order.php&deid=<?php echo $v['order_id'];?>"><?php echo $delimg;?></a>
          <?php } ?>
        </td>
      </tr>
      <?php 
        }
	?>
      <tr>
        <td colspan="9" align="right"><div class="page"><?php echo $pages?></div></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>