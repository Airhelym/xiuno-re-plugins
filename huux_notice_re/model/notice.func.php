<?php

function notice__create($arr) {
	$r = db_create('notice', $arr);
	return $r;
}

function notice__update($nid, $arr) {
	$r = db_update('notice', array('nid'=>$nid), $arr);
	return $r;
}

function notice__read($nid) {
	$post = db_find_one('notice', array('nid'=>$nid));
	return $post;
}

function notice__delete($nid) {
	$r = db_delete('notice', array('nid'=>$nid));
	return $r;
}

function notice__find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20) {
	$noticelist = db_find('notice', $cond, $orderby, $page, $pagesize, 'nid');
	return $noticelist;
}

function notice_send($fromuid, $recvuid, $message, $type = 99) {
	global $time;
	if(empty($fromuid) || empty($recvuid)) return FALSE;
	if($fromuid == $recvuid) return FALSE;
	$type == 0 AND $type = 99;
	$arr = array(
		'fromuid'=>$fromuid,
		'recvuid'=>$recvuid,
		'create_date'=>$time,
		'isread'=>0,
		'type'=>$type,
		'message'=>$message,
	);
	$nid = notice__create($arr);
	if($nid === FALSE) return FALSE;
	user__update($recvuid, array('unread_notices+'=>1, 'notices+'=>1));
	return $nid;
}

function notice_find_by_recvuid($recvuid, $page = 1, $pagesize = 20, $type = 99) {
	$cond = array('recvuid'=>$recvuid, 'type'=>$type);
	$type == 0 AND $cond = array('recvuid'=>$recvuid);
	$noticelist = notice_find($cond, $page, $pagesize);
	return $noticelist;
}

function notice_update_by_recvuid($recvuid, $arr = array('isread'=>1)) {
	$r = db_update('notice', array('recvuid'=>$recvuid), $arr);
	if($r === FALSE) return FALSE;
	user__update($recvuid, array('unread_notices'=>0));
	return $r;
}

function notice_update($nid, $arr = array('isread'=>1)) {
	$notice = notice__read($nid);
	if(empty($notice)) return FALSE;
	$recvuid = $notice['recvuid'];
	$r = notice__update($nid, $arr);
	if($r === FALSE) return FALSE;
	user__update($recvuid, array('unread_notices-'=>1));
	return $r;
}

function notice_delete($nid) {
	$notice = notice__read($nid);
	if(empty($notice)) return TRUE;
	$recvuid = $notice['recvuid'];
	$isread = $notice['isread'];
	$r = notice__delete($nid);
	if($r === FALSE) return FALSE;
	user__update($recvuid, array('notices-'=>1));
	$isread == 0 AND user__update($recvuid, array('unread_notices-'=>1));
	return $r;
}

function notice_delete_by_recvuid($recvuid) {
	$r = db_delete('notice', array('recvuid'=>$recvuid));
	if($r === FALSE) return FALSE;
	user__update($recvuid, array('unread_notices'=>0, 'notices'=>0));
	return $r;
}

function notice_find($cond = array(), $page = 1, $pagesize = 20) {
	$noticelist = notice__find($cond, array('nid'=>-1), $page, $pagesize);
	if($noticelist) foreach($noticelist as &$notice) notice_format($notice);
	return $noticelist;
}

function notice_find_by_nids($nids, $order = array('nid'=>-1)) {
	if(!$nids) return array();
	$noticelist = db_find('notice', array('nid'=>$nids), $order, 1, 1000, 'nid');
	if($noticelist) foreach($noticelist as &$notice) notice_format($notice);
	return $noticelist;
}

function notice_format(&$notice) {
	global $notice_menu;
	if(empty($notice)) return;
	$notice['create_date_fmt'] = humandate($notice['create_date']);
	$fromuser = user_read_cache($notice['fromuid']);
	$recvuser = user_read_cache($notice['recvuid']);
	$notice['from_username'] = $fromuser['username'];
	$notice['from_user_avatar_url'] = $fromuser['avatar_url'];
	$notice['recv_username'] = $recvuser['username'];
	$notice['recv_user_avatar_url'] = $recvuser['avatar_url'];
	!isset($notice_menu[$notice['type']]) AND $notice['type'] = 99;
	$notice['name'] = isset($notice_menu[$notice['type']]['name']) ? $notice_menu[$notice['type']]['name'] : 'message';
	$notice['class'] = isset($notice_menu[$notice['type']]['class']) ? $notice_menu[$notice['type']]['class'] : 'notice-type-other';
	$notice['icon'] = isset($notice_menu[$notice['type']]['icon']) ? $notice_menu[$notice['type']]['icon'] : '';

	$notice['message_escaped'] = xn_html_safe($notice['message']);
	$notice['from_username_escaped'] = htmlspecialchars($notice['from_username'], ENT_QUOTES, 'UTF-8');
	$notice['recv_username_escaped'] = htmlspecialchars($notice['recv_username'], ENT_QUOTES, 'UTF-8');
}

function notice_message_fmt(&$arr, $gid) {
	$arr['message'] = ($gid == 1 ? $arr['message'] : xn_html_safe(xn_substr($arr['message'], 0, 255)));
}

function notice_substr($s, $len = 20, $htmlspe = TRUE) {
	if($htmlspe == FALSE){
		$s = strip_tags($s);
		$s = htmlspecialchars($s);
	}
	$more = xn_strlen($s) > $len ? '...' : '';
	$s = xn_substr($s, 0, $len).$more;
	return $s;
}

function notice_count($cond = array()) {
	$n = db_count('notice', $cond);
	return $n;
}

?>
