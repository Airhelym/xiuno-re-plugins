<?php
$notice_menu = array(
	0 => array(
		'url'=>url('my-notice'),
		'name'=>lang('notice_lang_all'),
		'class'=>'notice-type-all',
		'icon'=>''
	),
	2 => array(
		'url'=>url('my-notice-2'),
		'name'=>lang('notice_lang_comment'),
		'class'=>'notice-type-comment',
		'icon'=>''
	),
	3 => array(
		'url'=>url('my-notice-3'),
		'name'=>lang('notice_lang_system'),
		'class'=>'notice-type-system',
		'icon'=>''
	),
	99 => array(
		'url'=>url('my-notice-99'),
		'name'=>lang('notice_lang_other'),
		'class'=>'notice-type-other',
		'icon'=>'bell'
	),
	// hook notice_route_menu_array_end.php
);
return $notice_menu;
?>
