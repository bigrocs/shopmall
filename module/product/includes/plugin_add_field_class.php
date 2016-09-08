<?php
class AddField
{
	var $db;
	var $tpl;
	var $page;
	var $module;
	
	function AddField($module)
	{
		global $db;
		global $config;
		$this  ->  db = & $db;
	}
	//$id:要编辑的ID（为0时表示增加）,$catid:产品类别ID列表, $table_name扩展表名
	function addfieldinput($id,$table_name,$frant = NULL)
	{
		global $config;
		$list = array();
		if(empty($table_name)) return false;
		$id *= 1; 	
		$j = 1;
		if($id > 0)
		{
			$this -> db -> query("SHOW TABLES LIKE '".$table_name."'");
			if($this -> db -> num_rows() == 1)
			{
				$sql = "select * from $table_name where product_id = '$id'";
				$this -> db -> query($sql);
				$de = $this -> db -> fetchRow();
			}
			else
				return false;
		}
		$type_id = explode('_',$table_name);
		$type_id = $type_id[2];
		
		$sql = "select * from ".PROPERTY." where type_id = '$type_id' order by displayorder";
		$this -> db -> query($sql);
		$re = $this -> db -> getRows();
		foreach($re as $key => $val)
		{
			$t = array();
			$t["name"] = $val["name"];
			$t["field"] = "property_".$val['id'];
			$field = $de[$t["field"]];
			$t["defaultValue"] = $field ? $field : "";
			$t["format"] = $val["format"];
			
			$sql = "select id,name from ".PROPERTYVALUE." where property_id = '$val[id]' order by displayorder";
			$this -> db -> query($sql);
			$t["catItem"] = $this -> db -> getRows();
			if($frant)
			{
				$list['s'][] = $this -> show_property($t);
			}
			else
			{
				$list['s'][] = $this -> addtabletr($t);
			}
		}
		
		$sql = "select a.* from ".SPEC." a left join ".TYPESPEC." b on a.id = b.spec_id where b.type_id = '$type_id' order by displayorder";
		$this -> db -> query($sql);
		$re = $this -> db -> getRows();
		$j = 0;
		foreach($re as $key => $val)
		{
			$t = array();
			$t["name"] = $val["name"];
			$t["field"] = "spec_".$val['id'];
			$t["color_img"] = $de["color_img"];
			$t["format"] = $val["format"];
			$t['j'] = $j ;
			
			$sql = "select * from ".SPECVALUE." where spec_id = '$val[id]' order by displayorder";
			$this -> db -> query($sql);
			$t["catItem"] = $this -> db -> getRows();
			
			if($frant)
			{
                if($this -> show_spec($t,$id)){                   //lemons 2015-05-20 规格变动后所有之前分类下的商品都出问题 bug
                    $list['d'][] = $this -> show_spec($t,$id);
                }
				$j ++ ;
			}
			else
			{
				$list['d'][] = $this -> addtabletr1($t,$id);
			}
		}
		return $list;
	}
	
	function show_property($ob)
	{
		$format = $ob["format"];
		$value = $ob["defaultValue"];
		$htmlstr = "";
		switch($format)
		{
			case "text":
			{	
				$htmlstr = "$value";		
				break;
			}
			case "select":
			{
				foreach($ob["catItem"] as $val)
				{
					if($val['id'] == $value)
						$selectnum = $val['name'];	
				}
				$htmlstr = $selectnum;		
				break;
			}
			case "checkbox":
			{
				$arr = $value ? explode(",",$value) : "";
				foreach($ob["catItem"] as $val)
				{
					if(@in_array($val['id'],$arr))
						$selectnum .= $val['name']."&nbsp;";	
				}
				$htmlstr = $selectnum;		
				break;
			}
		}
		return "<li>".$ob["name"]."：".$htmlstr."</li>";
	}
	
	function show_spec($ob,$id = '')
	{
		$format = $ob["format"];
		$value = $ob["defaultValue"];
		$htmlstr = "";
		$genre = '';
		$selectnum = "";
		if($id)
		{
			$sql = "select setmeal,property_value_id from ".SETMEAL." where pid = '$id'";
			$this -> db -> query($sql);	
			$re = $this -> db -> getRows();
			foreach($re as $val)
			{
				foreach(explode(',',$val['setmeal']) as $v)
				{
					$spec_name[] = $v;
				}
				foreach(explode(',',$val['property_value_id']) as $v)
				{
					$spec_value_id[] = $v;
				}
			}
			$spec_name = $spec_name ? array_unique($spec_name) : "";
			$spec_value_id = $spec_value_id ? array_unique($spec_value_id) : "";
		}
		foreach($ob["catItem"] as $key => $val)
		{
			if($spec_value_id && in_array($val['id'],$spec_value_id))
			{
				foreach($spec_value_id as $k=> $v)
				{
					if($v == $val['id'])
					{
						$val['name'] = $spec_name[$k] ? $spec_name[$k] : $val['name'];	
					}
				}
				$name = $pic?$pic:$val['name'];
				$htmlstr .= '<li title = "'.$val['name'].'"><a '.$cimg.' onclick = "selectSpec(\''.($ob['j']+1).'\',this,\''.$val['id'].'\');"  '.$str1.'>'.$name.'<i></i></a></li>';
			}
		}
        if($htmlstr){                   //lemons 2015-05-20 规格变动后所有之前分类下的商品都出问题 bug
            $returnstr  = "<dl id = '$ob[field]'><dt>".$ob["name"]."</dt>";
            $genre = 'genre = "property"';
            return $returnstr."<dd><ul ".$genre.">".$htmlstr."</ul></dd></dl>";
        }else{
            return "";
        }
	}
	
	//扩展属性
	function addtabletr($ob)
	{
		$htmlstr = "";
		
		switch($ob["format"])
		{
			case "text":
			{
				$htmlstr = "<input class = 'text' type = \"text\" name = \"".$ob["field"]."\" id = \"".$ob["field"]."\" $readonly value = \"".$ob["defaultValue"]."\" >";		
				break;
			}
			case "select":
			{
				$htmlstr = "<select $disabled class = 'select' name = \"".$ob["field"]."\">";
				foreach($ob["catItem"] as $key => $val)
				{
					if($ob["defaultValue"] == $val['id'])
						$htmlstr .= "<option selected=\"selected\" value = \"".$val['id']."\">".$val['name']."</option>";					
					else
						$htmlstr .= "<option value = \"".$val['id']."\">".$val['name']."</option>";					
				}
				$htmlstr  .=  "</select>";			
				break;
			}
			case "checkbox":
			{
				
				foreach($ob["catItem"] as $key => $val)
				{
					$arr = $ob["defaultValue"] ? explode(",",$ob["defaultValue"]) : "";
					if($arr && in_array($val['id'],$arr))
						$htmlstr .= "<label><input name = \"".$ob["field"]."[]\" type = \"checkbox\" checked = \"checked\" value = \"".$val['id']."\">&nbsp;".$val['name']."</label>";					
					else
						$htmlstr .= "<label><input name = \"".$ob["field"]."[]\" type = \"checkbox\" value = \"".$val['id']."\">&nbsp;".$val['name']."</label>";					
				}			
				break;
			}
		}
        if(is_mobile()){        //20150626 lemonx 手机端模版与PC端模版不一致，以此控制
            return "<div class='s_line'><div class='s_text'>".$ob["name"]."：</div><div class='s_input'>".$htmlstr."</div></div>";
        }else{
            return "<tr><th>".$ob["name"]."：</th><td>".$htmlstr."</td></tr>";
        }
	}
	
	//规格
	function addtabletr1($ob,$id = '')
	{
		$returnstr['name']  = $ob["name"];
		$returnstr['field']  = $ob["field"];
		$selectnum = "";
			
		if($id)
		{
			$sql = "select setmeal,property_value_id from ".SETMEAL." where pid = '$id'";
			$this -> db -> query($sql);	
			$re = $this -> db -> getRows();
			foreach($re as $val)
			{
				foreach(explode(',',$val['setmeal']) as $v)
				{
					$spec_name[] = $v;
				}
				foreach(explode(',',$val['property_value_id']) as $v)
				{
					$spec_value_id[] = $v;
				}
			}
			$spec_name = $spec_name ? array_unique($spec_name) : "";
			$spec_value_id = $spec_value_id ? array_unique($spec_value_id) : "";
		}
		foreach($ob["catItem"] as $key => $val)
		{			
			if($spec_value_id && in_array($val['id'],$spec_value_id))
			{
				foreach($spec_value_id as $k=> $v)
				{
					if($v == $val['id'])
					{
						$val['name'] = $spec_name[$k] ? $spec_name[$k] : $val['name'];	
					}
				}
				$str = "checked";
				$html = "<input maxlength = \"20\" type = \"text\" value = \"".$val['name']."\">";
			}
			else
			{
				$str = '';
				$html = $val['name'];
			}
			
			$selectnum .= "<li><span data_type = 'chk'><input class = 'checkbox' $str type = \"checkbox\" name = \"".$ob["field"]."[]\" value = \"".$val['id']."\" data_type = \"".$val['name']."\" ></span><span data_type = 'name'>".$html."</span></li>";	
		}
		$returnstr['item'] = $selectnum;
		return $returnstr;
	}
	
	function echoforeach($len,$sign)
	{
		if($len < $sign){
		
			$abc .=  "for (var i_".$len." = 0; i_".$len." < property_checked[".$len."].length; i_".$len."++){ td_".(intval($len)+1)." = property_checked[".$len."][i_".$len."];";
			$len++;
			$abc .= $this -> echoforeach($len,$sign);
		}
		else{
			$abc .=  "var spec_bunch = 'i_';";
			
			for($i = 0; $i< $len; $i++){
					$abc .=  "spec_bunch +=  td_".($i+1)."[1];";
			}
			for($i = 0; $i< $len; $i++){
					$abc .=  "str += '<td><input type = \"hidden\" name = \"spec['+spec_bunch+'][sp_value][".$i."]['+td_".($i+1)."[1]+']\" value = \"'+td_".($i+1)."[0]+'\" />'+td_".($i+1)."[0]+'</td>';";
			}
			$abc .=  "str += '<td><input class = \"text\" type = \"text\" name = \"spec['+spec_bunch+'][price]\" data_type = \"price\" date_type = \"'+spec_bunch+'|price\" value = \"\" /></td><td><input class = \"text\" type = \"text\" name = \"spec['+spec_bunch+'][stock]\" data_type = \"stock\" date_type = \"'+spec_bunch+'|stock\" value = \"\" /></td><td><input class = \"text\" type = \"text\" name = \"spec['+spec_bunch+'][sku]\" data_type = \"sku\" date_type = \"'+spec_bunch+'|sku\" value = \"\" /></td></tr>';";
			for($i = 0; $i< $len; $i++){
				$abc .=  "}";
			}
		}
		return $abc;
	}

	function delete_con($id,$table_name)
	{
		global $config;
		$this -> db -> query("SHOW TABLES LIKE '".$table_name."'");
		if($this -> db -> num_rows() == 1)
		{
			$sql = "delete from $table_name where product_id = '$id'";
			$this -> db -> query($sql);
		}
	}
	
	function update_con($product_id,$table_name)
	{	
		global $config;	
		$type_id = explode('_',$table_name);
		$type_id = $type_id[2];
		
		$sql =  "select id,format from ".PROPERTY." where type_id = '$type_id'";
		$this -> db -> query($sql);
		$re = $this -> db -> getRows();
		if(empty($re))
			return NULL;
			
		$filed[] = 'product_id';
		$values[] = $product_id;
		
		$filed[] = 'color_img';
		if(!empty($_POST['color_img']))
		{
			foreach($_POST['color_img'] as $key => $val)
			{
				if($val)
					$color_img_arr[] = "$key|$val";	
			}
			
			$color_img = @implode(',',$color_img_arr);
		}
	
		if(!empty($color_img)){
			$values[] = "'".$color_img."'";
		}
		else{
			$values[] = "''";
		}
		foreach($re as $v)
		{
			$fieldname = 'property_'.$v['id'];
			$filed[] = $fieldname;
			$values[] = is_array($_POST[''.$fieldname.'']) ? "'".implode(",",$_POST[''.$fieldname.''])."'" : "'".$_POST[''.$fieldname.'']."'";
		}
		$sql = "select * from $table_name where product_id = '$product_id'";
		$this -> db -> query($sql);
		if($this -> db -> num_rows())
		{
			$sql = "update $table_name set id = id ";
			foreach($filed as $key => $v)
			{
				$sql .= ",$v = $values[$key]";
			}
			$sql .= " where product_id = '$product_id' ";
			$this -> db -> query($sql);
		}
		else
		{
			$sql = "insert into ".$table_name;
			$sql .= "(".implode(",",$filed).")";
			$sql .= " values ";
			$sql .= "(".implode(',',$values).")";
			
			$this -> db -> query($sql);
		}
	}
}
?>