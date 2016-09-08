<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifySecurityGroupAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifySecurityGroupAttributeRequest
{
	/** 
	 * 修改后的安全组描述，不填则为空，默认值为空，[2,256]英文或中文字符，不能以http://和https://开头。
	 **/
	private $description;
	
	/** 
	 * 安全组所在的地域
	 **/
	private $regionId;
	
	/** 
	 * 需要修改的安全组ID
	 **/
	private $securityGroupId;
	
	/** 
	 * 修改后的安全组名称，不填则为空，默认值为空，[2,128]英文或中文字符，必须以大小字母或中文开头，可包含数字，”_”或”-”，这个值会展示在控制台。不能以http://和https://开头。
	 **/
	private $securityGroupName;
	
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

	public function setSecurityGroupName($securityGroupName)
	{
		$this->securityGroupName = $securityGroupName;
		$this->apiParas["SecurityGroupName"] = $securityGroupName;
	}

	public function getSecurityGroupName()
	{
		return $this->securityGroupName;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifySecurityGroupAttribute.2014-05-26";
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
