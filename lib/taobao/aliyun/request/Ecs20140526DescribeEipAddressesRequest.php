<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeEipAddresses.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DescribeEipAddressesRequest
{
	/** 
	 * 申请Id
	 **/
	private $allocationId;
	
	/** 
	 * 弹性IP地址
	 **/
	private $eipAddress;
	
	/** 
	 * 实例状态列表的页码，起始值为1，默认值为1<br /> 支持最小值为：1
	 **/
	private $pageNumber;
	
	/** 
	 * 分页查询时设置的每页行数，最大值50行，默认为10<br /> 支持最大值为：50
	 **/
	private $pageSize;
	
	/** 
	 * 需要查询的地域
	 **/
	private $regionId;
	
	/** 
	 * Eip状态，单选 InUse | Available
	 **/
	private $status;
	
	private $apiParas = array();
	
	public function setAllocationId($allocationId)
	{
		$this->allocationId = $allocationId;
		$this->apiParas["AllocationId"] = $allocationId;
	}

	public function getAllocationId()
	{
		return $this->allocationId;
	}

	public function setEipAddress($eipAddress)
	{
		$this->eipAddress = $eipAddress;
		$this->apiParas["EipAddress"] = $eipAddress;
	}

	public function getEipAddress()
	{
		return $this->eipAddress;
	}

	public function setPageNumber($pageNumber)
	{
		$this->pageNumber = $pageNumber;
		$this->apiParas["PageNumber"] = $pageNumber;
	}

	public function getPageNumber()
	{
		return $this->pageNumber;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["PageSize"] = $pageSize;
	}

	public function getPageSize()
	{
		return $this->pageSize;
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

	public function setStatus($status)
	{
		$this->status = $status;
		$this->apiParas["Status"] = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DescribeEipAddresses.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMinValue($this->pageNumber,1,"pageNumber");
		RequestCheckUtil::checkMaxValue($this->pageSize,50,"pageSize");
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
