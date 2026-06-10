<?php

function search_type() {
	static $search_conf = FALSE;
	if($search_conf === FALSE) $search_conf = kv_get('search_conf');
	return $search_conf['type'];
}

function search_fuzzy_enabled() {
	static $search_conf = FALSE;
	if($search_conf === FALSE) $search_conf = kv_get('search_conf');
	return isset($search_conf['fuzzy']) ? $search_conf['fuzzy'] : 1;
}

function search_message_format($s) {
	$s = xn_substr(str_replace('&amp;nbsp;', ' ', htmlspecialchars(strip_tags($s))), 0, 200);
	return $s;
}

function search_keyword_highlight($s, $keyword_arr) {
	foreach($keyword_arr as $keyword) {
		$s = str_ireplace($keyword, '<span class="text-danger">'.$keyword.'</span>', $s);
	}
	return $s;
}

function search_keyword_safe($s) {
	$s = str_replace(array('\'', '\\', '"', '%', '<', '>', '`', '*', '&', '#'), '', $s);
	$s = preg_replace('#\s+#', ' ', $s);
	$s = trim($s);
	return $s;
}

function search_fuzzy_split($s) {
	$tokens = array();
	$len = strlen($s);
	for($i = 0; $i < $len; $i++) {
		$o = ord($s[$i]);
		if($o < 0x80) {
			if($o > 32 && $o < 127) {
				$tokens[] = chr($o);
			}
		} else {
			if($i + 2 < $len) {
				$b1 = $o;
				$b2 = ord($s[$i+1]);
				$b3 = ord($s[$i+2]);
				if(($b1 & 0xE0) == 0xE0 && ($b2 & 0xC0) == 0x80 && ($b3 & 0xC0) == 0x80) {
					$tokens[] = $s[$i].$s[$i+1].$s[$i+2];
					$i += 2;
				}
			}
		}
	}
	if(empty($tokens)) return '%'.$s.'%';
	return '%'.implode('%', $tokens).'%';
}

function search_user_by_username($keyword, $page, $pagesize) {
	$userlist = db_sql_find("SELECT * FROM bbs_user WHERE username LIKE '%".addslashes($keyword)."%' ORDER BY uid ASC LIMIT ".(($page - 1) * $pagesize).", $pagesize;");
	foreach($userlist as &$user) {
		user_format($user);
	}
	return $userlist;
}

function search_user_count($keyword) {
	$arr = db_sql_find_one("SELECT COUNT(*) AS num FROM bbs_user WHERE username LIKE '%".addslashes($keyword)."%'");
	return $arr ? intval($arr['num']) : 0;
}

function search_cn_encode($s) {
	$r = '';
	$special_arr = array(
		'０', '１', '２', '３', '４',
		'５', '６', '７', '８', '９',
		'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ',
		'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ',
		'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
		'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ',
		'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ',
		'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ',
		'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
		'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ',
		'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ',
		'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ',
		'ｙ', 'ｚ', '－', '　', '：',
		'．', '，', '／', '％', '＃',
		'！', '＠', '＆', '（', '）',
		'＜', '＞', '＂', '＇', '？',
		'［', '］', '｛', '｝', '＼',
		'｜', '＋', '＝', '＿', '＾',
		'￥', '￣', '｀', '《', '》',
		'【', '】', '〖', '〗', '『', '』',
		'我', '你', '不', '是', '的', '了',
		'nbsp', '　',
	);
	$s = str_replace($special_arr, '', $s);
	$len = strlen($s);
	$f1 = intval(base_convert('10000000', 2, 10));
	$f2 = intval(base_convert('11000000', 2, 10));
	$f3 = intval(base_convert('11100000', 2, 10));
	for($i = 0; $i < $len; $i++) {
		$o = ord($s[$i]);
		if($o < 0x80) {
			if(($o >= 48 && $o <= 57) || ($o >= 97 && $o <= 122) || $o == 0x20) {
				$r .= $s[$i];
			} elseif($o >= 65 && $o <= 90) {
				$r .= strtolower($s[$i]);
			} else {
				$r .= ' ';
			}
		} else {
			if($i + 5 >= $len) break;
			$b1 = ord($s[$i]);
			$b2 = ord($s[$i+1]);
			$b3 = ord($s[$i+2]);
			$b4 = ord($s[$i+3]);
			$b5 = ord($s[$i+4]);
			$b6 = ord($s[$i+5]);
			if(
				($b1 & $f3) == $f3 &&
				(($b2 & $f1) == $f1 || ($b2 & $f2) == $f2) &&
				($b3 & $f1) == $f1
				&&
				($b4 & $f3) == $f3 &&
				(($b5 & $f1) == $f1 || ($b5 & $f2) == $f2) &&
				($b6 & $f1) == $f1
			) {
				$z = $s[$i].$s[$i+1].$s[$i+2].$s[$i+3].$s[$i+4].$s[$i+5];
				$i += 2;
				$r .= '  '.$z.' ';
			} else {
				continue;
			}
		}
	}
	$r = preg_replace('#\s\w{1}\s#', ' ', $r);
	$r = trim(preg_replace('#\s+#', ' ', $r));
	return $r;
}

function search_cn_encode_by_word($s) {
	$r = '';
	$len = strlen($s);
	$f1 = intval(base_convert('10000000', 2, 10));
	$f2 = intval(base_convert('11000000', 2, 10));
	$f3 = intval(base_convert('11100000', 2, 10));
	for($i = 0; $i < $len; $i++) {
		$o = ord($s[$i]);
		if($o < 0x80) {
			if(($o >= 48 && $o <= 57) || ($o >= 97 && $o <= 122) || $o == 0x20) {
				$r .= $s[$i];
			} elseif($o >= 65 && $o <= 90) {
				$r .= strtolower($s[$i]);
			} else {
				$r .= ' ';
			}
		} else {
			if($i + 2 >= $len) break;
			$b1 = ord($s[$i]);
			$b2 = ord($s[$i+1]);
			$b3 = ord($s[$i+2]);
			if(
				($b1 & $f3) == $f3 &&
				(($b2 & $f1) == $f1 || ($b2 & $f2) == $f2) &&
				($b3 & $f1) == $f1
			) {
				$z = $s[$i].$s[$i+1].$s[$i+2];
				$i += 2;
				$t = iconv('UTF-8', 'UCS-2', $z);
				$r .= '  u'.bin2hex($t).' ';
			} else {
				continue;
			}
		}
	}
	$r = preg_replace('#\s\w{1}\s#', ' ', $r);
	$r = trim(preg_replace('#\s+#', ' ', $r));
	return $r;
}

?>
