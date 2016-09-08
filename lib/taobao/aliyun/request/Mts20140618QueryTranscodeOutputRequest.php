<?php
/**
 * TOP API: mts.aliyuncs.com.QueryTranscodeOutput.2014-06-18 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Mts20140618QueryTranscodeOutputRequest
{
	/** 
	 * 媒资ID
	 **/
	private $mediaId;
	
	/** 
	 * 转码模板列表
	 **/
	private $templateIds;
	
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

	public function setTemplateIds($templateIds)
	{
		$this->templateIds = $templateIds;
		$this->apiParas["TemplateIds"] = $templateIds;
	}

	public function getTemplateIds()
	{
		return $this->templateIds;
	}

	public function getApiMethodName()
	{
		return "mts.aliyuncs.com.QueryTranscodeOutput.2014-06-18";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->mediaId,"mediaId");
		RequestCheckUtil::checkNotNull($this->templateIds,"templateIds");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
