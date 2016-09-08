<?php
if($_GET['id'] and is_numeric($_GET['id'])){
    $sql="insert into ".PRODUCT."(`member_id`, `member_name`, `catid`, `type`, `name`, `subhead`, `keywords`, `brand`, `market_price`, `price`, `stock`, `sales`, `code`, `con`, `pic`, `pic_more`, `start_time_type`, `start_time`, `valid_time`, `weight`, `cubage`, `freight_id`, `freight_type`, `post_price`, `express_price`, `ems_price`, `province_id`, `city_id`, `area_id`, `street_id`, `area`, `clicks`, `rank`, `uptime`, `status`, `is_shelves`, `custom_cat_id`, `promotion_id`, `goodbad`, `shop_rec`, `is_invoice`, `is_tg`, `is_virtual`, `is_dist`, `down_reason`) select `member_id`, `member_name`, `catid`, `type`, `name`, `subhead`, `keywords`, `brand`, `market_price`, `price`, `stock`, `sales`, `code`, `con`, `pic`, `pic_more`, `start_time_type`, `start_time`, `valid_time`, `weight`, `cubage`, `freight_id`, `freight_type`, `post_price`, `express_price`, `ems_price`, `province_id`, `city_id`, `area_id`, `street_id`, `area`, `clicks`, `rank`, `uptime`, `status`, `is_shelves`, `custom_cat_id`, `promotion_id`, `goodbad`, `shop_rec`, `is_invoice`, `is_tg`, `is_virtual`, `is_dist`, `down_reason` from ".PRODUCT." where id =$_GET[id]";
    $db->query($sql);
    $sql1 = "select * from " . SETMEAL . " where pid=$_GET[id]";
    $db->query($sql1);
    $re=$db->getRows($sql1);
    $sql="select id from " . PRODUCT . " where member_id=$buid order by id desc limit 0,1";
    $db->query($sql);
    $de = $db->fetchRow($sql);
    $pid = $de['id'];
    foreach ($re as $v){
        $sql1 = "insert into " .SETMEAL . "(pid,setmeal,spec_name,price,market_price,cost_price,stock,sku,property_value_id)values('$pid','$v[setmeal]','$v[spec_name]','$v[price]','$v[market_price]','$v[cost_price]','$v[stock]','$v[sku]','$v[property_value_id]')";
    $db->query($sql1);   
    }
    $sql="select userid,proid,detail from " .PRODETAIL." where proid =$_GET[id]";
    $db->query($sql);
    $re= $db->fetchRow($sql);
    $sql = "insert into " . PRODETAIL ."(userid,proid,detail)values('$re[userid]','$pid','$re[detail]')";
    $db->query($sql);
    
    msg("$config[weburl]/main.php?m=product&s=admin_product_list","操作成功");
}
