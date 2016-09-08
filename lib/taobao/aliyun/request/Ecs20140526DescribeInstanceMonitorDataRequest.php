<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeInstanceMonitorData.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DescribeInstanceMonitorDataRequest
{
	/** 
	 * 获取数据的结束时间点：ISO8601 表示法，并使用UTC时间。格式为： YYYY-MM-DDThh:mm:ssZ。如果秒不是00，则自动取为下一分钟开始时
	 **/
	private $endTime;
	
	/** 
	 * 指定监控的实例ID
	 **/
	private $instanceId;
	
	/** 
	 * 获取监控数据的精度，默认60秒，只能为60的倍数。
	 **/
	private $period;
	
	/** 
	 * 获取数据的起始时间点：ISO8601 表示法，并使用UTC时间。格式为： YYYY-MM-DDThh:mm:ssZ。如果秒不是00，则自动取为下一分钟开始时
	 **/
	private $startTime;
	
	private $apiParas = array();
	
	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
		$this->apiParas["EndTime"] = $endTime;
	}

	public function getEndTime()
	{
		return $this->endTime;
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

	public function setPeriod($period)
	{
		$this->period = $period;
		$this->apiParas["Period"] = $period;
	}

	public function getPeriod()
	{
		return $this->period;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
		$this->apiParas["StartTime"] = $startTime;
	}

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.DescribeInstanceMonitorData.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->endTime,"endTime");
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
		RequestCheckUtil::checkNotNull($this->startTime,"startTime");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
