<?php $wechat_config = unserialize('a:4:{s:6:"wechat";s:6:"winxin";s:13:"wechat_app_id";s:2:"11";s:17:"wechat_app_secret";s:1:"1";s:15:"wechat_app_item";s:2008:"{
    \"button\": [
        {
            \"name\": \"点击跳转\",
            \"sub_button\": [
                {
                    \"type\": \"click\",
                    \"name\": \"点击推事件\",
                    \"key\": \"FANGBEI\"
                },
                {
                    \"type\": \"view\",
                    \"name\": \"跳转URL\",
                    \"url\": \"http://mp.weixin.qq.com/s?__biz=MzA5NzM2MTI4OA==&mid=205215861&idx=1&sn=890e36a114827cb510542315dd706c9d#rd\"
                },
                {
                    \"type\": \"view\",
                    \"name\": \"微信登录\",
                    \"url\": \"http://aunt.sinaapp.com/demo/oauth2/index.php\"
                }
            ]
        },
        {
            \"name\": \"扫码发图\",
            \"sub_button\": [
                {
                    \"type\": \"scancode_waitmsg\",
                    \"name\": \"扫码带提示\",
                    \"key\": \"rselfmenu_0_0\"
                },
                {
                    \"type\": \"scancode_push\",
                    \"name\": \"扫码推事件\",
                    \"key\": \"rselfmenu_0_1\"
                },
                {
                    \"type\": \"pic_sysphoto\",
                    \"name\": \"系统拍照发图\",
                    \"key\": \"rselfmenu_1_0\"
                },
                {
                    \"type\": \"pic_photo_or_album\",
                    \"name\": \"拍照或相册发图\",
                    \"key\": \"rselfmenu_1_1\"
                },
                {
                    \"type\": \"pic_weixin\",
                    \"name\": \"微信相册发图\",
                    \"key\": \"rselfmenu_1_2\"
                }
            ]
        },
        {
            \"name\": \"发送位置\",
            \"type\": \"location_select\",
            \"key\": \"rselfmenu_2_0\"
        }
    ]
}";}');?>