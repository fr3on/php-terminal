<?php

function __phpterm_cd($path, $args, $flags)
{
	if ($args[0] == '~')
	{
		$abspath = ABSPATH;
	} else
	{
		$abspath = phpterm_path($path.(isset($args[0]) > 0 ? $args[0] : ''));
	}

	if ($abspath !== false && phpterm_is_dir($abspath))
	{
		if ( ! phpterm_is_readable($abspath))
		{
			return array('done' => true, 'result' => phpterm_i18n_permission_denied($args[0]));
		}

		return array('done' => true, 'path' => phpterm_path_relative($abspath));
	} else if (isset($args[0]))
	{
		return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($args[0]));
	}

	return array('done' => true);
}
