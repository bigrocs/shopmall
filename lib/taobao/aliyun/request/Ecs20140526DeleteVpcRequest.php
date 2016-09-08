<?php
/**
 * TOP API: ecs.aliyuncs.com.DeleteVpc.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DeleteVpcRequest
{
	/** 
	 * 需要删除的虚拟网络的ID
	 **/
	private $vpcId;
	
	private $apiParas = array();
	
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
		return "ecs.aliyuncs.com.DeleteVpc.2014-05-26";
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
