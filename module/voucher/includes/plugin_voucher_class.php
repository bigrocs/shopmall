<?php

class voucher
{
	var $db;
        var $page;
	function voucher()
	{
		global $db;	
                global $config;
                global $buid;
		$this -> db     = & $db;
	}
	
	/*
         * 店铺代金券列表
         */
        function voucher_shop_list()
        {
            if(isset($_GET['name']) && !empty($_GET['name']))
            {
                $where .= " and shop_name like '%".$_GET['name']."%' ";
            }
            if(isset($_GET['stime']) && !empty($_GET['stime']))
            {
                $where .= " and start_time > ".strtotime($_GET['stime']);
            }
            if(isset($_GET['etime']) && !empty($_GET['etime']))
            {
                $where .= " and end_time > ".strtotime($_GET['etime']);
            }
            if(isset($_GET['status']) && !empty($_GET['status']))
            {
                $where .= " and status =  ".$_GET['status']*1;
            }
            if(isset($_GET['isindex']) && !empty($_GET['isindex']))
            {
                $where .= " and isindex =  ".$_GET['isindex']*1;
            }
            
            $sql = "select * from ".VOUTEMO." where 1 $where  order by start_time desc ";
            
            $page = new Page;
            $page->listRows=10;
            if (!$page->__get('totalRows'))
            {
                    $this ->db -> query($sql);
                    $page->totalRows = $this -> db -> num_rows();
            }
            $sql .= "  limit ".$page->firstRow.",".$page->listRows;
            $this -> db -> query($sql);
            $de['page'] = $page -> prompt();
            $de['list'] = $this -> db -> getRows();
            
            // 过期则自动改变状态
            foreach($de['list'] as $key => $val)
            {
                if($val['end_time'] < time() && $val['status'] == 1)
                {
                    $sql = "update ".VOUTEMO." set status = 2 where id= ".$val['id'];
                    $this ->db -> query($sql);
                }
            }
            
            return $de;
        }
        
        /*
         * 推荐设置
         */
        function addindex($id)
        {
            $sql = "update ".VOUTEMO." set `isindex` = 1  where id = ".$id;
            $this ->db -> query($sql);
        }

        /*
         * 取消推荐
         */
        function delindex($id)
        {
            $sql = "update ".VOUTEMO." set `isindex` = 0  where id = ".$id;
            $this ->db -> query($sql);
        }
        
        /*
         * 设置失效
         */
        function delstatus($id)
        {
            $sql = "update ".VOUTEMO." set `status` = 2  where id = ".$id;
            $this ->db -> query($sql);
        }
        
        /*
         * 应用申请列表
         */
        function apply_list()
        {
            if(isset($_GET['name']) && !empty($_GET['name']))
            {
                $where .= " and shop_name like '%".$_GET['name']."%' ";
            }
            
            $sql = "select * from ".APPLY." where 1 $where  order by create_time desc ";
            
            $page = new Page;
            $page->listRows=10;
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
         * 面额列表
         */
        function amount_list()
        {
            $sql = "select * from ".VOUAMOUNT." order by id desc ";
            
            $page = new Page;
            $page->listRows=10;
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
         * 所有面额信息
         */
        function amount_list_all()
        {
            $sql = "select * from ".VOUAMOUNT." order by id asc ";
            $this -> db -> query($sql);
            return  $this -> db -> getRows();
        }
        
        /*
         * 添加面额
         */
        function amount_add()
        {
            $sql = "insert into ".VOUAMOUNT." (`price`,`name`,`points`) values ('".$_POST['price']."','".$_POST['name']."','".$_POST['points']."')";
            return $this -> db -> query($sql);
        }
        
        /*
         * 编辑面额
         */
        function amount_edit()
        {
            $sql = "update ".VOUAMOUNT." set `price`='".$_POST['price']."',`name`='".$_POST['name']."',`points` ='".$_POST['points']."' where id = ".$_POST['id']*1;
            return $this -> db -> query($sql);
        }
        
        function amount_getone()
        {
            $id = $_GET['id']*1;
            $sql = "select * from ".VOUAMOUNT." where id = $id ";
            $this -> db -> query($sql);
            return $this -> db -> fetchRow();
        }
        
        /*
         * 删除面额
         */
        function amount_delete($id){
            $id = $id*1?$id*1:$_GET['delid']*1;
            if($id)
            {
                $sql = "delete from ".VOUAMOUNT." where id = $id ";
                $this -> db -> query($sql);
            }
        }
        
        /*
         *  店铺代金券列表
         */
        function shop_voucher_list($id)
        {
            if(!$id) return false;
            
            $sql = "select * from ".VOUTEMO." where shop_id = $id $where  order by start_time desc ";
            
            $page = new Page;
            $page->listRows=10;
            if (!$page->__get('totalRows'))
            {
                    $this ->db -> query($sql);
                    $page->totalRows = $this -> db -> num_rows();
            }
            $sql .= "  limit ".$page->firstRow.",".$page->listRows;
            $this -> db -> query($sql);
            $de['page'] = $page -> prompt();
            $de['list'] = $this -> db -> getRows();
            
            // 如果过期则自动改变状态
            foreach($de['list'] as $key => $val)
            {
                if($val['end_time'] < time() && $val['status'] == 1)
                {
                    $sql = "update ".VOUTEMO." set status = 2 where id= ".$val['id'];
                    $this ->db -> query($sql);
                }
            }
            
            return $de;
        }
        
        /*
         * 店铺添加代金券模板
         */
        function shop_add_voucher($id,$limit)
        {
            if(!$id) return false;
            
            $sql = "select * from ".APPLY." where appid=1 and shop_id = $id ";
            $this -> db -> query($sql);
            if($this -> db -> num_rows())
            {
                $time = time();
                $re = $this -> db -> fetchrow();
                if($time > $re['end_time']) //购买应用已过期
                {
                    return -2;
                }
                else
                {
                    $sql = "select * from ".VOUTEMO." where shop_id = $id and FROM_UNIXTIME(`start_time`,'%Y-%m') = date_format(now(),'%Y-%m')";
                    $this -> db -> query($sql);
                    $knum = $this -> db -> num_rows();
                    if($knum >= $limit)
                    {
                        return -3;
                    }
                    
                    foreach($_POST as $key => $val){$$key = $val;}
                    /*
                    $sql = "select * from ".VOUAMOUNT." where  id = $price ";
                    $this -> db -> query($sql);
                    $mu = $this -> db -> fetchRow();
                    */
                    $end_time = strtotime($etime);
                    $end_time = $end_time > $re['end_time'] ? $re['end_time'] : $end_time;
                    $sql = "insert into ".VOUTEMO." (`name`,`desc`,`start_time`,`end_time`,`price`,`limit`,`shop_id`,`shop_name`,`total`,`eachlimit`,`logo`,`status`,`points`) values ('$name','$desc',".time().",'$end_time','$price','$limit',$id,'$re[shop_name]','$total','$eachlimit','$logo',1,'$points')";
                    if($this -> db -> query($sql))
                    {
                        return 1;
                    }
                }
            }
            else // 没有购买该应用,无权添加
            {
                return -1;
            }
        }
        
        /*
         * 编辑代金券模板
         */
        function shop_eidt_voucher()
        {
            foreach($_POST as $key => $val){$$key = $val;}
            $end_time = strtotime($_POST['etime']);
            /*
            $sql = "select * from ".VOUAMOUNT." where  id = $price";
            $this -> db -> query($sql);
            $mu = $this -> db -> fetchRow();
            */
            $sql = "update ".VOUTEMO." set  `name` = '$name',`desc` = '$desc',`end_time` = '$end_time',`points` = '$points',`price` = '$price',`limit` = '$limit',`total` = '$total',`eachlimit` = '$eachlimit',`logo` = '$logo',`status` = '$status' where id =".$_POST['id'];
            return $this -> db -> query($sql);
        }
        
        /*
         * 删除代金券模板
         */
        function voucher_temp_delete($id)
        {
             global $buid;
             $sql = "delete from ".VOUTEMO." where id = $id and shop_id = $buid ";
             return $this -> db -> query($sql);
        }
        
        
        /*
         * 用户申请应用详细信息
         */
        function getApplyInfo($id)
        {
            if(!$id) return false;
            
            $sql = "select * from ".APPLY." where appid=1 and shop_id = $id ";
            $this -> db -> query($sql);
            if($this -> db -> num_rows())
            {
                return $this -> db -> fetchrow();
            }
            else
            {
                return 0;
            }
        }
        
        /*
         * 购买应用
         */
        function buyApply()
        {
            global $buid;
            global $config;
            global $admin;
            
            if(!$p_voucher_config)
            {
                @include $config['webroot']."/config/p_voucher_config.php";
            }
            
            /*** 这里是付钱的！！废弃接口 ****/ 
            //$info = file_get_contents($config['weburl']."/pay/api/direct_pay.php?buid=".$buid."&appid=1&price=".$p_voucher_config['p_v_price']*$_POST['mounth']);
            //if($info == "-1") return -1;
            
            /*** 付款成功了 ****/
            $sql = "select * from ".APPLY." where shop_id = $buid ";
            $this -> db -> query($sql);
            $re = $this -> db -> fetchrow();
            $_POST['mounth'] = ($_GET['price']*100/100)/$p_voucher_config['p_v_price']*1;
            
            if($re['end_time'])
            {
                $end_time = $re['end_time'] + $_POST['mounth']*1*30*24*60*60;
                if($re['end_time'] < time()) $str = ",start_time = '".time()."'";
                $sql = "update ".APPLY." set end_time = '$end_time' $str  where id=".$re['id'];
            }
            else
            {
                $time = time();
                $end_time = $time + $_POST['mounth']*1*30*24*60*60;
                $sql = "select company from ".SHOP." where userid = $buid limit 1";
                $this -> db -> query($sql);
                $shop_name = $this -> db -> fetchfield("company");

                $sql = "insert into ".APPLY." (`appid`,`create_time`,`start_time`,`end_time`,`shop_id`,`shop_name`,`tlimit`) values (1,'$time','$time','$end_time',$buid,'$shop_name','$_POST[tlimit]')";
            }
            if($this -> db -> query($sql))
            {
                return 1;
            }
        }
        
        /*
         * 获取代金券模板详细信息
         */
        function voucher_detial($id)
        {
            global $buid;
            if(!$id) return false;
            
            $sql = "select * from ".VOUTEMO." where id = $id and shop_id = $buid";
            $this -> db -> query($sql);
            if($this -> db -> num_rows())
            {
                return $this -> db -> fetchRow();
            }
            else
            {
                return -1;
            }
        }
        
        /*
         * 统计店铺代金券领取情况
         */
        function total_temp($id)
        {
            global $buid;
            $sql = "select * from ".VOUCHER." where `shop_id` = $buid and `temp_id` = $id order by create_time desc";
            
            $page = new Page;
            $page->listRows=15;
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
         * 买家代金券列表
         */
        function buyvoucher()
        {
            global $buid;
            $sql = "select * from ".VOUCHER." where member_id = $buid and status<4 order by create_time desc ";
            
            $page = new Page;
            $page->listRows=10;
            if (!$page->__get('totalRows'))
            {
                    $this ->db -> query($sql);
                    $page->totalRows = $this -> db -> num_rows();
            }
            $sql .= "  limit ".$page->firstRow.",".$page->listRows;
            $this -> db -> query($sql);
            $de['page'] = $page -> prompt();
            $de['list'] = $this -> db -> getRows();
            
            foreach($de['list'] as $key => $val) //更新过期状态
            {
                if($val['end_time'] < time())
                {
                    $sql = "select * from  ".VOUCHER." where id= ".$val['id']." and `status` = 1 ";
                    $this ->db -> query($sql);
                    if($this -> db -> num_rows())
                    {
                        $sql = "update ".VOUCHER." set `status` = 3 where id= ".$val['id']." and `status` = 1";
                        $flag = $this ->db -> query($sql);
                        $de['list'][$key]['status'] = 3;
                    }
                }
                $sql = "select eachlimit from ".VOUTEMO." where id=".$val['temp_id'];
                $this ->db -> query($sql);
                $de['list'][$key]['eachlimit'] = $this->db->fetchField('eachlimit');

                $sql = "select id from ".VOUCHER." where member_id = $buid and status<4 and temp_id=".$val['temp_id'];
                $this ->db -> query($sql);
                $de['list'][$key]['getnum'] = $this -> db -> num_rows();

            }
            return $de;
        }
        
        /*
         * 删除代金券
         */
        function voucher_delete($id)
        {
            $sql = "update  ".VOUCHER." set status = 4 where id = $id ";
            return $this -> db -> query($sql);
        }
}
?>
