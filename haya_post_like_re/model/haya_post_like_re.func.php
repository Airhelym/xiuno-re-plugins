<?php

function haya_post_like_re_create($arr) {
	$r = db_create('haya_post_like', $arr);
	return $r;
}

function haya_post_like_re_count($cond = array()) {
	$n = db_count('haya_post_like', $cond);
	return $n;
}

function haya_post_like_re_find(
	$cond = array(),
	$orderby = array(),
	$page = 1,
	$pagesize = 20
) {
	$likes = db_find('haya_post_like', $cond, $orderby, $page, $pagesize);

	if (!empty($likes)) {
		foreach ($likes as & $like) {
			$like['post'] = post_read($like['pid']);
			$like['user'] = user_read($like['uid']);
		}
	}

	return $likes;
}

function haya_post_like_re_find_by_uid_and_pid($uid, $pid) {
	$r = db_find('haya_post_like', array('uid' => $uid, 'pid' => $pid));
	return empty($r) ? false : true;
}

function haya_post_like_re_find_by_pid($pid, $num = 20) {
	$likes = haya_post_like_re_find(array('pid' => $pid), array('create_date' => -1), 1, $num);
	return $likes;
}

function haya_post_like_re_find_by_pids($pids) {
	if (!$pids) {
		return array();
	}

	$orderby = array('create_date' => -1);
	$r = db_find('haya_post_like', array('pid' => $pids), $orderby, 1, 1000, 'pid');
	return $r;
}

function haya_post_like_re_find_users_by_pid($pid, $num = 20) {
	$likes = db_find('haya_post_like', array('pid' => $pid), array('create_date' => -1), 1, $num);
	if (!empty($likes)) {
		foreach ($likes as & $like) {
			$like['like_user'] = user_read($like['uid']);
		}
	}
	return $likes;
}

function haya_post_like_re_delete_by_tid($tid) {
	$r = db_delete('haya_post_like', array('tid' => $tid));
	return $r;
}

function haya_post_like_re_delete_by_pid($pid) {
	$r = db_delete('haya_post_like', array('pid' => $pid));
	return $r;
}

function haya_post_like_re_delete_by_pid_and_uid($pid, $uid) {
	$r = db_delete('haya_post_like', array('pid' => $pid, 'uid' => $uid));
	return $r;
}

function haya_post_like_re_loves($pid, $n = 1) {
	$r = db_update('post', array('pid' => $pid), array('haya_post_likes+' => $n));
	return $r;
}

function haya_post_like_re_find_hot_loves_by_tid($tid, $num = 5, $thumbup = 10) {
	$likes = post_find(array('tid' => $tid, 'haya_post_likes' => array(">=" => $thumbup)), array('haya_post_likes' => -1, 'create_date' => -1), 1, $num);
	return $likes;
}
