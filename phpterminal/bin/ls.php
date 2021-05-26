<?php

function __phpterm_ls($path, $args, $flags)
{
	// a - hidden files
	// R - recursive
	// r - reverse order
	// Coloring scheme
	// Blue: Directory
	// Green: Executable or recognized data file
	// Cyan (Sky Blue): Symbolic link file
	// Yellow with black background: Device
	// Magenta (Pink): Graphic image file
	// Red: Archive file
	// Red with black background: Broken link

	$abspath = phpterm_path($path.(isset($args[0]) > 0 ? $args[0] : ''));

	if ($abspath === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_no_file_or_directory($args[0]));
	}

	$result = array_keys(phpterm_dirlist($abspath, isset($flags['a']), isset($flags['R'])));
	sort($result);

	if (isset($flags['r']))
	{
		$result = array_reverse($result);
	}

	return array('done' => true, 'result' => $result);
}
