<?php

function __phpterm_rename($path, $args, $flags)
{
	$result = array();

	$src = $args[0];
	$dst = basename($args[1]);

	if ( ! empty($dst))
	{
		$abspath = phpterm_path($path.$src, false);

		if ($abspath === false)
		{
			return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($src));
		}

		$srcdirpath = phpterm_path($path.dirname($src));
		$dstpath = dirname($src);

		if ($dstpath == '.')
		{
			$dstpath = '';
		}

		$dstpath .= ( ! empty($dstpath) ? '/' : '').$dst;

		if ($srcdir !== false)
		{
			if (phpterm_exists($srcdirpath.$dst))
			{
				if (phpterm_is_dir($srcdirpath.$dst))
				{
					return array('done' => true, 'result' => phpterm_i18n_directory_exists($dstpath));
				} else
				{
					return array('done' => true, 'result' => phpterm_i18n_file_exists($dstpath));
				}
			} else
			{
				phpterm_move($abspath, $srcdirpath.$dst);

				if (isset($flags['v']))
				{
					$result = phpterm_i18n_renamed($src, $dstpath);
				}
			}
		}
	}

	return array('done' => true, 'result' => $result);
}
