<?php

function __phpterm_mkdir($path, $args, $flags)
{
	$result = array();

	foreach ($args as $arg)
	{
		$dirname = basename($arg);
		$parentdirname = dirname($arg);

		$abspath = phpterm_path($path.$parentdirname, false);

		if ($abspath === false)
		{
			$result[] = phpterm_i18n_no_file_or_directory($parentdirname);
		} else
		{
			if (phpterm_is_file($abspath.'/'.$dirname))
			{
				$result[] = phpterm_i18n_file_exists($dirname);
			} else if (phpterm_is_dir($abspath.'/'.$dirname))
			{
				$result[] = phpterm_i18n_directory_exists($dirname);
			} else
			{
				if (phpterm_mkdir($abspath.'/'.$dirname))
				{
					if (isset($flags['v']))
					{
						$result[] = phpterm_i18n_created_directory($arg);
					}
				}
			}
		}
	}

	return array('done' => true, 'result' => $result);
}
