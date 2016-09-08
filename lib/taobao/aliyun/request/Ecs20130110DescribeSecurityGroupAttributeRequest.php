<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeSecurityGroupAttribute.2013-01-10 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20130110DescribeSecurityGroupAttributeRequest
{
	/** 
	 * 网络类型，取值：internet|intranet
不指定时默认值为internet
	 **/
	private $nicType;
	
	/** 
	 * 安全组所属Region ID
	 **/
	private $regionId;
	
	/** 
	 * 安全组ID
	 **/
	private $securityGroupId;
	
	private $apiParas = array();
	
	public function setNicType($nicType)
	{
		$this->nicType = $nicType;
		$this->apiParas["NicType"] = $nicType;
	}

	public function getNicType()
	{
		return $this->nicType;
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

	public function setSecurityGroupId($securityGroupId)
	{
		$this->securityGroupId = $securityGroupId;
		$this->apiParas["SecurityGroupId"] = $securityGroupId;
	}

	public function getSecurityGroupId()
	{
		return $this->securityGroupId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DescribeSecurityGroupAttribute.2013-01-10";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
		RequestCheckUtil::checkNotNull($this->securityGroupId,"securityGroupId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
