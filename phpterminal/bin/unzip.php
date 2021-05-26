<?php

function __phpterm_unzip($path, $args, $flags)
{
	$result = array();
	$dst = array_shift($args);

	$abspath = phpterm_path($path.$dst, false);

	if ( ! extension_loaded('zip') || ! class_exists('ZipArchive'))
	{
		return array('done' => true, 'result' => phpterm_i18n_zip_extension());
	}

	if ($abspath === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($dst));
	} else
	{
		if (phpterm_is_dir($abspath))
		{
			return array('done' => true, 'result' => phpterm_i18n_target_is_directory($dst));
		}

		$zip = new ZipArchive();

		if ( ! $zip->open($abspath))
		{
			$zip->close();

			return array('done' => true, 'result' => phpterm_i18n_not_zip($dst));
		}

		$job = phpterm_job_create();

		if ($job === false)
		{
			$zip->close();

			return array('done' => true, 'result' => phpterm_i18n_job_failed());
		}

		for ($i = 0; $i < $zip->numFiles; $i++)
		{
			phpterm_job_append($job, array('task' => 'unzip', 'path' => $path, 'args' => array($abspath, $i), 'flags' => $flags));
		}

		$zip->close();

		return array('job' => $job, 'result' => $result);
	}

	return array('done' => true);
}
