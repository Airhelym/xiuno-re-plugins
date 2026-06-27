
if ($action == 'post') {

    $xn_setting = xn_mypost_re_setting_get();
    if (!$xn_setting['show_on_user']) {
        http_404();
        exit;
    }

    // hook user_post_start.php

    $_uid = param(2, 0);
    empty($_uid) AND $_uid = $uid;
    $_user = user_read($_uid);
    empty($_user) AND message(-1, lang('user_not_exists'));

    $page = param(3, 1);
    $pagesize = 20;

    $result = post_find_by_uid($_uid, $page, $pagesize, $xn_setting['posts_only']);
    $totalnum = $result['total'];
    $postlist = $result['list'];

    $pagination = pagination(url("user-post-$_uid-{page}"), $totalnum, $page, $pagesize);

    post_list_access_filter($postlist, $gid);

    // hook user_post_end.php

    include _include(APP_PATH.'plugin/xn_mypost_re/view/htm/user_post.htm');
}
