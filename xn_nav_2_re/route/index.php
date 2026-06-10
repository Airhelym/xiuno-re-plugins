<?php

!defined('DEBUG') AND exit('Access Denied.');

if($conf['nav_2_bbs_on']) {

    // hook xn_nav_2_re_include_index_htm_before.php
    include _include(APP_PATH.'plugin/xn_nav_2_re/view/htm/index.htm');
} else {

    // hook xn_nav_2_re_include_index_php_before.php
    include _include(APP_PATH.'route/index.php');
}

?>
