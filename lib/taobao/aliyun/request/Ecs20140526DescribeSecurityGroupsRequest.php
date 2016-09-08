<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeSecurityGroups.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DescribeSecurityGroupsRequest
{
	/** 
	 * 当前页码，起始值为1，默认值为1<br /> 支持最小值为：1
	 **/
	private $pageNumber;
	
	/** 
	 * 分页查询时设置的每页行数，最大值50，默认值为10<br /> 支持最大值为：50<br /> 支持最小值为：1
	 **/
	private $pageSize;
	
	/** 
	 * 数据中心id
	 **/
	private $regionId;
	
	/** 
	 * 虚拟专有网络Id
	 **/
	private $vpcId;
	
	private $apiParas = array();
	
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
		return "ecs.aliyuncs.com.DescribeSecurityGroups.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMinValue($this->pageNumber,1,"pageNumber");
		RequestCheckUtil::checkMaxValue($this->pageSize,50,"pageSize");
		RequestCheckUtil::checkMinValue($this->pageSize,1,"pageSize");
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
