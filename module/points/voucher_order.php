<?php

/* 
 *  直接交易成功
 */

include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
$member=new member();
$points=$member->get_points();

if(!isset($_GET['data']) || empty($_GET['data'])) die("非法链接！");

$key = explode(",", $_GET['data']);
$key[0] = intval($key[0]);
$key[1] = intval($key[1]);

$_GET['data'] = implode(',', $key);

$sql = "select * from ".VOUTEMO." where id = ".$key[0]." and shop_id = ".$key[1];
$db -> query($sql);
$de = $db -> fetchRow();

$sql = "select user from ".MEMBER." where userid = $buid ";
$db -> query($sql);
$user = $db -> fetchfield("user");

if($points>=$de['points'])
{
        $serial = time().rand(100000,999999);
        $sql = "select * from ".VOUCHER." where member_id = $buid and temp_id = ".$de['id'];
        $db -> query($sql);
        $num = $db -> num_rows();
       
        if($num >= $de['eachlimit']) // 每人限制领取几张
        {
             echo "<h2 style='text-align:center;line-height:48px;'>兑换失败,每人最多可兑换 <em>".$de['eachlimit']."</em> 张</h2>";
             exit;
        }
        if($de['total'] <= $de['giveout']) // 兑换完了
        {
            echo "<h2 style='text-align:center;line-height:48px;'>您来晚了,代金券已经兑换完了~</h2>";
            exit;
        }
        $sql = "insert into ".VOUCHER." (`serial`,`temp_id`,`name`,`desc`,`start_time`,`end_time`,`price`,`limit`,`shop_id`,`status`,`create_time`,`member_id`,`member_name`,`logo`,`shop_name`) values "
                . "('$serial','$de[id]','$de[name]','$de[desc]','$de[start_time]','$de[end_time]','$de[price]','$de[limit]','$de[shop_id]',1,'".time()."','$buid','$user','$de[logo]','$de[shop_name]') ";
        if($db -> query($sql))
        {
            // 增加模板表中的已发放数量
            $sql = "update ".VOUTEMO." set `giveout` = giveout+1 where id= $de[id]";
            $db -> query($sql);
            
            $flag=$member->add_points(-$de['points'],'7','',$buid);
            $flag=$member->add_points($de['points'],'7','',$de['shop_id']);
        }
        
        echo "<h2 style='text-align:center;line-height:48px;'>恭喜您，兑换成功！</h2>";
}
else
{
        echo "<h2 style='text-align:center;line-height:48px;'>积分不足，兑换失败！</h2>";
}

