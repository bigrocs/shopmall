<?php
/**
 * TOP API: ecs.aliyuncs.com.CreateSecurityGroup.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526CreateSecurityGroupRequest
{
	/** 
	 * 用于保证请求的幂等性。由客户端生成该参数值，要保证在不同请求间唯一，最大不值过64个ASCII字符。 具体参见附录：如何保证幂等性。
	 **/
	private $clientToken;
	
	/** 
	 * 安全组描述信息，长度限制在500个字节，不支持中文
	 **/
	private $description;
	
	/** 
	 * 安全组所属Region ID
	 **/
	private $regionId;
	
	/** 
	 * 安全组名称
	 **/
	private $securityGroupName;
	
	/** 
	 * 虚拟网络类型实例组成的安全组，需要指定安全组所在的虚拟网络ID
	 **/
	private $vpcId;
	
	private $apiParas = array();
	
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

	public function setSecurityGroupName($securityGroupName)
	{
		$this->securityGroupName = $securityGroupName;
		$this->apiParas["SecurityGroupName"] = $securityGroupName;
	}

	public function getSecurityGroupName()
	{
		return $this->securityGroupName;
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

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.CreateSecurityGroup.2014-05-26";
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
