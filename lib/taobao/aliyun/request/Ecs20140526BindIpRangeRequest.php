<?php
/**
 * TOP API: ecs.aliyuncs.com.BindIpRange.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526BindIpRangeRequest
{
	/** 
	 * 实例名称
	 **/
	private $instanceId;
	
	/** 
	 * 采用CIDR格式来指定IP地址范围。
	 **/
	private $ipAddress;
	
	private $apiParas = array();
	
	public function setInstanceId($instanceId)
	{
		$this->instanceId = $instanceId;
		$this->apiParas["InstanceId"] = $instanceId;
	}

	public function getInstanceId()
	{
		return $this->instanceId;
	}

	public function setIpAddress($ipAddress)
	{
		$this->ipAddress = $ipAddress;
		$this->apiParas["IpAddress"] = $ipAddress;
	}

	public function getIpAddress()
	{
		return $this->ipAddress;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.BindIpRange.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
		RequestCheckUtil::checkNotNull($this->ipAddress,"ipAddress");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
