<?php 
class activity
{
	var $db;
	function activity()
	{
		global $db;	
		$this -> db     = & $db;
	}
	//添加活动
	function add_activity()
	{
		global $buid;
		
		if(!empty($_POST["free_ship_money"]))
			$meet_money=$_POST["free_ship_money"];  //满足包邮活动金额
		if(!empty($_POST["full_cut_money"]))        
			$meet_money=$_POST["full_cut_money"];   //满足按金额满减金额
		if(!empty($_POST["full_gift_money"]))
			$meet_money=$_POST["full_gift_money"];  //满足满赠金额
		
		if(!empty($_POST["full_cut_num"]))     		//满足按数量满减数量
			$meet_num=$_POST["full_cut_num"];
		if(!empty($_POST["full_gift_num"]))			//满足按数量满赠数量
			$meet_num=$_POST["full_gift_num"];
		
		if(!empty($_POST["cut_buy_money"]))			//减少的金额
			$cut_money=$_POST["cut_buy_money"];
		if(!empty($_POST["cut_buy_num"]))			//根据数量减少的金额
			$cut_money=$_POST["cut_buy_num"];
	
		$activity_rule=$_POST['activity_rule']?$_POST['activity_rule']:0;
		$gift_pid=$_POST["chk"]?$_POST["chk"]:0;
		
		$sql="INSERT INTO ".ACTIVITY." (`title` ,`desc` ,`activity_rule`, `meet_money`,`meet_num`,`cut_money`,`gift_pid`,`start_time` ,`end_time` ,`create_time` ,`status`,`displayorder`,`create_by` )VALUES ( '$_POST[title]','$_POST[desc]','$activity_rule','$meet_money','$meet_num','$cut_money','$gift_pid','".strtotime($_POST['stime'])."','".strtotime($_POST['etime'])."','".time()."',1,'255','$buid')";
		$this->db->query($sql);
	}
	//修改活动
	function edit_activity()
	{
		$sql="UPDATE ".ACTIVITY."  SET `title` = '$_POST[title]',`desc` = '$_POST[desc]',
		`ads_code` = '$_POST[ads_code]',`start_time` =  '".strtotime($_POST['stime'])."',`end_time` = '".strtotime($_POST['etime'])."',`templates` = '$_POST[templates]',`status` = '$_POST[status]' 
		WHERE `id` ='$_POST[id]'  ";	
		$this->db->query($sql);
	}
	//获取所有活动列表
	function get_activity_list()
	{
		global $buid;
		$sql="select id,activity_rule,title,start_time,end_time from ".ACTIVITY." where status>0 ";
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows =$this->db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",20";
		//=====================
		$this->db->query($sql);
		$re['list']=$this->db->getRows();
		$re['page']=$page->prompt();	
		return $re;
	}
	//参加活动的商品
	function get_activity_prolist($activity_id)
	{
		global $buid;
		$sql="select a.product_id,a.status,b.name as pname,b.price,b.stock as amount,b.pic from ".ACTIVITYPRODUCT." a left join ".PRODUCT." b on a.product_id=b.id where a.activity_id='$activity_id' and b.member_id='$buid' ";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	//所有可以作为赠品的商品
	function get_full_prolist()
	{
		global $buid;
		$sql="select id,name as pname,price from ".PRODUCT." where member_id='$buid' and status>=1 and is_shelves=1 order by convert(name using gbk) asc,id DESC";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	/*status 申请状态
	**1-待审核
	**2-审核通过
	**3-审核未通过
	**4-再次申请审核
	*/
	function save_activity_product()
	{
		global $buid;
		$id=$_POST['id']*1;
		$rule_id=$_POST['rule_id']*1;
		$gift_pid=$_POST['gift_pid']?$_POST['gift_pid']*1:0;
		
		$sql="select user from ".MEMBER." where userid='$buid'";
		$this->db->query($sql);
		$user=$this->db->fetchField('user');
		
		if(!empty($_POST['chk']))
		{
			foreach($_POST['chk'] as $val)
			{
				$sql="select status from ".ACTIVITYPRODUCT." where product_id='$val' and activity_id=$id";
				$this->db->query($sql);
				$status=$this->db->fetchField('status');
				if($status==3)
				{
					$sql="UPDATE ".ACTIVITYPRODUCT."  SET `status` = '4' WHERE `product_id` ='$val' and `activity_id` ='$id'  ";	
					$this->db->query($sql);	
					
					$sql="update ".PRODUCT." set promotion_id='$id' where id=$val";	
					$re=$this->db->query($sql);
				}
				else
				{
					$sql="select name as pname from ".PRODUCT." where id='$val'";
					$this->db->query($sql);
					$pname=$this->db->fetchField('pname');
					
					$sql="INSERT INTO ".ACTIVITYPRODUCT."(`activity_id` ,`product_id` ,`product_name` ,`gift_pid`,`member_id` ,`member_name` ,`status` ,`create_time`) VALUES ('$id', '$val', '$pname','$gift_pid','$buid', '$user', '1','".time()."')";
					$re=$this->db->query($sql);
					
					$sql="update ".PRODUCT." set promotion_id='$id' where id=$val";	
					$re=$this->db->query($sql);
				}
				
			}
		}
		return $re;
	}
}
?>