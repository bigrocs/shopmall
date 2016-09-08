<?php
/**
 * TOP API: ecs.aliyuncs.com.ModifyInstanceAttribute.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526ModifyInstanceAttributeRequest
{
	/** 
	 * 实例说明
	 **/
	private $description;
	
	/** 
	 * 表示实例的主机名。必须以字母开始，并只可以含有字母、“-”字符。Windows系统最长为15字节，Linux系统最长为30字节
	 **/
	private $hostName;
	
	/** 
	 * 指定的实例ID
	 **/
	private $instanceId;
	
	/** 
	 * 实例名称
	 **/
	private $instanceName;
	
	/** 
	 * 重置为用户指定的密码，密码只能是数字或者英文
字母。长度必须在6~30个英文字符
	 **/
	private $password;
	
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

	public function setHostName($hostName)
	{
		$this->hostName = $hostName;
		$this->apiParas["HostName"] = $hostName;
	}

	public function getHostName()
	{
		return $this->hostName;
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

	public function setInstanceName($instanceName)
	{
		$this->instanceName = $instanceName;
		$this->apiParas["InstanceName"] = $instanceName;
	}

	public function getInstanceName()
	{
		return $this->instanceName;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		$this->apiParas["Password"] = $password;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.ModifyInstanceAttribute.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->instanceId,"instanceId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
