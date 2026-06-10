<?php

defined('DEBUG') OR exit('Forbidden');

$action = 'setting';
$header['title'] = '附件管理设置';

$config = haya_attach_re_find_key();
$attach_count = attach_count();

include _include(HAYA_ATTACH_RE_HTML.'setting.htm');
