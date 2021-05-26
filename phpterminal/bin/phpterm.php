<?php

function __phpterm_phpterm($path, $args, $flags)
{
	$result = array(
	'   ___  __ _____  ______              _           __',
	'  / _ \/ // / _ \/_  __/__ ______ _  (_)__  ___ _/ /',
	' / ___/ _  / ___/ / / / -_) __/  \' \/ / _ \/ _ `/ / ',
	'/_/  /_//_/_/    /_/  \__/_/ /_/_/_/_/_//_/\_,_/_/  ',
	'                                                v'.PHPTERM_VERSION);

	if (isset($flags['v']))
	{
		$result = 'v'.PHPTERM_VERSION;
	}

	if ( ! phpterm_is_writable(PHPTERM_PATH.'jobs/'))
	{
		$result[] = '';
		$result[] = phpterm_i18n_jobs_not_writable();
	}

	return array('done' => true, 'result' => $result);
}
