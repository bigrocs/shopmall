<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//======================

$str=" and uid=$buid";
if(!empty($_GET['gid']) && $_GET['gid']!=0)
{

    $grid = $_GET['gid'];
    $sql="select id,uid,uname,fnickname,uimg,fuid,funame,fuimg,state,b.area,b.buyerpoints,b.sex,
c.group_id,c.name,c.describe from ".FRIEND." a left join ".MEMBER." b on a.fuid = b.userid left join ".FRIENDG." c on
a.group_id = c.group_id where c.group_id = '$grid' and 1 $str order by addtime desc";
} else {
    $sql = "select id,uid,uname,fnickname,uimg,fuid,funame,fuimg,state,b.area,b.buyerpoints,b.sex,
c.group_id,c.name,c.describe from " . FRIEND . " a left join " . MEMBER . " b on a.fuid = b.userid left join " . FRIENDG . " c on
a.group_id = c.group_id where 1 $str order by addtime desc";
}
    include_once($config['webroot']."/includes/page_utf_class.php");

    $page = new Page;
    $page->listRows=15;

    if (!$page->__get('totalRows')){
        $db->query($sql);
        $page->totalRows = $db->num_rows();
    }
    $sql .= "  limit ".$page->firstRow.",".$page->listRows;
    $db->query($sql);
    $re["list"]=$db->getRows();

    $sql="select * from ".POINTS." order by id";
    $db ->query($sql);
    $de=$db->getRows();
    foreach($re["list"] as $key=>$val)
    {
        foreach($de as $k=>$v)
        {
            $ar=explode('|',$v['points']);
            if($val['buyerpoints']<=$ar[1] and $val['buyerpoints']>=$ar[0])
            {
                $re["list"][$key]["buyerpointsimg"]=$v['img'];
            }
        }
    }
    $re["page"]=$page->prompt();


   $che = rtrim($_POST['ddd'],",");
   $sql = "UPDATE " . FRIEND . " set group_id='$_POST[id]' where LOCATE(id,'$che') > 0";
   $db ->query($sql);


$tpl->assign("config",$config);
$tpl->assign("re",$re);

tplfetch("ajax_friends_group.htm",'',true);
?>