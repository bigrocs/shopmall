<?php
/**
 * TOP API: mts.aliyuncs.com.AddVideo.2014-06-18 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Mts20140618AddVideoRequest
{
	/** 
	 * 视频的描述<br /> 支持最大长度为：512<br /> 支持的最大列表长度为：512
	 **/
	private $description;
	
	/** 
	 * 用户授权给视频服务访问的OSS Bucket下的视频文件地址<br /> 支持最大长度为：1152<br /> 支持的最大列表长度为：1152
	 **/
	private $inputFileUrl;
	
	/** 
	 * 视频标签，如果有多个用逗号分隔<br /> 支持最大长度为：256<br /> 支持的最大列表长度为：256
	 **/
	private $tags;
	
	/** 
	 * 视频标题<br /> 支持最大长度为：256<br /> 支持的最大列表长度为：256
	 **/
	private $title;
	
	private $apiParas = array();
	
	public function setDescription($description)
	{
		$this->description = $description;
		$this->apiParas["Description"] = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setInputFileUrl($inputFileUrl)
	{
		$this->inputFileUrl = $inputFileUrl;
		$this->apiParas["InputFileUrl"] = $inputFileUrl;
	}

	public function getInputFileUrl()
	{
		return $this->inputFileUrl;
	}

	public function setTags($tags)
	{
		$this->tags = $tags;
		$this->apiParas["Tags"] = $tags;
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		$this->apiParas["Title"] = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getApiMethodName()
	{
		return "mts.aliyuncs.com.AddVideo.2014-06-18";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->description,"description");
		RequestCheckUtil::checkMaxLength($this->description,512,"description");
		RequestCheckUtil::checkNotNull($this->inputFileUrl,"inputFileUrl");
		RequestCheckUtil::checkMaxLength($this->inputFileUrl,1152,"inputFileUrl");
		RequestCheckUtil::checkNotNull($this->tags,"tags");
		RequestCheckUtil::checkMaxLength($this->tags,256,"tags");
		RequestCheckUtil::checkNotNull($this->title,"title");
		RequestCheckUtil::checkMaxLength($this->title,256,"title");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
