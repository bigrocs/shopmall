<?php

/* 
 * Dsc : 代金券管理
 */

include  $config['webroot']."/module/voucher/includes/plugin_voucher_class.php";
include_once("../includes/page_utf_class.php");
$voucher = new voucher();

/*** 商铺代金券列表 ****/
if(!isset($_GET['operation']))
{
    $re = $voucher ->voucher_shop_list();
    $tpl -> assign("re",$re);
}

/*** 功能申请列表 ****/
if(isset($_GET['operation']) && $_GET['operation'] == "apply")
{
    $re = $voucher ->apply_list();
    $tpl -> assign("re",$re);
}

/*** 店铺代金券模板推荐 ****/
if(isset($_GET['delindex']) && $_GET['delindex'] > 0)
{
    $id = $_GET['delindex'];
    $re = $voucher ->delindex($id);
    admin_msg("module.php?m=voucher&s=voucher.php","取消成功");
}
if(isset($_GET['addindex']) && $_GET['addindex'] > 0)
{
    $id = $_GET['addindex'];
    $re = $voucher ->addindex($id);
    admin_msg("module.php?m=voucher&s=voucher.php","推荐成功");
}
if(isset($_GET['delstatus']) && $_GET['delstatus'] > 0)
{
    $id = $_GET['delstatus'];
    $re = $voucher ->delstatus($id);
    admin_msg("module.php?m=voucher&s=voucher.php","设置成功");
}


/*** 添加面额 ****/
if(isset($_POST['act']) && $_POST['act'] == "add_amount")
{
    $re = $voucher ->amount_add();
    admin_msg("module.php?m=voucher&s=voucher.php&operation=amount","添加成功");
}

/*** 编辑面额 ****/
if(isset($_POST['act']) && $_POST['act'] == "edit_amount")
{
    $re = $voucher ->amount_edit();
    admin_msg("module.php?m=voucher&s=voucher.php&operation=amount","编辑成功");
}

if(isset($_GET['operation']) && $_GET['operation'] == "amount" && isset($_GET['act']) && $_GET['act'] == "edit")
{
    $re = $voucher -> amount_getone();
    $tpl -> assign("re",$re);
}

/*** 删除面额 ****/
if(isset($_GET['operation']) && $_GET['operation'] == "amount" && isset($_GET['delid']) && $_GET['delid'] > 0)
{
    $id = $_GET['delid']*1;
    if($voucher ->amount_delete($id))
    {
        admin_msg("module.php?m=voucher&s=voucher.php&operation=amount","删除成功");
    }
}

/*** 面额列表 ****/
if(isset($_GET['operation']) && $_GET['operation'] == "amount")
{
    $re = $voucher ->amount_list();
    $tpl -> assign("re",$re);
}

/*** 配置信息 ****/
if(isset($_GET['operation']) && $_GET['operation'] == "config")
{
    @include "config/p_voucher_config.php";
    $tpl -> assign("p_voucher_config",$p_voucher_config);
}

/*** 保存配置信息 ****/
if(!empty($_POST['act']) && $_POST["act"] == "config")
{
        $type = "p_voucher";
	unset($_POST['act']);
	foreach($_POST as $pname=>$pvalue)
	{
            $sql="select * from ".CONFIG." where `index`='$pname' and type='$type'";
            $db->query($sql);
            if($db->num_rows())
               $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='$type'";
            else
               $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','$type')";
            $db->query($sql1);
	}
        
        //===读库函数，生成config形式的数组====
        function read_config($type)
        {
                global $db;
                $sql="select * from ".CONFIG." where type='$type'";
                $db->query($sql);
                $re=$db->getRows();
                foreach($re as $v)
                {
                        $index=$v['index'];
                        $value=$v['value'];
                        $configs[$index]=$value;
                }
                return $configs;
        }
        
	/****更新缓存文件******/
	$write_config_con_array=read_config($type);//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	$write_config_con_str='<?php $'.$type.'_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/'.$type.'_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("module.php?m=voucher&s=voucher.php&operation=config","设置成功");
	exit;
}


$tpl->display("voucher.htm");
