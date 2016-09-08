<?php

class membercard
{
	var $db;
        
	function membercard()
	{
		global $db;	
		$this -> db     = & $db;
	}
        
         /*
         * 店铺添加会员卡模板并批量生成
         */
        function shop_add_card($id)
        {
            if(!$id) return false;
            
            $sql = "select user,company from ".SHOP." where userid=$id ";
            $this -> db -> query($sql);
            $re = $this -> db -> fetchRow();
            
            foreach($_POST as $key => $val){$$key = $val;}
           
            $sql = "insert into ".MECARTTEM." (`name`,`create_time`,`discounts`,`shop_id`,`shop_name`,`total`,`logo`,`status`) values ('$name',".time().",'$discounts',$id,'$re[company]','$total','$logo',1)";
            if($this -> db -> query($sql))
            {
                $tid =$this -> db -> lastid();
            }
            
            for($i = 1;$i <= $total;$i++)
            {
                $serial = time().rand(10000,99999).rand(10000,99999);
                $sql = "insert into ".MECART." (`name`,`create_time`,`discounts`,`logo`,`temp_id`,`shop_id`,`shop_name`,`serial`) values ('$name',".time().",'$discounts','$logo',$tid,$id,'$re[company]','$serial')";
                $this -> db -> query($sql);
            }
        }        
        
        /*
         *  店铺卡模板列表
         */
        
        function shop_list_card($id)
        {
            global $buid;
            if(!$id || $id != $buid) return false;
            $sql = "select * from ".MECARTTEM." where `shop_id` = $id and `status` = 1";
            $this -> db -> query($sql);
            return $this -> db -> getRows();
        }
        
		 /*增加的会员卡分页*/
        function shop_list_card1($id)
        {
            global $buid;
            if(!$id || $id != $buid) return false;
            $sql = "select * from ".MECARTTEM." where `shop_id` = $id and `status` = 1";
			
			$page = new Page;
            $page->listRows=4;
            if (!$page->__get('totalRows'))
            {
                    $this ->db -> query($sql);
                    $page->totalRows = $this -> db -> num_rows();
            }
            $sql .= "  limit ".$page->firstRow.",".$page->listRows;
            $this -> db -> query($sql);
            $de['page'] = $page -> prompt();
            $de['list'] = $this -> db -> getRows();
			
          
            return $de;
        } 
        /*
         * 店铺删除卡模板，所有生成卡均失效
         */
        function shop_delete_card($id)
        {
            global $buid;
            if(!$id) return false;
            
            $sql = "update ".MECARTTEM." set `status` = 0 where `id` = $id and `shop_id` = $buid";
            $this -> db -> query($sql);
            
            $sql = "update ".MECART." set `status` = 0 where `temp_id` = $id and `shop_id` = $buid and `status` = 1";
            $this -> db -> query($sql);
            
            return true;
        }
        
        /*
         * 获取卡信息
         */
        function shop_detial_card($id)
        {
            global $buid;
            if(!$id) return false;
            
            $sql = "select * from ".MECARTTEM." where `id` = $id and `shop_id` = $buid";
            $this -> db -> query($sql);
            if($this -> db -> num_rows())
            {
                return $this -> db -> fetchRow();
            }
            else
            {
                return false;
            }
        }
        
        /*
         * 会员编辑
         */
        function shop_edit_card()
        {
           global $buid;
           foreach($_POST as $key => $val){$$key = $val;} 
           if(!$id) return false;
           
           $sql = "update ".MECARTTEM." set `status` = '$status',`name` = '$name',`discounts` = '$discounts',`logo` = '$logo' where id = ".$id."  and `shop_id` = $buid";
           $this -> db -> query($sql);
           
           $sql = "update ".MECART." set `status` = '$status',`name` = '$name',`discounts` = '$discounts',`logo` = '$logo' where `temp_id` = ".$id."  and `shop_id` = $buid";
           $this -> db -> query($sql);
        }
        
        /*
         * 会员卡统计
         */
        function get_shopcard_total($id)
        {
            global $buid;
            $sql = "select * from ".MECART." where `temp_id` = $id and `shop_id` = $buid order by used_time desc ,id desc ";
            
            $page = new Page;
            $page->listRows=9;
            if (!$page->__get('totalRows'))
            {
                    $this ->db -> query($sql);
                    $page->totalRows = $this -> db -> num_rows();
            }
            $sql .= "  limit ".$page->firstRow.",".$page->listRows;
            $this -> db -> query($sql);
            $de['page'] = $page -> prompt();
            $de['list'] = $this -> db -> getRows();
            
            return $de;
        }
        
        /*
         * 会员卡绑定
         */
        function bind_card()
        {
            global $buid;
            $serial = $_POST['serial'];
            if(strlen($serial) != 18){return -1;}
            
            $sql = "select * from ".MECART." where `serial` = '$serial' and `status` = 1 and `blind_member_id` = 0";
            $this -> db -> query($sql);
         
            if($this -> db -> num_rows())
            {
                $de = $this -> db -> fetchRow();
                
                $sql = "select `user` from ".MEMBER." where `userid` = $buid ";
                $this -> db -> query($sql);
                $re = $this -> db -> fetchRow();
                
                $sql = "update ".MECART." set `blind_member_id` = '$buid',`blind_member_name` ='$re[user]',`used_time` = '".time()."' where `id` = $de[id]";
                $this -> db -> query($sql);
                
                $sql = "update ".MECARTTEM." set `used` = `used` + 1 where id =".$de['temp_id'];
                $this -> db -> query($sql);
                return 1;
            }
            else
            {
                return -1;
            }
        }
        
        /*
         * 获取某个会员绑定的所有会员卡
         */
        function member_card_list($id)
        {
            global $buid;
            if($id != $buid){return false;}
            
            $sql = "select * from ".MECART." where `status` > -1 and `blind_member_id` = $buid order by `used_time` desc ";
            
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
            
            return $de;
        }
        
        /*
         * 会员删除会员卡
         */
        function relase_bind_card($id)
        {
            global $buid;
            $sql = "select * from ".MECART." where `id` = $id ";
            $this -> db -> query($sql);
            $de = $this -> db -> fetchRow();
            if($de['blind_member_id'] != $buid){return false;}
            
            $sql = "update ".MECART." set `status` = '-1' where `id` = $id";
            $this -> db -> query($sql);
            
            return 1;
        }
        
        /*
         * 获取某个会员在某个商铺的会员信息
         */
        function getMemberCardInfo($id,$shopid)
        {
			if($id)
			{
				 $sql = "select * from ".MECART." where blind_member_id = $id and shop_id = $shopid and status = 1 order by discounts desc limit 1";
				$this -> db -> query($sql);
				return $this -> db -> fetchRow();
			}
           
        }
}
