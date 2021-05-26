<?php

function __phpterm_download($path, $args, $flags)
{
	$abspath = phpterm_path($path.$args[0], false);

	if ($abspath === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($args[0]));
	}

	if (phpterm_is_dir($abspath))
	{
		return array('done' => true, 'result' => phpterm_i18n_is_directory($args[0]));
	} else if (phpterm_is_file($abspath) && phpterm_is_readable($abspath))
	{
		if (isset($_POST['download']) && $_POST['download'] == $args[0])
		{
			$length = filesize($abspath);
			$time = filemtime($abspath);

			header('HTTP/1.1 200 OK');
			header('Pragma: public');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
			header('Content-Disposition: attachment; filename="'.basename($args[0]).'"');
			header('Accept-Ranges: bytes');
			header('Content-Type: application/x-unknown');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$length);

			return phpterm_get_contents($abspath);
		} else
		{
			return array('download' => $args[0], 'result' => phpterm_i18n_processing_download());
		}
	} else
	{
		return array('done' => true, 'result' => phpterm_i18n_permission_denied($args[0]));
	}

	return array('done' => true);
}
