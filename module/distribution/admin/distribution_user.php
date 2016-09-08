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
	$subsql.=" and s.userid='".$us['userid']."'";
}

if ($_GET['shop_type'])
{
	$subsql.=" AND s.shop_type IN (" . $_GET['shop_type'] . ") ";
}
else
{
	$subsql.=" AND  s.shop_type IN (1, 3) ";
}

$sql="SELECT * FROM " . SHOP . " s LEFT JOIN " . DISTRIBUTION_USER . " u ON s.userid = u.user_id  where  1  $subsql  ORDER BY s.userid DESC";

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
					<td ><select class="select" name="shop_type" id="shop_type">
							<?php
							$order_status = array('0'=>'全部', '1'=>'分销商', '3'=>'分销商和商家');
							foreach($order_status as $key=>$v)
							{
								?>
								<option value="<?php echo $key;?>" <?php if($_GET['shop_type']==$key&&$_GET['shop_type']!='') echo "selected";?>> <?php echo $v;?> </option>
							<?php } ?>
						</select></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>" />
						<input name="m" type="hidden" value="distribution" />
						<input name="s" type="hidden" value="distribution_user.php" /></td>
				</tr>
			</table>
		</form>
		<table width="100%" border="0" cellpadding="2" cellspacing="0">
			<tr class="theader">
				<td width="13%" align="left">用户名称</td>
				<td width="25%">店铺名称</td>
				<td width="11%">佣金总额</td>
				<td width="27%">已提现</td>
				<td width="12%" align="left" >开通时间</td>
			</tr>
			<?php
			//----送货方式
			foreach ($re as $v)
			{
				?>
				<tr>
					<td width="13%" align="left" ><a href="to_login.php?action=submit&user=<?php echo $v['user'];?>"><?php echo $v['user']; ?></a></td>
					<td width="25%" ><?php echo $v['company']?>&nbsp;</td>
					<td width="11%" ><?php
						//$v['distribution_user_amount'] = $v['distribution_shop_amount_0']+$v['distribution_reg_amount_0']+$v['distribution_click_amount_0'];
						echo number_format($v['distribution_shop_amount_0']+$v['distribution_reg_amount_0']+$v['distribution_click_amount_0'],2)?></td>
					<td width="27%"><?php echo number_format($v['distribution_user_settlement_amount'],2)?></td>
					<td width="12%" align="left"><?php echo date("Y-m-d H:i",$v['distribution_user_apply_time']); ?></td>

				</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="7" align="right"><div class="page"><?php echo $pages?></div></td>
			</tr>
		</table>
	</div>
</div>
</body>
</html>
