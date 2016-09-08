<?php
/**
 * TOP API: ecs.aliyuncs.com.DescribeImages.2014-05-26 request
 * 
 * @author auto create
 * @since 1.0, 2014-09-03 12:48:17
 */
class Ecs20140526DescribeImagesRequest
{
	/** 
	 * 镜像系统类型：i386 | x86_64
	 **/
	private $architecture;
	
	/** 
	 * 镜像ID，可以输入多个，以”,”分割
	 **/
	private $imageId;
	
	/** 
	 * 镜像的名称
	 **/
	private $imageName;
	
	/** 
	 * 镜像所有者别名。
取值：
？	System| self| others| marketplace
默认值:无，表示返回system+self+others
不设置该参数说明不使用该参数进行过滤条件
取值说明:
system 阿里云官方提供镜像
self 用户自定义镜像
others 非自己的可用镜像
？	marketplace 镜像市场提供的镜像
	 **/
	private $imageOwnerAlias;
	
	/** 
	 * 实例状态列表的页码，起始值为1，默认值为1<br /> 支持最小值为：1
	 **/
	private $pageNumber;
	
	/** 
	 * 分页查询时设置的每页行数，最大值50行，默认为10<br /> 支持最大值为：50<br /> 支持最小值为：1
	 **/
	private $pageSize;
	
	/** 
	 * 实例所属于的Region ID
	 **/
	private $regionId;
	
	/** 
	 * 创建镜像的快照ID
	 **/
	private $snapshotId;
	
	private $apiParas = array();
	
	public function setArchitecture($architecture)
	{
		$this->architecture = $architecture;
		$this->apiParas["Architecture"] = $architecture;
	}

	public function getArchitecture()
	{
		return $this->architecture;
	}

	public function setImageId($imageId)
	{
		$this->imageId = $imageId;
		$this->apiParas["ImageId"] = $imageId;
	}

	public function getImageId()
	{
		return $this->imageId;
	}

	public function setImageName($imageName)
	{
		$this->imageName = $imageName;
		$this->apiParas["ImageName"] = $imageName;
	}

	public function getImageName()
	{
		return $this->imageName;
	}

	public function setImageOwnerAlias($imageOwnerAlias)
	{
		$this->imageOwnerAlias = $imageOwnerAlias;
		$this->apiParas["ImageOwnerAlias"] = $imageOwnerAlias;
	}

	public function getImageOwnerAlias()
	{
		return $this->imageOwnerAlias;
	}

	public function setPageNumber($pageNumber)
	{
		$this->pageNumber = $pageNumber;
		$this->apiParas["PageNumber"] = $pageNumber;
	}

	public function getPageNumber()
	{
		return $this->pageNumber;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["PageSize"] = $pageSize;
	}

	public function getPageSize()
	{
		return $this->pageSize;
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
		return "ecs.aliyuncs.com.DescribeImages.2014-05-26";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMinValue($this->pageNumber,1,"pageNumber");
		RequestCheckUtil::checkMaxValue($this->pageSize,50,"pageSize");
		RequestCheckUtil::checkMinValue($this->pageSize,1,"pageSize");
		RequestCheckUtil::checkNotNull($this->regionId,"regionId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
