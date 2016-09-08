<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class MessageCtl extends Yf_AppController
{
    public $messageModel = null;

    /**
     * 初始化方法，构造函数
     * 
     * @access public
     */
    public function init()
    {
        // $this->view->getView();
        $this->messageModel = new MessageModel();
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function history()
    {
        $user_id = $_REQUEST['user_id'];
        $iflook  = $_REQUEST['iflook'];

		$from_id = null;

		if (isset($_REQUEST['fromuserid']))
		{
			$from_id = $_REQUEST['fromuserid'];
		}

		if ($from_id)
		{
			if (2 == $iflook)
			{
				$d = $this->messageModel->getMessageUnreadUserByUserId($user_id, $from_id);
			}
			else
			{
				//获取全部
				$d = $this->messageModel->getMessageUserByUserId($user_id, $from_id);
			}
		}
		else
		{
			if (2 == $iflook)
			{
				$d = $this->messageModel->getMessageUnread($user_id);
			}
			else
			{
				//获取全部
				$d = $this->messageModel->getMessageAll($user_id);
			}
		}

        //过滤自己接收的消息，更新iflook状态

        //update iflook = 2, 未区分已读未读
        $msg_id_row = array();
        $from_id_row = array();

        foreach ($d as $key=>$item)
        {
            //过滤掉自己发送的消息状态的修改
            if ($user_id!=$item['fromuserid'] && 2==$item['iflook'])
            {
                array_push($msg_id_row, $item['id']);
            }

			if (!in_array($item['fromuserid'], $from_id_row))
			{
				array_push($from_id_row, $item['fromuserid']);
			}
        }

		//读取发送者账户信息

		//读取发送者账户信息
		$from_user_rows = array();

		if ($from_id_row)
		{
			$sql = 'SELECT  * FROM ' . TABEL_PREFIX . 'member WHERE userid IN (' . implode(',', $from_id_row) . ')';
			$from_user_tmp_rows = $this->messageModel->sql->getAll($sql);

			if ($from_user_tmp_rows)
			{
				foreach ($from_user_tmp_rows as $v)
				{
					$from_user_rows[$v['userid']] = $v;
				}
			}
		}

		foreach ($d as $k => $v)
		{
			//过滤掉自己发送的消息状态的修改
			if ($user_id!=$v['fromuserid'] && 2==$v['iflook'])
			{
				if (0 == $v['fromuserid'])
				{
					unset($d[$k]);
					continue;
				}
			}

			$v['from_name'] = $from_user_rows[$v['fromuserid']]['user'];
			$v['from_logo'] = $from_user_rows[$v['fromuserid']]['logo'];
			$d[$k] = $v;
		}

		/*
		if (isset($_REQUEST['flag']))
		{
			$flag = $_REQUEST['flag'];
		}
		else
		{
			$flag  = true;
		}

        if ($msg_id_row && $flag)
        { 
            $field_row = array(
                    'iflook' => 1
                );

            $this->messageModel->editMessage($msg_id_row, $field_row);
        }
		*/

		$rs = array();

		if (isset($_REQUEST['page']))
		{
			$rs['list'] = array_values($d);
			$rs['total_page'] = 3;
		}
		else
		{
			$rs = array_values($d);
		}

        $this->data->addBody(-30, $rs);
    }

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = $_REQUEST['user_id'];
		$iflook  = $_REQUEST['iflook'];

		$from_id = null;

		if (isset($_REQUEST['fromuserid']))
		{
			$from_id = $_REQUEST['fromuserid'];
		}

		if ($from_id)
		{
			if (2 == $iflook)
			{
				$d = $this->messageModel->getMessageUnreadUserByUserId($user_id, $from_id);
			}
			else
			{
				//获取全部
				$d = $this->messageModel->getMessageUserByUserId($user_id, $from_id);
			}
		}
		else
		{
			if (2 == $iflook)
			{
				$d = $this->messageModel->getMessageUnread($user_id);
			}
			else
			{
				//获取全部
				$d = $this->messageModel->getMessageAll($user_id);
			}
		}

		//过滤自己接收的消息，更新iflook状态

		//update iflook = 2, 未区分已读未读
		$msg_id_row = array();
		$from_id_row = array();

		foreach ($d as $key=>$item)
		{
			//过滤掉自己发送的消息状态的修改
			if ($user_id!=$item['fromuserid'] && 2==$item['iflook'])
			{
				array_push($msg_id_row, $item['id']);
			}

			if (!in_array($item['fromuserid'], $from_id_row))
			{
				array_push($from_id_row, $item['fromuserid']);
			}
		}

		//读取发送者账户信息

		//读取发送者账户信息
		$from_user_rows = array();

		if ($from_id_row)
		{
			$sql = 'SELECT  * FROM ' . TABEL_PREFIX . 'member WHERE userid IN (' . implode(',', $from_id_row) . ')';
			$from_user_tmp_rows = $this->messageModel->sql->getAll($sql);

			if ($from_user_tmp_rows)
			{
				foreach ($from_user_tmp_rows as $v)
				{
					$from_user_rows[$v['userid']] = $v;
				}
			}
		}

		foreach ($d as $k => $v)
		{
			//过滤掉自己发送的消息状态的修改
			if ($user_id!=$v['fromuserid'] && 2==$v['iflook'])
			{

				if (0 == $v['fromuserid'])
				{
					//unset($d[$k]);
					//continue;

					$v['from_name'] = '管理员';
					$v['from_logo'] = 'image/default/avatar.png';
					$d[$k] = $v;
				}
				else
				{
					$v['from_name'] = $from_user_rows[$v['fromuserid']]['user'];
					$v['from_logo'] = $from_user_rows[$v['fromuserid']]['logo'];
					$d[$k] = $v;
				}

			}
		}

		if (isset($_REQUEST['flag']))
		{
			$flag = $_REQUEST['flag'];
		}
		else
		{
			$flag  = true;
		}

		if ($msg_id_row && $flag)
		{
			$field_row = array(
				'iflook' => 1
			);

			$this->messageModel->editMessage($msg_id_row, $field_row);
		}

		$this->data->addBody(-30, array_values($d));

		//用户状态表
		Sns_FriendModel::setUserOnline($user_id);
	}

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $msg_row = array();
        $msg_row['uid']      = $_REQUEST['fromuserid'];
        $msg_row['touserid'] = $_REQUEST['touserid'];
        $msg_row['fromuserid'] = $_REQUEST['fromuserid'];
        $msg_row['fromInfo'] = $_REQUEST['fromInfo'];
        $msg_row['sub']      = $_REQUEST['fromInfo'];
        $msg_row['con']      = $_REQUEST['fromInfo'];
        $msg_row['msgtype']  = 2;
        $msg_row['iflook']   = 1;
        $msg_row['date'] = date('Y-m-d H:i:s', time());

        $d = $this->messageModel->addMessage($msg_row);

        $message_rows = array();

        if ($d)
        {
            $message_rows = $this->messageModel->getMessage($d);
        }

        //多一份记录
        $msg_other_row = array();
        $msg_other_row  = $msg_row;
        $msg_other_row['uid']        = $_REQUEST['touserid'];
        $msg_other_row['msgtype']  = 1;
        $msg_other_row['iflook']   = 2;

        $d = $this->messageModel->addMessage($msg_other_row);

        $this->data->addBody(-30, array_values($message_rows));
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function removeByFuid()
    {
        $fuid  = $_REQUEST['fuid'];
        $user_id = $_REQUEST['user_id'];

        $this->messageModel->removeMessageByFuid($user_id, $fuid);

        $this->data->addBody(-32, array($fuid));
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $fuid  = $_REQUEST['fuid'];
        $user_id = $_REQUEST['user_id'];

        //需要判断是否属于自己的消息, 理论上可以sql where直接操作，启用cache后，可以先读取，判断后操作
        
        $msg_rows = $this->messageModel->getMessage($fuid);
        if ($msg_rows)
        {
            $msg_row = array_pop($msg_rows);

            if ($msg_row && $user_id==$msg_row['touserid'])
            {
                $flag = $this->messageModel->removeMessageByFuid($fuid);
                
                if ($flag)
                {
                }
            
                $this->data->addBody(-32, array($fuid));
            }
            else
            {
                $this->data->addBody(-32, array($fuid));
                //throw new Yf_ProtocalException(_('非法数据'), 2, 0);
            }

        }
        else
        {
            $this->data->addBody(-32, array($fuid));
            //throw new Yf_ProtocalException(_('非法数据'), 2, 0);
        }

    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $d = $this->messageModel->editMessage(1, array('id'=>'1'));
        $this->data->addBody(-140, $d);
    }
}
?>