<?php
/**
 * TOP API: ecs.aliyuncs.com.CreateVpc.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526CreateVpcRequest
{
	/** 
	 * 可选值 192.168.0.0/16和172.16.0.0/16，新建的虚拟网络分配的网络地址。不指定时，默认是172.16.0.0/16
	 **/
	private $cidrBlock;
	
	/** 
	 * 用于保证请求的幂等性。由客户端生成该参数值，要保证在不同请求间唯一，最大不值过64个ASCII字符。 具体参见附录：如何保证幂等性。
	 **/
	private $clientToken;
	
	/** 
	 * [1,256]英文或中文字符，不能以http://和https://开头。
	 **/
	private $description;
	
	/** 
	 * 新建的虚拟网络所在的地域
	 **/
	private $regionId;
	
	/** 
	 * VPC名称，[1,128]英文或中文字符，不能以http://和https://开头
	 **/
	private $vpcName;
	
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

	public function setRegionId($regionId)
	{
		$this->regionId = $regionId;
		$this->apiParas["RegionId"] = $regionId;
	}

	public function getRegionId()
	{
		return $this->regionId;
	}

	public function setVpcName($vpcName)
	{
		$this->vpcName = $vpcName;
		$this->apiParas["VpcName"] = $vpcName;
	}

	public function getVpcName()
	{
		return $this->vpcName;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.CreateVpc.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
