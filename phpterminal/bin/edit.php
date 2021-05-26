<?php

function __phpterm_edit($path, $args, $flags)
{
	$abspath = phpterm_path($path.$args[0], false);

	if ($abspath === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($args[0]));
	}

	if (phpterm_is_dir($abspath))
	{
		return array('done' => true, 'result' => phpterm_i18n_is_directory($args[0]));
	} else if (phpterm_is_file($abspath) && phpterm_is_readable($abspath))
	{
		return array('done' => true, 'edit' => true, 'file' => $args[0], 'data' => phpterm_get_contents($abspath));
	} else
	{
		return array('done' => true, 'result' => phpterm_i18n_permission_denied($args[0]));
	}

	return array('done' => true);
}

