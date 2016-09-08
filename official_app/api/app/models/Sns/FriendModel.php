<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_FriendModel extends Sns_Friend
{
    public $_multiCondConcernSingle   = array('uid'=>null, 'state'=>1);  //单方关注
    public $_multiCondConcern         = array('uid'=>null, 'state'=>2);  //双方关注


    public function getFriendSingle($user_id)
    {
        $this->_multiCondConcernSingle['uid'] = $user_id;

        $id_rows = $this->getKeyByMultiCond($this->_multiCondConcernSingle);

        $friend_rows = array();

        if ($id_rows)
        {
            $friend_rows = $this->getFriend($id_rows);
        }

		$from_id_row = array_filter_key('fuid', $friend_rows);


		//读取发送者账户信息
		$from_user_rows = array();

		if ($from_id_row)
		{
			$sql = 'SELECT  * FROM ' . TABEL_PREFIX . 'member WHERE userid IN (' . implode(',', $from_id_row) . ')';
			$from_user_tmp_rows = $this->sql->getAll($sql);

			if ($from_user_tmp_rows)
			{
				foreach ($from_user_tmp_rows as $v)
				{
					$from_user_rows[$v['userid']] = $v;
				}
			}
		}

		foreach ($friend_rows as $k => $v)
		{
			//过滤掉自己发送的消息状态的修改
			$v['user'] = $v['fnickname'] ? $v['fnickname'] : $from_user_rows[$v['fuid']]['user'];
			$v['logo'] = $from_user_rows[$v['fuid']]['logo'];

			$v['online'] = self::getUserOnline($v['fuid']);
			$friend_rows[$k] = $v;
		}

        return $friend_rows;
    }

    public function getFriendAll($user_id)
    {
        $this->_multiCondConcern['uid'] = $user_id;
        $this->_multiCondConcernSingle['uid'] = $user_id;

        $id_rows = $this->getKeyByMultiCond($this->_multiCondConcern);
        //$id_single_rows = $this->getKeyByMultiCond($this->_multiCondConcernSingle);

        //$id_rows = array_merge($id_rows, $id_single_rows);

        $friend_rows = array();

        if ($id_rows)
        {
            $friend_rows = $this->getFriend($id_rows);
        }

		$from_id_row = array_filter_key('fuid', $friend_rows);

		//读取发送者账户信息
		$from_user_rows = array();

		if ($from_id_row)
		{
			$sql = 'SELECT  * FROM ' . TABEL_PREFIX . 'member WHERE userid IN (' . implode(',', $from_id_row) . ')';
			$from_user_tmp_rows = $this->sql->getAll($sql);

			if ($from_user_tmp_rows)
			{
				foreach ($from_user_tmp_rows as $v)
				{
					$from_user_rows[$v['userid']] = $v;
				}
			}
		}

		foreach ($friend_rows as $k => $v)
		{
			//过滤掉自己发送的消息状态的修改
			if (isset($from_user_rows[$v['fuid']]))
			{
				$v['user'] = $v['fnickname'] ? $v['fnickname'] : $from_user_rows[$v['fuid']]['user'];


				$v['logo'] = $from_user_rows[$v['fuid']]['logo'];

				$v['online'] = self::getUserOnline($v['fuid']);
			}
			else
			{
				$v['addtime'] = 0;
				$v['fuid'] = 0;
				$v['fuimg'] = 'image/default/avatar.png';
				$v['funame'] = '管理员';
				$v['id'] = 0;
				$v['logo'] = 'image/default/avatar.png';
				$v['online'] = 1;
				$v['state'] = 1;
				$v['uid'] = 0;
				$v['uimg'] = 'image/default/avatar.png';
				$v['uname'] = '管理员';
				$v['user'] = '管理员';

				$v['user'] = '管理员';
				$v['logo'] = 'image/default/avatar.png';
			}

			$friend_rows[$k] = $v;
		}


        return $friend_rows;
    }

	//极端低效方法，先满足功能需求，后续有时间优化，更改实现方式
	public function getFriendRecent($user_id)
	{
		$date = date('Y-m-d H:i:s', time()-3600 * 24 * 10);

		$sql = '
					SELECT
						id,
						uid,
						touserid,
						fromuserid,
						con,
						date
					FROM
						' . TABEL_PREFIX . 'message
					WHERE
						uid = ' . $user_id . ' and date >= "' . $date . '"
					GROUP BY touserid, fromuserid
			';

		$from_user_tmp_rows = $this->sql->getAll($sql);

		$from_id_row = array();

		//
		foreach ($from_user_tmp_rows as $from_user_tmp_row)
		{
			if ($user_id != $from_user_tmp_row['touserid'] && !in_array($from_user_tmp_row['touserid'], $from_id_row))
			{
				array_push($from_id_row, $from_user_tmp_row['touserid']);
			}

			if ($user_id != $from_user_tmp_row['fromuserid'] && !in_array($from_user_tmp_row['fromuserid'], $from_id_row))
			{
				array_push($from_id_row, $from_user_tmp_row['fromuserid']);
			}
		}

		//读取发送者账户信息
		$from_user_rows = array();
		$friend_temp_rows = array();

		if ($from_id_row)
		{
			$sql = 'SELECT  * FROM ' . TABEL_PREFIX . 'member WHERE userid IN (' . implode(',', $from_id_row) . ')';
			$friend_temp_rows = $this->sql->getAll($sql);
		}

		$friend_rows = array();

		if (in_array('0', $from_id_row))
		{
			//系统管理员
			$r = array();

			$r['online'] = 1;
			$r['uid'] = '0';
			$r['uname'] = '管理员';
			$r['uimg'] = 'image/default/avatar.png';
			$r['fuid'] = '0';
			$r['funame'] = '管理员';
			$r['fuimg'] = 'image/default/avatar.png';
			$r['recent'] = 1;

			$r['user'] = '管理员';
			$r['logo'] = 'image/default/avatar.png';

			$friend_rows[0] = $r;
		}

		if ($friend_temp_rows)
		{
			foreach ($friend_temp_rows as $k => $v)
			{
				//过滤掉自己发送的消息状态的修改
				$r = array();

				$r['online'] = self::getUserOnline($v['userid']);
				$r['uid'] = $v['userid'];
				$r['uname'] = $v['user'];
				$r['uimg'] = $v['logo'];
				$r['fuid'] = $v['userid'];
				$r['funame'] = $v['user'];
				$r['fuimg'] = $v['logo'];
				$r['recent'] = 1;

				$r['user'] = $v['user'];
				$r['logo'] = $v['logo'];

				$friend_rows[$v['userid']] = $r;
			}
		}

		return  $friend_rows;
	}

	static  public  function  getUserOnline($user_id=null)
	{
		$userOnlineCache = self::getUserOnlineCache();

		$online_key = 'online_' . $user_id;

		$rs = $userOnlineCache->get($online_key);

		if ($rs)
		{
			$state = 1;
		}
		else
		{
			$state = 0;
		}

		return $state;
	}

	static  public  function  setUserOnline($user_id=null)
	{
		$userOnlineCache = self::getUserOnlineCache();

		$online_key = 'online_' . $user_id;

		$rs = $userOnlineCache->save(1, $online_key);
	}



	static  public  function  getUserOnlineCache()
	{
		//用户状态表
		$cache_dir = APP_PATH . '/data/cache/user_online/';
		make_dir_path($cache_dir);

		//设置cache 参数
		//cacheType 1:file  2:memcache   3：redis
		$config_cache['user_online'] = array(
			'cacheType' => 1,
			'cacheDir' => $cache_dir,
			'memoryCaching' => false,
			'automaticSerialization' => true,
			'hashedDirectoryLevel' => 3,
			'hashedDirectoryUmask' => 0777,
			'cacheFileMode' => 0777,
			'lifeTime' => 10
		);

		$userOnlineCache = new Cache_Lite($config_cache['user_online']);

		return $userOnlineCache;
	}
}
?>