<?php

defined('DEBUG') OR exit('Forbidden');

$header['title'] = '查看附件';

$id = param(2);

if (empty($id)) {
	message(1, jump('id不能为空', url('attach_re-attachs')));
}

$action = 'attachs';

$attach = attach_read($id);
if (empty($attach)) {
	message(1, jump('文件不能存在', url('attach_re-attachs')));
}

$thread = thread_read($attach['tid']);
$post = post_read($attach['pid']);
$user = user_read($attach['uid']);

$config = haya_attach_re_find_key();

include _include(HAYA_ATTACH_RE_HTML.'read.htm');
