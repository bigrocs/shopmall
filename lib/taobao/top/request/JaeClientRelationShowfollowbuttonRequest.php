<?php
/**
 * TOP API: taobao.jae.client.relation.showfollowbutton request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class JaeClientRelationShowfollowbuttonRequest
{
	
	private $apiParas = array();
	
	public function getApiMethodName()
	{
		return "taobao.jae.client.relation.showfollowbutton";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
