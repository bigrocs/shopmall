<?php
/**
 * TOP API: slb.aliyuncs.com.AddBackendServers.2014-05-15 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Slb20140515AddBackendServersRequest
{
	/** 
	 * BackendServers 数组格式，需要添加的后端服务器列表，最多20个
	 **/
	private $backendServers;
	
	/** 
	 * slb id
	 **/
	private $loadBalancerId;
	
	private $apiParas = array();
	
	public function setBackendServers($backendServers)
	{
		$this->backendServers = $backendServers;
		$this->apiParas["BackendServers"] = $backendServers;
	}

	public function getBackendServers()
	{
		return $this->backendServers;
	}

	public function setLoadBalancerId($loadBalancerId)
	{
		$this->loadBalancerId = $loadBalancerId;
		$this->apiParas["LoadBalancerId"] = $loadBalancerId;
	}

	public function getLoadBalancerId()
	{
		return $this->loadBalancerId;
	}

	public function getApiMethodName()
	{
		return "slb.aliyuncs.com.AddBackendServers.2014-05-15";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->loadBalancerId,"loadBalancerId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
