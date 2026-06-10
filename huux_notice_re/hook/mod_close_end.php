<?php exit;
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if(forum_access_mod($fid, $gid, 'allowtop')) {
			if($close != $thread['closed']) {
				$thread['subject'] = notice_substr($thread['subject'], 20);
				$todo = lang('notice_template_yourtopic_closeopen'.$close);
				$thread_close_notice_message = lang('notice_admin').'<span class="handle mx-1">'.$todo.'</span>'.lang('notice_template_yourtopic').'<a href="'.url("thread-$thread[tid]").'">《'.$thread['subject'].'》</a>';
				$notice_nid = notice_send($user['uid'], $thread['uid'], $thread_close_notice_message, 3);
			}
		}
	}
?>
