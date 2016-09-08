<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Player_CardCtl extends Yf_AppController
{
    public $playerCardModel = null;

    /**
     * 初始化方法，构造函数
     * 
     * @access public
     */
    public function init()
    {
        // $this->view->getView();
        $this->playerCardModel = new Player_CardModel();
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $player_id = $_REQUEST['user_id'];

        $d = $this->playerCardModel->getCard(array(1, 2, 3));

        $this->data->addBody(-140, $d);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $d = $this->playerCardModel->addCard(array('player_card_id'=>'1', 'player_card_name'=>'ssafdf6'));

        $this->data->addBody(-140, $d);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $d = $this->playerCardModel->removeCard(array(1));

        $this->data->addBody(-140, $d);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $d = $this->playerCardModel->editCard(1, array('player_card_id'=>'1', 'player_card_name'=>'nnnnn'));
        $this->data->addBody(-140, $d);
    }
}
?>