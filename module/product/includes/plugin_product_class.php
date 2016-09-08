<?php
class product
{
	var $db;
	var $tpl;
	var $page;
	
	function product()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		
		if(!empty($_POST['title'])||!empty($_POST['title']))
		{
			include_once($config['webroot'].'/includes/filter_class.php');
			$word=new Text_Filter();
		
			$_POST['title']=htmlspecialchars($_POST['title']);
			$_POST["subhead"]=htmlspecialchars($_POST["promotion_tips"]);
			$_POST["detail"]=replace_outside_link($_POST["detail"]);//过虑内容中的a
		
			$_POST['detail']=$word->wordsFilter($_POST['detail'], $matche_row);
			$_POST['title']=$word->wordsFilter($_POST['title'], $matche_row);
			$_POST['subhead']=$word->wordsFilter($_POST['subhead'], $matche_row);
		}
	}
	function get_brand($id,$sbrand=NULL)
	{
		//------------------------------------------------------------
		$id=explode(",",$id);
		if(!empty($id[2]))
			$this->db->query("SELECT brand FROM ".PCAT." WHERE catid='$id[2]'");
		$brand=$this->db->fetchField('brand');
		
		if(empty($brand)&&!empty($id[1]))
		{
			$this->db->query("SELECT brand FROM ".PCAT." WHERE catid='".$id[1]."'");
			$brand=$this->db->fetchField('brand');
		}
		if(empty($brand)&&!empty($id[0]))
		{
			$this->db->query("SELECT brand FROM ".PCAT." WHERE catid='".$id[0]."'");
			$brand=$this->db->fetchField('brand');
		}
		if(!empty($brand))
		{
			$sql="select * from ".BRAND." where id in ($brand) order by displayorder asc ";
			$this->db->query($sql);
			$re=$this->db->getRows();
			$input=$s=$op=NULL;
			$ss="0";
			foreach($re as $v)
			{
				if($v['name']==$sbrand)
				{	
					$s='selected="selected"';
					$ss="1";
				}
				else
					$s=NULL;
				$op.='<option '.$s.' value="'.$v["name"].'">'.$v["name"].'</option>';
			}
			return '<select select="select" name="brand" id="brand" />'.$op.'</select>'.$input;
		}
		else
			return '<input maxlength="20" type="text" name="brand" value="'.$sbrand.'" />';
	}
	function getProTypeName($prod)
	{
		if(!empty($prod))
		{
            $prod = addslashes($prod);
			$sql = "select cat from ".PCAT." where catid in($prod)";
			$this->db->query($sql);
			$fieldlist="";
			while($v=$this->db->fetchRow())
			{
				if($v["cat"]!="")
				$fieldlist.=$v["cat"]."->";
			}
			$fieldlist = trim($fieldlist,"->");
		}
		return $fieldlist;
	}
	function get_user_common_lower_cat()
	{
		if(!empty($_GET['cat']))
		{
			$sql="select pid from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and id='$_GET[cat]' order by nums asc";
			$this->db->query($sql);
			$pid=$this->db->fetchField('pid');
			if($pid!='0')
				$num=$pid;
			else
				$num=$_GET['cat'];
			$sql="select * from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and pid='$num' order by nums asc";
			$this->db->query($sql);
			$re=$this->db->getRows();
			return $re;
		}
	}
	function add_user_common_cat($cid)
	{
		global $buid;
		//-------------------
		$cid=explode(",",$cid);

		foreach ($cid as $k => $v)
		{
			$cid[$k] = intval($v);
		}

		$id=$cid[0];
		if(!empty($cid[1]))
			$id=$cid[1];
		if(!empty($cid[2]))
			$id=$cid[2];
		if(!empty($cid[3]))
			$id=$cid[3];
		$sql="select shop_id,common_cat from ".SSET." where shop_id='$buid'";
		$this->db->query($sql);
		$rec=$this->db->fetchRow();
		if($rec['shop_id'])
		{
			$buid*=1;
			if(empty($rec['common_cat']))
				$sql="update ".SSET." set common_cat='$id' where shop_id='$buid'";
			else
				$sql="update ".SSET." set common_cat=REPLACE(common_cat,',$id',''),common_cat=concat(common_cat,',$id') where shop_id='$buid'";
		}
		else
		{
			$buid=$buid?$buid:"0";
			$sql="insert into ".SSET." (shop_id,common_cat) values ('$buid',',$id')";
		}
		$re=$this->db->query($sql);
	}
	
	function rec_pro($id,$type)
	{	
		$sql="update ".PRODUCT." set shop_rec = '$type' where id= '$id' ";
		$re=$this->db->query($sql);
		if($re)
			return 1;
		else
			return 0;
	}
	
	function operate_product($type,$is_tg='false')
	{
		global $config,$buid,$admin,$config;
		$buid=$buid?$buid:"0";
		$_POST['ptype']*=1;//类型
		$_POST['market_price']*=1;//市场价
		$_POST['price']*=1;//价格
		$_POST['amount']*=1;//库存
		$_POST['freight']*=1;//0为自设定，其它为运费模板。
		
		$sql="select type,province_id,city_id,area_id,street_id,area from ".LGSTEMP." where id='$_POST[freight]'";
		$this->db->query($sql);
		$de=$this->db->fetchRow($sql);
			
		$_POST['freight_type'] = $de['type'] == 1?"1":"0";//1为买家承担，0为卖家承担
		$_POST['province'] = $de['province_id'];
		$_POST['city'] = $de['city_id'];
		$_POST['area'] = $de['area_id'];
		$_POST['street'] = $de['street_id'];
		$_POST['t'] = $de['area'];
		
		$_POST['post_price']*=1;//平邮价格
		$_POST['express_price']*=1;//快递价格
		$_POST['ems_price']*=1;//ems价格
		$_POST['weight']*=1;//体积
		$_POST['cubage']*=1;//重量
		$_POST['stime_type']*=1;//开始时间
		$_POST['validTime']*=1;//有效期
		$_POST['custom_cat']*=1;//自定义分类
		$_POST['is_shelves']='1';//是否上架
		
		$_POST['is_virtual'] = $_POST['is_virtual']?$_POST['is_virtual']:0;
		$_POST['is_invoice']=$_POST['is_invoice']==1?"true":"false";

		$_POST['is_dist']= intval($_POST['is_dist']);
		
		$preg = "/<script[\s\S]*?<\/script>/i";
		
		$_POST['detail'] = preg_replace($preg,"",$_POST['detail']);
		

		$status = 1;
		$start_time = 0;
		if($_POST['stime_type']==2)
		{
			$start_time=strtotime($_POST['stime']);
		}
		elseif($_POST['stime_type']==3)
		{
			$_POST['is_shelves']='0';
		}
		$catid=$_POST['catid'];
		$pactidlist=$_POST['catid'];
		if(!empty($_POST['tcatid'])){
			$catid=$_POST['tcatid'];
			$pactidlist.= ",".$_POST['tcatid'];
		}
		if(!empty($_POST['scatid'])){
			$catid=$_POST['scatid'];
			$pactidlist.=",".$_POST['scatid'];
		}
		if(!empty($_POST['sscatid'])){
			$catid=$_POST['sscatid'];
			$pactidlist.=",".$_POST['sscatid'];
		}
		$pic='';
		$pic_more='';
		$img='';
		$_POST['pic']=array_unique($_POST['pic']);
		if(!empty($_POST['pic']))
		{
			foreach($_POST['pic'] as $val)
			{
				if($val)
				{
					$img[]=$val;	
				}
			}
			if(!empty($img))
			{
				$pic=$img['0'];
				$pic_more=implode(',',array_unique($img));
				if($type=='edit')
				{
					$sub_sql.= $pic ?",pic='$pic'":",pic=''";
					$sub_sql.= $pic_more ?",pic_more='$pic_more'":",pic_more=''";	
				}
			}
		}
		if($type=='add')
		{
			if($_POST['is_virtual']) //虚拟商品
			{
				$_POST['freight_type'] = 0;
				$start_time=strtotime($_POST['stime']);
				$status = 0;
				unset($str0);
				unset($str00);
				$str0 = ",`status`";
				$str00 = ",'0'";
			}
			$sql="INSERT INTO ".PRODUCT." (`weight`,`cubage`,member_id,member_name,catid,name,brand,market_price,price,uptime,pic,pic_more,start_time_type,start_time,valid_time,stock,code,type,keywords,custom_cat_id,freight_type,freight_id,post_price,express_price,ems_price,subhead,is_shelves,province_id,city_id,area_id,street_id,area,is_invoice,is_tg,`is_virtual`,`is_dist` $str0) VALUES ('$_POST[weight]', '$_POST[cubage]','$buid','$_COOKIE[USER]','$catid','$_POST[title]','$_POST[brand]','$_POST[market_price]','$_POST[price]','".time()."','$pic','$pic_more','$_POST[stime_type]','$start_time','$_POST[validTime]','$_POST[amount]','$_POST[code]','$_POST[ptype]','$_POST[keywords]','$_POST[custom_cat]','$_POST[freight_type]','$_POST[freight]','$_POST[post_price]','$_POST[express_price]','$_POST[ems_price]','$_POST[subhead]','$_POST[is_shelves]','$_POST[province]','$_POST[city]','$_POST[street]','$_POST[area]','$_POST[t]','$_POST[is_invoice]','$is_tg','$_POST[is_virtual]','$_POST[is_dist]' $str00 )";
			$re=$this->db->query($sql);
			$id=$this->db->lastid();
			$sql="INSERT INTO ".PRODETAIL." (userid,proid,detail) VALUES ('$buid','$id','$_POST[detail]')";
			$re=$this->db->query($sql);

			if($_POST['is_virtual']) //虚拟商品
			{
				$maxbuy = $_POST['maxbuy'] * 1;
				$recate = $_POST['recate'] * 1;
				$end_time = strtotime($_POST['etime']);

				$sql = "insert into ".PROVIR." (`pid`,`promotion_msg`,`maxbuy`,`recate`,`end_time`) values ('$id','$_POST[promotion_msg]','$maxbuy','$recate','$end_time')";
				$this -> db -> query($sql);
			}

			//
			$PluginManager = Yf_Plugin_Manager::getInstance();

			$commission_product_price_row = array();
			$commission_product_price_row['commission_product_price_0'] = $_REQUEST['commission_product_price_0'];
			$commission_product_price_row['commission_product_price_1'] = $_REQUEST['commission_product_price_1'];
			$commission_product_price_row['commission_product_price_2'] = $_REQUEST['commission_product_price_2'];
			$commission_product_price_row['commission_product_price_plantform'] = $_REQUEST['commission_product_price_plantform'];
			$commission_product_price_row['commission_product_price_settlement'] = $_REQUEST['commission_product_price_settlement'];

			$rs = $PluginManager->trigger('add_distribution_goods', $buid, $id, $commission_product_price_row);
		}
		if($type=='edit')
		{
			$id=$_POST["editID"]*1;
			
			if($_POST['is_virtual']) //虚拟商品
			{
				$_POST['freight_type'] = 0;
				$start_time=strtotime($_POST['stime']);
				$status = 0;
				unset($str00);
				$str00 = ", `status` = 0";
			}

			$sql="UPDATE ".PRODUCT." SET name='$_POST[title]',catid='$catid',			brand='$_POST[brand]',market_price='$_POST[market_price]',price='$_POST[price]',code='$_POST[code]',uptime='".time()."',start_time_type='$_POST[stime_type]',start_time='$start_time',valid_time='$_POST[validTime]',province_id='$_POST[province]',city_id='$_POST[city]',street_id='$_POST[street]',area='$_POST[t]',area_id='$_POST[area]',stock='$_POST[amount]',type='$_POST[ptype]',keywords='$_POST[keywords]',freight_id='$_POST[freight]',freight_type='$_POST[freight_type]',weight='$_POST[weight]',cubage='$_POST[cubage]',post_price='$_POST[post_price]',express_price='$_POST[express_price]',ems_price='$_POST[ems_price]',custom_cat_id='$_POST[custom_cat]',subhead='$_POST[promotion_tips]',`is_shelves`='$_POST[is_shelves]' $str00,`is_invoice`='$_POST[is_invoice]',`is_dist`='$_POST[is_dist]' ".$sub_sql." WHERE id=$id and member_id='$buid'";
			$re=$this->db->query($sql);
			//-----------------更新产品详情
			$sql="select proid from ".PRODETAIL." where proid='$id'";
			$this->db->query($sql);
			if($this->db->num_rows())
				$re=$this->db->query("UPDATE ".PRODETAIL." SET detail='$_POST[detail]' WHERE proid='$id'");
			else
				$re=$this->db->query("INSERT INTO ".PRODETAIL." (userid,proid,detail) VALUES ('$buid','$id','$_POST[detail]')");

			if($_POST['is_virtual']) //虚拟商品
			{
				$maxbuy = $_POST['maxbuy'] * 1;
				$recate = $_POST['recate'] * 1;
				$end_time = strtotime($_POST['etime']);

				$sql = "select pid from ".PROVIR." where `pid` = $id";
				$this -> db -> query($sql);
				if($this -> db -> num_rows())
				{
					$sql = "update ".PROVIR." set `end_time` = '$end_time', `promotion_msg` = '$_POST[promotion_msg]',`maxbuy` = '$maxbuy',`recate` = '$recate' where `pid` = '$id'";
				}
				else
				{
					$sql = "insert into ".PROVIR." (`pid`,`promotion_msg`,`maxbuy`,`recate`,`end_time`) values ('$id','$_POST[promotion_msg]','$maxbuy','$recate','$end_time')";
				}
				$this -> db -> query($sql);
			}

			$PluginManager = Yf_Plugin_Manager::getInstance();


			$commission_product_price_row = array();
			$commission_product_price_row['commission_product_price_0'] = $_REQUEST['commission_product_price_0'];
			$commission_product_price_row['commission_product_price_1'] = $_REQUEST['commission_product_price_1'];
			$commission_product_price_row['commission_product_price_2'] = $_REQUEST['commission_product_price_2'];
			$commission_product_price_row['commission_product_price_plantform'] = $_REQUEST['commission_product_price_plantform'];
			$commission_product_price_row['commission_product_price_settlement'] = $_REQUEST['commission_product_price_settlement'];

			$rs = $PluginManager->trigger('edit_distribution_goods', $buid, $id, $commission_product_price_row);
		}
		//--------------------规格
		if(!empty($_POST['spec']))
		{
			$spec_name = implode(',',$_POST['spec_name']);
			
			foreach($_POST['spec'] as $key => $v)
			{
				$str1 = array();
				$str2 = array();
				foreach($v['sp_value'] as $val)
				{
					foreach($val as $num => $s)
					{
						$str1[] = $s;
						$str2[] = $num;
					}
				}
				$name = $str1 ? implode(',',$str1) : "";
				$arr[] = $pids = $str2 ? implode(',',$str2) : "";
				
				$sql = "select id from ".SETMEAL." where property_value_id = '$pids' and pid = '$id' ";
				$this -> db -> query($sql);
				$spec_id = $this -> db -> fetchField('id');
				
				if($spec_id)
				{
					$sql = "UPDATE ".SETMEAL." SET setmeal='$name',spec_name='$spec_name',price='$v[price]',stock='$v[stock]',sku='$v[sku]' WHERE id = '$spec_id'";
					$this->db->query($sql);
				}
				else
				{		
					$sql = "insert into ".SETMEAL." (pid,setmeal,spec_name,price,stock,sku,property_value_id) values ('$id','$name','$spec_name','$v[price]','$v[stock]','$v[sku]','$pids')";			
					$this->db->query($sql);
				}
			}
			if($type=='edit')
			{
				$sql = "select id,property_value_id from ".SETMEAL." where pid = '$id' ";
				$this -> db -> query($sql);
				$re = $this -> db -> getRows();
				if($re)
				{
					foreach($re as $val)
					{
						if(!in_array($val['property_value_id'],$arr))
						{
							$sql="DELETE FROM ".SETMEAL." WHERE `id` = '$val[id]'";
							$this->db->query($sql);
						}
					}
				}
			}
		}
		else
		{
			$sql="DELETE FROM ".SETMEAL." WHERE `pid` = $id";
			$this->db->query($sql);
		}
		//--------------------推荐产品
		if($_POST['rec_pro']==1)
			$this->rec_pro($id,'1');
		else
			$this->rec_pro($id,'0');
		//--------------------填加自定义字段
		$sql="select ext_table from ".PCAT." where catid='$catid'";
		$this->db->query($sql);
		$ext_table=$this->db->fetchField('ext_table');
		include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
		if($ext_table)
		{
			$addfield = new AddField('product');
			$addfield->update_con($id,$ext_table);
		}
		//---------------------
		return $re;
	}

	function add_product_batch()
	{	
		global $config,$buid,$admin,$config;

		setlocale(LC_ALL, 'en_US.UTF-8');
		$fname = $config['webroot']."/uploadfile/preview/".$buid.".csv"; 
		$do = copy($_FILES['csv']['tmp_name'],$fname);
		$content = file_get_contents($fname);
		$content = iconv("GB2312","UTF-8//IGNORE",$content);
		$fp = fopen($fname, "w");
		fputs($fp, $content);
		fclose($fp);

		$sql="";
		$num=1;
		$file = fopen($fname,"r");
		$catid=$_POST['catid'];
		if(!empty($_POST['tcatid']))
			$catid=$_POST['tcatid'];
		if(!empty($_POST['scatid']))
			$catid=$_POST['scatid'];
		if(!empty($_POST['sscatid']))
			$catid=$_POST['sscatid'];
		$_POST['custom_cat']=$_POST['custom_cat']?$_POST['custom_cat']:"0";
		while ($data = fgetcsv ($file, 2000, ","))
		{
			if($num!=1)
			{
				$sql="INSERT INTO ".PRODUCT." 
				(catid,member_id,member_name,name,brand,price,code,stock ,keywords,uptime,status,province_id,city_id,area_id,area,custom_cat_id) 
				VALUES
	 			('$catid','$buid','$_COOKIE[USER]','$data[0]','$data[4]','$data[1]','$data[2]','$data[3]','$data[5]','".time()."','0','$_POST[province]','$_POST[city]','$_POST[area]','$_POST[t]','$_POST[custom_cat]')";
				$this->db->query($sql);
				$id=$this->db->lastid();
				$sql="INSERT INTO ".PRODETAIL." (userid,proid,detail) VALUES ('$buid','$id','$data[6]')";
				$re=$this->db->query($sql);
			}
			$num++;
		}
		fclose($file);
		@unlink($fname);
	}
	
	function set_pro_statu($pid,$status)
	{
		$sql="update ".PRODUCT." set uptime='".time()."',is_shelves='$status' where id='$pid'";
		$this->db->query($sql);
	}
	
	function del_pro($id,$uid=NULL)
	{
		global $config,$buid;
		if(!empty($uid))
			$buid=$uid;
		//---------------------------------------------------
		$this->db->query("select catid,pic from ".PRODUCT." where id='$id'");
		$re=$this->db->fetchRow();
		
		//---------------------------------------------------
		$this->db->query("delete from  ".PRODUCT." where id='$id'");
		$this->db->query("delete from  ".PRODETAIL." where proid='$id'");
	
		//----------------------------------------------------删除自定义数据
		$sql="select ext_table from ".PCAT." where catid='$catid'";
		$this->db->query($sql);
		$ext_table=$this->db->fetchField('ext_table');
		
		include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
		$addfield = new AddField('product');
		$addfield->delete_con($id,$ext_table);
	}
	
	function detail($pid)
	{
		global $buid,$config;
				
		$sql="update ".PRODUCT." set clicks=clicks+1 where id='$pid'";
		$this->db->query($sql);
		
		$sql="select a.*,b.detail from ".PRODUCT." a left join ".PRODETAIL." b on a.id=b.proid where a.id='$pid'";
		$this->db->query($sql);
		$prod=$this->db->fetchRow();
		$time=time();
		if($prod["promotion_id"])
		{
			$sql="select a.activity_id,a.status,b.* from ".ACTIVITYPRODUCT." a left join ".ACTIVITY." b on a.activity_id=b.id where a.product_id='$pid' and a.status=2 and b.start_time <= $time and b.end_time >= $time";
			$this->db->query($sql);
			$activity_re=$this->db->fetchRow();	
			if(!empty($activity_re))
			{
				$prod["activity_list"]=$activity_re;
			}
		}
		
		/*** 判断是否为虚拟类型 ****/
		if($prod['is_virtual'] == 1)
		{
			$sql = "select `promotion_msg`,`maxbuy`,`recate`,`end_time` from ".PROVIR." where `pid` = ".$pid;
			$this -> db -> query($sql);
			$prod = array_merge($prod,$this -> db -> fetchRow());
		}

		$prod['district']=explode(' ',$prod['area']);
		
        if($buid)
        {
            //========商品是否收藏===================================
            $sql="select id from ".SPRO." where pid=".$pid." and uid=$buid";
            $this->db->query($sql);
            if($this->db->fetchRow()){
                $prod["is_collect"]=1;
            }

            $sql = "select discounts from ".MECART." where `shop_id` = $prod[member_id] and `blind_member_id` = $buid and `status` = 1 order by discounts asc limit 1";
            $this->db->query($sql);
            if($this -> db -> num_rows())
            {
                $discounts = $this -> db -> fetchField("discounts");
            }
            if($discounts > 0){$prod['discounts'] = $discounts;$prod['price'] = ($prod['price'] * $discounts)/10;}
        }
	
		$sql="select count(*) as count from ".PCOMMENT." where pid=$pid and userid!=$prod[member_id]";
		$this->db->query($sql);
		$prod['count']=$this->db->fetchField('count');
		
		if($prod['status']<=0||$prod['is_shelves']==0)
		{
			$prod['down']=1;	
		}
		else
		{
			
			$sql="select * from ".SETMEAL." where pid=$pid and property_value_id !='' ";
			$this->db->query($sql);
			$prod['porperty']=$this->db->getRows();
			if($prod['valid_time']==0) $validTime=7;
			else if($prod['valid_time']==1) $validTime=30;
			$time=$prod['start_time_type']==2?$prod['start_time']:$prod['uptime'];
			if($time+$validTime*24*3600-time()<0&&$prod['valid_time']!=2)
			{
				$sql="update ".PRODUCT." set is_shelves=0 where id='$pid'";
				$this->db->query($sql);
				$prod['down']=1;
			}
			else
			{	
				$prod['rest']='0';
				if($prod['start_time_type']==2&&$time>time())
				{
					$prod['rest']=$time-time();
				}	
			}
                        if($discounts > 0)
                        {
                            foreach($prod['porperty'] as $key => $val)
                            {
                                $prod['porperty'][$key]['price'] = ( $prod['porperty'][$key]['price'] * $discounts)/10;
                            }
                        }
		}
		if(!empty($prod['pic'])){
			$prod['pic_more'] = explode(",",$prod['pic_more']);		
		}
		//=======产品类型========================================
		$ptype=explode('|',$config['ptype']);
		$prod["ptype"]=$ptype[$prod['type']];
		return $prod;
	}
	
	function product_detail($id)
	{
		if($id)
		{	
			global $config,$buid;
			$sql="select a.*,b.detail from ".PRODUCT." a left join ".PRODETAIL." b on a.id=b.proid where a.id=$id and a.member_id='$buid'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();

			/*** 判断是否为虚拟类型 ****/
			if($re['is_virtual'] == 1)
			{
				$sql = "select `promotion_msg`,`maxbuy`,`recate`,`end_time` from ".PROVIR." where `pid` = ".$id;
				$this -> db -> query($sql);
				$re = array_merge($re,$this -> db -> fetchRow());
			}
			
			$sql="select ext_table from ".PCAT." where catid='$re[catid]'";
			$this->db->query($sql);
			$re['ext_table']=$this->db->fetchField('ext_table');
			
			$re['classid']=$re['catid'];
			if(strlen($re['catid'])>8)
				$re['sscatid']=$re['catid'];
			if(strlen($re['catid'])>6)
				$re['scatid']=substr($re['catid'],0,8);	
			if(strlen($re['catid'])>4)
				$re['tcatid']=substr($re['catid'],0,6);
			$re['catid']=substr($re['catid'],0,4);
			//======================================
			$re['pic'] = $re['pic_more']?explode(',',$re['pic_more']):'';
						
			$sql="select * from ".SETMEAL." where pid=$id and property_value_id !='' ";
			$this->db->query($sql);
			$re['porperty']=$this->db->getRows();
			foreach($re['porperty'] as $key=>$val)
			{
				$a=explode(',',$val['setmeal']);
				$b=explode(',',$val['property_value_id']);
				$c=array();
				$d='';
				foreach($a as $k=>$v)
				{
					$num=$b[$k];
					$c[$k][$num]=$v;
					$d.=$num;
				}
				$re['porperty'][$key]['setmeal']=$c;
				$re['porperty'][$key]['property_value_id']=$d;
			}

			//
			global $distribution_open_flag;

			if ($distribution_open_flag)
			{
				global $distribution;

				$re_dist = $distribution->getDistributionProductInfo($buid, $id);

				$re_dist_row = array();

				if ($re_dist)
				{
					$re_dist_row = array_pop($re_dist);
				}

				$re = array_merge($re, $re_dist_row);

			}

			return $re;
		}
	}
	
	function pro_list($status,$is_shelves='1',$is_tg="false")
	{
		global $buid,$config;
		
		if(isset($status))
		{
			if($status=='1')
				$sqls="and status>='1'";
			elseif($status=='-2')
				$sqls="and status='0'";
			elseif($status=='0')
				$sqls="and status>'0'";	
			else
				$sqls="and status='$status'";
		}
		if($is_tg)
		{
			$sqls.=" and is_tg = '$is_tg'";	
		}
		if($is_shelves!='ALL')
		{
			if($is_shelves==0)
			{
				$sqls.=" and is_shelves='0'";
			}
			else
			{
				$sqls.=" and is_shelves='1'";
			}
		}
		if(!empty($_GET['key']))
		{
			$sqls.=" and (name like '%$_GET[key]%' or code like '%$_GET[key]%')";
		}
		
		$sql="select id,name as pname,uptime,pic,status,price,stock as amount,code,shop_rec,down_reason from ".PRODUCT."  where member_id='$buid' $sqls order by uptime desc";
		//=============================
	  	$page = new Page;
		$page->listRows=10;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows =$this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",10";
		//=====================
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		$re["page"]=$page->prompt();
		return $re;
	}
	
	function shop_pro_list()
	{
		global $config;
		switch ($_GET['sort'])
		{
			case "sell_amount":
			{	
				$sort=' order by sales desc';break;
			}
			case "_sell_amount":
			{	
				$sort=' order by sales';break;
			}
			case "price":
			{	
				$sort=' order by price';break;
			}
			case "_price":
			{	
				$sort=' order by price desc';break;
			}
			case "uptime":
			{	
				$sort=' order by uptime desc';break;
			}
			case "_uptime":
			{	
				$sort=' order by uptime';break;
			}
			case "read_nums":
			{	
				$sort=' order by clicks desc';break;
			}
			case "_read_nums":
			{	
				$sort=' order by clicks';break;
			}
			default:
			{
				$sort=' order by id desc';break;
			}
		}
		if($_GET['keyword'])
		{
			$keyword=trim($_GET['keyword']);
			$str.=" and name  like '%$keyword%' ";	
		}
		if($_GET['price1'])
		{
			$str.=" and price >= $_GET[price1] ";	
		}
		if($_GET['price2'])
		{
			$str.=" and price <= $_GET[price2]";	
		}
		if(!empty($_GET['cat']))
		{	
			$sql="select id from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and pid='".$_GET['cat']."' order by nums asc";
			$this->db->query($sql);
			$de=$this->db->getRows();
			$cats=$_GET['cat'];
			foreach($de as $val)
			{
				if($val['id'])
				$cats=$cats.','.$val['id'];
			}
			$sql="select id,name as pname,member_id as userid,market_price,price,member_name as user,pic,sales from ".PRODUCT." where member_id='$_GET[uid]' and is_shelves=1 and custom_cat_id in ($cats) and status>0 and is_tg = 'false' $str $sort";
			
		}
		else
		{
			$sql="select a.id,a.member_id as userid,market_price,name as pname,pic,uptime,price,sales,a.member_name as user from ".PRODUCT." a where a.member_id='$_GET[uid]' and is_shelves=1 and a.status>0 and is_tg = 'false' $str $sort";
		}
		//-------------------------------------------------
		include_once($config['webroot']."/includes/page_utf_class.php");
		$page = new Page;
		$page->url='shop.php';
		$page->listRows=20;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows =$this->db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.", ".$page->listRows;
		$infoList['page']=$page->prompt();
		$infoList['count']=$page->totalRows;
		//--------------------------------------------------
		$this->db->query($sql);
		$infoList['list']=$this->db->getRows();
		return $infoList;
	}


	function shop_pro_list_by_sphinx()
	{
		if($_GET['keyword'])
		{
			$key=trim($_GET['keyword']);
		}

		global $config;
		global $sphinx_search_host;
		global $sphinx_search_port;

		$b_time = microtime(true);
		//$key = "我是一个测试";
		$index = "shop_search";
		//========================================分词

		$so = new Yf_Search_Scws($key);
		$words = $so->getResult();
		fb($words);


		//========================================搜索
		$sc = new SphinxClient();
		$sc->SetServer($sphinx_search_host, $sphinx_search_port);
		#$sc->SetMatchMode(SPH_MATCH_ALL);
		$sc->SetMatchMode(SPH_MATCH_EXTENDED);
		$sc->SetArrayResult(TRUE);
		$sc->setFilter('is_shelves', array(1));
		$sc->setFilter('status', array(1));
		$sc->setFilter('userid', array($_GET[uid]));

		if(!empty($_GET['cat']))
		{
			$sql="select id from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and pid='".$_GET['cat']."' order by nums asc";
			$this->db->query($sql);
			$de=$this->db->getRows();
			$cats=$_GET['cat'];

			$cat_id_row = array();

			foreach($de as $val)
			{
				if($val['id'])
					$cat_id_row[] = $val['id'];
			}

			$sc->setFilter('custom_cat_id', $cat_id_row);
		}

		if($_GET['price1'])
		{
			$mix_price = $_GET['price1'];
		}
		else
		{
			$mix_price = 0;
		}

		if($_GET['price2'])
		{
			$max_price = $_GET['price2'];
		}
		else
		{
			$max_price = 9999999999;
		}

		$sc->setFilterFloatRange('price', $mix_price, $max_price);


		$sort = '';

		switch ($_GET['sort'])
		{
			case "sell_amount":
			{
				$sort='sales DESC';break;
			}
			case "_sell_amount":
			{
				$sort='sales ASC';break;
			}
			case "price":
			{
				$sort='price DESC';break;
			}
			case "_price":
			{
				$sort='price ASC';break;
			}
			case "uptime":
			{
				$sort='uptime DESC';break;
			}
			case "_uptime":
			{
				$sort='uptime ASC';break;
			}
			case "read_nums":
			{
				$sort='clicks DESC';break;
			}
			case "_read_nums":
			{
				$sort='clicks ASC';break;
			}
			default:
			{
				$sort='id DESC';break;
			}
		}


		$sc->SetSortMode(SPH_SORT_EXTENDED, $sort);

		if ($_GET['firstRow'])
		{
			$start = $_GET['firstRow'];
		}
		else
		{
			$start = 0;
		}

		$sc->SetLimits($start, 16, 1000);    // 最大结果集10000

		$res = $sc->Query($words,$index);
		//print_r($res);
		$e_time = microtime(true);
		$time = $e_time - $b_time;
		//echo '<p>'.$e_time.'</p>';

		//echo '<p>'.$time.'</p>';
		fb($time);


		$prol = array();

		if ($res['matches'])
		{
			foreach ($res['matches'] as $matches)
			{
				$matches['attrs']['id'] = $matches['id'];
				$prol[] = $matches['attrs'];
			}

		}


		include_once("includes/page_utf_class.php");
		$page = new Page;
		$page->url='shop.php';
		$page->listRows=16;

		if(!$page->__get('totalRows'))
		{
			$page->totalRows = $res['total'];
		}


		$infoList['list']=$prol;
		$infoList['page']=$page->prompt();
		$infoList['count']=$res['total'];

		return $infoList;
	}

    function get_shop_pro($shop_id)
    {
        global $config;

        $sql="select a.id,a.member_id as userid,market_price,name as pname,pic,uptime,price,sales,a.member_name as user, a.is_dist from ".PRODUCT." a where a.member_id='$shop_id' and is_shelves=1 and a.status>0 and is_tg = 'false'";

        $this->db->query($sql);
        $info = $this->db->getRows();
        return $info;
    }
	public function change_status($id,$flag=0)
	{
		if($flag > 0)
		{
			$str = " `is_shelves` = 0";
		}
		else
		{
			$str = " `is_shelves` = 1";
		}

		$sql = "update ".PRODUCT." set $str where id in(".$id.") ";
		return $this->db->query($sql);
	}
}
?>