<?php
/**
 * TOP API: ecs.aliyuncs.com.DeleteVSwitch.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DeleteVSwitchRequest
{
	/** 
	 * 需要删除的虚拟交换机的ID
	 **/
	private $vSwitchId;
	
	private $apiParas = array();
	
	public function setvSwitchId($vSwitchId)
	{
		$this->vSwitchId = $vSwitchId;
		$this->apiParas["VSwitchId"] = $vSwitchId;
	}

	public function getvSwitchId()
	{
		return $this->vSwitchId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DeleteVSwitch.2014-05-26";
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
