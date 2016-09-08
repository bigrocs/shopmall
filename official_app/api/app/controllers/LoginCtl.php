<?php
class LoginCtl extends Yf_AppController
{
	public function info()
	{
        //让wap也登录， 操作cookie，并返回状态
        sleep(5);
        //$_COOKIE['PHPSESSID']
        $d = array(null, 0);
        $this->data->addBody(-100, $d);
	}

    /**
     * 用户登录
     * 
     * @access public
     */
	public function login()
	{
        //检测登录状态
	}
}