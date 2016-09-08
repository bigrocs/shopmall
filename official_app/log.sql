ALTER TABLE `mallbuilder_shop` add `lng` varchar(50) NOT NULL COMMENT '经度';
ALTER TABLE `mallbuilder_shop` add `lat` varchar(50) NOT NULL COMMENT '纬度'

ALTER TABLE `mallbuilder_sns` add `like_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数';
ALTER TABLE `mallbuilder_sns_comment` add `like` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否点赞'



