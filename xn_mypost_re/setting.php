<?php
!defined('DEBUG') AND exit('Access Denied.');

if ($method == 'GET') {
    $setting = xn_mypost_re_setting_get();
    include _include(APP_PATH.'plugin/xn_mypost_re/setting.htm');
} else {
    $setting = array(
        'show_on_my' => param('show_on_my', 0),
        'show_on_user' => param('show_on_user', 0),
        'posts_only' => param('posts_only', 0),
    );
    kv_set('xn_mypost_re_setting', $setting);
    message(0, lang('save_successfully'));
}
