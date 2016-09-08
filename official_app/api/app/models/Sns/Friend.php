<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * 
 * 
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Sns_Friend extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|sns_friend|';
    public $_cacheName       = 'sns';
    public $_tableName       = 'sns_friend';
    public $_tablePrimaryKey = 'id';

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id='mallbuilder_data', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        parent::__construct($db_id, $user);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $id  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getFriend($id=null, $sort_key_row=null)
    {
        $rows = array();
        $rows = $this->get($id, $sort_key_row);

        return $rows;
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $user_id    用户Id
     * @param  int   $friend_id  好友Id
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getFriendByFriendId($user_id=null, $friend_id=null)
    {
        $row = array();

        $this->sql->setWhere('uid', $user_id);
        $this->sql->setWhere('fuid', $friend_id);
        $rows = $this->_select();

        if ($rows)
        {
            $row = array_pop($rows);
        }

        return $row;
    }


    /**
     * 取得影响的行数
     *
     * @return int $num 行数
     * @access public
     */
    public function getFoundRows()
    {
        $num = $this->_selectFoundRows();
        
        return $num;
    }

    /**
     * 插入
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
    public function addFriend($field_row)
    {
        $add_flag = $this->add($field_row);

        //$this->removeKey($id);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $id  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editFriend($id=null, $field_row)
    {
        $update_flag = $this->edit($id, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $id
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editFriendSingleField($id, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($id, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }    
    
    /**
     * 删除操作
     * @param int $id
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeFriend($id)
    {
        $del_flag = $this->remove($id);

        //$this->removeKey($id);
        return $del_flag;
    }
}
?>