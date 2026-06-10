<?php

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}thread_search (
  fid int(11) unsigned NOT NULL default '0',
  tid int(11) unsigned NOT NULL default '0',
  message longtext NOT NULL,
  UNIQUE KEY (tid),
  FULLTEXT(message)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
db_exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}post_search (
  fid int(11) unsigned NOT NULL default '0',
  pid int(11) unsigned NOT NULL default '0',
  message longtext NOT NULL,
  UNIQUE KEY (pid),
  FULLTEXT(message)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
db_exec($sql);

$search_conf = kv_get('search_conf');
if(empty($search_conf)) {
	$search_conf = array(
		'type'=>'like',
		'range'=>0,
		'fuzzy'=>1,
		'site_url' => 'https://www.baidu.com/s?wd=site%3A'._SERVER('HTTP_HOST').'%20{keyword}',
	);
	kv_set('search_conf', $search_conf);
} elseif(!isset($search_conf['fuzzy'])) {
	$search_conf['fuzzy'] = 1;
	kv_set('search_conf', $search_conf);
}

?>
