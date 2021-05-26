<?php

ini_set('upload_max_filesize', (PHPTERM_UPLOAD_LIMIT / 1024 / 1024).'M');
ini_set('post_max_size', (PHPTERM_UPLOAD_LIMIT / 1024 / 1024 * 2).'M');
ini_set('max_value_length', '-1');

if (function_exists('add_filter'))
{
	add_filter('upload_size_limit', function($size)
	{
		return PHPTERM_UPLOAD_LIMIT;
	}, 20);
}

function __phpterm_upload($path, $args, $flags)
{
	return array('done' => true, 'upload' => true);
}
