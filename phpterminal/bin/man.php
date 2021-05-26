<?php

function __phpterm_man($path, $args, $flags)
{
	$result = array();

	$abspath = phpterm_path(PHPTERM_PATH.'man/'.basename($args[0]));



	if ( ! phpterm_exists(PHPTERM_PATH.'man/'.basename($args[0])))
	{
		$result[] = phpterm_i18n_no_manual(basename($args[0]));
	}

	$man = phpterm_get_contents(PHPTERM_PATH.'man/'.basename($args[0]));
	$result[] = str_replace(array("\r\n", "\n"), '<br />', htmlspecialchars($man));

	return array('done' => true, 'result' => $result);
}
