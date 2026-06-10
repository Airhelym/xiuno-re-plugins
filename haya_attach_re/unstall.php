<?php

defined('DEBUG') OR exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "DROP TABLE IF EXISTS {$tablepre}haya_attach;";
$r = db_exec($sql);

$r === FALSE AND message(-1, '卸载插件表失败');
