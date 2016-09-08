<?php

$order_id = preg_replace('/[^\d]/', '', $_GET['order_id']);
$sql = "SELECT * FROM ".ORDER." WHERE `seller_id` != '' AND `order_id` = $order_id";
$db->query($sql);
$list = $db->fetchRow();

$db->query("SELECT * FROM ".ORPRO." WHERE `order_id` = $order_id");
$list['product'] = $db->getRows();

$tpl->assign('list', $list);
tplfetch('admin_orderprint.htm','',true);
