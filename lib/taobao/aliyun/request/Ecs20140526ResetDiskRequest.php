<?php
/**
 * TOP API: ecs.aliyuncs.com.ResetDisk.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ResetDiskRequest
{
	/** 
	 * 指定的磁盘设备ID
	 **/
	private $diskId;
	
	/** 
	 * 恢复磁盘的快照ID
	 **/
	private $snapshotId;
	
	private $apiParas = array();
	
	public function setDiskId($diskId)
	{
		$this->diskId = $diskId;
		$this->apiParas["DiskId"] = $diskId;
	}

	public function getDiskId()
	{
		return $this->diskId;
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
		return "ecs.aliyuncs.com.ResetDisk.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->diskId,"diskId");
		RequestCheckUtil::checkNotNull($this->snapshotId,"snapshotId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
