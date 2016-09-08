<?php
class member
{
	var $db;
	function member()
	{
		global $db;	
		$this -> db     = & $db;
	}
	
	/**
	 * 邮箱是否验证
	 * @param $member_id 会员ID 默认值：NULL
	 * return 结果字符串
	 */
	function email_reg($member_id=NULL)
	{
		global $buid;
		$buid=$uid?$uid:$buid;
		$sql="SELECT email_verify FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$email_verify=$this->db->fetchField('email_verify');
		return $email_verify==1?"true":"false";
	}

	/**
	 * 获取会员好友,粉丝,微博数量
	 * @param $member_id 会员ID 默认值：NULL
	 * return 结果数组
	 */
	function get_count($member_id)
	{
		if(empty($member_id)) return NULL;
		$sql="select * from ".MEMBERINFO." WHERE member_id='$member_id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	function get_member_detail($id)
	{
		global $buid,$config;
		if(empty($id))
			$id=$buid;
		$sql="select * from ".MEMBER." WHERE userid='$id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	
	function update_member($uid)
	{
		global $config,$buid;$logo=NULL;$ssql=NULL;
		if(empty($uid))
			$uid=$buid;
		
		$_POST['sex']=empty($_POST['sex'])?1:$_POST['sex'];
		$_POST['province']*=1;
		$_POST['city']*=1;
		$_POST['area']*=1;
		$_POST['street']*=1;
		$_POST['name'] = htmlspecialchars($_POST['name']);
		$_POST['qq'] = htmlspecialchars($_POST['qq']);
		$_POST['ww'] = htmlspecialchars($_POST['ww']);
		
		$sql="UPDATE ".MEMBER." SET name='$_POST[name]',qq='$_POST[qq]',provinceid='$_POST[province]',cityid='$_POST[city]',areaid='$_POST[area]',area='$_POST[t]',sex='$_POST[sex]',ww='$_POST[ww]',streetid='$_POST[street]',logo='$_POST[logo]'
		WHERE userid='$uid'";

		$re=$this->db->query($sql);
		if(preg_match("/member/",$_POST['logo']))
		{
			$de = $this -> get_member_detail($uid);
			$flag = @file_get_contents($config['pay_url']."/api/update_logo.php?uid=".$de['userid']."&pay_id=".$de['pay_id']."&logo=".$_POST['logo']);
		}

		return $re;
	}
	
	
	function resetpass($buid)
	{
		$sql="SELECT password FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(empty($_POST["oldpass"]) || empty($_POST["newpass"]) || empty($_POST["renewpass"]))
		{
			return '0';	die;
		}
		if($re['password']!=md5($_POST["oldpass"]))
		{
			return '1';	die;
		}
		if($_POST["newpass"]!=$_POST["renewpass"])
		{
			return '2';	die;
		}
		$sql="UPDATE ".MEMBER." SET password='".md5($_POST['newpass'])."' WHERE userid='$buid'";
		$re=$this->db->query($sql);
		return '3';
		
	}
	function resetemail($buid)
	{
		$sql="SELECT password FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(empty($_POST["pass"]) || empty($_POST["mail"]))
		{
			return '0';	die;
		}
		if($re['password']!=md5($_POST["pass"]))
		{
			return '1';	die;
		}
		$sql="UPDATE ".MEMBER." SET email='".$_POST['mail']."' WHERE userid='$buid'";
		$re=$this->db->query($sql);
		return '2';
	}
	
	function verifymail($buid)
	{
		if(strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
		{
			$pass=md5($_POST["password"]);
			$sql="SELECT userid FROM ".MEMBER." WHERE userid='$buid' and password='$pass'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			if(!$re)
			{
				return '2';die;
			}
		}
		else
		{
			return '1';die;
		}
	}
	function editmail($buid)
	{
		if(strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
		{
			$sql="select userid from ".MEMBER." where email='$_POST[email]' or user='$_POST[email]'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			if($re)
			{
				return '2';die;
			}
			else
			{
				$sql="UPDATE ".MEMBER." SET email='".$_POST['email']."' WHERE userid='$buid'";
				$re=$this->db->query($sql);
			}
		}
		else
		{
			return '1';die;
		}
	}
	
	function get_points($uid='')
	{
		global $buid;
		$buid=$uid?$uid:$buid;
		$sql="select points from ".MEMBERINFO." WHERE member_id='$buid'";
		$this->db->query($sql);
		$points=$this->db->fetchField('points');
		return $points;
	}
	
	function is_qd($uid='')
	{
		global $buid;
		$buid=$uid?$uid:$buid;
		$min= strtotime(date("Y-m-d"));   
		$max= $min+24*60*60;      
		$sql="select * from ".POINTSLOG." WHERE create_time>='$min' and create_time<'$max 'and type='4' and member_id='$buid'";
		$this->db->query($sql);
		return 	$this->db->num_rows();
	}
	
	function add_points($sum,$type,$order_id='',$uid='')
	{
		global $buid;
		$sum=$sum?round($sum):0;
		$num=$sum<0?$sum*(-1):$sum;
                
		$points=$this->get_points($uid);
		if($num>$points and $sum<0)
		{
			return '1';
		}
             
                $uuid=$uid>0?$uid:$buid;
                
		$statu=1;
		$sql="UPDATE ".MEMBERINFO." SET `points` = points + $sum  WHERE `member_id` = $uuid";
		$this -> db->query($sql);	
		if($type==5)
		{
			$desc="注册会员赠送";	
		}
		elseif($type==2)
		{
			$desc="兑换礼品".$order_id."消耗积分";	
		}
		elseif($type==3)
		{
			$desc="取消购物订单".$order_id;	
		}
		elseif($type==4)
		{
			$desc="每日签到";	
		}
		elseif($type==6)
		{
			$desc="系统操作";	
		}
                elseif($type==7)
		{
			$desc="兑换购物券消费积分";	
		}
		else
		{
			$desc="订单".$order_id."购物消费";	
		}
		
		$sql="select user from ".MEMBER." WHERE userid='$uuid'";
		$this->db->query($sql);
		$user=$this->db->fetchField('user');

		$sql = "INSERT INTO ".POINTSLOG." (member_id,member_name,points,type,create_time,`desc`) VALUES ('$uuid','$user','$sum','$type','".time()."','$desc')"; 
	
		$this -> db->query($sql);
                unset($uid);
	}
	
	
	
	
	//获取会员类型列表
	function GetMemberGradeList()
	{
		$sql="select * from ".MEMBERGRADE;
		$this->db->query($sql);
		$re = $this->db->getRows();
		foreach($re as $key=>$val)
		{
			$re[$key]['pic'] = (substr($val['pic'],0,4)=='http')?$val['pic']:"../".$val['pic'];
			$re[$key]['pic1'] = (substr($val['pic1'],0,4)=='http')?$val['pic1']:"../".$val['pic1'];	
		}
		return $re;
	}
	//获取会员类型
	function GetMemberGrade($id)
	{
		$sql="select * from ".MEMBERGRADE." where id='$id'";
		$this->db->query($sql);
		return $this->db->fetchRow();
	}
	//添加会员类型
	function AddMemberGrade()
	{
		$time=time();
		$sql="insert into ".MEMBERGRADE." (name,pic,pic1,`desc`,status,create_time) values ('$_POST[name]','$_POST[pic]','$_POST[pic1]','$_POST[desc]','$_POST[status]','$time')";
		$this->db->query($sql);
	}
	//修改会员类型
	function EditMemberGrade($id)
	{
		$sql="update ".MEMBERGRADE." set name='$_POST[name]',`desc`='$_POST[desc]',status='$_POST[status]',pic='$_POST[pic]',pic1='$_POST[pic1]' where id='$id'";
		$this->db->query($sql);
	}
    // 我的顾客
    function MyCusomer($id)
    {
            global $buid;
            $buid = $buid?$buid:$id;
            $sql = "select buyer_id,status,product_price,create_time from ".ORDER." where order_id in (select order_id from ".ORDER." where seller_id = $id ) and buyer_id > 0 group by buyer_id";
            
            $page = new Page;
            $page->listRows=12;
            if (!$page->__get('totalRows'))
            {
                    $this ->db -> query($sql);
                    $page->totalRows = $this -> db -> num_rows();
            }
            $sql .= "  limit ".$page->firstRow.",".$page->listRows;
            $this -> db -> query($sql);
            $de['page'] = $page -> prompt();
            $de['list'] = $this -> db -> getRows();
            foreach($de['list'] as $key => $val)
            {
                $sql = "select product_price from ".ORDER." where buyer_id = $val[buyer_id] and status >2";
                $this -> db -> query($sql);
                $re = $this -> db -> getRows();
                $price = 0;
                if(count($re) > 0)
                {
                    foreach($re as $key => $vala){$price += $vala['product_price']*1;}
                }
                $de['list'][$key]['price'] = $price;

                $sql = "select count(*) as nums  from ".ORDER." where buyer_id = $val[buyer_id]";
                $this -> db -> query($sql);

                $de['list'][$key]['nums'] = $this -> db -> fetchField("nums");
            }
            return $de;
    }
        
    /*
     * 商家为会员绑定会员卡
     */
    function bindMembercard()
    {
        $id = $_POST['id'] * 1;
        $card_temp = $_POST['card_s']*1;
        if(!$id || !$card_temp){die();}
        
        $sql = "select * from ".MECART." where `temp_id` = '$card_temp' and `blind_member_id` = 0 and `status` =1  order by `id` asc limit 1";
        $this -> db -> query($sql);
        $re = $this -> db -> fetchRow();
        if(!empty($re))
        {
        	$sql = "select * from ".MEMBER." where userid=$id ";
            $this -> db -> query($sql);
            $de = $this -> db -> fetchRow();
            $name = $de['name']?$de['name']:$de['user'];
            
            $sql = "update ".MECART." set  blind_member_id=$id,blind_member_name='$name',used_time='".time()."' where id = ".$re['id'];
            $this -> db -> query($sql);

            $sql = "update ".MECARTTEM." set used = used + 1 where id = ".$card_temp; //更新已经发放数量
            $this -> db -> query($sql);

            return true;
        }
        else
        {
        	return false;
        }
    }
}
?>
