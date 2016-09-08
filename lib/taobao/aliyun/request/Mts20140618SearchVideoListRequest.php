<?php
/**
 * TOP API: mts.aliyuncs.com.SearchVideoList.2014-06-18 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Mts20140618SearchVideoListRequest
{
	/** 
	 * 当前页号
	 **/
	private $pageNumber;
	
	/** 
	 * 分页查询时设置的每页行数页面大小<br /> 支持最大值为：100<br /> 支持最小值为：1
	 **/
	private $pageSize;
	
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

	public function getApiMethodName()
	{
		return "mts.aliyuncs.com.SearchVideoList.2014-06-18";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMaxValue($this->pageSize,100,"pageSize");
		RequestCheckUtil::checkMinValue($this->pageSize,1,"pageSize");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
