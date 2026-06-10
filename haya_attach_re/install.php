<?php

defined('DEBUG') OR exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "
CREATE TABLE IF NOT EXISTS {$tablepre}haya_attach (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`key` varchar(50) NOT NULL DEFAULT '' COMMENT '设置关键字',
	`value` varchar(200) NOT NULL DEFAULT '' COMMENT '配置信息',
	`desc` varchar(250) NOT NULL DEFAULT '' COMMENT '配置描述说明',
	`rank` int(11) NULL DEFAULT '0' COMMENT '配置等级',
	`status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
	PRIMARY KEY (id),
	KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
$r = db_exec($sql);

$r === FALSE AND message(-1, '安装插件失败');
