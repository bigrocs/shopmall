<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//==========================================
include_once("module/payment/includes/plugin_pay_class.php");
$act=$_GET['act']?$_GET['act']:NULL;
$op=$_GET['op']?$_GET['op']:NULL;
$pay=new pay();
$de=$pay->get_member_info($buid);
$tpl->assign("de",$de);

$sql="select con_group,con_title,con_id from ".WEBCON." where type=1 limit 0,4";
$db->query($sql);
$help=$db->getRows();
$tpl->assign("help",$help);

switch($act)
{
	case "logout":
	{
		bsetcookie("USERID",NULL,NULL,"/",$config['baseurl']);
		header("Location: $config[web_url]/login.php");
		break;	
	}
	case "edit":
	{
		switch($op)
		{
			case "name":
			{
				if($_POST['act']=='name')
				{
					$pay->edit_name($buid);
					msg("index.php?act=edit&op=name",'修改成功');	
				}
				$output=tplfetch("edit_name.htm");
				break;
			}
			default:break;
		}
		$page="edit.htm";
		break;	
	}
	default:
	{
		$re=$pay->get_trade_record($buid);
		$tpl->assign("re",$re);
		if(!empty($_GET['m']))
		{
			include("module/".$_GET['m']."/".$_GET['s'].".php");
			break;
		}
		else
		{
			$tpl->assign("config",$config);
			$output=tplfetch("main.htm");
		}
		break;
	}
}
$tpl->assign("output",$output);
include_once("footer.php");
$tpl->display("index.htm");
?>