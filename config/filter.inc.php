<?php
	$find= array();
	$replace=array();
	$banned='/(admin|法轮功|21世纪中国基金会)/i';
	$_CACHE['word_filter'] = Array
	(
		'filter' => Array
		(
			'find' => &$find,
			'replace' => &$replace
		),
		'banned' => &$banned
	);
	?>