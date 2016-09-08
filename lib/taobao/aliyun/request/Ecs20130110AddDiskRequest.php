<?php
/**
 * TOP API: ecs.aliyuncs.com.AddDisk.2013-01-10 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20130110AddDiskRequest
{
	/** 
	 * 用于保证请求的幂等性。由客户端生成该参数值，要保证在不同请求间唯一，最大不值过64个ASCII字符。 具体参见附录：如何保证幂等性。
	 **/
	private $clientToken;
	
	/** 
	 * 指定实例的名称
	 **/
	private $instanceId;
	
	/** 
	 * 需要增加的磁盘大小，以GB为单位，取值范围为5~2048
	 **/
	private $size;
	
	/** 
	 * 如不传则增加空数据盘，若指定SnapshotId，磁盘的大小以Snapshot大小为准。Snapshot只能是数据盘的Snapshot。
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

	public function setInstanceId($instanceId)
	{
		$this->instanceId = $instanceId;
		$this->apiParas["InstanceId"] = $instanceId;
	}

	public function getInstanceId()
	{
		return $this->instanceId;
	}

	public function setSize($size)
	{
		$this->size = $size;
		$this->apiParas["Size"] = $size;
	}

	public function getSize()
	{
		return $this->size;
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
		return "ecs.aliyuncs.com.AddDisk.2013-01-10";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
		RequestCheckUtil::checkNotNull($this->size,"size");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
