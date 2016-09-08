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

$userid=$_REQUEST["uid"];
$memid=$_REQUEST["memid"];
if(!empty($memid))
{
    $sql="select a.userid,a.user,a.logo,a.area,b.company,b.main_pro from ".MEMBER." a left join ".SHOP." b on a.userid= b.userid where a.userid=$memid";
    $db->query($sql);
    $re=$db->fetchRow();
    if($re['logo']=='image/default/avatar.png'){
        $re['logo']=$config['weburl']."/".$re['logo'];
    }
    $re['logo'] = $re['logo']?$re['logo']:$config['weburl']."/image/default/avatar.png";
    $re['company']=$re['company']?$re['company']:"未开店";
    $re['main_pro']=$re['main_pro']?$re['main_pro']:"无";

    $sql="select state from ".FRIEND." where uid='$userid' and fuid='$memid'";
    $db->query($sql);
    $friend=$db->fetchRow();
    $re['friendship']=$friend['state'];
    if($re)
    {
        $sql="select * from ".PRODUCT." where member_id='$re[userid]' order by sales DESC limit 0,4";
        $db->query($sql);
        $pros=$db->getRows();
        $products=array();
        foreach($pros as $key=>$p){
            $products[$key]['id']=$p['id'];
            $products[$key]['pic']=$p['pic'];
            $products[$key]['name']=$p['name'];
            $products[$key]['price']=$p['price'];
        }
        $re["productlist"]=$products;
        $re["result"]="success";
    }else{
        $re["result"]="noshop";
    }
    print_r( json_encode($re));
}

if($_REQUEST["method"]=="prepare_settings"){
	$sql="select user,name,mobile,logo from ".MEMBER." where userid=".$_REQUEST['id'];
    $db->query($sql);
    $re=$db->fetchRow();
    $re["result"]="success";
    print_r( json_encode($re));
}
if($_REQUEST["method"]=="save_settings"){
	/*$str="";
	if($_REQUEST["user"]){
		$str.="user = ".$_REQUEST["user"];
	}
	if($_REQUEST["name"]){
		if($str){
			$str.=" and name = ".$_REQUEST["name"];
		}else{
			$str.="name = ".$_REQUEST["name"];
		}
	}*/
	$sql="update ".MEMBER." set user = '".$_REQUEST['user']."' , name = '".$_REQUEST['name']."' where userid=".$_REQUEST['id'];
	//print_r($sql);die;
    $db->query($sql);
    $re=$db->fetchRow();
    $re["result"]="success";
    print_r( json_encode($re));
}

if($_REQUEST["method"]=="order_count"){

    $sqls[] = "select * from ".ORDER." where buyer_id = '$userid' and status = '1' ";
    $sqls[] = "select * from ".ORDER." where buyer_id = '$userid' and status = '2' ";
    $sqls[] = "select * from ".ORDER." where buyer_id = '$userid' and status = '3' ";
    $sqls[] = "select * from ".ORDER." where buyer_id = '$userid' and status = '4' and buyer_comment='0' and seller_comment = '0' ";
    foreach($sqls as $val)
    {
        $db->query($val);
        $count[] = $db->num_rows();
    }
    $re["count"]=$count;

    $re["result"]="success";
    print_r( json_encode($re));
}

?>