<?php
/**
 * TOP API: ecs.aliyuncs.com.CreateImage.2013-01-10 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20130110CreateImageRequest
{
	/** 
	 * 用于保证请求的幂等性。由客户端生成该参数值，要保证在不同请求间唯一，最大不值过64个ASCII字符。 具体参见附录：如何保证幂等性。
	 **/
	private $clientToken;
	
	/** 
	 * 镜像的描述信息，长度限制在1~200个英文字符
	 **/
	private $description;
	
	/** 
	 * 镜像版本号，长度限制在1~40个英文字符
	 **/
	private $imageVersion;
	
	/** 
	 * 镜像所在的Region ID
	 **/
	private $regionId;
	
	/** 
	 * 快照ID。从指定的快照创建自定义镜像。
	 **/
	private $snapshotId;
	
	private $apiParas = array();
	
	public function setClientToken($clientToken)
	{
		$this->clientToken = $clientToken;
		$this->apiParas["ClientToken"] = $clientToken;
	}

	public function getClientToken()
	{
		return $this->clientToken;
	}

	public function setDescription($description)
	{
		$this->description = $description;
		$this->apiParas["Description"] = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setImageVersion($imageVersion)
	{
		$this->imageVersion = $imageVersion;
		$this->apiParas["ImageVersion"] = $imageVersion;
	}

	public function getImageVersion()
	{
		return $this->imageVersion;
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

	public function setSnapshotId($snapshotId)
	{
		$this->snapshotId = $snapshotId;
		$this->apiParas["SnapshotId"] = $snapshotId;
	}

	public function getSnapshotId()
	{
		return $this->snapshotId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.CreateImage.2013-01-10";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
		RequestCheckUtil::checkNotNull($this->snapshotId,"snapshotId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
