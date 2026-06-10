<?php

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_post_like;
";
$r = db_exec($sql);

$sql = "
ALTER TABLE {$tablepre}post DROP COLUMN haya_post_likes;
";
$r = db_exec($sql);

setting_delete('haya_post_like_re');
