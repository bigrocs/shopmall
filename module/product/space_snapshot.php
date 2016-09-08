<?php
if($de["spec_name"]&&$de["spec_value"])
{
	$de['spec_value'] = explode(",",$de['spec_value']);
	$de['spec_name'] = explode(",",$de['spec_name']); 
	foreach($de['spec_name'] as $key => $val)
	{
		$re[$key]['name'] = $val;
		$re[$key]['value'] = $de['spec_value'][$key];
	}
	$de['spec'] = $re;
}
unset($de['spec_name']);
unset($de['spec_value']);
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$output=tplfetch("space_snapshot.htm",$flag);
?>