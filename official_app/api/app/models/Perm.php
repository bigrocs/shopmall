<?php

class Perm
{
	public static $login	= false;
	public static $userId	= 0;
	public static $_COOKIE	= array();
	public static $key		= array('player_id');

	/**
	 * 初始化登录的用户信息cookie
	 *
	 * @access public
	 *
	 * @return Array  $user_row;
	 */
	public static function getUserByCookie()
	{
		$user_row = array();

		if (array_key_exists('key', $_COOKIE))
		{
			$encrypt_str = $_COOKIE['key'];
			$user_row = self::decryptUserInfo($encrypt_str);
		}
		else
		{
			
		}

		return $user_row;
	}

	/**
	 * 用户数组信息编码成字符串， 设置cookie
	 *
	 * @param array $user_row  用户信息
	 * @access public
	 *
	 * @return string  $encrypt_str;
	 */
	public static function encryptUserInfo($user_row = null)
	{
		$user_str = http_build_query($user_row);

		$user_str = str_replace('&amp;', '&', $user_str);

		$encrypt_str = Yf_Hash::encrypt($user_str);

		$expires = time() + 60*60*24*3;

		//setcookie("key", $encrypt_str, $expires, '/');
		setcookie("key", $encrypt_str);

		return $encrypt_str;
	}


	/**
	 * 用户logout
	 *
	 * @access public
	 *
	 * @return bool  true/false;
	 */
	public static function removeUserInfo()
	{
		$expires = time() - 3600;

		//setcookie('key', '', $expires, '/');
		setcookie('key', '', $expires);

		return $true;
	}

	/**
	 * 还原cookie信息为数组
	 *
	 * @param string  $encrypt_str;
	 * @access public
	 *
	 * @return array $user_row  用户信息
	 */
	public static function decryptUserInfo($encrypt_str = null)
	{
		if (!$encrypt_str)
		{
			//$encrypt_str = 'AnUJfwM5ACJdVFNtU2tbMAJkBnAOJVUiUjcFfQhSBjoJalI6UGoAbV1zAT8GNFR4VGZUIgwnBnECZwZ+CFJVaQJpCW8DNwA+XWpTaVNqWzACLQY/Dj5VK1I3BSkIbAY4CWdSPlBuAD5daAEzBgVUa1RnVDkMYwYkAm8GbQh9VVgCaQloA2EAZF07UzZTO1s9AmYGcA4zVThSJgV2CFIGOglqUjpQagBtXXMBPwY0VHhUZlQhDBcGNQInBjUITFUiAjgJOAN5ABVdPlMhUzZbSwJwBm4OFVV0UhcFOQgoBhYJOlJyUE4AYF0tAToGNVRlVGpUagwNBnYCawZhCGhVdAI9CT0Dbg==';
		}

		$decrypt_str = Yf_Hash::decrypt($encrypt_str);
		parse_str($decrypt_str, $user_row);

		return $user_row;
	}


	/**
	 * 判断用户是否拥有访问权限
	 *
	 * @return bool true/false 
	 */
	public static function checkUserPerm()
	{
        throw new Yf_ProtocalException(_('_COOKIE：') . json_encode($_COOKIE), 2, 0);
        //临时使用，明显有漏洞
        if (isset($_COOKIE["USER"]))
        {
            //
		    $user = $_COOKIE["USER"];

            $member_model = new MemberModel();
            $member_row   = $member_model->getMemberByUsername($user);

            self::$userId = $member_row['userid'];
            
            return true;
        }
        else
        {
            
        }
	}

	public static function getPlayerId()
	{
		return isset(self::$_COOKIE['player_id']) ? self::$_COOKIE['player_id'] : 0;
	}

	/**
	 * 判断用户所属网站
	 *
	 * @return bool true/false 
	 */
	public static function isRenrenUser()
	{
		if (isset($_REQUEST['xn_sig_user']) && isset($_REQUEST['xn_sig_domain']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>