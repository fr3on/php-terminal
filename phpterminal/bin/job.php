<?php

function __phpterm_job($path, $args, $flags)
{
	$result = array();
	$job = $args[0];
	$task = phpterm_job_get($args[0]);

	if (isset($task['task']) && isset($task['args']))
	{
		switch ($task['task'])
		{
			case 'copy':
			case 'move':
				$dir = dirname($task['args'][1]);

				if ( ! phpterm_is_dir($dir))
				{
					phpterm_mkdir($dir);
				}

				if ($task['task'] == 'copy')
				{
					phpterm_copy($task['args'][0], $task['args'][1], true);
				} else if ($task['task'] == 'move')
				{
					phpterm_move($task['args'][0], $task['args'][1], true);
				}

				if (isset($task['flags']['v']))
				{
					$result[] = $task['args'][0].' -> '.$task['args'][1];
				}
			break;

			case 'remove':
				if (phpterm_is_dir($task['args'][0]) || phpterm_is_file($task['args'][0]))
				{
					phpterm_delete($task['args'][0], isset($task['flags']['r']) || isset($task['flags']['R']));
				}
			break;

			case 'removedir':
				if (phpterm_is_dir($task['args'][0]))
				{
					phpterm_rmdir($task['args'][0], isset($task['flags']['r']) || isset($task['flags']['R']));
				}
			break;

			case 'removeemptydir':
				if (phpterm_is_dir($task['args'][1]))
				{
					$dirs = explode('/', $task['args'][0]);
					$dirparts = '';
					$dircount = 0;
					$files = array();

					foreach ($dirs as $dir)
					{
						if ( ! empty($dir))
						{
							$dirparts .= ( ! empty($dirparts) ? '/' : '').$dir;
							$absdir = phpterm_path($task['path'].$dirparts);

							if (phpterm_is_dir($absdir))
							{
								$dirlist = array_keys(phpterm_dirlist($absdir, true));

								foreach ($dirlist as $direntry)
								{
									if (phpterm_is_file($absdir.$direntry))
									{
										$dircount++;
									}
								}
							}
						}
					}

					if ($dircount == 0)
					{
						phpterm_rmdir($task['args'][1], isset($task['flags']['r']) || isset($task['flags']['R']));
					}
				}
			break;

			case 'zip':
				if (phpterm_is_file($task['args'][0]) || phpterm_is_dir($task['args'][0]))
				{
					$zip = new ZipArchive();

					if ( ! $zip->open($task['args'][1], ZIPARCHIVE::CREATE))
					{
						$result[] = array('done' => true, 'result' => phpterm_i18n_zip_failed(str_replace(phpterm_path($task['path']), '', $task['args'][1])));
					} else
					{
						$relativepath = str_replace(phpterm_path($task['path']), '', $task['args'][0]);

						$result[] = '  adding: '.$relativepath;

						if (phpterm_is_dir($task['args'][0]))
						{
							$zip->addEmptyDir($relativepath);

						} else if (phpterm_is_file($task['args'][0]))
						{
							$zip->addFile($task['args'][0], $relativepath);
						}
					}

					$zip->close();
				}
			break;

			case 'unzip':
				if (phpterm_is_file($task['args'][0]))
				{
					$zip = new ZipArchive();

					if ( ! $zip->open($task['args'][0]))
					{
						$result[] = array('done' => true, 'result' => phpterm_i18n_zip_failed(str_replace(phpterm_path($task['path']), '', $task['args'][0])));
					} else
					{
						$relativepath = $zip->getNameIndex($task['args'][1]);
						$dirpath = '';

						if (substr($relativepath, -1) == '/')
						{
							$dirpath = $relativepath;
						} else
						{
							$dirpath = dirname($relativepath);

							if ($dirpath == '.')
							{
								$dirpath = '';
							}
						}

						if ( ! empty($dirpath))
						{
							$dirs = explode('/', $dirpath);
							$dirparts = '';

							foreach ($dirs as $dir)
							{
								if ( ! empty($dir))
								{
									$dirparts .= ( ! empty($dirparts) ? '/' : '').$dir;

									if ( ! phpterm_is_file(phpterm_path($task['path']).$dirparts))
									{
										phpterm_mkdir(phpterm_path($task['path']).$dirparts);
									} else
									{
										$result[] = phpterm_i18n_is_directory($dirparts);
									}
								}
							}
						}

						$result[] = '  extracting: '.$task['path'].$relativepath;

						$zip->extractTo(phpterm_path($task['path']), $relativepath);
					}

					$zip->close();
				}
			break;
		}

		return array('job' => $job, 'result' => $result);
	}

	return array('done' => true, 'result' => $result);
}
