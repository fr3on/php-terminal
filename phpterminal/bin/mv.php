<?php

function __phpterm_mv($path, $args, $flags)
{
	$result = array();
	$dst = array_pop($args);
	$dstpath = phpterm_path($path.$dst);
	$dstisfile = $dstpath !== false && phpterm_is_file(rtrim($dstpath, '/'));

	if ($dstpath === false || $dstisfile)
	{
		$srcpath = phpterm_path($path.$args[0], false);

		if (count($args) == 1 && $srcpath !== false && phpterm_is_file($srcpath))
		{
			if ($dstisfile)
			{
				phpterm_move($srcpath, $dstpath, true);
			} else
			{
				$dstdirpath = phpterm_path(dirname($path.$dst));

				if ($dstdirpath !== false)
				{
					if (phpterm_is_dir($dstdirpath))
					{
						phpterm_move($srcpath, $dstdirpath.basename($dst), true);
					} else
					{
						return array('done' => true, 'result' => phpterm_i18n_target_is_not_directory(dirname($dst)));
					}
				} else
				{
					return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory(dirname($dst)));
				}
			}

			return array('done' => true, 'result' => (isset($flags['v']) ? $args[0].' -> '.$dst : ''));
		} else if ($dstisfile)
		{
			return array('done' => true, 'result' => phpterm_i18n_target_is_not_directory($dst));
		} else
		{
			return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($dst));
		}
	}

	$job = phpterm_job_create();

	if ($job === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_job_failed());
	}

	$result = __phpterm_recursive_dir($job, 'move', $path, $path, $args, $flags, $dstpath);

	if (isset($flags['r']) || isset($flags['R']))
	{
		foreach ($args as $arg)
		{
			$dirpath = phpterm_path($path.$arg);

			if ($dirpath !== false && phpterm_is_dir($dirpath))
			{
				phpterm_job_append($job, array('task' => 'removeemptydir', 'path' => $path, 'args' => array($arg, $dirpath), 'flags' => $flags));
			}
		}
	}

	return array('job' => $job, 'result' => $result);
}
