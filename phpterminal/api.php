<?php

function phpterm_register($name, $args, $callback, $shortman = '', $man = '')
{
	global $PHPTERM_BIN;

	if ( ! isset($PHPTERM_BIN[$name]))
	{
		$PHPTERM_BIN[$name] = array
		(
			'args' => $args,
			'callback' => $callback,
			'shortman' => $shortman,
			'man' => $man
		);

		return true;
	}

	return false;
}

function phpterm_exec($path, $input)
{
	global $PHPTERM_BIN;

	$tokens = phpterm_tokenize($input);
	$command = strtolower(array_shift($tokens));
	$args = array();
	$flags = array();

	if (isset($PHPTERM_BIN[$command]))
	{
		foreach ($tokens as $token)
		{
			if (strlen($token) > 1 && substr($token, 0, 1) == '-')
			{
				$flags[substr($token, 1, 1)] = substr($token, 2, strlen($token) - 2);
			} else
			{
				$args[] = $token;
			}
		}

		if (count($args) < $PHPTERM_BIN[$command]['args'])
		{
			return array('done' => true, 'result' => $PHPTERM_BIN[$command]['shortman']);
		}

		return call_user_func_array($PHPTERM_BIN[$command]['callback'], array($path, $args, $flags));
	}

	return array('done' => true, 'result' => phpterm_i18n_command_not_found($command));
}
