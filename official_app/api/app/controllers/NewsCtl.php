<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class NewsCtl extends Yf_AppController
{
    public $newsModel = null;

    /**
     * 初始化方法，构造函数
     * 
     * @access public
     */
    public function init()
    {
        // $this->view->getView();
        $this->newsModel = new NewsModel();
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $player_id = $_REQUEST['user_id'];

        $d = $this->newsModel->getNews(array(1, 2, 3));

        $this->data->addBody(-140, $d);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $d = $this->newsModel->addNews(array('nid'=>'1'));

        $this->data->addBody(-140, $d);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $d = $this->newsModel->removeNews(array(1));

        $this->data->addBody(-140, $d);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $d = $this->newsModel->editNews(1, array('nid'=>'1'));
        $this->data->addBody(-140, $d);
    }
}
?>