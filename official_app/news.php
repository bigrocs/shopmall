<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
$input_data = trim(file_get_contents("php://input"));

if ($input_data)
{
    parse_str($input_data, $user_request_data);

    if (is_array($user_request_data))
    {
        $_REQUEST = array_merge($_REQUEST, $user_request_data);
    }
}

$search=$_REQUEST["search"]?$_REQUEST["search"]:$_GET["search"];
$nid=$_REQUEST["nid"]?$_REQUEST["nid"]:$_GET["nid"];
if($search=="newsList"){
	$sql="SELECT nid,title,ftitle,titleurl,titlefont,smalltext,uptime,titlepic,onclick,ispl FROM ".NEWSD." WHERE ispass=1 ORDER BY uptime DESC";
	//-----------------------
	$db->query($sql);
	$re["list"]=$db->getRows();
	foreach($re["list"] as $key=>$val)
	{
		$re["list"][$key]['uptime'] = date('Y-m-d H:i:s',$val['uptime']);
		if(empty($val['titleurl']))
			$re["list"][$key]['url']=$config["weburl"].'/?m=news&s=newsd&id='.$val['nid'];
		else
			$re["list"][$key]['url']=$val['titleurl'];
	}
	$re["result"]="success";
	print_r(json_encode($re));
}else{
	$db->query("update ".NEWSD." set onclick=onclick+1 where nid='$nid'");
	$sql="SELECT a.*,b.con FROM ".NEWSD." a left join ".NEWSDATA." b on a.nid=b.nid WHERE a.nid='$nid'";
	//print_r($sql);die;
	$db->query($sql);
	$re=$db->fetchRow();
	$re["result"]="success";
	print_r(json_encode($re));
}
?>
