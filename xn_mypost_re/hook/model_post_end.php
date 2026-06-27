
function xn_mypost_re_setting_get() {
    $s = kv_get('xn_mypost_re_setting');
    if (empty($s)) {
        $s = kv_get('xn_mypost_setting');
        if (!empty($s)) kv_set('xn_mypost_re_setting', $s);
    }
    empty($s) AND $s = array();
    $s['show_on_my'] = isset($s['show_on_my']) ? $s['show_on_my'] : 1;
    $s['show_on_user'] = isset($s['show_on_user']) ? $s['show_on_user'] : 1;
    $s['posts_only'] = isset($s['posts_only']) ? $s['posts_only'] : 1;
    return $s;
}

function post_find_by_uid($uid, $page = 1, $pagesize = 50, $posts_only = FALSE) {
    global $conf;

    // hook model_post_find_by_uid_start.php

    if ($posts_only) {
        $total = db_count('post', array('uid'=>$uid, 'isfirst'=>0));
        $arrlist = db_find('post', array('uid'=>$uid, 'isfirst'=>0), array('pid'=>-1), $page, $pagesize, '', array('pid'));
    } else {
        $total = db_count('post', array('uid'=>$uid));
        $arrlist = db_find('post', array('uid'=>$uid), array('pid'=>-1), $page, $pagesize, '', array('pid'));
    }

    $pids = arrlist_values($arrlist, 'pid');
    if (empty($pids)) return array('total' => 0, 'list' => array());

    $postlist = post_find_by_pids($pids);
    $postlist = arrlist_multisort($postlist, 'pid', FALSE);

    foreach ($postlist as $k => &$post) {
        // truncate message for short preview
        $msg = strip_tags($post['message_fmt']);
        $msg = preg_replace('#[\r\n]+#', ' ', $msg);
        $msg = trim($msg);
        $post['message_short'] = xn_substr($msg, 0, 80);

        $post['filelist'] = array();
        $post['floor'] = 0;
        $thread = thread_read_cache($post['tid']);
        $post['subject'] = $thread['subject'] ? $thread['subject'] : '';

        // hook model_post_find_by_uid_post.php
    }

    // hook model_post_find_by_uid_end.php
    return array('total' => $total, 'list' => $postlist);
}
