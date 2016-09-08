<?php
class Yf_Utils_String
{
    public static function isEmail($email) 
    {
        /*
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (preg_match($pattern, $email_address))
        {
            $user_name = preg_replace( $pattern ,"$1", $email_address );
            $domain_name = preg_replace( $pattern ,"$2", $email_address );
        }
        else
        {
        }
        */

        //匹配包括._-在内的各种邮箱
        //$email = 'love.you-2013_mark@siteuurl.com.cn.gd.fuck';

        //$regex = '/^[\w\d][0-9a-z-._]+@{1}([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/i';
        $regex = '/^[0-9a-z][0-9a-z-._]+@{1}[0-9a-z.-]+[a-z]{2,4}$/i';

        if (preg_match($regex, $email, $match))
        {
            return true;
        }
     
        return false;
    }
}
?>