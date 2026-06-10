<?php

!defined('DEBUG') and exit('Access Denied.');

include _include(APP_PATH.'plugin/haya_attach_re/model/attach.func.php');

define('HAYA_ATTACH_RE_ROOT', APP_PATH.'plugin/haya_attach_re/');
define('HAYA_ATTACH_RE_HTML', HAYA_ATTACH_RE_ROOT.'html/');
define('HAYA_ATTACH_RE_INC', HAYA_ATTACH_RE_ROOT.'inc/');

$menuTab = array(
	'attachs' => array(
		'url' => url('attach_re-attachs'),
		'text' => '附件管理',
	),
	'setting' => array(
		'url' => url('attach_re-setting'),
		'text' => '插件设置',
	),
);

$header['title'] = '附件管理设置';

if ($method == 'GET') {

	$action = 'setting';
	$config = haya_attach_re_find_key();
	$attach_count = attach_count();

	include _include(HAYA_ATTACH_RE_HTML.'setting.htm');

} else {

	$config = $_REQUEST['config'];

	if (empty($config)) {
		message(1, jump('配置不能为空', url('attach_re')));
	}

	foreach ($config as $key => $value) {
		$file = haya_attach_re_read_by_key($key);
		if (!empty($file)) {
			haya_attach_re_update($file['id'], array('value' => $value));
		} else {
			haya_attach_re_create(array(
				'key' => $key,
				'value' => $value,
			));
		}
	}

	message(0, jump('配置更改成功', url('plugin-setting-haya_attach_re')));
}
