<?php

//用户登录认证
class Plugin_Perm implements Yf_Plugin_Interface
{
    //解析函数的参数是pluginManager的引用
    function __construct()
    {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        Yf_Plugin_Manager::getInstance()->register('perm', $this, 'checkPerm');
        Yf_Plugin_Manager::getInstance()->register('server_state', $this, 'checkServerState');
    }

	function desc()
    {
        echo 'Hello World';
    }

    public function checkPerm()
    {
        $data = new Yf_Data();

		//无需权限判断的文件
		$not_perm = array('Login');

        if (Perm::checkUserPerm() || (isset($_REQUEST['ctl']) && in_array($_REQUEST['ctl'], $not_perm) && $_REQUEST['met']!='main'))
        {
        }
        else
        {
			$data->setError(_('需要登录'), 30);

            return $this->outputError($data);
        }
    }

    public function outputError($data)
    {
        $d = $data->getDataRows();

        $protocol_data = Yf_Data::encodeProtocolData($d);
        echo $protocol_data;

        exit();
    }
}