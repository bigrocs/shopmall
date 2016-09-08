<?php
/**
 * 框架初始化文件
 * 
 * 将一些处理方式固定化，例如，统一魔术引用
 * 
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
if (!is_php('5'))
{
    die('Zero Framework runtime environment required PHP version higher than 5.0！');
}

if (!defined('SAPI'))
{
    define('SAPI', php_sapi_name());
}

//设置转义信息
//如果<=5.3,运行。
if (!is_php('5.3'))
{
    ini_set('magic_quotes_runtime', 0); 
}

@ini_set('magic_quotes_sybase', 0);

Yf_Registry::set('magic_quotes_gpc', get_magic_quotes_gpc());
Yf_Registry::set('magic_quotes_runtime', get_magic_quotes_runtime());

if(!defined('LIB_PATH'))
{
    die('请先定义框架地址：LIB_PATH');
}

//set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH);
set_include_path(LIB_PATH . PATH_SEPARATOR . MOD_PATH);



function copy_dir($source, $destination)  
{
    if (is_dir($source) == false)  
    {
        exit("The Source Directory Is Not Exist!");  
    }

    if (is_dir($source) !== false)  
    {  
        mkdir($destination, 0777);  
    }

    $handle = opendir($source);  

    while (false !== ($file = readdir($handle)))  
    {  
        if ($file != "." && $file != ".." && $file != ".svn")  
        {  
            is_dir("$source/$file")?  
            copy_dir("$source/$file", "$destination/$file"):  
            copy("$source/$file", "$destination/$file");  
        }  
    }  

    closedir($handle);  
}

//国际化语言设置
/*
$Translate = new Zend_Translate('gettext', APP_PATH . '/data/locales/', 'zh_CN', array(
    'scan' =>Zend_Translate::LOCALE_DIRECTORY
));

//$Translate->setLocale('auto');
$Translate->setLocale('zh_CN');
*/

//初始化语言包
if (function_exists('_'))
{
    //init_locale(APP_PATH . '/data/locales/', 'zh_CN', 'HelloWorld');
    /*
    $path    = APP_PATH . '/data/locales/';
    $domain  = 'HelloWorld';
    $codeset = 'UTF-8';
    $lang    = 'zh_CN.UTF-8';

    new Yf_Locale($path, $domain, $codeset, $lang);
    */
}
else
{
    function _($str)
    {
        return $str;
    }
}


/*
define('XHPROF_PATH',   ROOT_PATH.'/../../xhprof');
define('XHPROF_DOMAIN','http://xhprof');
if(function_exists('xhprof_enable'))
{
	xhprof_enable();
	$start_time = microtime(true);
	register_shutdown_function('save_xhprof');
//    save_xhprof();
}

function save_xhprof()
{
	global $start_time;
	$run_time = microtime(true) - $start_time;
	$cost_memory = memory_get_usage(true);
	if($run_time < 0.001 && 0 )
	{
		return true;
	}
	$data = xhprof_disable();
	$date = date("Ymd",time());
	include_once XHPROF_PATH . "/xhprof_lib/utils/xhprof_lib.php";
	include_once XHPROF_PATH . "/xhprof_lib/utils/xhprof_runs.php";
	
	$source = $_SERVER['HTTP_HOST'];
	$xhprof_runs = new XHProfRuns_Default();
	$run_id = $xhprof_runs->save_run($data, $source);
	$file = XHPROF_PATH.'/log/'.$date.'.php';
	if(!file_exists($file))
	{
		touch($file);
	}
	$file_content = file_get_contents($file);
	$new_file_content = "<font color='red'>script runtime = {$run_time}s cost memory = ".round($cost_memory/1024/1024,2)."M time=".date("Y-m-d H:i:s",time())." file={$_SERVER['SCRIPT_NAME']} {$_REQUEST['action']} {$_REQUEST['command']}&source={$source}</font>".'<a href="'.XHPROF_DOMAIN.'/xhprof_html/index.php?run='.$run_id.'&source='.$source.'">link</a><br/>'."\n".$file_content;
	file_put_contents(XHPROF_PATH.'/log/'.$date.'.php',$new_file_content);
}
*/

//$Translate = new Lable();

/**
 * Determines if the current version of PHP is greater then the supplied value
 *
 * Since there are a few places where we conditionally test for PHP > 5
 * we'll set a static variable.
 *
 * @access  public
 * @param   string
 * @return  bool
 */
function is_php($version = '5.0.0')
{
    static $_is_php;
    $version = (string)$version;
    
    if (!isset($_is_php[$version]))
    {
        $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
    }

    return $_is_php[$version];
}

/**
 * 判断操作系统
 */
function  get_sys()
{
    return substr(PHP_OS, 0, 3) ;
}

/**
 * 转义字符函数
 *
 * @param mixed $content  contents should be addslashes
 *
 * @return mixed  $content
 *
 */
function quotes(&$content)
{
    if(is_array($content))
    {
        foreach ($content as $key=>$value)
        {
            $content[$key] = quotes($value);
        }
    }
    else
    {
        $content = addslashes($content);
    }

    return $content;
}

function unquotes(&$content)
{
    if (is_array($content))
    {
        foreach($content as $key=>$value)
        {
            $content[$key] =unquotes($value);
        }
    }
    else
    {
        $content = stripslashes($content);
    }

    return $content;
}


function mres($str)
{
    $str = addslashes($str);
    //$str = str_replace('_', '\_', $str); //转义掉”_” 
    //$str = str_replace('%', '\%', $str); //转义掉”%”

    //return addslashes($str);
    //return mysql_real_escape_string($str);

    return $str;
}

function addslashes_array(& $array)
{  
    $array = is_array($array) ? array_map('addslashes_array', $array) : addslashes($array);  

    return $array;  
}

function stripslashes_array(& $array)
{  
    $array = is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);  

    return $array;  
}

function untrim($val)
{
    if ('string' == gettype($val))
    {
        $val =  '"' . mres($val) . '"';
    }
    
    return $val;
}

function format_update_sql(&$item1, $key)
{
    $item1 = $key . '=' . '"' . $item1 . '"';
}


//$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");

//array_walk($fruits, 'formatUpdateSql');
//implode(',', $fruits)
function array_values_recursive($arr)
{
	$arr = array_values($arr);
	foreach($arr as $key => $val)
	{
		if(is_array($val))
		{
			$arr[$key] = array_values_recursive($val);
		}
	}
	
	 return $arr;
}

//如果使用zend, 则关掉此处
function __autoload(&$class_name)
{
    if (!class_exists($class_name, false) && !interface_exists($class_name, false))
    {
        if (false !== strpos($class_name, '_'))
        {
            $class_file_suffix = '/' . str_replace('_', '/', $class_name) . '.php';
        }
        else if (false !== strpos($class_name, '\\'))
        {
            $class_file_suffix = '/' . str_replace('\\', '/', $class_name) . '.php';
        }
        else
        {
            $class_file_suffix = '/' . $class_name . '.php';
        }

        $class_file_path = MOD_PATH . $class_file_suffix;

        if (is_file($class_file_path))
        {
            import($class_file_path);
        }
        else
        {
            $class_file_path = LIB_PATH . $class_file_suffix;

            if (is_file($class_file_path))
            {
                import($class_file_path);
            }
            else
            {
                $class_file_path = CTL_PATH . $class_file_suffix;

                if (is_file($class_file_path))
                {
                    import($class_file_path);
                }
            }
        }

        if (!class_exists($class_name, false) && !interface_exists($class_name, false))
        {
            //throw new Exception('Class ' . $class_name . ' does not exists : ' . $class_file_suffix);
            error_header(404, 'Page Not Found');
            //echo 'Class ' . $class_name . ' does not exists : ' . $class_file_suffix;
            throw new Exception('Class ' . $class_name . ' does not exists : ' . $class_file_suffix);
        }
    }
}

function import($file_path=null, $flag=true)
{
    global $import_file_row;

    array_unshift($import_file_row, $file_path);

    if ($flag)
    {
        include_once $file_path;
    }
}

function check_request()
{
    if (SERVER_HOST == 'youleen.com')
    {
        if ($_SERVER['DOCUMENT_ROOT'])
        {
            if ($_SERVER['HTTP_REFERER'])
            {
                $referer_url_row = parse_url($_SERVER['HTTP_REFERER']);

                if ($referer_url_row['host'] != $_SERVER['SERVER_NAME'])
                {
                    die('禁止通过此途径访问!');
                }

                if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
                {
                    die('请使用浏览器正常途径访问!');
                }
            }
            else
            {
                die('禁止通过此途径访问');
            }
        }
    }
    else
    {
        
    }
}

if (!function_exists('clean_cache'))
{
    function clean_cache($dir=null, $del_dir=null) 
    {
        if (is_dir($dir))
        {
            $dh = opendir($dir);

            while(false !== ($f = readdir($dh)))
            {
                if($f == '.' || $f=='..')
                {
                    continue;
                }
                elseif(is_dir($dir . '/' . $f))
                {
                    clean_cache($dir . '/' . $f, $del_dir);
                }
                else
                {
                    unlink($dir . '/' . $f);            
                }
            }

            closedir($dh);

            if($del_dir && 'cache' != $dir)
            {
                rmdir($dir);
            }

            return true;
        }
        else
        {
            return false;
        }
    }
}


function make_dir_path($path)
{
    if (!file_exists($path))
    {
        make_dir_path(dirname($path));
        mkdir($path, 0777);
    }
}

function l()
{
    if (!preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si', $_SERVER['HTTP_USER_AGENT'] , $m) || !version_compare($m[1][0], '0.0.6', '>='))
    {
        $Logger   = Log::singleton('file', APP_PATH . '/data/logs/db.log', '', array(), PEAR_LOG_DEBUG);

        $content = func_get_args();
        $len = count($content) - 1;

        for ($i=$len; $i>=0; $i--)
        {
            $Logger->log($content[$i]);
        }
    }
    else
    {
        $FirePHP = new FirePHP();

        if ($FirePHP)
        {
            $instance = $FirePHP->getInstance(true);
            $args = func_get_args();
            return call_user_func_array(array($instance,'fb'),$args);
        }
    }
}

function file_log()
{
    $Logger   = Log::singleton('file', APP_PATH . '/data/logs/player_info.log', '', array(), PEAR_LOG_DEBUG);

    $content = func_get_args();
    $len = count($content);

    for ($i=0; $i<$len; $i++)
    {
        $str = "";

        if (is_array($content[$i]))
        {
            $str = json_encode($content[$i]);
        }
        else
        {
             $str = $content[$i];
        }

        $Logger->log($str);

    }

}

function fb()
{
    if ('cli' == SAPI)
    {
        return ;
        //print_r(func_get_args());
        $content = func_get_args();
        $len = count($content) - 1;
        for ($i=$len; $i>=0; $i--)
        {
            print_r($content[$i]);
        }

        echo "\n";
    }
    else
    {
        if (!isset($_SERVER['HTTP_USER_AGENT']))
        {
            return ;
        }

        if (!preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si', $_SERVER['HTTP_USER_AGENT'] , $m) || !version_compare($m[1][0], '0.0.6', '>='))
        {
        }
        else
        {
            $FirePHP = new FirePHP();
            if ($FirePHP)
            {
                $instance = $FirePHP->getInstance(true);
                $args = func_get_args();
                return call_user_func_array(array($instance,'fb'),$args);
            }
        }
    }

    return true;
}

function get_ip() 
{
    if (getenv('HTTP_CLIENT_IP')) 
    {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR')) 
    {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif(getenv('HTTP_X_FORWARDED')) 
    {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif(getenv('HTTP_FORWARDED_FOR')) 
    {
        $ip = getenv('HTTP_FORWARDED_FOR');
    }
    elseif(getenv('HTTP_FORWARDED')) 
    {
        $ip = getenv('HTTP_FORWARDED');
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function ceil_r($num)
{
    return ceil(round($num, 10));
}

function floor_r($num)
{
    return floor(round($num, 10));
}

/**
 * 取得当前时间
 *
 * @return  int $time 是否成功
 *
 */
function get_time()
{
    if ('cli' == SAPI)
    {
        $time = time();         
    }
    else
    {
        if (isset($_SERVER['REQUEST_TIME']))
        {
            $time = $_SERVER['REQUEST_TIME'];
        }
        else
        {
            $time = time();
        }
    }

    return $time;
}

function get_date_time()
{
    return date("Y-m-d H:i:s");
}

function get_days($start_time, $end_time)
{
    return (strtotime(date("Y-m-d",$end_time)) - strtotime(date("Y-m-d",$start_time)))/86400 + 1;
}

/**
 * 取得执行结果
 *
 * @return  array   $rs_row             是否成功
 */
function is_ok(&$rs_row=array())
{
    $rs = true;

    if (in_array('0', $rs_row) || in_array(false, $rs_row) || in_array('', $rs_row))
    {
        $rs = false;
    }

    return $rs;
}

function get_query1() 
{
    $query_string = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']) +1, strlen($_SERVER['REQUEST_URI']));
    
    if ($length = strpos($query_string, '?')) 
    {
        $query_string = substr($query_string, 0, $length);
    }
    
    return $query_string;
}

/**
 * 获得友好的URL访问
 */
function get_query()
{
    $_SGETS   = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    $_SLEN    = count($_SGETS);
    $_SGET    = $_GET;

    for($i=0; $i<$_SLEN; $i+=2)
    {
        if(!empty($_SGETS[$i]) && !empty($_SGETS[$i+1])) $_SGET[$_SGETS[$i]]=$_SGETS[$i+1];
    }

    if (!isset($_REQUEST['ctl']))
    {
        $_SGET['ctl'] = !empty($_SGET['ctl']) && is_string($_SGET['ctl']) ? trim($_SGET['ctl']) : 'main';
    }

    if (!isset($_REQUEST['act']))
    {
        $_SGET['act'] = !empty($_SGET['act']) && is_string($_SGET['act']) ? trim($_SGET['act']) : 'index';
    }

    return $_SGET;
}

/**
 * 生成链接URL
 *
 */
function setUrl($arr)
{
    global $global;
    
    $query_string = '';

    if($global['urlmode'] == 2)
    {
        foreach($arr as $k=> $v)
        {
            $query_string .= $k. '/' . $v. '/';
        }
    }

    $query_string .= $global['urlsuffix'];

    return $query_string;
}

/**
 * 本地化  I18N 程序范例开始 
 *
 *
 * @param string $lan_path 设置某个域的mo文件路径  
 * @param string $lang     bsd use zh_CN.UTF-8
 * @param string $domain   定义要用的mo文件名称，常规来说，我们都把PACKAGE的名称定义和程序名称相同。 
 * @return void 
 */
function init_locale($lan_path, $lang, $domain)
{
    setlocale(LC_ALL, $lang);   //// bsd use zh_CN.UTF-8

    bindtextdomain($domain, $lan_path); //设置某个域的mo文件路径  
    bind_textdomain_codeset($domain, 'UTF-8'); //设置mo文件的编码为UTF-8    
    textdomain($domain); //设置gettext()函数从哪个域去找mo文件 
}


if (!function_exists('get_called_class')) 
{
    function get_called_class() 
    {
        $bt = debug_backtrace();
        $l = 0;
        do 
        {
            $l++;
            $lines = file($bt[$l]['file']);
            $callerLine = $lines[$bt[$l]['line'] - 1];
            preg_match('/([a-zA-Z0-9\_]+)::' . $bt[$l]['function'] . '/', $callerLine, $matches);
            
            if ($matches[1] == 'self') 
            {
                $line = $bt[$l]['line'] - 1;
                
                while ($line > 0 && strpos($lines[$line], 'class') === false) 
                {
                    $line--;
                }
                preg_match('/class[\s]+(.+?)[\s]+/si', $lines[$line], $matches);
            }
        }
        
        while ($matches[1] == 'parent' && $matches[1]);
        
        return $matches[1];
    }
}

/**
 * 通知客户端错误原因
 *
 * @param  int $error_no
 * @param  string $error_msg
 */
function error_header($error_no, $error_msg)
{
    header('HTTP/1.0 ' . $error_no . ' ' . $error_msg);
}

//按二维数组指定属性排序
function array_sort($arr, $keys, $type='asc')
{
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v)
	{
		$keysvalue[$k] = $v[$keys];
	}

	if($type == 'asc')
	{
		asort($keysvalue);
	}
	else
	{
		arsort($keysvalue);
	}

	reset($keysvalue);
	foreach ($keysvalue as $k=>$v)
	{
		$new_array[$k] = $arr[$k];
	}

	return $new_array;
}
function array_reset($sort_key_row, $data_rows)
{
    $data_rows_new = array();

    if(($n = count($sort_key_row)) > 0)
    {
        switch($n)
         {
             case 1:
        		   foreach($data_rows as $key => $value)
        		   {
        		       $data_rows_new[$value[$sort_key_row[0]]] = $value;
        		   }
        		   break;
        	  case 2:
        		   foreach($data_rows as $key => $value)
        		   {
        		       $data_rows_new[$value[$sort_key_row[0]]][$value[$sort_key_row[1]]] = $value;
        		   }
        		   break;
        	  case 3:
        		    foreach($data_rows as $key => $value)
        		    {
        		       $data_rows_new[$value[$sort_key_row[0]]][$value[$sort_key_row[1]]][$value[$sort_key_row[2]]] = $value;
        		     }
        		     break;
        	  case 4:
        		    foreach($data_rows as $key => $value)
        		    {
        		        $data_rows_new[$value[$sort_key_row[0]]][$value[$sort_key_row[1]]][$value[$sort_key_row[2]]][$value[$sort_key_row[3]]] = $value;
        		    }
        		    break;
        	}
    }
    else
    {
    	  $data_rows_new = $data_rows;
    }

    return $data_rows_new;
}

function template_replace($template,$arr_replace)
{
    if (empty($arr_replace))
    {
        return $template;
    }
    $search = array();
    $replace = array();
    foreach($arr_replace as $key => $value)
    {
        $search[] = '{{'.$key.'}}';
        $replace[] = $value;
    }
    return str_replace($search,$replace,$template);		
}

function get_strlen($str)
{
    /*
    $strlen = strlen($str);
    $mb_strlen = mb_strlen($str, "utf8");

    return ($strlen - $mb_strlen)/2 + $mb_strlen;
    */

    return mb_strlen($str, "utf8");
}
/*判断一个数字是否是整数
*@param $intNum 要判断的数字
*@param $scope 1 > 0 ,0 >= 0, -1 所有整数;
*/
function is_int_numeric($intNum,$scope=1)
{
    if(is_numeric($intNum) && (round($intNum, 0)) == $intNum)//是整数
    {
        if($scope == 1)
        {
            return $intNum > 0;
        }
        elseif($scope == 0)
        {
            return $intNum >= 0;
        }
        elseif($scope == -1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function is_good_array($array)
{
    return (is_array($array) && count($array) > 0);
}

//按值将元素从数组中移除
function array_remove_value($value, $arr_value)
{
    if (is_array($value))
    {
        foreach ($value as $k => $v)
        {
            $del_key = array_search($v, $arr_value);

            if (false !== $del_key)
            {
                unset($arr_value[$del_key]);
            }
        }
    }
    else
    {
        $del_key = array_search($value, $arr_value);

        if (false !== $del_key)
        {
            unset($arr_value[$del_key]);
        }
    }

    return $arr_value;
}

//将2个二维数组的值相加
function array_add($arr_first, $arr_second)
{
    foreach($arr_second as $key => $value)
    {
        foreach($value as $k => $v)
        {
            $arr_first[$key][$k] = (isset($arr_first[$key][$k]) ? $arr_first[$key][$k] : 0 ) + $v;
        }
    }

    return $arr_first;
}

//将2个二维数组的值相减
function array_sub($arr_first, $arr_second)
{
    foreach($arr_second as $key => $value)
    {
        foreach($value as $k => $v)
        {
            $arr_first[$key][$k] = (isset($arr_first[$key][$k]) ? $arr_first[$key][$k] : 0) - $v;
        }
    }

    return $arr_first;
}

/**
 * 根据概率值返回是否成功
 *
 * @param $value : 概率值 ( 百分比值，如：0.3 表示 30% )
 * @param int $base 基数
 * @param int $degree 精度倍数
 * @param int $seed
 * @return boolean
 */
function probability($value, $base = 1, $degree = 10000)
{
    if ($value <= 0)
    {
        return false;
    }

    $value = $value * $degree;

    $rand = mt_rand(1, intval($degree * $base));

    return $rand <= $value ;
}
/**
 * 根据数组值作为概率进行随机选择
 * $array : 概率数组，格式为：
 *	array (
 *		键名1 => 概率值1,
 *		键名2 => 概率值2,
 *		......
 *		)
 *   $except : 忽略的键名
 *   $degree : 精度倍数
 */
function get_probability_key ( $array, $degree = 10000, $except = null )
{
    if ( !is_null ( $except ) )
    {
        unset ( $array[$except] );
    }
    $total = @array_sum ( $array ) * $degree;
    if ( !$total )
    {
        return false;
    }
    $intRand = mt_rand ( 0, $total );
    $offset = 0;
    foreach ( $array as $key => $item )
    {
        $value = $item * $degree;
        if ( $intRand <= $value + $offset )
        {
            $result = $key;
            break;
        }
        $offset += $value;
    }

    return $result;
}

function get_protocal($cmd_id, $data_rows)
{
    return array('cmd_id'=>$cmd_id) + $data_rows;
}

function get_sign($arr_param, $sign_key)
{
    ksort($arr_param);
    $str = '';
    foreach($arr_param as $k=>$v)
    {
        $str .= $k.'='.$v.'&';
    }

    return md5($str.$sign_key);
}

function get_check_sign($arr_param, $sign_key)
{
    unset($arr_param['sign']);
    ksort($arr_param);
    $str = '';
    foreach($arr_param as $k=>$v)
    {
        $str .= $k.'='.$v.'&';
    }

    return md5($str.$sign_key);
}

function send_request($url, $arr_param=array(), $method='post', $sign_key='', $timeout=10, $curl_header=array())
{
    $params = '';

    if(is_array($arr_param))
    {
        if($arr_param)
        {
            if($sign_key)
            {
                $arr_param['sign'] = get_sign($arr_param, $sign_key);
            }

            $params = http_build_query($arr_param);
        }
    }
    else
    {
        $params = $arr_param;
    }


    $curl = curl_init();//初始化curl

    if ('get' == $method)//以GET方式发送请求
    {
        $pos = strpos($url, "?");

        if($pos === false)
        {
            $request_url = $url."?".$params;
        }
        else
        {
            $request_url = $url."&".$params;
        }

        curl_setopt($curl, CURLOPT_URL, $request_url);
    }
    else//以POST方式发送请求
    {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);//设置传送的参数
    }

    if ($curl_header)
    {
	    curl_setopt($curl,CURLOPT_HTTPHEADER, $curl_header);
    }

    //curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers_login );
    curl_setopt($curl, CURLOPT_HEADER, false);//设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//设置等待时间
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    for($i=0 ;$i < 5; $i++)
    {
        $res = curl_exec($curl);//运行curl
        $err = curl_error($curl);

        if(empty($err))
        {
            break;
        }
    }

    if (false === $res || !empty($err))
    {
        $errno = curl_errno($curl);
        $Info = curl_getinfo($curl);
        curl_close($curl);

        return array(
            'result' => false,
            'errno' => $errno,
            'msg' => $err,
            'info' => $Info,
        );
    }

    curl_close($curl);//关闭curl

    return array(
        'result' => true,
        'msg' => $res,
    );
}

function file_get_contents_time($url)
{
    $ctx = stream_context_create(array(
       'http' => array(
           'timeout' => 3 //设置一个超时时间，单位为秒
           )
       )
    );
    
    return file_get_contents($url, 0, $ctx);
}

function array_filter_key($key, $data_rows)
{
	$data_rows_new = array();

	foreach ($data_rows as $row)
	{
		if (isset($row[$key]))
		{
			$data_rows_new[] = $row[$key];
		}
	}


	return $data_rows_new;
}
?>