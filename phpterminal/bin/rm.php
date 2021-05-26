<?php

function __phpterm_rm($path, $args, $flags)
{
	$result = array();

	foreach ($args as $filename)
	{
		$abspath = phpterm_path($path.$filename, false);

		if ($abspath === false)
		{
			$result[] = phpterm_i18n_no_file_or_directory($filename);
		} else if (phpterm_is_dir($abspath))
		{
			if ( ! isset($flags['d']) &&  ! isset($flags['r']) && ! isset($flags['R']))
			{
				$result[] = phpterm_i18n_is_directory($filename);

				continue;
			}

			$empty = count(array_keys(phpterm_dirlist($abspath, true))) == 0;

			if (isset($flags['d']) && ! $empty)
			{
				$result[] = phpterm_i18n_directory_not_empty($filename);

				continue;
			}

			if (phpterm_delete($abspath, isset($flags['r']) || isset($flags['R'])))
			{
				if (isset($flags['v']))
				{
					$result[] = phpterm_i18n_removed($filename);
				}
			}
		} else if (phpterm_is_file($abspath))
		{
			if (phpterm_delete($abspath))
			{
				if (isset($flags['v']))
				{
					$result[] = phpterm_i18n_removed($filename);
				}
			}
		}
	}

	return array('done' => true, 'result' => $result);
}
