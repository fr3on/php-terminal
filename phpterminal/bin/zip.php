<?php

function __phpterm_recursive_zip($job, $task, $jobpath, $path, $args, $flags, $dst)
{
	$result = array();

	foreach ($args as $src)
	{
		$subsrc = ltrim(rtrim($path, '/').'/'.$src, '/');
		$srcpath = phpterm_path($subsrc, false);

		if (basename($src) == '*')
		{
			$subsrc = ltrim(rtrim($path, '/').'/'.dirname($src), '/');
			$srcpath = phpterm_path($subsrc, false);

			$dirlist = array_keys(phpterm_dirlist($srcpath, true));
			sort($dirlist);

			$result = array_merge($result, __phpterm_recursive_zip($job, $task, $jobpath, $subsrc, $dirlist, $flags, $dst));
		} else
		{
			if ($srcpath !== false)
			{
				if (phpterm_is_dir($srcpath))
				{
					phpterm_job_append($job, array('task' => $task, 'path' => $jobpath, 'args' => array($srcpath, $dst), 'flags' => $flags));

					if (isset($flags['r']))
					{
						$dirlist = array_keys(phpterm_dirlist($srcpath, true));
						sort($dirlist);

						$result = array_merge($result, __phpterm_recursive_zip($job, $task, $jobpath, $subsrc, $dirlist, $flags, $dst));
					}
				} else if (phpterm_is_file($srcpath))
				{
					phpterm_job_append($job, array('task' => $task, 'path' => $jobpath, 'args' => array($srcpath, $dst), 'flags' => $flags));
				}
			} else
			{
				$result[] = phpterm_i18n_no_file_or_directory($subsrc);
			}
		}
	}

	return $result;
}

function __phpterm_zip($path, $args, $flags)
{
	$result = array();
	$dst = array_shift($args);
	$filename = basename($dst);
	$dirname = dirname($dst);

	$abspath = phpterm_path($path.$dirname, false);

	if ( ! extension_loaded('zip') || ! class_exists('ZipArchive'))
	{
		return array('done' => true, 'result' => phpterm_i18n_zip_extension());
	}

	if ($abspath === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($dirname));
	} else
	{
		$zip = new ZipArchive();

		if ( ! $zip->open($abspath.'/'.$filename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE))
		{
			$zip->close();

			return array('done' => true, 'result' => phpterm_i18n_zip_failed($dst));
		}

		$zip->close();

		$job = phpterm_job_create();

		if ($job === false)
		{
			return array('done' => true, 'result' => phpterm_i18n_job_failed());
		}

		$result = __phpterm_recursive_zip($job, 'zip', $path, $path, $args, $flags, $abspath.'/'.$filename);

		return array('job' => $job, 'result' => $result);
	}

	return array('done' => true);
}
