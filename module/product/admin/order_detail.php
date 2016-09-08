<?php
$_GET['oid'] = addslashes($_GET['oid']);
if(!empty($_GET['tuikuan'])&&!empty($_GET['oid']))
{
	include_once($config['webroot']."/module/product/includes/plugin_order_class.php");
	$order=new order();
	$order->set_order_statu($_GET['oid'],6);
}

$sqld="select * from ".ORDER." where order_id='".$_GET['oid']."'";
$db->query($sqld);
$od=$db->fetchRow();
if ($dist_user_id = $od['dist_user_id'])
{
	$str = " AND a.order_id='".$_GET['oid']."'";

	if (4 == $od['status'])
	{
		$sql="select a.*,b.company,b.user, p.commission_product_price_0, p.commission_product_price_1, p.commission_product_price_2 from ".ORDER." a left join ".SHOP." b  on a.buyer_id = b.userid  left join " . DISTRIBUTION_PRODUCT_ORDER . " p  ON a.order_id = p.order_id where a.dist_user_id = '".$dist_user_id."' and a.buyer_id != '' $str order by a.id desc";

	}
	else
	{
		$sql="select a.*,b.company,b.user, p.commission_product_price_0, p.commission_product_price_1, p.commission_product_price_2  from ".ORDER." a left join ".SHOP." b  on a.buyer_id = b.userid left join " . ORPRO . " c  ON a.order_id = c.order_id left join " . DISTRIBUTION_PRODUCT . " p  ON c.pid = p.product_id where a.dist_user_id = '".$dist_user_id."' and a.buyer_id != '' $str order by a.id desc";
	}

	$db->query($sql);
	$od=$db->fetchRow();
}

/*确认付款*/
if(!empty($_GET['oid']) && $_GET['opeator']=='fk'){

  $sql=" update ".ORDER." set status=4 where order_id='{$_GET['oid']}'";
  $db->query($sql);
  
  $sqll=" update ".WORDER."  set statu=4 where order_id='{$_GET['oid']}'";
   $db->query($sqll);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>订单详情</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<style>
.ickd_return th{ display:none;}
.ickd_return td{border-top:none; padding:5px 20px 5px 0;}
</style>
<div class="bigbox">
    <div class="bigboxhead">订单详情</div>
    <div class="bigboxbody">
    <table width="100%" border="0"cellspacing="1" cellpadding="6">
        <tr class="theader">
        	<td colspan="2" >订单详情</td>
        </tr>
        <tr>
            <td width="13%">订单编号</td>
            <td width="87%"><?php echo $od['order_id'];?></td>
        </tr>
        <tr>
            <td>订单状态</td>
            <td>
            <?php
				include("../lang/$config[language]/company_type_config.php");
				$sta=$od['status'];
				echo $order_status[$sta];
            ?>
            </td>
        </tr>
        <?php if($od['invoice_no']) { ?>
        <tr>
        	<td>物流公司</td>
            <td><?php echo $od['logistics_name'] ?></td>
        </tr>
        <tr>
        	<td>发货单号</td>
            <td><?php echo $od['invoice_no'] ?></td>
        </tr>
        <tr>
            <td>物流信息</td>
            <td style="padding:3px 3px 3px 0;">
            <script src="<?php echo $config['weburl'] ?>/api/logistic.php?com=<?php echo $od['logistics_name'] ?>&nu=<?php echo $od['invoice_no'] ?>"></script>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td>订购时间</td>
            <td><?php echo date("Y-m-d H:m",$od['create_time']);?></td>
        </tr>
        <tr>
            <td>收货人</td>
            <td><?php echo $od['consignee'];?></td>
        </tr>
        <tr>
            <td>收货人地址</td>
            <td><?php echo $od['consignee_address'];?></td>
        </tr>
        <tr>
            <td>收货人联系方式</td>
            <td>
			<?php echo $od['consignee_mobile'];?>&nbsp;
            <?php echo $od['consignee_tel'];?>
            </td>
        </tr>
        <tr>
            <td>运送方式</td>
            <td><?php echo $od['logistics_type'];?></td>
        </tr>
        <tr>
            <td>运送总价</td>
            <td><?php echo number_format($od['logistics_price'],2);?>元</td>
        </tr>
        <tr>
            <td>商品总价</td>
            <td><?php echo number_format($od['product_price'],2);?>元(不含运费)</td>
        </tr>
        <?php if($od['dist_user_id']) {?>
			<tr>
				<td>一级佣金</td>
				<td height="30" colspan="2" ><?php echo number_format($od['commission_product_price_0'],2);?> 元</td>
			</tr>
			<tr>
				<td>二级佣金</td>
				<td height="30" colspan="2" ><?php echo number_format($od['commission_product_price_1'],2);?> 元</td>
			</tr>
			<tr>
				<td>三级佣金</td>
				<td height="30" colspan="2" ><?php echo number_format($od['commission_product_price_2'],2);?> 元</td>
			</tr>
		<?php } ?>
        <?php if($od['des']) {?>
        <tr>
            <td height="30" colspan="2" ><?php echo $od['des'];?></td>
        </tr>
        <?php } ?>
	</table>
        
	<table width="100%" border="0"cellspacing="1" cellpadding="6">
        <tr class="theader">
        	<td colspan="2">产品详情</td>
        </tr>
        <?php
        $sql="select * from ".ORPRO." where order_id=$od[order_id]";	
        $db->query($sql);
        $re=$db->getRows();
        foreach($re as $key=>$pro)
        {
        ?>
        <tr>
            <td width="114" > 
                <a href='../?m=product&s=detail&id=<?php echo $pro['pid'];?>' target='_blank'>
                <?php if(!empty($pro['pic'])){ ?>
                <img src="<?php echo $pro['pic'];?>"  width="80" height="80"/>
                <?php }else{ ?> 
                <img src="../image/default/nopic.gif"  width="80" height="80"/>
                <?php }?> 
                </a>
            </td>
            <td align="left" valign="top" >
            <?php
            echo "产品名称：<a href='../?m=product&s=detail&id=$pro[pid]' target='_blank'>".$pro['name']."</a><br>";
            echo "产品单价：". number_format($pro['price'],2)."元<br>";
            echo "产品数量：".$pro['num'];
            ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
	
	<div style=" clear:both; height:0px; line-height:0px; margin:0px; padding:0px;"></div>
	<div style=" width:60px; height:30px; margin-top:20px; cursor:pointer;"><a href="javascript:if(confirm('确认付款么？')){ window.location.href='<?php echo $config['weburl'] ?>/admin/module.php?m=product&s=order_detail.php&oid=<?php echo $_GET['oid'];?>&opeator=fk'}"><button>确认付款</button></a></div>

	</div>
</div>
</body>
</html>