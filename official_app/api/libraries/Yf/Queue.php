<?php
/**
 * 队列 管理者类
 * 
 * 负责初始化并存放所有的队列类。
 * 
 * @category   Framework
 * @package    队列
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Queue
{
    public static $keyPre   = '_msg_|';

    static public function send($queue, $data)
    {
        self::$keyPre   = Yf_Registry::get('queue_pre_key');
        $queue = self::$keyPre . $queue;

		if ('WIN' != substr(PHP_OS, 0, 3))
		{
			return Yf_Queue_MsgQueue::send($queue, $data);
		}
		else
		{
            return Yf_Queue_Redis::send($queue, $data);
		}
    }

    static public function receive($queue)
    {
        self::$keyPre   = Yf_Registry::get('queue_pre_key');
        $queue = self::$keyPre . $queue;

		if ('WIN' != substr(PHP_OS, 0, 3))
		{
			$data = Yf_Queue_MsgQueue::receive($queue);
		}
		else
		{
            $data = Yf_Queue_Redis::receive($queue);
		}

		return $data;
    }

    //不会改动内容  
	public static function all($key)
    {
        self::$keyPre   = Yf_Registry::get('queue_pre_key');
        $key = self::$keyPre . $key;

		if ('WIN' != substr(PHP_OS, 0, 3))
		{
			$data = Yf_Queue_MsgQueue::all($key);
		}
		else
		{
			//
			//
            if (true)
            {
			    $data = Yf_Queue_Redis::all($key);
            }
		}

		return $data;
	}

    public static function remove($queue)
    {
        self::$keyPre   = Yf_Registry::get('queue_pre_key');
        $queue = self::$keyPre . $queue;

        if ('WIN' != substr(PHP_OS, 0, 3))
        {
            $rs = Yf_Queue_MsgQueue::remove($queue);
        }
        else
        {
            $rs = Yf_Queue_Redis::remove($queue);
        }

        return $rs;
    }

	public static function msgStat($queue)
    {
        self::$keyPre   = Yf_Registry::get('queue_pre_key');
        $queue = self::$keyPre . $queue;

		if ('WIN' != substr(PHP_OS, 0, 3))
		{
			$queue_status = Yf_Queue_MsgQueue::msgStat($queue);
		}
		else
		{
            $queue_status['msg_qnum'] = Yf_Queue_Redis::size($queue);
		}

		return $queue_status;
	}

	public static function msgStatQueueNum($queue)
    {
        $queue_status = self::msgStat($queue);

        return $queue_status['msg_qnum'];
	}

    private function __construct()
    {
    }
}
?>