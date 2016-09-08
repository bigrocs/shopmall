<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyVRouterAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyVRouterAttributeRequest
{
	/** 
	 * 修改后的虚拟路由器描述
	 **/
	private $description;
	
	/** 
	 * 虚拟路由ID
	 **/
	private $vRouterId;
	
	/** 
	 * 虚拟路由名称
	 **/
	private $vRouterName;
	
	private $apiParas = array();
	
	public function setDescription($description)
	{
		$this->description = $description;
		$this->apiParas["Description"] = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setvRouterId($vRouterId)
	{
		$this->vRouterId = $vRouterId;
		$this->apiParas["VRouterId"] = $vRouterId;
	}

	public function getvRouterId()
	{
		return $this->vRouterId;
	}

	public function setvRouterName($vRouterName)
	{
		$this->vRouterName = $vRouterName;
		$this->apiParas["VRouterName"] = $vRouterName;
	}

	public function getvRouterName()
	{
		return $this->vRouterName;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyVRouterAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->vRouterId,"vRouterId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
