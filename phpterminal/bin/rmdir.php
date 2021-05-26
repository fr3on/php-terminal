<?php

function __phpterm_rmdir($path, $args, $flags)
{
	$result = array();

	foreach ($args as $filename)
	{
		$abspath = phpterm_path($path.$filename, false);

		if ($abspath === false)
		{
			$result[] = phpterm_i18n_no_file_or_directory($filename);
		} else if (phpterm_is_file($abspath))
		{
			$result[] = phpterm_i18n_is_file($filename);
		} else if (phpterm_is_dir($abspath))
		{
			$list = phpterm_dirlist($abspath, true);

			if (empty($list) || isset($flags['r']) || isset($flags['R']))
			{
				if (phpterm_rmdir($abspath, isset($flags['r']) || isset($flags['R'])))
				{
					if (isset($flags['v']))
					{
						$result[] = phpterm_i18n_removed($filename);
					}
				}
			} else
			{
				$result[] = phpterm_i18n_directory_not_empty($filename);
			}
		}
	}

	return array('done' => true, 'result' => $result);
}
