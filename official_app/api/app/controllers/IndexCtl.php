<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Yf_AppController
{
    public function index()
    {
        //include $this->view->getView();
        //$c = new Player_Card();
        //$d = $c->getCard(array(1, 2, 3));

        $this->data->addBody(-140, array(1, 2, 3));
    }

    public function add()
    {
        // $this->view->getView();
        $c = new Player_Card();
        $d = $c->addCard(array('player_card_id'=>'1', 'player_card_name'=>'ssafdf6'));

        $this->data->addBody(-140, $d);
    }

    public function remove()
    {
        // $this->view->getView();
        $c = new Player_Card();
        $d = $c->removeCard(array(1));

        $this->data->addBody(-140, $d);
    }

    public function edit()
    {
        // $this->view->getView();
        $c = new Player_Card();
        $d = $c->editCard(1, array('player_card_id'=>'1', 'player_card_name'=>'nnnnn'));
        $this->data->addBody(-140, $d);
    }
}
?>