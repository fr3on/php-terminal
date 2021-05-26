<?php

function __phpterm_chmod($path, $args, $flags)
{
	$result = array();
	$mode = array_shift($args);

	foreach ($args as $filename)
	{
		$abspath = phpterm_path($path.$filename, false);

		if ($abspath === false)
		{
			$result[] = phpterm_i18n_no_file_or_directory($filename);
		} else if (phpterm_is_file($abspath))
		{
			if (phpterm_chmod($abspath, octdec($mode)))
			{
				if (isset($flags['v']))
				{
					$result[] = phpterm_i18n_changed_mode($filename, $mode);
				}
			}
		} else if (phpterm_is_dir($abspath))
		{
			if (phpterm_chmod($abspath, octdec($mode), isset($flags['R'])))
			{
				if (isset($flags['v']))
				{
					$result[] = phpterm_i18n_changed_mode($filename, $mode);
				}
			}
		}
	}

	return array('done' => true, 'result' => $result);
}
