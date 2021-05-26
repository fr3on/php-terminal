<?php

function __phpterm_touch($path, $args, $flags)
{
	$result = array();

	foreach ($args as $arg)
	{
		$filename = basename($arg);
		$dirname = dirname($arg);

		$abspath = phpterm_path($path.$dirname, false);

		if ($abspath === false)
		{
			$result[] = phpterm_i18n_no_file_or_directory($dirname);
		} else
		{
			if (isset($flags['c']) && phpterm_exists($abspath.'/'.$filename))
			{
				continue;
			}

			phpterm_touch($abspath.'/'.$filename);
		}
	}

	return array('done' => true, 'result' => $result);
}
