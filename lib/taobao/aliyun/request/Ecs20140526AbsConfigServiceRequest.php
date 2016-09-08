<?php
/**
 * TOP API: ecs.aliyuncs.com.AbsConfigService.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526AbsConfigServiceRequest
{
	/** 
	 * configKey
	 **/
	private $configKey;
	
	private $apiParas = array();
	
	public function setConfigKey($configKey)
	{
		$this->configKey = $configKey;
		$this->apiParas["configKey"] = $configKey;
	}

	public function getConfigKey()
	{
		return $this->configKey;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.AbsConfigService.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->configKey,"configKey");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
