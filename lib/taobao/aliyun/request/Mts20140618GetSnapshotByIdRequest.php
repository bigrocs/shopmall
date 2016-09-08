<?php
/**
 * TOP API: mts.aliyuncs.com.GetSnapshotById.2014-06-18 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Mts20140618GetSnapshotByIdRequest
{
	/** 
	 * 媒资ID
	 **/
	private $mediaId;
	
	/** 
	 * 需要获取的截图在视频中的时间点,单位毫秒
	 **/
	private $time;
	
	private $apiParas = array();
	
	public function setMediaId($mediaId)
	{
		$this->mediaId = $mediaId;
		$this->apiParas["MediaId"] = $mediaId;
	}

	public function getMediaId()
	{
		return $this->mediaId;
	}

	public function setTime($time)
	{
		$this->time = $time;
		$this->apiParas["Time"] = $time;
	}

	public function getTime()
	{
		return $this->time;
	}

	public function getApiMethodName()
	{
		return "mts.aliyuncs.com.GetSnapshotById.2014-06-18";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->mediaId,"mediaId");
		RequestCheckUtil::checkNotNull($this->time,"time");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
