<?php

function __phpterm_put($path, $args, $flags)
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
		$base64 = 'data:text/plain;base64,';

		if (substr($args[1], 0, strlen($base64)) == $base64)
		{
			$args[1] = base64_decode(substr($args[1], strlen($base64), strlen($args[1]) - strlen($base64)));
		}

		phpterm_put_contents($abspath, $args[1]);

		return array('done' => true);
	} else
	{
		return array('done' => true, 'result' => phpterm_i18n_permission_denied($args[0]));
	}

	return array('done' => true);
}
