<?php
/**
 * TOP API: ecs.aliyuncs.com.CreateSnapshot.2013-01-10 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20130110CreateSnapshotRequest
{
	/** 
	 * 用于保证请求的幂等性。由客户端生成该参数值，要保证在不同请求间唯一，最大不值过64个ASCII字符。 具体参见附录：如何保证幂等性。
	 **/
	private $clientToken;
	
	/** 
	 * 指定的磁盘ID
	 **/
	private $diskId;
	
	/** 
	 * 指定的实例ID
	 **/
	private $instanceId;
	
	/** 
	 * 快照的显示名称，由字母、数字、"-"组成，长度取值范围为[0,300]
	 **/
	private $snapshotName;
	
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

	public function setDiskId($diskId)
	{
		$this->diskId = $diskId;
		$this->apiParas["DiskId"] = $diskId;
	}

	public function getDiskId()
	{
		return $this->diskId;
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

	public function setSnapshotName($snapshotName)
	{
		$this->snapshotName = $snapshotName;
		$this->apiParas["SnapshotName"] = $snapshotName;
	}

	public function getSnapshotName()
	{
		return $this->snapshotName;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.CreateSnapshot.2013-01-10";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->diskId,"diskId");
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
