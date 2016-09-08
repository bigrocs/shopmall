<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class MemberModel extends Member
{
    //需要索引
    public $_multiCondUserId       = array('userid'=>null);  //根据会员Id查询
    public $_multiCondUser         = array('user'=>null);    //根据会员名称查询
    public $_multiCondEmail        = array('email'=>null);    //根据Eamil名称查询

    public function getMemberByUser($user, $password)
    {
        //业务逻辑封装， 判断是email还是user
        if (Yf_Utils_String::isEmail($user))
        {
            $this->_multiCondEmail['email'] = $user;
            $key_rows = $this->getKeyByMultiCond($this->_multiCondEmail);
        }
        else
        {
            $this->_multiCondUser['user'] = $user;
            $key_rows = $this->getKeyByMultiCond($this->_multiCondUser);
        }

        if ($key_rows)
        {
            $member_rows = $this->getMember($key_rows); //测数据有误

            if ($member_rows)
            {
                $member_row = array_pop($member_rows);
            }

            if ($member_row['password'] == md5($password))
            {
                $rs = array(
                    'uid'=>$member_row['userid'],
                    'user'=>$member_row['user'],
                    'logo'=>$member_row['logo']
                );
            }
            else
            {
                throw new Yf_ProtocalException(_('密码错误'), 2, 0);
            }
        }
        else
        {
            throw new Yf_ProtocalException(_('用户名错误'), 1, 0);
        }

        return $member_row;
    }


    public function getMemberByUsername($user)
    {
        $this->_multiCondUser['user'] = $user;
        $key_rows = $this->getKeyByMultiCond($this->_multiCondUser);

        if ($key_rows)
        {
            $member_rows = $this->getMember($key_rows); //测数据有误

            if ($member_rows)
            {
                $member_row = array_pop($member_rows);
            }
            else
            {
                throw new Yf_ProtocalException(_('密码错误'), 2, 0);
            }
        }
        else
        {
            throw new Yf_ProtocalException(_('用户名错误'), 1, 0);
        }

        return $member_row;
    }
}
?>