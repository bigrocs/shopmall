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

$uname=$_REQUEST["uname"]?$_REQUEST["uname"]:$_GET["uname"];
$method=$_REQUEST["method"]?$_REQUEST["method"]:$_GET["method"];
$sid=$_REQUEST["sid"]?$_REQUEST["sid"]:$_GET["sid"];
$uid=$_REQUEST["uid"]?$_REQUEST["uid"]:$_GET["uid"];
$uname=$_REQUEST["uname"]?$_REQUEST["uname"]:$_GET["uname"];
$comment=$_REQUEST["comment"]?$_REQUEST["comment"]:$_GET["comment"];
//print_r($method);die;

if($_REQUEST["method"]=="post"){    //发表新鲜事
	$member_id=$_REQUEST["uid"]?$_REQUEST["uid"]:$_GET["uid"];
	$member_name=$_REQUEST["uname"]?$_REQUEST["uname"]:$_GET["uname"];
	$title=$_REQUEST['content'];
	$create_time=time();
	$sql="insert into ".SNS." (original_id,original_member_id,original_status,member_id,member_name,title,content,img,type,create_time,status,privacy,comment_count,copy_count) VALUES ('0','0','0','$member_id','$member_name','$title','','','1','$create_time','0 ','0','0','0')";
	$db->query($sql);
	$db->query("update ".MEMBERINFO." set blog=blog+1 where member_id='$member_id'");
	$post['result']="success";
	print_r(json_encode($post));
}elseif($_REQUEST["method"]=="comment"){    //评论新鲜事
	/*$sql="select * from ".SNSCOMMENT." where original_id='$sid' and member_id='$uid'";
	$db->query($sql);
	$com=$db->getRows();
	if($com){
		$db->query("update ".SNSCOMMENT." set content='$comment' where original_id='$sid' and member_id='$uid'");
	}else{*/
		$original_id=$_REQUEST["sid"];
		$member_id=$_REQUEST["uid"];
		$member_name=$_REQUEST["uname"];
		$content=$_REQUEST["comment"];
		$sql="insert into ".SNSCOMMENT." (member_id,member_name,original_id,content,addtime,state) VALUES ('$member_id','$member_name','$original_id','$content','".time()."','0')";
		$db->query($sql);
	/*}    可以多次评论某条新鲜事的*/
	$post['result']="success";
	$post['sid']=$sid;
	$post['comment']=$comment;
	print_r(json_encode($post));
}elseif($_REQUEST["method"]=="del"){    //删除新鲜事
	$db->query("update ".MEMBERINFO." set blog=blog-1 where member_id='$uid'");
	$db->query("delete from ".SNS." where id='$sid'");
	$db->query("delete from ".SNSCOMMENT." where original_id='$sid'");
	$db->query("update ".SNS." set original_status=1 where original_id='$sid'");
	$post['result']="success";
	$post['sid']=$_REQUEST["sid"];
	print_r(json_encode($post));
}elseif($_REQUEST["method"]=="like"){    //点赞
	$sql="select * from ".SNSCOMMENT." where original_id='$sid' and member_id='$uid'";
	$db->query($sql);
	$com=$db->getRows();
	if($com){
		$db->query("update ".SNSCOMMENT." set `like`=1 where original_id='$sid' and member_id='$uid'");
	}else{      //没有评论过，只点赞
		$sql="insert into ".SNSCOMMENT." (member_id,member_name,original_id,content,addtime,state,`like`) VALUES ('$uid','$uname','$sid','','".time()."','0','1')";
		$db->query($sql);
	}
	$post['result']="success";
	print_r(json_encode($post));
}elseif($_REQUEST["method"]=="cancelLike"){    //取消赞
	$db->query("update ".SNSCOMMENT." set `like`=0 where original_id='$sid' and member_id='$uid'");
	$post['result']="success";
	print_r(json_encode($post));
}else{    //获取新鲜事列表
	if(!empty($uname)){
		$sql="select uid,fuid from ".FRIEND." where uname='$uname' order by addtime desc";
		$db->query($sql);
		$re=$db->getRows();
		$myfriend=$uid;
		foreach($re as $val)
		{
			$myfriend.=','.$val['fuid'];
		}
		$sql="select a.* , b.member_id as ouid , b.member_name as ouser , b.title as otitle, b.create_time as ocreate_time, b.content as ocontent,b.img as oimg,b.type as otype from ".SNS." a left join ".SNS." b on a.original_id= b.id where a.member_id in ($myfriend) order by a.create_time desc";
		$db->query($sql);
		$re=$db->getRows();
		if(!$re)
		{
			$sql="select a.* , b.member_id as ouid , b.member_name as ouser , b.title as otitle, b.create_time as ocreate_time, b.content as ocontent,b.img as oimg,b.type as otype from ".SNS." a left join ".SNS." b on a.original_id= b.id order by rand() , a.create_time desc";
		}
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $key=>$val)
		{
			$sql="select logo,name from ".MEMBER." WHERE userid='$val[member_id]'";
			$db->query($sql);
			$a=$db->fetchRow();
			if($a['logo']=='image/default/avatar.png'){
				$a['logo']=$config['weburl']."/".$a['logo'];
			}
			$news[$key]['uid']=$val['member_id'];
			$news[$key]['sid']=$val['id'];
			$news[$key]['flogo'] = $a['logo']?$a['logo']:$config['weburl']."/image/default/avatar.png";
			$news[$key]['fname']=$a['name']?$a['name']:$val['member_name'];
			$news[$key]['content']=$val['title'];
			$news[$key]['time']=date("m-d H:i", $val['create_time']);
			$sql="select * from ".SNSCOMMENT."  WHERE original_id='$val[id]' and member_id='$uid'";
			$db->query($sql);
			$ml=$db->fetchRow();
			if($ml&&$ml['like']){
				$news[$key]['like']=1;
			}else{
				$news[$key]['like']=0;
			}
			$sql="select * from ".SNSCOMMENT."  WHERE original_id='$val[id]' and length(content) order by id asc";
			$db->query($sql);
			$ss=$db->getRows();
			$comlist=array();
			if($ss)
			{
				foreach($ss as $i=>$list)
				{
					$comlist[$i]['replyName']=$list['member_name'];
					$comlist[$i]['like']=$list['like'];
					$comlist[$i]['replyContent']=$list['content'];
				}
			}
			$news[$key]['comment']=$comlist;
		}
		$sns['result']="success";
		$sns['news']=$news;
		print_r(json_encode($sns));
	}
}
?>