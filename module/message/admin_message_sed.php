<?php
include_once("$config[webroot]/module/message/includes/plugin_msg_class.php");
$msg=new msg();
//============================================================

if (isset($_POST['msgsend'])&&$_POST['sendid']!=',')
{

    global $buid,$admin;
    if (!empty($_POST['senduser'])&&!empty($_POST['msgcon']))
    {
        $date=date("Y-m-d H:i:s");
        $userr = explode(",",$_POST['senduser']);
        if (count($userr) <= 1){
            $sear=explode(';',$_POST['senduser']);
            //print_r($sear);
            $sear1=array_unique($sear);

            $sear1=implode("','",$sear1);

            $sql="select user,email,userid from ".MEMBER." where user in ('".$sear1."0')";
            $db->query($sql);
            $re=$db->getRows();

            foreach($re as $v)
            {
                $sql="insert into ".FEEDBACK." (touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES
				('$v[userid]','$buid','Business Friends Message','$_POST[msgtitle]','$_POST[msgcon]','$date','1')";
                $db->query($sql);

                $sql="insert into ".FEEDBACK." (touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES
				('$v[userid]','$buid','Business Friends Message','$_POST[msgtitle]','$_POST[msgcon]','$date','2')";
                $db->query($sql);
                //-----------------如果是回复邮件标记为已回复
                $db->query("UPDATE ".FEEDBACK." set iflook='3' where id='$_GET[id]'");

                if(!empty($_POST['semail'])&&$v["email"])
                {
                    send_mail($v["email"],$v["name"],$_POST['msgtitle'],$_POST['msgcon']);
                }
            }
        }else{
            $date=date("Y-m-d H:i:s");
            $sea = explode(";",$_POST['senduser']);


            $sql="select user,email,userid from " . MEMBER . " where LOCATE(user ,'$sea[0]') > 0";
            $db->query($sql);
            $re=$db->getRows();

            foreach($re as $v)
            {
                $sql="insert into ".FEEDBACK." (touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES
            ('$v[userid]','$buid','Business Friends Message','$_POST[msgtitle]','$_POST[msgcon]','$date','2')";
                $db->query($sql);

                $db->query("UPDATE ".FEEDBACK." set iflook='3' where id='$_GET[id]'");

                if(!empty($_POST['semail'])&&$v["email"])
                {
                    send_mail($v["email"],$v["name"],$_POST['msgtitle'],$_POST['msgcon']);
                }

            }


            foreach($re as $v)
            {
                $sql="insert into ".FEEDBACK." (touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES
            ('$v[userid]','$buid','Business Friends Message','$_POST[msgtitle]','$_POST[msgcon]','$date','1')";
                $db->query($sql);
                $db->query("UPDATE ".FEEDBACK." set iflook='3' where id='$_GET[id]'");

                if(!empty($_POST['semail'])&&$v["email"])
                {
                    send_mail($v["email"],$v["name"],$_POST['msgtitle'],$_POST['msgcon']);
                }
            }


        }
        $admin->msg("main.php?m=message&s=admin_message_list_inbox");//发送成功
    }
    else
        $admin->msg("main.php?m=message&s=admin_message_sed&msgsend=error");

}
//------------------邮件详情
if(!empty($_GET['id']))
{
    $de=$msg ->mail_det($_GET['id']);
    $tpl->assign("de",$de);
    $_GET['uid']=$de['fromuserid'];
}
//--------------------收件人


if (!empty($_GET['uid']))
{
    $mess = rtrim($_GET['uid'], ",");

    $sql = "SELECT fuid FROM " . FRIEND . " WHERE id in($mess) ";
    $db->query($sql);
    $res =$db->getRows();
    $arr1=array();
    foreach($res as $v){
        array_push($arr1,$v['fuid']);
    }
    if (!empty($arr1)){
        $mes = implode(",",$arr1);

        $sql="select user from " . MEMBER . " where userid in ($mes) ";
    }else{
        $sql="select user from " . MEMBER . " where userid='$_GET[uid]'";

    }

    $db->query($sql);
    $rr = $db->getRows();

    $arr=array();
    foreach($rr as $vv){
        array_push($arr,$vv['user']);
    }

    $auser = implode(",",$arr);

    $tpl->assign("auser",$auser);

}
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_message_sed.htm");
?>