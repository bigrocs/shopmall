<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class MemberCtl extends Yf_AppController
{
    public $memberModel = null;

    /**
     * 初始化方法，构造函数
     * 
     * @access public
     */
    public function init()
    {
        // $this->view->getView();
        $this->memberModel = new MemberModel();
    }

    /**
     * 用户登录
     * 
     * @access public
     */
    public function login()
    {
        //检测登录状态
        $user = $_REQUEST['user'];
        $password = $_REQUEST['password'];

        $d = $this->memberModel->getMemberByUser($user, $password);
        $this->data->addBody(-100, $d);
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $player_id = $_REQUEST['user_id'];

        $d = $this->memberModel->getMember(array($player_id));

		if ($d)
		{
			$d = array_pop($d);
		}

        $this->data->addBody(-140, $d);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $d = $this->memberModel->addMember(array('userid'=>'1'));

        $this->data->addBody(-140, $d);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $d = $this->memberModel->removeMember(array(1));

        $this->data->addBody(-140, $d);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $d = $this->memberModel->editMember(1, array('userid'=>'1'));
        $this->data->addBody(-140, $d);
    }
}
?>