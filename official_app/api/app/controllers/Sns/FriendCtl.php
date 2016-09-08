<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_FriendCtl extends Yf_AppController
{
    public $snsFriendModel = null;

    /**
     * 初始化方法，构造函数
     * 
     * @access public
     */
    public function init()
    {
        // $this->view->getView();
        $this->snsFriendModel = new Sns_FriendModel();
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $user_id = $_REQUEST['user_id'];

        $d = $this->snsFriendModel->getFriendAll($user_id);

        $this->data->addBody(-40, $d);
    }

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function getFriendSingle()
	{
		$user_id = $_REQUEST['user_id'];

		$all = $this->snsFriendModel->getFriendAll($user_id);
		$d = $this->snsFriendModel->getFriendSingle($user_id);

		$data = $all +  $d;

		$recent_user = $this->snsFriendModel->getFriendRecent($user_id);

		$group_id_row = array();

		foreach ($data as $k => $v)
		{
			if (!in_array($v['group_id'], $group_id_row))
			{
				array_push($group_id_row, $v['group_id']);
			}

			if (isset($recent_user[$v['fuid']]))
			{
				$data[$k]['recent'] = 1;

				unset($recent_user[$v['fuid']]);
			}
		}

		fb($recent_user);
		foreach ($recent_user as $k => $v)
		{
			$t = $v;

			$data[] = $t;
		}

		fb($group_id_row);
		$group_rows = array();

		//取得好友分组
		$snsGroup = new Sns_GroupModel();

		if ($group_id_row)
		{
			$group_rows = $snsGroup->getGroup($group_id_row);
		}


		$this->data->addBody(-40, $data);
		$this->data->addBody(-41, $group_rows);
	}


    /**
     * 添加
     *
     * @access public
     */
    public function invite()
    {
        $uid  = $_REQUEST['user_id'];
        $fuid = $_REQUEST['uid'];

        if ($fuid == $uid)
        {
            throw new Yf_ProtocalException(_('网络异常，请稍后再试!'), 2, 0);
        }

        //判断记录是否存在， 对方是否关注
        $sns_friends_other_row = $this->snsFriendModel->getFriendByFriendId($uid, $fuid);
        if ($sns_friends_other_row)
        {
            if (1 == $sns_friends_other_row['state'])
            {
                //则为同意，修改为2
                $this->snsFriendModel->editFriendSingleField($sns_friends_other_row['id'], 'state', 2, 1);
                $sns_friends_other_row['state'] = 2;
            }
            else
            {
                //已经相互关注
            }
        }

        //我是否已经关注
        $sns_friends_row = $this->snsFriendModel->getFriendByFriendId($fuid, $uid);
        if ($sns_friends_row)
        {
            if (1 == $sns_friends_row['state'])
            {
                //
                
            }
            else if (2 == $sns_friends_row['state'])
            {
                //已经相互关注
            }
        }
        else
        {
            //添加或者修改数据
            //用户好友是否存在，
            $member_model = new MemberModel();
            $member_friend_rows = $member_model->getMember($fuid);
            
            if ($member_friend_rows)
            {
                $member_friend_row = array_pop($member_friend_rows);
                $funame = $member_friend_row['user'];
                $fuimg  = $member_friend_row['logo'];
            }
            else
            {
                throw new Yf_ProtocalException(_('用户不存在'), 2, 0);
            }
            

            //读取用户数据
            $member_rows = $member_model->getMember($uid);
            
            if ($member_rows)
            {
                $member_row = array_pop($member_rows);
                $uname = $member_row['user'];
                $uimg  = $member_row['logo'];
            }
            else
            {
                throw new Yf_ProtocalException(_('请稍后再试!'), 2, 0);
            }


            $friend_row = array();
            $friend_row['uid'] = $fuid;
            $friend_row['uname'] = $funame;
            $friend_row['uimg'] = $fuimg;
            $friend_row['fuid'] = $uid;
            $friend_row['funame'] = $uname;
            $friend_row['fuimg'] = $uimg;
            $friend_row['addtime'] = time();
            $friend_row['state'] = 1;

            $d = $this->snsFriendModel->addFriend($friend_row);
        }

        if ($sns_friends_other_row && 2 != $sns_friends_other_row['state'])
        {

            $sns_friends_other_row = array();
            $this->data->addBody(-41, array($sns_friends_other_row));
        }
        else
        {
            if ($sns_friends_other_row)
            {
                $sns_friends_other_row['fid']  = $sns_friends_other_row['fuid'];
                $sns_friends_other_row['name'] = $sns_friends_other_row['funame'];
                $sns_friends_other_row['img']  = $sns_friends_other_row['fuimg'];
            }

            $this->data->addBody(-40, array($sns_friends_other_row));
        }

    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $d = $this->snsFriendModel->removeFriend(array(1));

        $this->data->addBody(-140, $d);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $d = $this->snsFriendModel->editFriend(1, array('id'=>'1'));
        $this->data->addBody(-140, $d);
    }
}
?>