<?php

function __phpterm_sql($path, $args, $flags)
{
	$mysqli = new mysqli(PHPTERM_SQL_HOSTNAME, PHPTERM_SQL_USERNAME, PHPTERM_SQL_PASSWORD, PHPTERM_SQL_DATABASE);

	if ($mysqli->connect_error)
	{
		return array('done' => true, 'result' => phpterm_i18n_error($mysqli->connect_error));
	}

	if (count($args) == 0)
	{
		return array('done' => true, 'result' => phpterm_i18n_query_is_empty());
	}

	$query = $args[0];

	$result = $mysqli->query($query);

	if ($result === false)
	{
		return array('done' => true, 'result' => phpterm_i18n_error($mysqli->error));
	}

	if ($result !== true)
	{
		$result = explode("\n", phpterm_ascii_table(json_decode(json_encode($result->fetch_all(MYSQLI_ASSOC)), true)));
	} else if ($mysqli->affected_rows)
	{
		$result = $mysqli->affected_rows.' '.phpterm_i18n_rows_affected($mysqli->affected_rows);
	}

	return array('done' => true, 'result' => $result);
}
