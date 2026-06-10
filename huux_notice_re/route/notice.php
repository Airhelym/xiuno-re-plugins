<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

if($action == 'create') {

	if($method == 'GET') {

		$input = array();
		$input['recvuid'] = form_text('recvuid', '');
		$input['message'] = form_textarea('message', '', '100%', 100);

		$header['title'] = lang('notice_admin_send_notice');
		$header['mobile_title'] = lang('notice_admin_send_notice');

		include _include(APP_PATH."plugin/huux_notice_re/view/htm/admin_notice_create.htm");

	} else {

		$message = param('message', '', FALSE);
		$recvuid = param('recvuid', 0);

		empty($message) AND message('message', lang('notice_admin_send_notice_message_empty'));
		empty($recvuid) AND message('recvuid', lang('notice_admin_send_notice_recvuid_empty'));

		$recvuid_check = user__read($recvuid);
		$recvuid_check === FALSE AND message('recvuid', lang('notice_admin_send_notice_user_empty'));

		$nid = notice_send($uid, $recvuid, $message, 3);
		$nid === FALSE AND message(-1, lang('notice_admin_send_notice_failed'));

		message(0, lang('notice_admin_send_notice_sucessfully'));
	}

} elseif($action == 'delete') {

	$nid = param('nid');
	$r = notice_delete($nid);
	$r === FALSE AND message(-1, lang('notice_delete_notice_failed'));
	message(0, lang('notice_delete_notice_sucessfully'));

} elseif($action == 'list'){

	$page = param(2, 1);
	$pagesize = 20;
	$active = 'default';
	$notices = notice_count();
	$cond = array();
	$orderby = 'nid';

	$notice_menu = include _include(APP_PATH.'plugin/huux_notice_re/conf/notice_menu.conf.php');
	$noticelist = notice_find($cond, $page, $pagesize);
	$pagination = pagination(url("notice-list-{page}"), $notices, $page, $pagesize);

	$header['title'] = lang('notice_admin_notice_list');
	$header['mobile_title'] = lang('notice_admin_notice_list');

	include _include(APP_PATH."plugin/huux_notice_re/view/htm/admin_notice_list.htm");

}

?>
