<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyDiskAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyDiskAttributeRequest
{
	/** 
	 * 删除实例的时候是否删除此磁盘
	 **/
	private $deleteWithInstance;
	
	/** 
	 * 磁盘描述，不填则保持原值，默认值为空，[0,256]英文或中文字符，磁盘描述会展示在控制台。不能以http://和https://开头。
	 **/
	private $description;
	
	/** 
	 * 磁盘ID
	 **/
	private $diskId;
	
	/** 
	 * 磁盘名称，不填则原值，默认值为空，[0,128]英文或中文字符，磁盘名称会展示在控制台。不能以http://和https://开头。
	 **/
	private $diskName;
	
	private $apiParas = array();
	
	public function setDeleteWithInstance($deleteWithInstance)
	{
		$this->deleteWithInstance = $deleteWithInstance;
		$this->apiParas["DeleteWithInstance"] = $deleteWithInstance;
	}

	public function getDeleteWithInstance()
	{
		return $this->deleteWithInstance;
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

	public function setDiskId($diskId)
	{
		$this->diskId = $diskId;
		$this->apiParas["DiskId"] = $diskId;
	}

	public function getDiskId()
	{
		return $this->diskId;
	}

	public function setDiskName($diskName)
	{
		$this->diskName = $diskName;
		$this->apiParas["DiskName"] = $diskName;
	}

	public function getDiskName()
	{
		return $this->diskName;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyDiskAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->diskId,"diskId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
