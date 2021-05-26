<?php

function __phpterm_get_loaded_extensions($path, $args, $flags)
{
	return array('done' => true, 'result' => get_loaded_extensions());
}
