<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeVSwitches.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DescribeVSwitchesRequest
{
	/** 
	 * 实例状态列表的页码，起始值为1，默认值为1<br /> 支持最小值为：1
	 **/
	private $pageNumber;
	
	/** 
	 * 分页查询时设置的每页行数，最大值50行，默认为10<br /> 支持最大值为：50
	 **/
	private $pageSize;
	
	/** 
	 * 需要查询的虚拟交换机的Id
	 **/
	private $vSwitchId;
	
	/** 
	 * 虚拟网络Id
	 **/
	private $vpcId;
	
	/** 
	 * 可用区Id
	 **/
	private $zoneId;
	
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

	public function setvSwitchId($vSwitchId)
	{
		$this->vSwitchId = $vSwitchId;
		$this->apiParas["VSwitchId"] = $vSwitchId;
	}

	public function getvSwitchId()
	{
		return $this->vSwitchId;
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

	public function setZoneId($zoneId)
	{
		$this->zoneId = $zoneId;
		$this->apiParas["ZoneId"] = $zoneId;
	}

	public function getZoneId()
	{
		return $this->zoneId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DescribeVSwitches.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMinValue($this->pageNumber,1,"pageNumber");
		RequestCheckUtil::checkMaxValue($this->pageSize,50,"pageSize");
		RequestCheckUtil::checkNotNull($this->vpcId,"vpcId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
