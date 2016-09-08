<?php
/**
 * TOP API: ecs.aliyuncs.com.DeleteRouteEntry.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DeleteRouteEntryRequest
{
	/** 
	 * 新增路由条目的目的网段
	 **/
	private $destinationCidrBlock;
	
	/** 
	 * NextHopId
	 **/
	private $nextHopId;
	
	/** 
	 * 虚拟路由表Id
	 **/
	private $routeTableId;
	
	private $apiParas = array();
	
	public function setDestinationCidrBlock($destinationCidrBlock)
	{
		$this->destinationCidrBlock = $destinationCidrBlock;
		$this->apiParas["DestinationCidrBlock"] = $destinationCidrBlock;
	}

	public function getDestinationCidrBlock()
	{
		return $this->destinationCidrBlock;
	}

	public function setNextHopId($nextHopId)
	{
		$this->nextHopId = $nextHopId;
		$this->apiParas["NextHopId"] = $nextHopId;
	}

	public function getNextHopId()
	{
		return $this->nextHopId;
	}

	public function setRouteTableId($routeTableId)
	{
		$this->routeTableId = $routeTableId;
		$this->apiParas["RouteTableId"] = $routeTableId;
	}

	public function getRouteTableId()
	{
		return $this->routeTableId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DeleteRouteEntry.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->destinationCidrBlock,"destinationCidrBlock");
		RequestCheckUtil::checkNotNull($this->nextHopId,"nextHopId");
		RequestCheckUtil::checkNotNull($this->routeTableId,"routeTableId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
