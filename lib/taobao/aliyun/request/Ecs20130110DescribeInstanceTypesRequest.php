<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeInstanceTypes.2013-01-10 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20130110DescribeInstanceTypesRequest
{
	
	private $apiParas = array();
	
	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DescribeInstanceTypes.2013-01-10";
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
