<?php
/**
 * TOP API: taobao.crm.qn.labels.seller.get request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:16
 */
class CrmQnLabelsSellerGetRequest
{
	
	private $apiParas = array();
	
	public function getApiMethodName()
	{
		return "taobao.crm.qn.labels.seller.get";
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
