
--
-- 表的结构 `pay_admin`
--

CREATE TABLE IF NOT EXISTS `pay_admin` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user` char(30) NOT NULL COMMENT '帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '用户名',
  `password` char(35) NOT NULL COMMENT '密码',
  `group_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '会员组',
  `desc` text COMMENT '描述',
  `logonums` int(5) DEFAULT '0' COMMENT '登录次数',
  `lastlogotime` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `logoip` varchar(30) DEFAULT NULL COMMENT '登录IP',
  `province` varchar(60) DEFAULT NULL COMMENT '省',
  `city` varchar(60) DEFAULT NULL COMMENT '市',
  `area` varchar(60) DEFAULT NULL COMMENT '区',
  `type` smallint(1) unsigned DEFAULT NULL COMMENT '1manager',
  `lang` varchar(10) DEFAULT NULL COMMENT '语言',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `pay_admin`
--

INSERT INTO `pay_admin` (`id`, `user`, `name`, `password`, `group_id`, `desc`, `logonums`, `lastlogotime`, `logoip`, `province`, `city`, `area`, `type`, `lang`) VALUES
(1, 'admin', NULL, '21232f297a57a5a743894a0e4a801fc3', 0, NULL, 6, 1456283260, '101.81.140.40', NULL, NULL, NULL, 1, 'cn');

-- --------------------------------------------------------

--
-- 表的结构 `pay_admin_menu`
--

CREATE TABLE IF NOT EXISTS `pay_admin_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `displayorder` int(3) NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_admin_operation_log`
--

CREATE TABLE IF NOT EXISTS `pay_admin_operation_log` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user` varchar(20) DEFAULT NULL COMMENT '管理员帐号',
  `scriptname` varchar(50) DEFAULT NULL COMMENT '文件名',
  `url` varchar(200) DEFAULT NULL COMMENT '地址',
  `time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员操作记录表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_card`
--

CREATE TABLE IF NOT EXISTS `pay_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `card_num` varchar(30) NOT NULL COMMENT '卡号',
  `total_price` int(11) NOT NULL COMMENT '面额',
  `give_price` float(10,2) DEFAULT '0.00' COMMENT '赠送',
  `password` varchar(30) NOT NULL COMMENT '密码',
  `statu` tinyint(4) NOT NULL COMMENT '状态',
  `use_name` varchar(20) DEFAULT NULL COMMENT '使用者',
  `creat_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `stime` int(10) unsigned DEFAULT NULL COMMENT '有效开始时间',
  `etime` int(10) unsigned DEFAULT NULL COMMENT '到期时间',
  `pic` varchar(80) DEFAULT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值卡表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_cashflow`
--

CREATE TABLE IF NOT EXISTS `pay_cashflow` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pay_uid` int(11) DEFAULT NULL COMMENT '会员支付ID',
  `member_name` varchar(50) DEFAULT NULL COMMENT '会员名',
  `buyer_email` varchar(50) DEFAULT NULL COMMENT '买家账号',
  `seller_email` varchar(50) DEFAULT NULL COMMENT '卖家账号',
  `price` decimal(10,2) DEFAULT NULL COMMENT '金钱',
  `dist_commission_out` float(15,4) NOT NULL DEFAULT '0.0000' COMMENT '分销佣金',
  `flow_id` varchar(50) DEFAULT NULL COMMENT '流水账号',
  `order_id` varchar(18) DEFAULT NULL COMMENT '外部订单号',
  `note` varchar(255) DEFAULT NULL COMMENT '注解',
  `censor` varchar(50) DEFAULT NULL COMMENT '管理员',
  `time` int(11) unsigned DEFAULT NULL COMMENT '时间',
  `statu` tinyint(1) DEFAULT NULL COMMENT '0取消,1待处理,2已付款,3.发货中,4.成功,5.退货中,6.退货成功',
  `is_refund` enum('true','false') DEFAULT 'false',
  `refund_amount` float(10,2) DEFAULT '0.00',
  `return_url` varchar(200) DEFAULT NULL COMMENT '返回地址',
  `notify_url` varchar(200) DEFAULT NULL COMMENT '通知地址',
  `extra_param` varchar(100) DEFAULT NULL COMMENT '额外参数',
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '1直接到账 2担保接口',
  `mold` tinyint(2) DEFAULT '0' COMMENT '类型',
  `display` tinyint(1) unsigned DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金明细表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_cashpickup`
--

CREATE TABLE IF NOT EXISTS `pay_cashpickup` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pay_uid` int(8) NOT NULL COMMENT '会员支付ID',
  `cashflowid` varchar(50) DEFAULT NULL COMMENT '流水账号ID',
  `amount` decimal(10,2) NOT NULL COMMENT '总数',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `censor` varchar(50) DEFAULT NULL COMMENT '管理员',
  `check_time` int(11) DEFAULT NULL COMMENT '操作时间',
  `is_succeed` tinyint(2) DEFAULT '0' COMMENT '是否成功',
  `bankflow` varchar(50) DEFAULT NULL COMMENT '银行流水账号',
  `con` text COMMENT '描述',
  `bank` varchar(50) DEFAULT NULL COMMENT '银行',
  `cardno` varchar(32) DEFAULT NULL,
  `cardname` varchar(50) DEFAULT NULL,
  `supportTime` int(6) DEFAULT '0',
  `fee` float(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现申请表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_member`
--

CREATE TABLE IF NOT EXISTS `pay_member` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_email` varchar(100) DEFAULT NULL,
  `pay_mobile` varchar(30) DEFAULT NULL,
  `login_pass` varchar(32) DEFAULT NULL,
  `pay_pass` varchar(32) DEFAULT NULL,
  `real_name` varchar(30) DEFAULT NULL,
  `identity_card` varchar(30) DEFAULT NULL,
  `identity_pic` varchar(255) DEFAULT NULL,
  `profession` varchar(20) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `userid` int(11) unsigned DEFAULT NULL,
  `cash` decimal(8,2) DEFAULT '0.00',
  `dist_commission_out` float(15,4) NOT NULL DEFAULT '0.0000' COMMENT ' 商家分佣花费',
  `dist_commission_in` float(15,4) NOT NULL DEFAULT '0.0000' COMMENT '分销赚取佣金',
  `unreachable` decimal(10,2) DEFAULT '0.00',
  `email_verify` enum('true','false') DEFAULT 'false',
  `mobile_verify` enum('true','false') DEFAULT 'false',
  `identity_verify` enum('true','false','refused') DEFAULT 'false',
  `question` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `regtime` int(10) DEFAULT NULL,
  `lastLoginTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付会员表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_service_fee`
--

CREATE TABLE IF NOT EXISTS `pay_service_fee` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `fee_rates` float(12,2) DEFAULT '0.00',
  `fee_min` int(2) DEFAULT '0',
  `fee_max` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
INSERT INTO `pay_service_fee` (`id`, `name`, `fee_rates`, `fee_min`, `fee_max`) VALUES
(1, '2小时内到账', 0.50, 2, 25),
(2, '次日24点前到账', 0.15, 2, 25),
(4, '次日48点前到账', 0.05, 2, 25);
--
-- 表的结构 `pay_session`
--

CREATE TABLE IF NOT EXISTS `pay_session` (
  `sesskey` char(32) NOT NULL,
  `expiry` int(11) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`sesskey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pay_type`
--

CREATE TABLE IF NOT EXISTS `pay_type` (
  `payment_id` tinyint(3) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `payment_type` varchar(20) DEFAULT NULL COMMENT '类型',
  `payment_name` varchar(100) DEFAULT NULL COMMENT '名称',
  `payment_commission` varchar(8) DEFAULT '0' COMMENT '手续费',
  `payment_desc` text COMMENT '描述',
  `payment_config` text COMMENT '配置',
  `active` tinyint(1) DEFAULT '0' COMMENT '是否启用',
  `nums` tinyint(3) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`payment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付方式表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_web_con`
--

CREATE TABLE IF NOT EXISTS `pay_web_con` (
  `con_id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `con_desc` mediumtext COMMENT '描述',
  `con_province` varchar(20) DEFAULT NULL COMMENT '省',
  `con_city` varchar(20) DEFAULT NULL COMMENT '市',
  `con_no` int(2) DEFAULT '0' COMMENT '排序',
  `con_statu` int(1) DEFAULT '0' COMMENT '状态',
  `con_title` varchar(30) DEFAULT NULL COMMENT '标题',
  `con_linkaddr` varchar(60) DEFAULT NULL COMMENT '链接地址',
  `con_group` tinyint(3) NOT NULL COMMENT '组',
  `template` varchar(50) DEFAULT NULL COMMENT '模板',
  `title` varchar(200) DEFAULT NULL COMMENT 'SEO标题',
  `keywords` varchar(200) DEFAULT NULL COMMENT 'SEO关键字',
  `description` varchar(200) DEFAULT NULL COMMENT 'SEO描述',
  `msg_online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否调用留言板',
  `lang` varchar(5) DEFAULT NULL COMMENT '语言',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型',
  PRIMARY KEY (`con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站初始化内容设置' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_web_config`
--

CREATE TABLE IF NOT EXISTS `pay_web_config` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ＩＤ',
  `index` varchar(30) NOT NULL COMMENT '数组下标',
  `value` text NOT NULL COMMENT '数组值',
  `statu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
  `type` varchar(50) DEFAULT NULL COMMENT '类型',
  `des` text COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `index` (`index`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站配置表' AUTO_INCREMENT=119 ;

--
-- 转存表中的数据 `pay_web_config`
--

INSERT INTO `pay_web_config` (`id`, `index`, `value`, `statu`, `type`, `des`) VALUES
(1, 'company', '支付宝', 1, 'main', NULL),
(2, 'weburl', 'http://mall.wangchangming.com/pay', 1, 'main', NULL),
(3, 'baseurl', '', 1, 'main', NULL),
(4, 'logo', '', 1, 'main', NULL),
(5, 'owntel', '400-8888-888', 1, 'main', NULL),
(6, 'email', 'admin@yeshijun.com', 1, 'main', NULL),
(7, 'regname', 'register.php', 1, 'main', NULL),
(8, 'cacheTime', '0', 1, 'main', NULL),
(9, 'money', '¥', 1, 'main', NULL),
(10, 'date_format', '%Y-%m-%d', 1, 'main', NULL),
(11, 'language', 'cn', 1, 'main', NULL),
(12, 'temp', 'default', 1, 'main', NULL),
(13, 'domaincity', '1', 1, 'main', NULL),
(14, 'enable_gzip', '0', 1, 'main', NULL),
(15, 'enable_tranl', '0', 1, 'main', NULL),
(16, 'openstatistics', '1', 1, 'main', NULL),
(17, 'copyright', '', 1, 'main', NULL),
(18, 'closetype', '0', 1, 'main', NULL),
(19, 'closecon', '', 1, 'main', NULL),
(20, 'opensuburl', '0', 1, 'seo', NULL),
(21, 'rewrite', '0', 1, 'seo', NULL),
(22, 'title', '', 1, 'seo', NULL),
(23, 'keyword', '', 1, 'seo', NULL),
(50, 'email', 'admin@systerm.com', 1, 'module_payment', NULL),
(83, 'pay_name', '微用宝', 1, 'main', NULL),
(117, 'web_url_name', '', 1, 'main', NULL),
(118, 'web_url', 'http://mall.wangchangming.com', 1, 'main', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `pay_web_con_group`
--

CREATE TABLE IF NOT EXISTS `pay_web_con_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(60) DEFAULT NULL COMMENT '标题',
  `lang` varchar(5) DEFAULT NULL COMMENT '语言',
  `sort` smallint(4) DEFAULT '0' COMMENT '排序',
  `logo` varchar(100) DEFAULT NULL COMMENT 'logo',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站初始化内容分组表' AUTO_INCREMENT=1 ;

