<?php

function haya_attach_re_create($arr) {
	$r = db_create('haya_attach', $arr);
	return $r;
}

function haya_attach_re_update($id, $arr) {
	$r = db_update('haya_attach', array(
		'id' => $id
	), $arr);
	return $r;
}

function haya_attach_re_read($id) {
	$haya_attach = db_find_one('haya_attach', array(
		'id' => $id
	));
	return $haya_attach;
}

function haya_attach_re_delete($id) {
	$r = db_delete('haya_attach', array('id' => $id));
	return $r;
}

function haya_attach_re_read_by_key($key) {
	$haya_attach = db_find_one('haya_attach', array(
		'key' => $key
	));
	return $haya_attach;
}

function haya_attach_re_find(
	$cond = array(), 
	$orderby = array(), 
	$page = 1, 
	$pagesize = 20
) {
	$haya_attach_list = db_find('haya_attach', $cond, $orderby, $page, $pagesize);
	return $haya_attach_list;
}

function haya_attach_re_find_key(
	$cond = array(), 
	$orderby = array(), 
	$page = 1, 
	$pagesize = 20
) {
	$haya_attach_list = haya_attach_re_find($cond, $orderby, $page, $pagesize);
	
	$haya_attach_key_list = array();
	foreach ($haya_attach_list as $haya_attach_list_key => $haya_attach_list_value) {
		$haya_attach_key_list[$haya_attach_list_value['key']] = $haya_attach_list_value;
	}
	return $haya_attach_key_list;
}

function haya_attach_re_maxid() {
	return db_maxid('haya_attach', 'id');
}

function haya_attach_re_file_count($cond = array()) {
	$n = db_count('attach', $cond);
	return $n;
}
