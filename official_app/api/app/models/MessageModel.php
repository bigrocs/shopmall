<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class MessageModel extends Message
{
    public $_multiCond       = array('uid'=>null);  //消息
    public $_multiCondUnread = array('uid'=>null, 'iflook'=>2);  //未读消息
    public $_multiCondUnreadByUserId = array('uid'=>null, 'iflook'=>2, 'fromuserid'=>null);  //未读消息
    public $_multiCondSendByUserId = array('uid'=>null, 'touserid'=>null);  //用户自己发送的
    public $_multiCondReceiveByUserId = array('uid'=>null, 'fromuserid'=>null);  //用户自己接收的
    public $_multiCondRead   = array('uid'=>null, 'iflook'=>1);  //已读消息

    public function getMessageAll($user_id)
    {
        $this->_multiCond['uid'] = $user_id;

        $id_rows = $this->getKeyByMultiCond($this->_multiCond);

        $message_rows = array();

        if ($id_rows)
        {
			$this->sql->setLimit(0, 50);
            $message_rows = $this->getMessage($id_rows);
        }

        return $message_rows;
    }

    public function getMessageUnread($user_id)
    {
        $this->_multiCondUnread['uid'] = $user_id;

        $id_rows = $this->getKeyByMultiCond($this->_multiCondUnread);

        $message_rows = array();

        if ($id_rows)
        {
			$this->sql->setLimit(0, 50);
            $message_rows = $this->getMessage($id_rows);
        }

        return $message_rows;
    }


	public function getMessageUnreadUserByUserId($user_id, $from_id)
	{
		$this->_multiCondUnreadByUserId['uid'] = $user_id;
		$this->_multiCondUnreadByUserId['fromuserid'] = $from_id;

		$id_rows = $this->getKeyByMultiCond($this->_multiCondUnreadByUserId);

		$message_rows = array();

		if ($id_rows)
		{
			$this->sql->setLimit(0, 50);
			$message_rows = $this->getMessage($id_rows);
		}

		return $message_rows;
	}


	public function getMessageUserByUserId($user_id, $from_id)
	{
		//用户自己发送的
		$this->_multiCondSendByUserId['uid'] = $user_id;
		$this->_multiCondSendByUserId['touserid'] = $from_id;

		if ($_REQUEST['time_from'])
		{
			$time_from = $_REQUEST['time_from'];

			$date = date('Y-m-d H:i:s', time() - 3600*24*$time_from);
			$this->sql->setWhere('date', $date, '>');
		}

		$id_send_rows = $this->getKeyByMultiCond($this->_multiCondSendByUserId);


		//用户接收的
		$this->_multiCondReceiveByUserId['uid'] = $user_id;
		$this->_multiCondReceiveByUserId['fromuserid'] = $from_id;

		if ($_REQUEST['time_from'])
		{
			$time_from = $_REQUEST['time_from'];

			$date = date('Y-m-d H:i:s', time() - 3600*24*$time_from);
			$this->sql->setWhere('date', $date, '>');
		}

		$id_receive_rows = $this->getKeyByMultiCond($this->_multiCondReceiveByUserId);

		$id_rows = array_merge($id_send_rows, $id_receive_rows);

		fb($id_rows);

		$message_rows = array();

		if ($id_rows)
		{
			$this->sql->setLimit(0, 5000);
			$this->sql->setOrder('id', 'DESC');

			$message_rows = $this->getMessage($id_rows);
		}

		return $message_rows;
	}

    public function getMessageRead($user_id)
    {
        $this->_multiCondRead['uid'] = $user_id;

        $id_rows = $this->getKeyByMultiCond($this->_multiCondRead);

        $message_rows = array();

        if ($id_rows)
        {
			$this->sql->setLimit(0, 50);
            $message_rows = $this->getMessage($id_rows);
        }

        return $message_rows;
    }

    //删除某个好友发送的消息
    public function removeMessageByFuid($user_id, $fuid)
    {
        $this->sql->setWhere('uid', $user_id);
        $this->sql->setWhere('fromuserid', $fuid);
        $this->sql->setWhere('msgtype', 1);
        $flag = $this->_delete();

        return $flag;
    }
}
?>