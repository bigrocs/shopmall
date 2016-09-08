<?php
/**
 * 视图类
 * 
 * 用来管理html模板
 * 
 * @category   Framework
 * @package    View
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_View
{
    public $tpl;
    public $stc;
    public $img;
    public $css;
    public $js ;

    public function __construct(&$ctl, &$met)
    {
        $this->tpl = TPL_PATH . '/' . implode('/', explode('_', $ctl)) . '/' . $met . '.php';

        //正式可以去掉
        /*
        if (!file_exists(dirname($this->tpl)))
        {
            mkdir(dirname($this->tpl), 0777, true);
        }
        */
    }

    public function getDir()
    {
        return $this->tpl;
    }


    public function getView()
    {
        return $this->tpl;
    }
}
?>