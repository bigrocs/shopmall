<?php
/**
 * TOP API: bss.aliyuncs.com.SetResourceBusinessStatus.2014-07-14 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Bss20140714SetResourceBusinessStatusRequest
{
	/** 
	 * 操作类型
expired:过期状态
normal:正常状态
	 **/
	private $businessStatus;
	
	/** 
	 * 要操作的实例Id可创建多实例的服务，需要填写此参数。
	 **/
	private $resourceId;
	
	/** 
	 * 要操作的资源的类型。
	 **/
	private $resourceType;
	
	private $apiParas = array();
	
	public function setBusinessStatus($businessStatus)
	{
		$this->businessStatus = $businessStatus;
		$this->apiParas["BusinessStatus"] = $businessStatus;
	}

	public function getBusinessStatus()
	{
		return $this->businessStatus;
	}

	public function setResourceId($resourceId)
	{
		$this->resourceId = $resourceId;
		$this->apiParas["ResourceId"] = $resourceId;
	}

	public function getResourceId()
	{
		return $this->resourceId;
	}

	public function setResourceType($resourceType)
	{
		$this->resourceType = $resourceType;
		$this->apiParas["ResourceType"] = $resourceType;
	}

	public function getResourceType()
	{
		return $this->resourceType;
	}

	public function getApiMethodName()
	{
		return "bss.aliyuncs.com.SetResourceBusinessStatus.2014-07-14";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->businessStatus,"businessStatus");
		RequestCheckUtil::checkNotNull($this->resourceId,"resourceId");
		RequestCheckUtil::checkNotNull($this->resourceType,"resourceType");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
