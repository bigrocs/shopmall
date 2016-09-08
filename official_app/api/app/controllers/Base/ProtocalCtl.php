<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_ProtocalCtl extends Yf_AppController
{
    public $baseProtocalModel = null;

    /**
     * 初始化方法，构造函数
     * 
     * @access public
     */
    public function init()
    {
        // $this->view->getView();
        $this->baseProtocalModel = new Base_ProtocalModel();
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $player_id = $_REQUEST['user_id'];

        $d = $this->baseProtocalModel->getProtocal(array(1, 2, 3));

        $this->data->addBody(-140, $d);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $d = $this->baseProtocalModel->addProtocal(array('player_card_id'=>'1', 'player_card_name'=>'ssafdf6'));

        $this->data->addBody(-140, $d);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $d = $this->baseProtocalModel->removeProtocal(array(1));

        $this->data->addBody(-140, $d);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $d = $this->baseProtocalModel->editProtocal(1, array('player_card_id'=>'1', 'player_card_name'=>'nnnnn'));
        $this->data->addBody(-140, $d);
    }
}
?>