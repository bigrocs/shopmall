<?php
/**
 * TOP API: ecs.aliyuncs.com.CreateVSwitch.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526CreateVSwitchRequest
{
	/** 
	 * 新建的虚拟交换机分配的网络地址
	 **/
	private $cidrBlock;
	
	/** 
	 * 用于保证请求的幂等性。由客户端生成该参数值，要保证在不同请求间唯一，最大不值过64个ASCII字符。
	 **/
	private $clientToken;
	
	/** 
	 * 虚拟交换机描述，[1,256]英文或中文字符，不能以http://和https://开头。
	 **/
	private $description;
	
	/** 
	 * 虚拟交换机名称，[1,128]英文或中文字符，不能以http://和https://开头。
	 **/
	private $vSwitchName;
	
	/** 
	 * 新建的虚拟交换机所在的虚拟网络
	 **/
	private $vpcId;
	
	/** 
	 * 可用区Id
	 **/
	private $zoneId;
	
	private $apiParas = array();
	
	public function setCidrBlock($cidrBlock)
	{
		$this->cidrBlock = $cidrBlock;
		$this->apiParas["CidrBlock"] = $cidrBlock;
	}

	public function getCidrBlock()
	{
		return $this->cidrBlock;
	}

	public function setClientToken($clientToken)
	{
		$this->clientToken = $clientToken;
		$this->apiParas["ClientToken"] = $clientToken;
	}

	public function getClientToken()
	{
		return $this->clientToken;
	}

	public function setDescription($description)
	{
		$this->description = $description;
		$this->apiParas["Description"] = $description;
	}

	public function getDescription()
	{
		return $this->description;
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

	public function setVpcId($vpcId)
	{
		$this->vpcId = $vpcId;
		$this->apiParas["VpcId"] = $vpcId;
	}

	public function getVpcId()
	{
		return $this->vpcId;
	}

	public function setZoneId($zoneId)
	{
		$this->zoneId = $zoneId;
		$this->apiParas["ZoneId"] = $zoneId;
	}

	public function getZoneId()
	{
		return $this->zoneId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.CreateVSwitch.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->cidrBlock,"cidrBlock");
		RequestCheckUtil::checkNotNull($this->vpcId,"vpcId");
		RequestCheckUtil::checkNotNull($this->zoneId,"zoneId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
