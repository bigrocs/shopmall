<?php
## 完成下载订单，发货和下载退货单三个操作。
##

##
##

include_once("../includes/global.php");
//传递数据
if($_SERVER['HTTP_HOST'])
    $host = "http://".$_SERVER['HTTP_HOST']."/";
else
    $host = "http://".$_SERVER['SERVER_NAME']."/";
$url = "http://mall.18bc.com/api/gethttp.php";
$post = array(
    "https" => $host);
$data = curl_post($url,$post);
$data = json_decode($data,true);

if(file_get_contents("php://input"))
    $_POST = json_decode(file_get_contents("php://input"),true);

//将订单表中一条订单的两条数据合为一条
function merge_order($rows1)
{
    if ($rows1)
    {
        foreach ($rows1 as $ko => $vo)
        {
            if ($ko % 2 != 0) {
                $rows1[$ko - 1]['buyer_id'] = $rows1[$ko]['buyer_id']; //订单表中买卖家两条数据合一条
            }
        }
        $i = -1;
        foreach ($rows1 as $kk => $vv)
        {
            $i++;
            if ($i % 2 != 0)
            {
                unset($rows1[$i]); //删掉重复的订单数据
            }
        }
    }

    return $rows1;
}
//查出卖家店铺名称和ID
function seller_name($rows3)
{
    global $db;
    if ($rows3)
    {
        $sql = "select user, company from ".SHOP." where userid= $rows3";
        $db->query($sql);
        $shop_rows = $db->getRows();
    }
    return $shop_rows;
}
//查出买家店铺名称和ID
function buyler_name($rows4)
{
    global $db;
    if($rows4)
    {
        $sql = "select user, name, sex, mobile, email, qq, ww from ".MEMBER." where userid= $rows4";
        $db->query($sql);
        $member_rows = $db->getRows();
    }
    return $member_rows;
}

//===================
//判断是发货还是下载订单或者下载退货单
//===================

if ($_GET['orderIdCloud'] && $_GET['expressId'] && $_GET['expressNum'] && $_GET['express']) //发货
{
    $arr1 = array();
    $sql="update ".ORDER." set status=3, invoice_no=$_GET[expressNum], logistics_name='$_GET[express]' where order_id=$_GET[orderIdCloud] ";
    $db->query($sql);
    $num = mysql_affected_rows();
    if ($num > 0) //判断影响行数
    {
        $arr1['orderId'] = $_GET['orderId'];
        $arr1['msg'] = 'success';
        $arr1['total'] = $num/2;
        $arr1['orderIdCloud'] = $_GET['orderIdCloud'];
    }

    echo json_encode($arr1);
}
// 下载退货单
//==========
elseif($_GET['met'] == 'downreorder')
{

    //按店铺查找

    $sql1 = "select a.* ,b.user from " . ORDER . " as a left join ".MEMBER." as b on a.userid = b.userid";
    /*//按店铺查找
    if(!empty($_GET['sid']))
    {
        $where =" where b.user = '$_GET[sid]' and";
    }
    else
    {
        $where = " where ";
    }*/

    //按店铺查找
    if(!empty($_GET['sid']))
    {
        $sqls = "select userid from " .MEMBER. " where user = '$_GET[sid]' ";
        $db->query($sqls);
        $mem = current($db->getRows());
    }
    $where = " where ";
    //按下载方式即订单生成时间查找create_time
    if($_GET['start_created'] && $_GET['end_created'])
    {
        $start = strtotime($_GET['start_created']);
        $end = strtotime($_GET['end_created']);
        $time = " a.uptime > $start and a.uptime <= $end";
    }

    //按订单编号查询
    if($_GET['number'])
    {
        if (strstr($_GET['number'], ',')){
            $where .=" order_id in($_GET[number]) ";
        }else{
            $where .="  order_id= $_GET[number] ";
        }
    }
    $sql= $sql1.$where.$time;

    $db->query($sql);

    $rows2=$db->getRows();

    //mall中的订单是两个所以需要合起来，
    $orders = merge_order($rows2);
    $goods = array();
    foreach ($orders as $kg => $vg) {
        if ($_GET['sid'] != -1 ? $mem['userid'] == $vg['seller_id'] : !empty($orders)) //按买家
        {
            $sql = "select * from " . ORPRO . " where status in (4,5) and order_id=$vg[order_id] $time ";
            $db->query($sql);
            $rows = $db->getRows();
            $goods = $rows;
            foreach ($rows as $k1 => $v1) {
                if ($v1['status'] == 4 || $v1['status'] == 5) {
                    $address = explode(" ", $vg['consignee_address']);
                    if ($address[0] == '北京市') {
                        $order[$kg]['order_delivery_address_province'] = '北京';
                        $order[$kg]['order_delivery_address_city'] = '北京市';
                        $order[$kg]['order_delivery_address_county'] = $address[2];
                        $order[$kg]['order_delivery_address_address'] = $address[3];
                    } elseif ($address[0] == '上海市') {
                        $order[$kg]['order_delivery_address_province'] = '上海';
                        $order[$kg]['order_delivery_address_city'] = '上海市';
                        $order[$kg]['order_delivery_address_county'] = $address[2];
                        $order[$kg]['order_delivery_address_address'] = $address[3];
                    } elseif ($address[0] == '天津市') {
                        $order[$kg]['order_delivery_address_province'] = '天津';
                        $order[$kg]['order_delivery_address_city'] = '天津市';
                        $order[$kg]['order_delivery_address_county'] = $address[2];
                        $order[$kg]['order_delivery_address_address'] = $address[3];
                    } elseif ($address[0] == '重庆市') {
                        $order[$kg]['order_delivery_address_province'] = '重庆';
                        $order[$kg]['order_delivery_address_city'] = '重庆市';
                        $order[$kg]['order_delivery_address_county'] = $address[2];
                        $order[$kg]['order_delivery_address_address'] = $address[3];
                    } else {
                        $order[$kg]['order_delivery_address_province'] = $address[0];
                        $order[$kg]['order_delivery_address_city'] = $address[1];
                        $order[$kg]['order_delivery_address_county'] = $address[2];
                        $order[$kg]['order_delivery_address_address'] = $address[3];
                    }
                    //查出卖家店铺名称和ID
                    $shop_rows = seller_name($vg['seller_id']);
                    $order[$kg]['shop_name'] = $shop_rows[0]['user'];
                    $order[$kg]['shop_id'] = $vg['seller_id'];
                    $order[$kg]['order_finished_time'] = $vg['uptime'];


                    //查出买家名称和ID
                    $member_rows = buyler_name($vg['buyer_id']);
                    $order[$kg]['user_name'] = $member_rows[0]['name'];
                    $order[$kg]['user_id'] = $vg['buyer_id'];
                    //拼接其他字段
                    $order[$kg]['order_delivery_address_mobile'] = $vg['consignee_mobile']; // 收货手机号码
                    $order[$kg]['order_delivery_address_contacter'] = $vg['consignee']; // 收货联系人
                    $order[$kg]['order_id'] = $vg['order_id']; // 订单号

                    $sqls = "select * from mallbuilder_return where order_id=$v1[order_id]";
                    $db->query($sqls);
                    $return_row = $db->getRows();

                    foreach ($return_row as $kr => $vr) {
                        $order[$kg]['reason'] = $vr['reason'];
                        $order[$kg]['refund_price'] = $vr['refund_price'];
                        $order[$kg]['goods_status'] = $vr['goods_status'];
                        $order[$kg]['status'] = $vr['status'];
                        $order[$kg]['crea_time'] = $vr['create_time'];
                    }
                    $order[$kg]['goods_msg'] = $goods;

                }

            }
        }
    }

    echo json_encode($order);

}
// 退货或者拒绝退货
//==========
elseif(!empty($_GET['met']) && !empty($_GET['regoods']))
{
    include_once("$config[webroot]/module/product/includes/plugin_refund_class.php");
    @include_once("$config[webroot]/config/image_config.php");
    $refund=new refund();
    $order_id = explode(',' ,$_GET['Ids']);
    //$goods_id = explode(',' ,$_GET['regoods']);
    $i = 0;
    foreach($order_id as $val)
    {

        //根据ERP传来的商品ID从订单商品表中查出退货表所要用的product_id，
        //此操作存在问题是：当同时同意退货多个订单，订单中所含商品一样时可能会退错订单中商品。
        $sql = "SELECT id FROM ". ORPRO ." WHERE order_id=$val and pid in($_GET[regoods])";
        $db->query($sql);
        $orders = $db->getRows();
        foreach($orders as $k=>$v)
        {
            $pid .= ','.$v['id'];

        }
        $pids = ltrim($pid, ',');

        $sql = "SELECT refund_id FROM ". REFUND ." WHERE order_id=$val and product_id in($pids)";
        $db->query($sql);
        $order = $db->getRows();

        foreach($order as $kk=>$vv)
        {
            if ($_GET['met'] == 'agree')
            {

                $msg = "卖家于 ".date("Y-m-d H:i:s",time())." 同意退款申请。";
                $refund->add_talk($vv['refund_id'], $de['order_id'], $msg,'');
                $refund->agree_refund($vv['refund_id']);
            }
            elseif($_GET['met'] == 'refuce')
            {
                $msg = "卖家已经拒绝了退款申请";
                $refund->add_talk($vv['refund_id'], $val, $msg,'');
                $refund->refuse($vv['refund_id'], '');
            }
            $i++ ;
        }
    }
    if ($i>0)
    {
        echo json_encode($i); //粗放的认为拒绝或者同意退货商品数作为返回数据。
    }

}
else
{

    //============
    //下载订单
    //============
    $psql = "select a.* ,b.user from " . ORDER . " as a left join ".MEMBER." as b on a.userid = b.userid";
   
	$where = " where  1 ";
   //按店铺查找
    if(!empty($_GET['sid']) && $_GET['sid'] != -1)
    {
        $sqls = "select userid from " .MEMBER. " where user = '$_GET[sid]' ";
        $db->query($sqls);
        $mem = current($db->getRows());
		
		$where .= "and b.userid = $mem[userid] ";
    }
    

    //按网店查找
    /*if($_GET['goodsStatus']) {
        $where .= "";
    }*/
    //按下载方式即订单生成时间查找create_time

    if ($_GET['start_created'] && $_GET['end_created'])
    {
        $start = strtotime($_GET['start_created']);
        $end = strtotime($_GET['end_created']);
        $where .= " and `create_time` > $start and `create_time` <= $end";
    }
    //按订单编号查询
    if ($_GET['number'])
    {
        if (strstr($_GET['number'], ','))
        {
            $where .= " and order_id in($_GET[number]) ";
        }
        else
        {
            $where .= " and order_id= $_GET[number] ";
        }
    }
	
    $sql1 = $psql . $where ;
    $db->query($sql1);
    $orders = $db->getRows();


    //mall中的订单是两个所以需要合起来，
    $orders = merge_order($orders);
    
    foreach ($orders as $kg => $vg) {
        if ($_GET['sid'] != -1 ? $mem['userid'] == $vg['seller_id'] && $vg['status'] != -1 : $vg['status'] != -1 ) //按买家
        {
            //将mall中地址拆分。因为mall中的直辖市和ERP中不一样多了一个市字，所以需要分别判断，
            if ($vg['consignee_address']) {
                $address = explode(" ", $vg['consignee_address']);
                
                if ($address[0] == '北京市') {
                    $orders[$kg]['order_delivery_address_province'] = '北京';
                    $orders[$kg]['order_delivery_address_city'] = '北京市';
                    $orders[$kg]['order_delivery_address_county'] = $address[1];
					
					unset($address[0]);unset($address[1]);
					$adstr = implode(' ',$address);
                    $orders[$kg]['order_delivery_address_address'] = $adstr;
                } elseif ($address[0] == '上海市') {
                    $orders[$kg]['order_delivery_address_province'] = '上海';
                    $orders[$kg]['order_delivery_address_city'] = '上海市';
                    $orders[$kg]['order_delivery_address_county'] = $address[1];
                    
					unset($address[0]);unset($address[1]);
					$adstr = implode(' ',$address);
                    $orders[$kg]['order_delivery_address_address'] = $adstr;
                } elseif ($address[0] == '天津市') {
                    $orders[$kg]['order_delivery_address_province'] = '天津';
                    $orders[$kg]['order_delivery_address_city'] = '天津市';
                    $orders[$kg]['order_delivery_address_county'] = $address[1];
					
                    unset($address[0]);unset($address[1]);
					$adstr = implode(' ',$address);
                    $orders[$kg]['order_delivery_address_address'] = $adstr;
                } elseif ($address[0] == '重庆市') {
                    $orders[$kg]['order_delivery_address_province'] = '重庆';
                    $orders[$kg]['order_delivery_address_city'] = '重庆市';
                    $orders[$kg]['order_delivery_address_county'] = $address[1];
                    
					unset($address[0]);unset($address[1]);
					$adstr = implode(' ',$address);
                    $orders[$kg]['order_delivery_address_address'] = $adstr;
                } else {
                    $orders[$kg]['order_delivery_address_province'] = $address[0];
                    $orders[$kg]['order_delivery_address_city'] = $address[1];
                    $orders[$kg]['order_delivery_address_county'] = $address[2];
                    
					unset($address[0]);unset($address[1]);unset($address[2]);
					$adstr = implode(' ',$address);
                    $orders[$kg]['order_delivery_address_address'] = $adstr;
                }

            }

            //查出卖家店铺名称和ID
            $shop_rows = seller_name($vg['seller_id']);
            $orders[$kg]['shop_name'] = $shop_rows[0]['user'];
            $orders[$kg]['shop_id'] = $vg['seller_id'];
            $member_row = buyler_name($vg['seller_id']);
            $orders[$kg]['shop_mobile'] = $member_row[0]['mobile'];
            $orders[$kg]['store_account'] = $member_row[0]['user'];

            //查出买家名称和ID user, name, sex, mobile, email, qq, ww
            $member_rows = buyler_name($vg['buyer_id']);
            $orders[$kg]['user_account'] = $member_rows[0]['user'];
            $orders[$kg]['user_name'] = $member_rows[0]['name'];
            $orders[$kg]['user_sex'] = $member_rows[0]['sex'];
            $orders[$kg]['user_mobile'] = $member_rows[0]['mobile'];
            $orders[$kg]['user_email'] = $member_rows[0]['email'];
            $orders[$kg]['user_qq'] = $member_rows[0]['qq'];
            $orders[$kg]['user_ww'] = $member_rows[0]['ww'];
            $orders[$kg]['user_id'] = $vg['buyer_id'];



            //查出支付名称和id
            if ($vg['payment_name'])
            {
                $sql = "select payment_id  from pay_type where payment_name= '$vg[payment_name]'";
                $db->query($sql);
                $pay_rows = $db->getRows();
                $orders[$kg]['payment_id'] = $pay_rows[0]['payment_id'];
                $orders[$kg]['payment_name'] = $vg['payment_name'];
            }
            //
            if ($vg['order_id'])
            {
                $sql = "select id, serial, price, `limit`, `status`  from mallbuilder_voucher where order_id= $vg[order_id]";
                $db->query($sql);
                $voucher_rows = $db->getRows();
                $orders[$kg]['voucher_id'] = $voucher_rows[0]['id'];
                $orders[$kg]['voucher_number'] = $voucher_rows[0]['serial'];
                $orders[$kg]['voucher_price'] = $voucher_rows[0]['price'];
                if ($voucher_rows[0]['status'] == 2) //代金券已用
                {
                    $orders[$kg]['order_goods_amount'] = ($voucher_rows[0]['price'] + $vg['product_price']) / $vg['discounts'];//总价
                    $orders[$kg]['order_discount_amount'] = ($voucher_rows[0]['price'] + $vg['product_price']) * $vg['discounts'] + $voucher_rows[0]['price'];//优惠的金额
                } elseif (!empty($vg['discounts']) && $vg['discounts'] != 0)//有折扣
                {
                    $orders[$kg]['order_goods_amount'] = $vg['product_price'] / $vg['discounts'];//总价
                    $orders[$kg]['order_discount_amount'] = $vg['product_price'] * $vg['discounts'];//优惠的金额
                } else  //没有使用过任何优惠措施
                {
                    $orders[$kg]['order_goods_amount'] = $vg['product_price'];
                    $orders[$kg]['order_discount_amount'] = 0;
                }
            }
            if ($vg['buyer_comment'] == 1 || $vg['seller_comment'] == 1)
            {
                $orders[$kg]['order_evaluation_status'] = "1";      //判断是否评论过
                $orders[$kg]['order_evaluation_time'] = $vg['uptime'];
            }
            $orders[$kg]['order_payment'] = $vg['product_price'] + $vg['logistics_type']; //应付款
            $orders[$kg]['order_shipping_fee_amount'] = $vg['logistics_type']; //运费金额
            $orders[$kg]['order_shipping_fee'] = ""; //实际运费金额,暂时不知道做何用order_message
            $orders[$kg]['order_message'] = $vg['des'];//订单备注*/


            if (!empty($vg['product_price']) || !empty($vg['logistics_type']))
            {
                $orders[$kg]['order_point_add'] = floor($vg['product_price'] + $vg['logistics_type']); //订单赠送积分与应付款1:1关系
            }
            if ($vg['status'] == 4 || $vg['status'] == 6) //订单状态为4,说明订单成功，6,退款成功,即为订单完成时间
            {
                $orders[$kg]['order_finished_time'] = $vg['uptime'];
            }
            else
            {
                $orders[$kg]['order_finished_time'] = "";
            }

            //订单商品
            $goods = array();
            if ($vg['order_id'])
            {
                $sql = "select *  from " . ORPRO . " where order_id= $vg[order_id]";
                $db->query($sql);
                $pay_rows = $db->getRows();
                $goods = $pay_rows;
            }
            $orders[$kg]['goods_msg'] = $goods;
        }
        else
        {
            unset($orders[$kg]);
        }
        //综合返产品列表
        $order['items'] = array_values($orders);
    }
	

    echo json_encode($order);
	
}
?>
