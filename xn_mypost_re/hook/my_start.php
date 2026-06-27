
if ($action == 'post') {

    $xn_setting = xn_mypost_re_setting_get();
    if (!$xn_setting['show_on_my']) {
        http_404();
        exit;
    }

    // hook my_post_start.php

    $page = param(2, 1);
    $pagesize = 20;

    $result = post_find_by_uid($uid, $page, $pagesize, $xn_setting['posts_only']);
    $totalnum = $result['total'];
    $postlist = $result['list'];

    $pagination = pagination(url("my-post-{page}"), $totalnum, $page, $pagesize);

    post_list_access_filter($postlist, $gid);

    // hook my_post_end.php

    include _include(APP_PATH.'plugin/xn_mypost_re/view/htm/my_post.htm');
}
