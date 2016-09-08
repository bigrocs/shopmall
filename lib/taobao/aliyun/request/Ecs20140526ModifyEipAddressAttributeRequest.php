<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyEipAddressAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyEipAddressAttributeRequest
{
	/** 
	 * 申请Id
	 **/
	private $allocationId;
	
	/** 
	 * 修改后的弹性IP地址带宽，带宽以Mbps计算。
	 **/
	private $bandwidth;
	
	private $apiParas = array();
	
	public function setAllocationId($allocationId)
	{
		$this->allocationId = $allocationId;
		$this->apiParas["AllocationId"] = $allocationId;
	}

	public function getAllocationId()
	{
		return $this->allocationId;
	}

	public function setBandwidth($bandwidth)
	{
		$this->bandwidth = $bandwidth;
		$this->apiParas["Bandwidth"] = $bandwidth;
	}

	public function getBandwidth()
	{
		return $this->bandwidth;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyEipAddressAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->allocationId,"allocationId");
		RequestCheckUtil::checkNotNull($this->bandwidth,"bandwidth");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
