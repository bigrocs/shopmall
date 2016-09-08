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

$userid=$_REQUEST["uid"]?$_REQUEST["uid"]:$_GET["uid"];
$uname=$_REQUEST["uname"]?$_REQUEST["uname"]:$_GET["uname"];
$logo=$_REQUEST["logo"]?$_REQUEST["logo"]:$_GET["logo"];
$addid=$_REQUEST["addid"]?$_REQUEST["addid"]:$_GET["addid"];
$search=$_REQUEST["search"]?$_REQUEST["search"]:$_GET["search"];
$key=$_REQUEST["key"]?$_REQUEST["key"]:$_GET["key"];

if($search=="key"){            //搜索好友
	$sql="select userid,logo,user,area from ".MEMBER." where user like '%$key%' or email like '%$key%' or mobile like '%$key%'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key=>$f){
		$re[$key]['logo'] = $f['logo']?$f['logo']:"http://192.168.0.88/tech05/mall_sj/image/default/avatar.png";
		if($f['logo']=='image/default/avatar.png'){
			$re[$key]['logo']="http://192.168.0.88/tech05/mall_sj/".$f['logo'];
		}
	}
	$list=array();
	$list["list"]=$re;
	$list["result"]="success";
	print_r(json_encode($list));
}
if($search=="nearmem"){            //附近的人
	$sql="select userid,logo,user,area from ".MEMBER." where user like '%$key%' or email like '%$key%' or mobile like '%$key%'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key=>$f){
		$re[$key]['logo'] = $f['logo']?$f['logo']:"http://192.168.0.88/tech05/mall_sj/image/default/avatar.png";
		if($f['logo']=='image/default/avatar.png'){
			$re[$key]['logo']="http://192.168.0.88/tech05/mall_sj/".$f['logo'];
		}
	}
	$list=array();
	$list["list"]=$re;
	$list["result"]="success";
	print_r(json_encode($list));
}
if($search=="nearshop"){            //附近的店铺
	$sql="select userid,logo,user,area from ".MEMBER." where (user like '%$key%' or email like '%$key%' or mobile like '%$key%') and userid in (select userid from ".SHOP." where shop_statu=1)";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key=>$f){
		$re[$key]['logo'] = $f['logo']?$f['logo']:"http://192.168.0.88/tech05/mall_sj/image/default/avatar.png";
		if($f['logo']=='image/default/avatar.png'){
			$re[$key]['logo']="http://192.168.0.88/tech05/mall_sj/".$f['logo'];
		}
	}
	$list=array();
	$list["list"]=$re;
	$list["result"]="success";
	print_r(json_encode($list));
}
if($search=="friendRequest"){            //好友申请列表
	$sql=$sql="select * from ".FRIEND." where uid='$userid' and state=1";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $k=>$f){
		$re[$k]['describe']="我是".$f['funame'];
        /*
		$re[$k]['uimg'] = $re[$k]['uimg']?$re[$k]['uimg']:"http://192.168.0.88/tech05/mall_sj/image/default/avatar.png";
        */
		if($f['uimg']=='image/default/avatar.png'){
			$re[$k]['uimg']="http://192.168.0.88/tech05/mall_sj/".$f['uimg'];
		}
	}
	$list=array();
	$list["list"]=$re;
	$list["result"]="success";
	print_r(json_encode($list));
}
if($search=="addfriend"){            //申请添加好友	
	$addArray = str_replace("\\","",$_REQUEST["array"]);
	$addArray = json_decode($addArray);
	//error_log(var_export($addArray[0]->num,true),3,__FILE__.'.log');
	
	for ($i = 0; $i < count($addArray); $i++) {
		//$sql="select * from ".MEMBER." where mobile='".$addArray[$i]['num']."' or email='".$addArray[$i]['email']."' or name='".$addArray[$i]['name']."' or user='".$addArray[$i]['num']."' or user='".$addArray[$i]['email']."' or user='".$addArray[$i]['name']."'";
		
		$num = preg_replace('# #', '',$addArray[$i]->num);             //去空格
		$sql="select * from ".MEMBER." where mobile='".$num."' or name='".$addArray[$i]->name."' or user='".$num."' or user='".$addArray[$i]->email."' or user='".$addArray[$i]->name."'";
		$db->query($sql);
		$mem=$db->fetchRow();
		if($mem){
			$sql="select * from ".FRIEND." where uid='$mem[userid]' and state=1 and fuid=$userid";
			$db->query($sql);
			$re=$db->fetchRow();
			if($re){    //该好友之前向我提出过申请
				$sql="select * from ".FRIEND." where fuid='$mem[userid]' and uid=$userid";
				$db->query($sql);
				$re=$db->fetchRow();
				if(empty($re)){
					$sql="insert into ".FRIEND." (`uid`, `uname`, `uimg`, `fuid`, `funame`, `fuimg`, `addtime`, `state`) VALUES ('$userid','$uname', '$logo', '$mem[userid]', '$mem[user]', '$mem[logo]', '".time()."', '2')";
					$db->query($sql);
					$sql="update ".FRIEND." set state=2 where fuid='$userid' and uid='$mem[userid]'";
					$db->query($sql);
				}else{   //异常情况
					$sql="update ".FRIEND." set state=2 where uid='$userid' and fuid='$mem[userid]'";
					$db->query($sql);
				}
			}else{    //该好友未向我提出过申请
				$sql="insert into ".FRIEND." (`uid`, `uname`, `uimg`, `fuid`, `funame`, `fuimg`, `addtime`, `state`) VALUES ('$userid','$uname', '$me[logo]', '$mem[userid]', '$mem[user]', '$mem[logo]', '".time()."', '1')";
				$db->query($sql);
			}
		}
	}
	/*foreach($arrArray as $key=>$adda){
		$sql="select * from ".MEMBER." where mobile='".$adda[num]."' or email='".$adda[email]."' or name='".$adda[name]."' or user='".$adda[num]."' or user='".$adda[email]."' or user='".$adda[name]."'";
		$db->query($sql);
		$mem=$db->fetchRow();
		if($mem){
			$sql="select * from ".FRIEND." where uid='$mem[userid]' and state=1 and fuid=$userid";
			$db->query($sql);
			$re=$db->fetchRow();
			if($re){    //该好友之前向我提出过申请
				$sql="select * from ".FRIEND." where fuid='$mem[userid]' and uid=$userid";
				$db->query($sql);
				$re=$db->fetchRow();
				if(empty($re)){
					$sql="insert into ".FRIEND." (`uid`, `uname`, `uimg`, `fuid`, `funame`, `fuimg`, `addtime`, `state`) VALUES ('$userid','$uname', '$logo', '$mem[userid]', '$mem[user]', '$mem[logo]', '".time()."', '2')";
					$db->query($sql);
					$sql="update ".FRIEND." set state=2 where fuid='$userid' and uid='$mem[userid]'";
					$db->query($sql);
				}else{   //异常情况
					$sql="update ".FRIEND." set state=2 where uid='$userid' and fuid='$mem[userid]'";
					$db->query($sql);
				}
			}else{    //该好友未向我提出过申请
				$sql="insert into ".FRIEND." (`uid`, `uname`, `uimg`, `fuid`, `funame`, `fuimg`, `addtime`, `state`) VALUES ('$userid','$uname', '$me[logo]', '$mem[userid]', '$mem[user]', '$mem[logo]', '".time()."', '1')";
				$db->query($sql);
			}
		}
	}*/
	$ll=array();
	$ll["result"]="success";
	print_r(json_encode($ll));
}
if($search=="agreefriend"){            //同意好友申请
	$sql="update ".FRIEND." set state=2 where fuid='$userid' and uid='$addid'";
	$db->query($sql);
	$sql="select * from ".FRIEND." where uid='$userid' and fuid='$addid'";
	$db->query($sql);
	$fr=$db->fetchRow();
	if($fr){
		$sql="update ".FRIEND." set state=2 where uid='$userid' and fuid='$addid'";
		$db->query($sql);
	}else{
		$sql="select userid,logo,user,area from ".MEMBER." where userid = $userid";
		$db->query($sql);
		$me=$db->fetchRow();
		$sql="select userid,logo,user,area from ".MEMBER." where userid = $addid";
		$db->query($sql);
		$fr=$db->fetchRow();
		$time =time();
		$sql="insert into ".FRIEND." (`uid`, `uname`, `uimg`, `fuid`, `funame`, `fuimg`, `addtime`, `state`) VALUES ('$userid','$me[user]', '$me[logo]', '$addid', '$fr[user]', '$fr[logo]', '$time', '2')";
		$db->query($sql);
	}
	$list["result"]="success";
	print_r(json_encode($list));
}
if(!empty($_REQUEST["uname"]))            //好友列表
{
	$sql="select * from ".MEMBER." where user='$_REQUEST[uname]' or email='$_REQUEST[uname]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re["userid"])
	{
		$sql="select * from ".FRIEND." where uid='$re[userid]' and state=2";
		$db->query($sql);
		$myfs=$db->getRows();
		$friends=array();
		foreach($myfs as $key=>$f){
			$friends[$key]['fid']=$f['fuid'];
			$friends[$key]['name']=$f['funame'];
			$friends[$key]['img']=$f['fuimg'];
		}
		//$result["friends"]=$friends;
	}

    $friends = array(
        array("cmd_id"=>-40, 'b'=>$friends)
    );
	print_r( json_encode($friends));
}
?>