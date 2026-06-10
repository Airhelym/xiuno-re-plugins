<?php

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_post_like;
CREATE TABLE {$tablepre}haya_post_like (
	`tid` int(11) NOT NULL COMMENT '帖子ID',
	`pid` int(11) NOT NULL COMMENT '回帖ID',
	`uid` int(11) NOT NULL COMMENT '用户ID',
	`create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
	`create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
	KEY `tid` (`tid`),
	KEY `pid` (`pid`),
	KEY `uid` (`uid`),
	KEY `pid_uid` (`pid`, `uid`),
	KEY `tid_pid_uid` (`tid`, `pid`, `uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$r = db_exec($sql);

$sql = "
ALTER TABLE {$tablepre}post ADD COLUMN haya_post_likes int(11) NULL DEFAULT '0' COMMENT '点赞数';
";
$r = db_exec($sql);

$r === FALSE AND message(-1, '创建表结构失败');

$haya_post_like_re_config = array(
	"open_thread" => 1,
	"open_post" => 1,
	"hot_like_post_low_count" => 10,
	"hot_like_post_size" => 5,
	"show_like_users" => 1,
	"like_user_count" => 20,
);
setting_set('haya_post_like_re', $haya_post_like_re_config);
