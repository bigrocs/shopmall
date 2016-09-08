<?php
class shop{
	var $db;
	var $webUrl;
	var $tpl;
	
	function shop()
	{
		global $db,$tpl;
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		$this -> view_user_times();
	}
	############################
	
	function get_custom_cat_list($type="")
	{
		global $config;
		if($type=='1')
			$str=" and pid='0'";	

		$sql="select * from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='$type' $str order by nums asc";
		$this->db->query($sql);
		$re=$this->db->getRows();
		foreach($re as $key=>$val)
		{
			$sql="select * from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='$type' and pid=$val[id]  order by nums asc";
			$this->db->query($sql);
			$re[$key]["con"]=$this->db->getRows();	
		}
		return $re;
	}
	function user_detail($uid)
	{	
		global $config,$action;
		
		if(!is_numeric($uid))
			$sqls=" WHERE user='$uid'";
		else
			$sqls=" WHERE userid='$uid'";
		
		$sql="select userid,statu,name,qq,email,mobile,area,logo as plogo,regtime,sellerpoints,buyerpoints from ".MEMBER.$sqls;
		$this ->db ->query($sql);
		$com=$this ->db->fetchRow();
		if($com)
		{	
			$_GET["uid"]=$uid;
			$sql="select * from ".POINTS." order by id";
			$this ->db ->query($sql);
			$re=$this ->db->getRows();
			foreach($re as $k=>$v)
			{
				$ar=explode('|',$v['points']);
				if($com['sellerpoints']<=$ar[1])
				{
					$com["sellerpointsimg"] = $v['img'];
					$com["sellerpointsid"] = ceil($v['id']/5);
				}
				if($com['buyerpoints']<=$ar[1] and $com['buyerpoints']>=$ar[0])
				{
					$com["buyerpointsimg"]=$v['img'];
				}
			}
			
			$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRODUCT." b on a.pid=b.id where b.member_id='$_GET[uid]' and a.userid <> '$_GET[uid]'";
			$this ->db->query($sql);
			$count=$this ->db->fetchField('count');
			
			if($count!=0)
			{
				$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRODUCT." b on a.pid=b.id where b.member_id='$_GET[uid]' and a.userid <> '$_GET[uid]' and a.goodbad=1";
				$this ->db->query($sql);
				$com["favorablerate"]=($this ->db->fetchField('count')/$count)*100;
			}else{
				$com["favorablerate"]="100";
			}
			
			$sql="select count(*) as count  from ".PRODUCT." where member_id=$_GET[uid] and is_shelves=1";
			$this ->db ->query($sql);
			$com['count']=$this ->db->fetchField('count');
			
			//----------------------------------------------------
			$sql="select * from ".SHOP." where userid='$com[userid]'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			if(is_array($re))
				$com=array_merge($com,$re);

			$sql="select name from ".SHOPGRADE." where id= '$com[grade]' ";
			$this ->db ->query($sql);
			$com['gradename']=$this ->db->fetchField('name');	
				
			$sql="select * from ".SSET." where shop_id='$com[userid]'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			$re['slide']=explode(',',$re['shop_slide']);
			$re['slideurl']=explode(',',$re['shop_slideurl']);
			if(is_array($re))
				$com=array_merge($com,$re);
				
			$com["time_long"]=date("Y")-substr($com["regtime"],0,4)+1;
			//----------------------------------------------------
			$this ->tpl->assign("com",$com);
			return $com;
		}
		else
			msg($config['weburl'].'/404.php');
	}
	function get_user_detail($uid,$type=1)
	{
		$sql="select intro,logo as img from ".SSET." where userid='$uid' and type=$type";
		$this->db->query($sql);
		return $this->db->fetchRow();
	}
	function get_user_link()
	{
		if(isset($_GET['uid']))
		{
			$sql="select * from ".SHOPLINK." WHERE shop_id='$_GET[uid]' and status=1";
			$this->db->query($sql);
			return $this->db->getRows();
		}
	}
	function get_shop_nav()
	{
		if(isset($_GET['uid']))
		{
			$sql="select id,title,url,new_open from ".SHOPN." where shop_id='$_GET[uid]' and if_show=1 order by sort";
			$this -> db->query($sql);
			$re=$this -> db->getRows();	
			return $re;
		}
	}
	function view_user_times()
	{
		$sql="update ".SHOP." SET view_times=view_times+1 WHERE userid='$_GET[uid]'";
		$this ->db ->query($sql);
	}
	//  lolololo
	function get_total_rows_by_userid($table="")
	{
		$sql="select count(*) as totalrows from ".$table." where userid='$_GET[uid]'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re['totalrows'];
	}

	function score()
	{
		$sql="select avg(item1) as a,avg(item2) as b,avg(item3) as c,avg(item4) as d from ".UCOMMENT." where byid='$_GET[uid]'";
		$this->db->query($sql);
		$u=$this->db->fetchRow();
		$u['aw']=$u['a']/5*100;
		$u['aw'] = $u['aw'] > 0 ? $u['aw'] : 5;
		$u['bw']=$u['b']/5*100;
		$u['bw'] = $u['bw'] > 0 ? $u['bw'] : 5;
		$u['cw']=$u['c']/5*100;
		$u['cw'] = $u['cw'] > 0 ? $u['cw'] : 5;
		$u['dw']=$u['d']/5*100;
		$u['dw'] = $u['dw'] > 0 ? $u['dw'] : 5;
		return $u;
	}
	
	function get_cs()
	{
		$sql="select * from ".CS." where uid= $_GET[uid] order by type , id";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		foreach($re as $key=>$val)
		{
			$str.=$val['number']."|".$val['name']."|".$val['tool'].",";
		}
		return substr($str,0,-1);
	}
}
?>
