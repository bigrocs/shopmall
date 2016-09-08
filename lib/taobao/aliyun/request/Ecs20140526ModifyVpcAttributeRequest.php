<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyVpcAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyVpcAttributeRequest
{
	/** 
	 * 修改后的虚拟网络描述，[1,256]英文或中文字符，不能以http://和https://开头。
	 **/
	private $description;
	
	/** 
	 * 需要修改的虚拟网络的ID
	 **/
	private $vpcId;
	
	/** 
	 * 修改后的虚拟网络名字，[1,128]英文或中文字符，不能以http://和https://开头
	 **/
	private $vpcName;
	
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

	public function setVpcId($vpcId)
	{
		$this->vpcId = $vpcId;
		$this->apiParas["VpcId"] = $vpcId;
	}

	public function getVpcId()
	{
		return $this->vpcId;
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
		return "ecs.aliyuncs.com.ModifyVpcAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->vpcId,"vpcId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
