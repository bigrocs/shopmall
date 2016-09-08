<?php
//设置时区
if (function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('Asia/Shanghai');
	//date_default_timezone_set('UTC');
}

if (file_exists($config['webroot']."/config/app_push_config.php"))
{
	include_once($config['webroot']."/config/app_push_config.php");
}
else
{
	die('采用Jpush，需设置：网站-通知管理-推送设置');
}

require_once 'vendor/autoload.php';

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

$br    = '<br/>';
$spilt = ' - ';

$app_key       = $app_push_config['jpush_app_key'];
$master_secret = $app_push_config['jpush_app_secret'];

JPushLog::setLogHandlers(array(new StreamHandler('jpush.log', Logger::DEBUG)));
$notification_client = new JPushClient($app_key, $master_secret);

function  send_notification($user = array(), $msg_title, $msg_content, $extras = array(), $alert = "", $badge = "+1", $category = "Ios8 Category")
{
	global $notification_client;
	global $br;

	// easy push with ios badge +1
	// 以下演示推送给 Android, IOS 平台下Tag为tag1的用户的示例
	try
	{
		$notification_client =  $notification_client->push()->setPlatform(M\Platform('android', 'ios'));

		if ($user)
		{
			$notification_client =  $notification_client->setAudience(M\Audience(M\Tag($user)));
		}
		else
		{
			$notification_client =  $notification_client->setAudience(M\all);
		}

		$result = $notification_client->setNotification(M\notification($alert,
																	   M\android($alert, $msg_title, 1, $extras),
																	   M\ios($alert, "happy", $badge, true, $extras, $category)
														))

			//->setOptions(M\options(null, 86400, null, true))//第二个参数为0，表示不接受离线数据。86400表示离线数据保留一天。
			//->setMessage(M\message($msg_content, $msg_title, 'Message Type', $extras))
			//->printJSON()
			->send();

		return $result;
	}
	catch (APIRequestException $e)
	{
		return false;
	}
	catch (APIConnectionException $e)
	{
		return false;
	}
}
/*
//easy push
try {
    $result = $notification_client->push()
        ->setPlatform(M\all)
        ->setAudience(M\all)
        ->setNotification(M\notification('Hi, JPush'))
        ->printJSON()
        ->send();
    echo 'Push Success.' . $br;
    echo 'sendno : ' . $result->sendno . $br;
    echo 'msg_id : ' .$result->msg_id . $br;
    echo 'Response JSON : ' . $result->json . $br;
} catch (APIRequestException $e) {
    echo 'Push Fail.' . $br;
    echo 'Http Code : ' . $e->httpCode . $br;
    echo 'code : ' . $e->code . $br;
    echo 'Error Message : ' . $e->message . $br;
    echo 'Response JSON : ' . $e->json . $br;
    echo 'rateLimitLimit : ' . $e->rateLimitLimit . $br;
    echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . $br;
    echo 'rateLimitReset : ' . $e->rateLimitReset . $br;
} catch (APIConnectionException $e) {
    echo 'Push Fail: ' . $br;
    echo 'Error Message: ' . $e->getMessage() . $br;
    //response timeout means your request has probably be received by JPUsh Server,please check that whether need to be pushed again.
    echo 'IsResponseTimeout: ' . $e->isResponseTimeout . $br;
}

echo $br . '-------------' . $br;
*/




/*
//full push
try {
    $result = $notification_client->push()
        ->setPlatform(M\platform('ios', 'android'))
        ->setAudience(M\audience(M\tag(array('555','666')), M\alias(array('555', '666'))))
        ->setNotification(M\notification('Hi, JPush', M\android('Hi, android'), M\ios('Hi, ios', 'happy', 1, true, null, 'THE-CATEGORY')))
        ->setMessage(M\message('msg content', null, null, array('key'=>'value')))
        ->setOptions(M\options(123456, null, null, false, 0))
        ->printJSON()
        ->send();

    echo 'Push Success.' . $br;
    echo 'sendno : ' . $result->sendno . $br;
    echo 'msg_id : ' .$result->msg_id . $br;
    echo 'Response JSON : ' . $result->json . $br;
} catch (APIRequestException $e) {
    echo 'Push Fail.' . $br;
    echo 'Http Code : ' . $e->httpCode . $br;
    echo 'code : ' . $e->code . $br;
    echo 'message : ' . $e->message . $br;
    echo 'Response JSON : ' . $e->json . $br;
    echo 'rateLimitLimit : ' . $e->rateLimitLimit . $br;
    echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . $br;
    echo 'rateLimitReset : ' . $e->rateLimitReset . $br;
} catch (APIConnectionException $e) {
    echo 'Push Fail: ' . $br;
    echo 'Error Message: ' . $e->getMessage() . $br;
    //response timeout means your request has probably be received by JPUsh Server,please check that whether need to be pushed again.
    echo 'IsResponseTimeout: ' . $e->isResponseTimeout . $br;
}



echo $br . '-------------' . $br;


//fail push
try {
    $result = $notification_client->push()
        ->setPlatform(M\all)
        ->setAudience(M\all)
        ->setNotification(M\notification('Hi, JPush'))
        ->setAudience(M\audience(array('no one')))
        ->printJSON()
        ->send();

    echo 'Push Success.' . $br;
    echo 'sendno : ' . $result->sendno . $br;
    echo 'msg_id : ' .$result->msg_id . $br;
    echo 'Response JSON : ' . $result->json . $br;
} catch (APIRequestException $e) {
    echo 'Push Fail.' . $br;
    echo 'Http Code : ' . $e->httpCode . $br;
    echo 'code : ' . $e->code . $br;
    echo 'message : ' . $e->message . $br;
    echo 'Response JSON : ' . $e->json . $br;
    echo 'rateLimitLimit : ' . $e->rateLimitLimit . $br;
    echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . $br;
    echo 'rateLimitReset : ' . $e->rateLimitReset . $br;
} catch (APIConnectionException $e) {
    echo 'Push Fail: ' . $br;
    echo 'Error Message: ' . $e->getMessage() . $br;
    //response timeout means your request has probably be received by JPUsh Server,please check that whether need to be pushed again.
    echo 'IsResponseTimeout: ' . $e->isResponseTimeout . $br;
}

*/
