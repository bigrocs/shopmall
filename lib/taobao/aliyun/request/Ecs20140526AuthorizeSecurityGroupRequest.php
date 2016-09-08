<?php
/**
 * TOP API: ecs.aliyuncs.com.AuthorizeSecurityGroup.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526AuthorizeSecurityGroupRequest
{
	/** 
	 * P 协议，取值：tcp|udp|icmp|gre|all； 
All 表示同时支持四种协议
	 **/
	private $ipProtocol;
	
	/** 
	 * 网络类型，取值：internet|intranet； 
默认值为internet
	 **/
	private $nicType;
	
	/** 
	 * 授权策略，参数值可为：accept（接受访问）、
drop（访问时丢弃包）、reject（访问时拒
绝包） 
默认值为：accept
	 **/
	private $policy;
	
	/** 
	 * IP 协议相关的端口号范围，tcp、udp协议
的默认端口号，取值范围为1~65535；例
如“1/200”意思是端口号范围为1~200，
若输入值为：“200/1”接口调用将报错。 
icmp 协议时端口号范围值为-1/-1，gre协
议时端口号范围值为-1/-1，当
IpProtocol为all时端口号范围值为-1/-1；取值范围
	 **/
	private $portRange;
	
	/** 
	 * 安全组所属Region ID
	 **/
	private $regionId;
	
	/** 
	 * 安全组编码
	 **/
	private $securityGroupId;
	
	/** 
	 * 授权可访问目标安全组的源IP 地址范围
（采用CIDR格式来指定IP 地址范围），
默认值为0.0.0.0/0（表示不受限制），其
他支持的格式如10.159.6.18/12、
10.159.6.186、或
10.159.6.186-10.159.6.201（IP 区间）
	 **/
	private $sourceCidrIp;
	
	/** 
	 * 授权同一Region内可访问目标安全组的源
安全组编码，SourceGroupId或者
SourceCidrIp参数必须设置一项，如果
两项都设置，则默认对SourceCidrIp授
权。指定了该字段之后，NicType只能选
择intranet
	 **/
	private $sourceGroupId;
	
	private $apiParas = array();
	
	public function setIpProtocol($ipProtocol)
	{
		$this->ipProtocol = $ipProtocol;
		$this->apiParas["IpProtocol"] = $ipProtocol;
	}

	public function getIpProtocol()
	{
		return $this->ipProtocol;
	}

	public function setNicType($nicType)
	{
		$this->nicType = $nicType;
		$this->apiParas["NicType"] = $nicType;
	}

	public function getNicType()
	{
		return $this->nicType;
	}

	public function setPolicy($policy)
	{
		$this->policy = $policy;
		$this->apiParas["Policy"] = $policy;
	}

	public function getPolicy()
	{
		return $this->policy;
	}

	public function setPortRange($portRange)
	{
		$this->portRange = $portRange;
		$this->apiParas["PortRange"] = $portRange;
	}

	public function getPortRange()
	{
		return $this->portRange;
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

	public function setSecurityGroupId($securityGroupId)
	{
		$this->securityGroupId = $securityGroupId;
		$this->apiParas["SecurityGroupId"] = $securityGroupId;
	}

	public function getSecurityGroupId()
	{
		return $this->securityGroupId;
	}

	public function setSourceCidrIp($sourceCidrIp)
	{
		$this->sourceCidrIp = $sourceCidrIp;
		$this->apiParas["SourceCidrIp"] = $sourceCidrIp;
	}

	public function getSourceCidrIp()
	{
		return $this->sourceCidrIp;
	}

	public function setSourceGroupId($sourceGroupId)
	{
		$this->sourceGroupId = $sourceGroupId;
		$this->apiParas["SourceGroupId"] = $sourceGroupId;
	}

	public function getSourceGroupId()
	{
		return $this->sourceGroupId;
	}

	public function getApiMethodName()
	{
		return "ecs.aliyuncs.com.AuthorizeSecurityGroup.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->ipProtocol,"ipProtocol");
		RequestCheckUtil::checkNotNull($this->portRange,"portRange");
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
		RequestCheckUtil::checkNotNull($this->securityGroupId,"securityGroupId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
