<?php

defined('DEBUG') OR exit('Forbidden');

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

$method = strtolower($method);

$action = param(1);
empty($action) and $action = 'attachs';

$actions = array(
	'attachs',
	'setting',
	'read',
	'delete',
);

if (!in_array($action, $actions)) {
	message(1, jump('访问错误', url('attach_re')));
}

$hayaFile = HAYA_ATTACH_RE_INC.$action.'.php';
if (!file_exists($hayaFile)) {
	message(1, jump($action . '动作不存在', url('attach_re')));
}

include _include(HAYA_ATTACH_RE_ROOT.'model/attach.func.php');

include _include($hayaFile);
