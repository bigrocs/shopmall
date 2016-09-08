<?php
/**
 * TOP API: mts.aliyuncs.com.CreateTranscodeJob.2014-06-18 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Mts20140618CreateTranscodeJobRequest
{
	/** 
	 * 媒资ID
	 **/
	private $mediaId;
	
	/** 
	 * 转码模板ID
	 **/
	private $templateId;
	
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

	public function setTemplateId($templateId)
	{
		$this->templateId = $templateId;
		$this->apiParas["TemplateId"] = $templateId;
	}

	public function getTemplateId()
	{
		return $this->templateId;
	}

	public function getApiMethodName()
	{
		return "mts.aliyuncs.com.CreateTranscodeJob.2014-06-18";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->mediaId,"mediaId");
		RequestCheckUtil::checkNotNull($this->templateId,"templateId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
