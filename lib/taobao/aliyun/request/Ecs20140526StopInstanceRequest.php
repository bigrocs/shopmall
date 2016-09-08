<?php
/**
 * TOP API: ecs.aliyuncs.com.StopInstance.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526StopInstanceRequest
{
	/** 
	 * 重启机器时的是否强制关机策略。取值：true|false；
若为false则走正常关机流程；若为true则强制关机。
如果不指定，则默认值为false。
	 **/
	private $forceStop;
	
	/** 
	 * 指定的实例ID
	 **/
	private $instanceId;
	
	private $apiParas = array();
	
	public function setForceStop($forceStop)
	{
		$this->forceStop = $forceStop;
		$this->apiParas["ForceStop"] = $forceStop;
	}

	public function getForceStop()
	{
		return $this->forceStop;
	}

	public function setInstanceId($instanceId)
	{
		$this->instanceId = $instanceId;
		$this->apiParas["InstanceId"] = $instanceId;
	}

	public function getInstanceId()
	{
		return $this->instanceId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.StopInstance.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
