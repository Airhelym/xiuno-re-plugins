<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(3);
empty($action) AND $action = 'set';

$search_conf = kv_get('search_conf');
if(empty($search_conf)) {
	$search_conf = array(
		'type'=>'like',
		'range'=>1,
		'fuzzy'=>1,
		'site_url' => 'https://www.baidu.com/s?wd=site%3A'._SERVER('HTTP_HOST').'%20{keyword}',
	);
	kv_set('search_conf', $search_conf);
} elseif(!isset($search_conf['fuzzy'])) {
	$search_conf['fuzzy'] = 1;
	kv_set('search_conf', $search_conf);
}

if($action == 'set') {

	if($method == 'GET') {

		$input['site_url'] = form_text('site_url', $search_conf['site_url'], '100%');
		include _include(APP_PATH.'plugin/xn_search_re/htm/setting.htm');

	} else {

		$search_conf['type'] = param('type');
		$search_conf['range'] = param('range');
		$search_conf['fuzzy'] = param('fuzzy', 1);
		$search_conf['site_url'] = param('site_url');
		kv_set('search_conf', $search_conf);

		message(0, lang('setting_saved'));
	}

} elseif($action == 'cn_encode') {

	$posts = $runtime['posts'] + $runtime['threads'];
	$input = array();
	$subject_start = intval(kv_get('xn_search_subject_start'));
	$post_start = intval(kv_get('xn_search_post_start'));
	$input['post_start'] = form_text('post_start', $post_start);
	$input['subject_start'] = form_text('subject_start', $subject_start);
	include _include(APP_PATH.'plugin/xn_search_re/htm/setting_cn_encode.htm');

} elseif($action == 'rebuild') {

	$range = param(4, 1);
	$start = param(5, 0);
	$limit = $range == 0 ? 2000 : 1000;

	if($range == 1) {

		$threads = $runtime['threads'];
		$page = max(1, ceil(($start + 1) / $limit));
		$tidlist = db_find('thread', array(), array('tid'=>1), $page, $limit, 'tid', array('tid'));
		if(empty($tidlist)) {
			$start = $threads;
			kv_set('xn_search_subject_start', $start);
			message(0, jump(lang('rebuild_done'), url('plugin-setting-xn_search_re-cn_encode')));
		} else {
			$tids = arrlist_values($tidlist, 'tid');
			$threadlist = db_find('thread', array('tid'=>$tids), array(), 1, 1000, 'tid');
			foreach ($threadlist as &$thread) {
				$tid = $thread['tid'];
				$subject_cn_encode = search_cn_encode($thread['subject']);
				db_replace('thread_search', array('tid'=>$tid, 'message'=>$subject_cn_encode));
			}

			$start += $limit;
			kv_set('xn_search_subject_start', $start);
		}
		$url = url("plugin-setting-xn_search_re-rebuild-$range-$start");
		message(0, jump(lang('rebuilding_subject')."：$threads, ".lang('rebuilding_current').($start - $limit), $url, 1));

	} elseif($range == 2) {

		$posts = $runtime['posts'] + $runtime['threads'];
		$page = max(1, ceil(($start + 1) / $limit));
		$pidlist = db_find('post', array(), array('pid'=>1), $page, $limit, 'pid', array('pid'));

		if(empty($pidlist)) {
			$start = $posts;
			kv_set('xn_search_post_start', $start);
			message(0, jump(lang('rebuild_done'), url('plugin-setting-xn_search_re-cn_encode')));
		} else {
			$pids = arrlist_values($pidlist, 'pid');
			$postlist = db_find('post', array('pid'=>$pids), array(), 1, $limit);
			foreach($postlist as $post) {
				$pid = $post['pid'];
				$s = strip_tags($post['message_fmt']);
				$s = preg_replace('#\[.*?\]#', '', $s);
				$message_cn_encode = search_cn_encode(strip_tags($s));
				db_replace('post_search', array('pid'=>$pid, 'message'=>$message_cn_encode));
			}
			$start += $limit;
			kv_set('xn_search_post_start', $start);
		}
		$url = url("plugin-setting-xn_search_re-rebuild-$range-$start");
		message(0, jump(lang('rebuilding_posts')."：$posts, ".lang('rebuilding_current').($start - $limit), $url, 5));

	}
}

?>
