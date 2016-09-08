<?php
/**
 * TOP API: ecs.aliyuncs.com.AllocateEipAddress.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526AllocateEipAddressRequest
{
	/** 
	 * 弹性IP地址的限速，如果不指定，默认为5Mbps
	 **/
	private $bandwidth;
	
	/** 
	 * PayByBandwidth 和 PayByTraffic，默认是PayByBandwidth
	 **/
	private $internetChargeType;
	
	/** 
	 * 申请的弹性IP地址所在的地域
	 **/
	private $regionId;
	
	private $apiParas = array();
	
	public function setBandwidth($bandwidth)
	{
		$this->bandwidth = $bandwidth;
		$this->apiParas["Bandwidth"] = $bandwidth;
	}

	public function getBandwidth()
	{
		return $this->bandwidth;
	}

	public function setInternetChargeType($internetChargeType)
	{
		$this->internetChargeType = $internetChargeType;
		$this->apiParas["InternetChargeType"] = $internetChargeType;
	}

	public function getInternetChargeType()
	{
		return $this->internetChargeType;
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

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.AllocateEipAddress.2014-05-26";
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
