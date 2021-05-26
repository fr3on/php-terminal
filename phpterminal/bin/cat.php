<?php

function __phpterm_cat($path, $args, $flags)
{
	$result = array();

	foreach ($args as $arg)
	{
		$abspath = phpterm_path($path.$arg, false);

		if ($abspath === false)
		{
			$result[] = phpterm_i18n_no_file_or_directory($arg);
		}

		if (phpterm_is_dir($abspath))
		{
			$result[] = phpterm_i18n_is_directory($arg);
		} else if (phpterm_is_file($abspath) && phpterm_is_readable($abspath))
		{
			$file = str_replace(array("\r\n", "\n"), "\n", phpterm_get_contents($abspath));

			if (isset($flags['n']))
			{
				$lines = explode("\n", $file);
				$linecount = count($lines);

				for ($i = 0; $i < $linecount; $i++)
				{
					$lines[$i] = str_pad(($i + 1), 6, ' ', STR_PAD_LEFT).'	'.$lines[$i];
				}

				$file = implode("\n", $lines);
			}

			$result[] = str_replace("\n", (isset($flags['E']) ? '$' : '').'<br />', htmlspecialchars($file));
		} else
		{
			$result[] =  phpterm_i18n_permission_denied($arg);
		}
	}

	return array('done' => true, 'result' => $result);
}
