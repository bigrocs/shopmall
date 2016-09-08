<?php
$ccmd_rows = array (
  'Bug' => 
  array (
    'bug' => 
    array (
      'cmd_id' => 'int16_t',
      'bug_type' => 'int8_t',
      'msg' => 'string',
    ),
  ),
  'Version' => 
  array (
    'version' => 
    array (
      'cmd_id' => 'int16_t',
      'client_version' => 'string',
    ),
  ),
  'Member' => 
  array (
    'login' => 
    array (
      'cmd_id' => 'int16_t',
      'user' => 'string',
      'password' => 'string',
    ),
  'get' =>
	  array (
		  'cmd_id' => 'int16_t',
		  'user_id' => 'uint32_t'
	  )
  ),
  'Chat' => 
  array (
    'socketOver' => 
    array (
      'cmd_id' => 'int16_t',
      'player_id' => 'uint32_t',
      'msg' => 'string',
      'alliance_id' => 'uint32_t',
    ),
  ),
  'Index' => 
  array (
    'index' => 
    array (
      'cmd_id' => 'int16_t',
    ),
    'info' => 
    array (
      'cmd_id' => 'int16_t',
    ),
  ),
  'Message' => 
  array (
    'get' => 
    array (
      'cmd_id' => 'int16_t',
      'iflook' => 'uint8_t',
      'user_id' => 'uint32_t',
    ),

	  'history' =>
		  array (
			  'cmd_id' => 'int16_t',
			  'iflook' => 'uint8_t',
			  'user_id' => 'uint32_t',
		  ),


    'add' => 
    array (
      'cmd_id' => 'int16_t',
      'uid' => 'uint32_t',
      'touserid' => 'uint32_t',
      'fromuserid' => 'uint32_t',
      'fromInfo' => 'string',
      'msgtype' => 'uint8_t',
    ),
    'removeByFuid' => 
    array (
      'cmd_id' => 'int16_t',
      'fuid' => 'uint32_t',
      'user_id' => 'uint32_t',
    ),
  ),
  'Sns_Friend' => 
  array (
    'get' => 
    array (
      'cmd_id' => 'int16_t',
      'state' => 'uint8_t',
      'user_id' => 'uint32_t',
    ),
  'getFriendSingle' =>
	  array (
		  'cmd_id' => 'int16_t',
		  'state' => 'uint8_t',
		  'user_id' => 'uint32_t',
	  ),
    'invite' => 
    array (
      'cmd_id' => 'int16_t',
      'uid' => 'uint32_t',
      'user_id' => 'uint32_t',
    ),
  ),
);

//APPCAN,使用了网络请求 Request ，没有使用uexXmlHttpMgr, 新版本有改动，直接变为$_REQUEST
$input_data = trim(file_get_contents("php://input"));

file_put_contents("d.txt", $input_data);
file_put_contents("r.txt", json_encode($_REQUEST));

$user_request_data = array();

if ($input_data)
{
	parse_str($input_data, $user_request_data);
}

if ($user_request_data)
{
	$_REQUEST = array_merge($user_request_data, $_REQUEST);
}