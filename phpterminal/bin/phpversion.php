<?php

function __phpterm_phpversion($path, $args, $flags)
{
	$version = phpversion();

	if (isset($args[0]))
	{
		$version = phpversion($args[0]);
	}

	return array('done' => true, 'result' => $version);
}
