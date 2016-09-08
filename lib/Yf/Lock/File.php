<?php

class Yf_Lock_File
{
	//文件锁存放路径
	private $path = null;

	//文件句柄
	private $fp = null;

	//锁粒度,设置越大粒度越小
	private $hashNum = 1000;

	//cache key
	private $name;

	/**
	 * 构造函数
	 * 传入锁的存放路径，及cache key的名称，这样可以进行并发
	 * @param string $path 锁的存放目录，以"/"结尾
	 * @param string $name cache key
	 */
	public function __construct($name, $lock_path = null)
	{
		$this->path = $lock_path . DIRECTORY_SEPARATOR . ($this->_mycrc32($name) % $this->hashNum) . '.lock';

		$this->name = $name;
	}

	/**
	 * crc32
	 * crc32封装
	 * @param int $string
	 * @return int
	 */
	private function _mycrc32($string)
	{
		$crc = abs(crc32($string));
		if ($crc & 0x80000000)
		{
			$crc ^= 0xffffffff;
			$crc += 1;
		}

		return $crc;
	}

	/**
	 * 加锁
	 * Enter description here ...
	 */
	public function lock()
	{
		//配置目录权限可写
		$this->fp = fopen($this->path, 'w+');
		if ($this->fp === false)
		{
			return false;
		}

		usleep(100);

		return flock($this->fp, LOCK_EX);
	}

	/**
	 * 解锁
	 * Enter description here ...
	 */
	public function unlock()
	{
		if ($this->fp !== false)
		{
			flock($this->fp, LOCK_UN);
			clearstatcache();
		}

		//进行关闭
		fclose($this->fp);
	}
}