<?php

defined('DEBUG') OR exit('Forbidden');

$header['title'] = '附件管理';

$config = haya_attach_re_find_key();

$keyword = param('keyword');

$pagesize = 20;
$srchtype = param(2);
$keyword  = trim(xn_urldecode(param(3)));
$page     = param(4, 1);

$cond = array();
if ($keyword) {
	if (!in_array($srchtype, array(
		'uid',
		'filename',
		'filetype',
		'orgfilename',
		'gid',
		'tid')
	)) {
		$srchtype = 'orgfilename';
	}

	$cond[$srchtype] = array('LIKE' => $keyword);
}

$attachlist = array();

$n = haya_attach_re_file_count($cond);
$attachlist = attach_find($cond, array('create_date' => 1, 'aid' => 1), $page, $pagesize);
$pagination = pagination(url("attach_re-attachs-$srchtype-".urlencode($keyword).'-{page}'), $n, $page, $pagesize);
$pager = pager(url("attach_re-attachs-$srchtype-".urlencode($keyword).'-{page}'), $n, $page, $pagesize);

if (!empty($attachlist)) {
	foreach ($attachlist as &$_attach) {
		$_attach['thread'] = array_value($attachlist, $_attach['tid'], '');
	}
}

include _include(HAYA_ATTACH_RE_HTML.'attachs.htm');
