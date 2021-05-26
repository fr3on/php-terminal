<?php

function __phpterm_ini_get($path, $args, $flags)
{
	return array('done' => true, 'result' => ini_get($args[0]));
}
