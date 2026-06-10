<?php
exit;

elseif ($action == 'post_like_re') {

	$header['title'] = "帖子点赞 - " . $conf['sitename'];

	$haya_post_like_re_config = setting_get('haya_post_like_re');

	if ($method == 'POST') {

		$pid = param('pid');

		$post = post_read($pid);
		empty($post) AND message(0, lang('post_not_exists'));

		$action2 = param(2, 'create');
		if ($action2 == 'users') {

			$like_user_count = isset($haya_post_like_re_config['like_user_count']) ? intval($haya_post_like_re_config['like_user_count']) : 20;

			$haya_post_like_re_users = haya_post_like_re_find_users_by_pid($pid, $like_user_count);
			ob_start();
			include _include(APP_PATH.'plugin/haya_post_like_re/view/htm/like_users.htm');
			$haya_post_like_re_user_html = ob_get_clean();

			message(1, $haya_post_like_re_user_html);

		} elseif ($action2 == 'create') {

			!$uid AND message(0, '只有登录后才能够点赞！');

			if ($post['isfirst'] == 1) {
				if (isset($haya_post_like_re_config['open_thread'])
					&& $haya_post_like_re_config['open_thread'] != 1
				) {
					message(0, '对帖子点赞功能没有启用！');
				}
			} else {
				if (isset($haya_post_like_re_config['open_post'])
					&& $haya_post_like_re_config['open_post'] != 1
				) {
					message(0, '对回帖点赞功能没有启用！');
				}
			}

			$haya_post_like_re_check = haya_post_like_re_find_by_uid_and_pid($uid, $pid);
			if (!empty($haya_post_like_re_check)) {
				message(0, '你已经点赞过该回帖！');
			}

			haya_post_like_re_create(array(
				'tid' => $post['tid'],
				'pid' => $pid,
				'uid' => $user['uid'],
				'create_date' => time(),
				'create_ip' => $longip,
			));

			haya_post_like_re_loves($pid, 1);

			$like_user_count = isset($haya_post_like_re_config['like_user_count']) ? intval($haya_post_like_re_config['like_user_count']) : 20;
			$haya_post_like_re_users = haya_post_like_re_find_users_by_pid($pid, $like_user_count);
			ob_start();
			include _include(APP_PATH.'plugin/haya_post_like_re/view/htm/like_users.htm');
			$haya_post_like_re_user_html = ob_get_clean();

			// hook haya_post_like_re_create_end.php
			if (function_exists("notice_send")) {
				$post_thread = thread_read($post['tid']);
				$post_thread['subject'] = notice_substr($post_thread['subject'], 20);

				$notice_post_subject = $post_thread['subject'];
				$notice_post_substr_subject = htmlspecialchars(strip_tags($post_thread['subject']));
				$notice_post_substr_subject = notice_substr($notice_post_substr_subject, 20);
				$notice_post_url = url('thread-'.$post['tid']);
				$notice_thread = '<a target="_blank" href="'.$notice_post_url.'">《'.$notice_post_subject.'》</a>';

				$notice_user_url = url('user-'.$user['uid']);
				$notice_user_avatar_url = $user['avatar_url'];
				$notice_user_username = $user['username'];
				$notice_user = '<a href="'.$notice_user_url.'" target="_blank"><img class="avatar-1" src="'.$notice_user_avatar_url.'"> '.$notice_user_username.'</a>';

				$notice_msg = $notice_user.' 点赞了你的回帖 '.$notice_thread;
				notice_send($user['uid'], $post['uid'], $notice_msg, 156);
			}

			$haya_post_like_re_count = haya_post_like_re_count(array('pid' => $pid));
			$haya_post_like_re_msg = array(
				'count' => intval($haya_post_like_re_count),
				'users' => $haya_post_like_re_user_html,
				'msg' => '点赞回帖成功！',
			);

			message(1, $haya_post_like_re_msg);
		} elseif ($action2 == 'delete') {
			!$uid AND message(0, '只有登录后才能够点赞！');
			$haya_post_like_re_check = haya_post_like_re_find_by_uid_and_pid($uid, $pid);
			if (empty($haya_post_like_re_check)) {
				message(0, '你还没有点赞过该回帖！');
			}

			haya_post_like_re_delete_by_pid_and_uid($pid, $user['uid']);

			haya_post_like_re_loves($pid, -1);

			$like_user_count = isset($haya_post_like_re_config['like_user_count']) ? intval($haya_post_like_re_config['like_user_count']) : 20;
			$haya_post_like_re_users = haya_post_like_re_find_users_by_pid($pid, $like_user_count);
			ob_start();
			include _include(APP_PATH.'plugin/haya_post_like_re/view/htm/like_users.htm');
			$haya_post_like_re_user_html = ob_get_clean();

			$haya_post_like_re_count = haya_post_like_re_count(array('pid' => $pid));
			$haya_post_like_re_msg = array(
				'count' => intval($haya_post_like_re_count),
				'users' => $haya_post_like_re_user_html,
				'msg' => '取消点赞成功！',
			);

			message(1, $haya_post_like_re_msg);
		}

		message(1, '访问错误！');
	}

	message(1, '访问错误！');

}


?>
