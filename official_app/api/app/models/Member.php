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
class Member extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|member|';
    public $_cacheName       = 'member';
    public $_tableName       = 'member';
    public $_tablePrimaryKey = 'userid';

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
     * @param  int   $userid  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getMember($userid=null, $sort_key_row=null)
    {
        $rows = array();
        $rows = $this->get($userid, $sort_key_row);

        return $rows;
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
    public function addMember($field_row)
    {
        $add_flag = $this->add($field_row);

        //$this->removeKey($userid);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $userid  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editMember($userid=null, $field_row)
    {
        $update_flag = $this->edit($userid, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $userid
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editMemberSingleField($userid, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($userid, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }    
    
    /**
     * 删除操作
     * @param int $userid
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeMember($userid)
    {
        $del_flag = $this->remove($userid);

        //$this->removeKey($userid);
        return $del_flag;
    }
}
?>