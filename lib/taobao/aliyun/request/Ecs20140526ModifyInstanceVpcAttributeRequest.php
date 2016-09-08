<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyInstanceVpcAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyInstanceVpcAttributeRequest
{
	/** 
	 * 指定的实例ID
	 **/
	private $instanceId;
	
	/** 
	 * 实例私网IP地址，不能单独指定。
	 **/
	private $privateIpAddress;
	
	/** 
	 * 目标虚拟交换机ID，不能跨Zone修改实例的虚拟交换机。
	 **/
	private $vSwitchId;
	
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

	public function setPrivateIpAddress($privateIpAddress)
	{
		$this->privateIpAddress = $privateIpAddress;
		$this->apiParas["PrivateIpAddress"] = $privateIpAddress;
	}

	public function getPrivateIpAddress()
	{
		return $this->privateIpAddress;
	}

	public function setvSwitchId($vSwitchId)
	{
		$this->vSwitchId = $vSwitchId;
		$this->apiParas["VSwitchId"] = $vSwitchId;
	}

	public function getvSwitchId()
	{
		return $this->vSwitchId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyInstanceVpcAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
		RequestCheckUtil::checkNotNull($this->vSwitchId,"vSwitchId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
