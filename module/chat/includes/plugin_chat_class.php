<?php

class chat
{
	public static function getChatHtml($layout=null)
	{
		$web_html = '';

		if (true)
		{
			global $db;
			global $config;
			global $logo;

			$buid = Yf_Registry::get('buid');

			if (isset($config['weburl']))
			{
				$domain_root = $config['weburl'];
			}
			else
			{
				$domain_root = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'] . '/';
			}
			/*
			$avatar   = getMemberAvatar($_SESSION['avatar']);
			$nchash   = getNchash();
			$formhash = Security::getTokenValue();
			*/

			$css_url  = './templates/default';
			$app_url  = APP_SITE_URL;
			$chat_url = CHAT_SITE_URL;
			$node_url = NODE_SITE_URL;
			$shop_url = SHOP_SITE_URL;

			$web_html = <<<EOT
					<link href="{$css_url}/css/chat.css" rel="stylesheet" type="text/css">
					<link href="{$css_url}/css/home_login.css" rel="stylesheet" type="text/css">
					<link href="{$css_url}/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
					<div style="clear: both;"></div>
					<div id="web_chat_dialog" style="display: none;float:right;">
					</div>
					<a id="chat_login" href="javascript:void(0)" style="display: none;"></a>
					<script type="text/javascript">

    				window.domain_root = "{$domain_root}/";

					var APP_SITE_URL  = '{$app_url}';
					var CHAT_SITE_URL = '{$chat_url}';
					var SHOP_SITE_URL = '{$shop_url}';
					var connect_url   = "{$node_url}";

					var layout     = "{$layout}";
					var act_op     = "{$_GET['act']}_{$_GET['op']}";
					var user       = {};

					user['u_id']   = "{$buid}";
					user['u_name'] = "{$_COOKIE['USER']}";
					user['s_id']   = "{$_COOKIE['USER']}";
					user['s_name'] = "{$_COOKIE['USER']}";
					user['avatar'] = "{$logo}";

					/*
					$("#chat_login").nc_login({
					  action:'/index.php?act=login',
					  nchash:'{$nchash}',
					  formhash:'{$formhash}'
					});
					*/
					</script>
EOT;
			if (true || defined('APP_ID') && APP_ID != 'shop')
			{
				$web_html .= '<script type="text/javascript" src="./script/jquery.ui.js"></script>';
				$web_html .= '<script type="text/javascript" src="./script/perfect-scrollbar.min.js"></script>';
				$web_html .= '<script type="text/javascript" src="./script/jquery.mousewheel.js"></script>';
				$web_html .= '<script type="text/javascript" src="./script/ajaxfileupload.js"></script>';
			}

			$web_html .= '<script type="text/javascript" src="./script/jquery.charCount.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="./script/chat.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="./script/chat/jquery.smilies.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="./script/my_lightbox.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="./script/chat/user.js" charset="utf-8"></script>';
		}

		return $web_html;
	}




	public static function getChatWapHtml($layout=null)
	{
		$web_html = '';

		if (true)
		{
			global $db;
			global $config;
			global $buid;
			global $logo;

			/*
			$avatar   = getMemberAvatar($_SESSION['avatar']);
			$nchash   = getNchash();
			$formhash = Security::getTokenValue();
			*/

			if (isset($config['weburl']))
			{
				$domain_root = $config['weburl'];
			}
			else
			{
				$domain_root = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'] . '/';
			}

			$logo = 'image/default/avatar.png';
			$css_url  = './templates/default';
			$app_url  = '';
			$chat_url = '';
			$node_url = '';
			$shop_url = '';

			$web_html = <<<EOT
<link href="./templates/wap/css/page.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="./templates/wap/css/fonts/font-awesome.min.css">
<link rel="stylesheet" href="./templates/wap/css/ui-box.css">
<link rel="stylesheet" href="./templates/wap/css/ui-base.css">
<link rel="stylesheet" href="./templates/wap/css/ui-color.css">
<link rel="stylesheet" href="./templates/wap/css/appcan.icon.css">
<link rel="stylesheet" href="./templates/wap/css/appcan.control.css">
<link rel="stylesheet" href="./templates/wap/css/chat.css">
<script src="{$config['weburl']}/script/jquery.cookie.js" type="text/javascript"></script>
<script src="{$config['weburl']}/script/my_lightbox.js" type="text/javascript"></script>
<script type="text/javascript" src="{$config['weburl']}/script/chat.js"></script>

<!--<dl id="chat_icon" class="msg_notice_icon"></dl>-->

<dl class="msg_notice_icon" id="chat_icon" style="display: block;">
	<dt show_name="test" show_id="55" class="show_member_btn" id="user_55"> <span class="badge"> 1</span><img src="image/default/avatar.png"></dt>
</dl>

<div id="page_0" class="um-vp up ub-ver bc-bg" tabindex="0" style="z-index: 999999999999;">
	<!--header开始-->
	<div class="chat_header">
		<div id="header" class="uh bc-text-head ub bc-head" style="display: -moz-box;  display: -webkit-box;display: box !important;">
			<div class="nav-btn" id="nav-left">
				<div class="fa fa-angle-left fa-2x"></div>
			</div>
			<h1 class="ut ub-f1 ulev-3 ut-s tx-c" tabindex="0" id="fname"></h1>
			<div class="nav-btn nav-bt" id="nav-right"><!--
                <div class="iconfont ulev1 chat_with" id="go_member">
                    &#xe604;
                </div>-->
			</div>
		</div>
	</div>
	<!--header结束-->
	<!--content开始-->
	<div id="chat_content" class="ub-f1 tx-l tx-l t-bla bc-bg ub"></div>
	<!--content结束-->
</div>

<script>
	window.temp = '{$_SESSION['temp']}';
	window.domain_root = "{$domain_root}";

    function init_chat() {
		$.cookie('USER', '{$_COOKIE['USER']}');
		init_chat_ui('{$buid}', '{$_COOKIE['USER']}', '{$logo}');

		window.get_msg_intverval_id = setInterval("getMsgUnread()", 2000);

        $("#chat_icon").show();
        $("#page_0").hide();


        if ($("#go_member").length>0)
        {
            $("#go_member").click(function ()
            {
                if ($(this).hasClass('chat_with'))
                {
                    $('#fileUploaderEmptyHole')[0].src =  'templates/wap/chat_session.html';

                    $(this).removeClass('chat_with');
                    $(this).addClass('member_list');


                    $('#fname').html('会话列表');
                }
                else
                {
                    $('#fileUploaderEmptyHole')[0].src =  'templates/wap/chat_content.html';

                    $(this).removeClass('member_list');
                    $(this).addClass('chat_with');

                    $('#fname').html(locStorage['member:chat:user_name']);
                }
            });
        }


		{
			chat_win_init({'id':55, 'name':'test', 'logo':'image/default/avatar.png'});
		}
    }

    $(window).load(function(){
    	window.domain_root = "{$domain_root}";
        setTimeout("init_chat()", 3000);
    });
</script>
EOT;
		}

		return $web_html;
	}

}
?>