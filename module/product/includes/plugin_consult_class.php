<?php
class consult
{
	var $db;
	var $tpl;
	var $page;
	
	function consult()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		if(!empty($_POST['con']))
		{
			include_once($config['webroot'].'/includes/filter_class.php');
			$word=new Text_Filter();
			$_POST['con']=$word->wordsFilter($_POST['con'], $matche_row);
		}
	}
	
	function add_question($de)
	{
		global $buid;
		$con = htmlspecialchars($_POST["con"]);
		$user = get_member_field($buid,'user');
		$sql="insert into ".CONSULT." (`catid`,product_member_id,product_id,product_name,member_id,member_name,question,answer,answer_id,question_time,answer_time,status) values ('0','$de[member_id]','$de[id]','$de[name]','$buid','$user','$con','','0','".time()."','0','1')";
		$this -> db->query($sql);
	}
}
?>