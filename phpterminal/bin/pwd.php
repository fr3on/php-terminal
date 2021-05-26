<?php

function __phpterm_pwd($path, $args, $flags)
{
	$abspath = phpterm_path($path);

	return array('done' => true, 'result' => $abspath);
}
