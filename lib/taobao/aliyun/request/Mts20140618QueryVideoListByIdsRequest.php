<?php
/**
 * TOP API: mts.aliyuncs.com.QueryVideoListByIds.2014-06-18 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Mts20140618QueryVideoListByIdsRequest
{
	/** 
	 * 视频ID列表
	 **/
	private $mediaIds;
	
	private $apiParas = array();
	
	public function setMediaIds($mediaIds)
	{
		$this->mediaIds = $mediaIds;
		$this->apiParas["MediaIds"] = $mediaIds;
	}

	public function getMediaIds()
	{
		return $this->mediaIds;
	}

	public function getApiMethodName()
	{
		return "mts.aliyuncs.com.QueryVideoListByIds.2014-06-18";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->mediaIds,"mediaIds");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
