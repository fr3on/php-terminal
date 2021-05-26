<?php

function __phpterm_recursive_dir($job, $task, $jobpath, $path, $args, $flags, $dstpath)
{
	$result = array();

	foreach ($args as $src)
	{
		$subsrc = ltrim(rtrim($path, '/').'/'.$src, '/');
		$srcpath = phpterm_path($subsrc, false);

		if ($srcpath !== false)
		{
			if (phpterm_is_dir($srcpath))
			{
				if ( ! isset($flags['r']) && ! isset($flags['R']))
				{
					$result[] = phpterm_i18n_omitting_directory($src);
				} else
				{
					$dirlist = array_keys(phpterm_dirlist($srcpath, true));
					sort($dirlist);

					$result = array_merge($result, __phpterm_recursive_dir($job, $task, $jobpath, $subsrc, $dirlist, $flags, $dstpath.basename($src).'/'));
				}
			} else if (phpterm_is_file($srcpath))
			{
				phpterm_job_append($job, array('task' => $task, 'path' => $jobpath, 'args' => array($srcpath, $dstpath.basename($src)), 'flags' => $flags));
			}
		} else
		{
			$result[] = phpterm_i18n_no_file_or_directory($subsrc);
		}
	}

	return $result;
}

function __phpterm_cp($path, $args, $flags)
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
				phpterm_copy($srcpath, $dstpath, true);
			} else
			{
				$dstdirpath = phpterm_path(dirname($path.$dst));

				if ($dstdirpath !== false)
				{
					if (phpterm_is_dir($dstdirpath))
					{
						phpterm_copy($srcpath, $dstdirpath.basename($dst), true);
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

	$result = __phpterm_recursive_dir($job, 'copy', $path, $path, $args, $flags, $dstpath);

	return array('job' => $job, 'result' => $result);
}
