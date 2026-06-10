<?php

!defined('DEBUG') AND exit('Access Denied.');

if($method == 'GET') {

    include _include(APP_PATH.'plugin/xn_nav_2_re/setting.htm');

} else {

    $nav_2_bbs_on = param('nav_2_bbs_on', 0);
    $nav_2_forum_list_pc_on = param('nav_2_forum_list_pc_on', 0);
    $nav_2_forum_list_mobile_on = param('nav_2_forum_list_mobile_on', 0);
    $nav_2_mobile_display = param('nav_2_mobile_display', 'collapse');

    $nav_2_channel_display = param('nav_2_channel_display', 'list');
    $nav_2_channel_cols = param('nav_2_channel_cols', 3);
    $nav_2_channel_border = param('nav_2_channel_border', 0);

    $nav_2_forumlist_display = param('nav_2_forumlist_display', 'list');
    $nav_2_forumlist_cols = param('nav_2_forumlist_cols', 3);
    $nav_2_forumlist_border = param('nav_2_forumlist_border', 0);

    $nav_2_my_display = param('nav_2_my_display', 'list');
    $nav_2_my_cols = param('nav_2_my_cols', 2);
    $nav_2_my_border = param('nav_2_my_border', 0);
    $nav_2_nav_fixed = param('nav_2_nav_fixed', 0);

    $replace = array();
    $replace['nav_2_bbs_on'] = $nav_2_bbs_on;
    $replace['nav_2_forum_list_pc_on'] = $nav_2_forum_list_pc_on;
    $replace['nav_2_forum_list_mobile_on'] = $nav_2_forum_list_mobile_on;
    $replace['nav_2_mobile_display'] = $nav_2_mobile_display;
    $replace['nav_2_channel_display'] = $nav_2_channel_display;
    $replace['nav_2_channel_cols'] = $nav_2_channel_cols;
    $replace['nav_2_channel_border'] = $nav_2_channel_border;
    $replace['nav_2_forumlist_display'] = $nav_2_forumlist_display;
    $replace['nav_2_forumlist_cols'] = $nav_2_forumlist_cols;
    $replace['nav_2_forumlist_border'] = $nav_2_forumlist_border;
    $replace['nav_2_my_display'] = $nav_2_my_display;
    $replace['nav_2_my_cols'] = $nav_2_my_cols;
    $replace['nav_2_my_border'] = $nav_2_my_border;
    $replace['nav_2_nav_fixed'] = $nav_2_nav_fixed;

    file_replace_var(APP_PATH.'conf/conf.php', $replace);

    message(0, '修改成功');
}

?>
