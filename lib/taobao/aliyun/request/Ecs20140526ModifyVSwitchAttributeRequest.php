<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyVSwitchAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyVSwitchAttributeRequest
{
	/** 
	 * 修改后的虚拟交换机描述
	 **/
	private $description;
	
	/** 
	 * 需要修改的虚拟交换机的ID
	 **/
	private $vSwitchId;
	
	/** 
	 * 虚拟交换机名称
	 **/
	private $vSwitchName;
	
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

	public function setvSwitchId($vSwitchId)
	{
		$this->vSwitchId = $vSwitchId;
		$this->apiParas["VSwitchId"] = $vSwitchId;
	}

	public function getvSwitchId()
	{
		return $this->vSwitchId;
	}

	public function setvSwitchName($vSwitchName)
	{
		$this->vSwitchName = $vSwitchName;
		$this->apiParas["VSwitchName"] = $vSwitchName;
	}

	public function getvSwitchName()
	{
		return $this->vSwitchName;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyVSwitchAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->vSwitchId,"vSwitchId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
