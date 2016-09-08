<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyInstanceNetworkSpec.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyInstanceNetworkSpecRequest
{
	/** 
	 * 指定的需要实例规格的实例ID
	 **/
	private $instanceId;
	
	/** 
	 * 公网入网带宽最大值，单位为Mbps。可取值范围为[1,200]
	 **/
	private $internetMaxBandwidthIn;
	
	/** 
	 * 公网出网带宽最大值，单位为Mbps。可取值范围为[1,200]
	 **/
	private $internetMaxBandwidthOut;
	
	private $apiParas = array();
	
	public function setInstanceId($instanceId)
	{
		$this->instanceId = $instanceId;
		$this->apiParas["InstanceId"] = $instanceId;
	}

	public function getInstanceId()
	{
		return $this->instanceId;
	}

	public function setInternetMaxBandwidthIn($internetMaxBandwidthIn)
	{
		$this->internetMaxBandwidthIn = $internetMaxBandwidthIn;
		$this->apiParas["InternetMaxBandwidthIn"] = $internetMaxBandwidthIn;
	}

	public function getInternetMaxBandwidthIn()
	{
		return $this->internetMaxBandwidthIn;
	}

	public function setInternetMaxBandwidthOut($internetMaxBandwidthOut)
	{
		$this->internetMaxBandwidthOut = $internetMaxBandwidthOut;
		$this->apiParas["InternetMaxBandwidthOut"] = $internetMaxBandwidthOut;
	}

	public function getInternetMaxBandwidthOut()
	{
		return $this->internetMaxBandwidthOut;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyInstanceNetworkSpec.2014-05-26";
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
