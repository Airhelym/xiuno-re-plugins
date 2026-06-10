<?php exit;
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if($top == 3 && ($gid != 1 && $gid != 2)) {
			continue;
		}
		if(forum_access_mod($fid, $gid, 'allowtop')) {
			if($top != $thread['top']) {
				$thread['subject'] = notice_substr($thread['subject'], 20);
				$todo = lang('notice_template_yourtopic_top'.$top);
				$thread_top_notice_message = lang('notice_admin').'<span class="handle mx-1">'.$todo.'</span>'.lang('notice_template_yourtopic').'<a href="'.url("thread-$thread[tid]").'">《'.$thread['subject'].'》</a>';
				$notice_nid = notice_send($user['uid'], $thread['uid'], $thread_top_notice_message, 3);
			}
		}
	}
?>
