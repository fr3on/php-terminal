<?php

define('PHPTERM_ASCII_PADDING_X', 1);
define('PHPTERM_ASCII_PADDING_Y', 0);
define('PHPTERM_ASCII_CORNER_CHAR', '+');
define('PHPTERM_ASCII_ROW_CHAR', '-');
define('PHPTERM_ASCII_COLUMN_CHAR', '|');

function phpterm_path($path, $dir = true)
{
	$abspath = realpath(ABSPATH.$path);

	if ($abspath === false)
	{
		return false;
	}

	if (substr($abspath, 0, strlen(ABSPATH)) != ABSPATH)
	{
		return ABSPATH;
	}

	if ($dir)
	{
		$abspath = rtrim($abspath, '/');

		if ( ! empty($abspath))
		{
			$abspath .= '/';
		}
	}

	return $abspath;
}

function phpterm_path_relative($path, $dir = true)
{
	$abslen = strlen(ABSPATH);

	if (substr($path, 0, $abslen) == ABSPATH)
	{
		$path = substr($path, $abslen, strlen($path) - $abslen);
	}

	if ($dir)
	{
		$path = rtrim($path, '/');

		if ( ! empty($path))
		{
			$path .= '/';
		}
	}

	return $path;
}

function phpterm_template($name)
{
	$path = PHPTERM_PATH.'templates/'.basename($name);

	if ( ! phpterm_exists($path))
	{
		return false;
	}

	return phpterm_get_contents($path);
}

function phpterm_tokenize($string)
{
	$result = array();
	$word = '';
	$open = false;
	$escape = false;

	for ($i = 0; $i < strlen($string); $i++)
	{
		$chr = substr($string, $i, 1);

		if ( ! $open && $chr != ' ' && $chr != '"' && $chr != ',')
		{
			$word .= $chr;
		} else if ( ! $open && $chr == ' ')
		{
			if (strlen($word) > 0)
			{
				array_push($result, $word);
			}

			$word = '';
		} else if ( ! $open && $chr == '"')
		{
			if (strlen($word) > 0)
			{
				array_push($result, $word);
			}

			$word = '';

			$open = true;
		} else if ($open)
		{
			if ($chr == '"' && ($i == 0 || substr($string, $i - 1, 1) != '\\'))
			{
				if (strlen($word) > 0)
				{
					array_push($result, $word);
				}

				$word = '';

				$open = false;
			} else
			{
				if ($chr != '\\' && ($i == 0 || substr($string, $i - 1, 1) != '\\'))
				{
					$word .= $chr;
				}
			}
		} else
		{
			$word .= $chr;
		}
	}

	if (strlen($word) > 0)
	{
		array_push($result, $word);
	}

	if (count($result) <= 1)
	{
		if (strlen($string) >= 2 && $string[0] == '"' && $string[strlen($string) - 1] == '"')
		{
			return array(substr($string, 1, strlen($string) - 2));
		} else
		{
			return array($string);
		}
	}

	return $result;
}

function phpterm_slug($string, $delimiters = '\/_|+ -')
{
	$string = strip_tags($string);
	$string = preg_replace('/[^a-zA-Z0-9'.$delimiters.']/', '', $string);
	$string = strtolower(trim($string, '-'));
	$string = str_replace('/', '-', $string);
	$string = preg_replace('/[_|+ -]+/', '-', $string);
	$string = preg_replace('/(\/)+/', '/', $string);

	return rtrim($string, $delimiters);
}

function phpterm_filesystem($url, $method, $context, $fields = null)
{
	global $wp_filesystem;

	if (false === ($credentials = request_filesystem_credentials($url, $method, false, $context, $fields)))
	{
		return false;
	}

	if ( ! WP_Filesystem($credentials))
	{
		request_filesystem_credentials($url, $method, true, $context);

		return false;
	}

	return true;
}

function phpterm_upload_limits()
{
	if (phpterm_exists(ABSPATH.'.htaccess') && phpterm_is_writable(ABSPATH.'.htaccess'))
	{
		$htsettings = array
		(
			'php_value upload_max_filesize' => (PHPTERM_UPLOAD_LIMIT / 1024 / 1024).'M',
			'php_value post_max_size' => (PHPTERM_UPLOAD_LIMIT / 1024 / 1024 * 2).'M',
			'php_value max_value_length' => -1
		);

		$htaccess = explode("\n", phpterm_get_contents(ABSPATH.'.htaccess'));

		foreach ($htsettings as $k => $v)
		{
			$k = strtolower($k);
			$found = false;

			foreach ($htaccess as $htline)
			{
				if (substr($htline, 0, strlen($k)) == $k)
				{
					$found = true;
					break;
				}
			}

			if ( ! $found)
			{
				array_unshift($htaccess, $k.' '.$v);
			}
		}

		phpterm_put_contents(ABSPATH.'.htaccess', implode("\n", $htaccess));
	}
}

function phpterm_ascii_table($table, $rows_limit = 50, $row_length_limit = 24, $html_special_chars = true)
{
	$ascii = '';

	if ( ! $table)
	{
		return $ascii;
	}

	if (count($table) > $rows_limit)
	{
		$table = array_slice($table, 0, $rows_limit);
	}

	$columns_headers = phpterm_ascii_column_header($table);
	$columns_lengths = phpterm_ascii_column_lengths($table, $columns_headers, $row_length_limit);
	$row_separator = phpterm_ascii_row_separator($columns_lengths);
	$row_spacer = phpterm_ascii_row_spacer($columns_lengths);
	$row_headers = phpterm_ascii_row_header($columns_headers, $columns_lengths);

	$ascii = $row_separator."\n";
	$ascii .= str_repeat($row_spacer."\n", PHPTERM_ASCII_PADDING_Y);
	$ascii .= $row_headers."\n";
	$ascii .= str_repeat($row_spacer."\n", PHPTERM_ASCII_PADDING_Y);
	$ascii .= $row_separator."\n";
	$ascii .= str_repeat($row_spacer."\n", PHPTERM_ASCII_PADDING_Y);

	foreach ($table as $row_cells)
	{
		$row_cells = phpterm_ascii_row_cells($row_cells, $columns_headers, $columns_lengths, $row_length_limit, $html_special_chars);
		$ascii .= $row_cells."\n";
		$ascii .= str_repeat($row_spacer."\n", PHPTERM_ASCII_PADDING_Y);
	}

	$ascii .= $row_separator;

	if ($html_special_chars)
	{
		$ascii = str_replace(' ', '&nbsp;', $ascii);
	}

	return $ascii;
}

function phpterm_ascii_column_header($table)
{
	return array_keys(reset($table));
}

function phpterm_ascii_column_lengths($table, $columns_headers, $row_length_limit = 24)
{
	$lengths = array();

	foreach ($columns_headers as $header)
	{
		$header_length = strlen($header);
		$max = $header_length;

		foreach ($table as $row)
		{
			$row[$header] = str_replace(array("\r\n", "\n", "\t"), ' ', $row[$header]);

			$length = strlen($row[$header]);

			if ($length > $row_length_limit)
			{
				$length = $row_length_limit;
			}

			if ($length > $max)
			{
				$max = $length;
			}
		}

		if (($max % 2) != ($header_length % 2))
		{
			$max += 1;
		}

		$lengths[$header] = $max;
	}

	return $lengths;
}

function phpterm_ascii_row_separator($columns_lengths)
{
	$row = '';

	foreach ($columns_lengths as $column_length)
	{
		$row .= PHPTERM_ASCII_CORNER_CHAR.str_repeat(PHPTERM_ASCII_ROW_CHAR, (PHPTERM_ASCII_PADDING_X * 2) + $column_length);
	}

	$row .= PHPTERM_ASCII_CORNER_CHAR;

	return $row;
}

function phpterm_ascii_row_spacer($columns_lengths)
{
	$row = '';

	foreach ($columns_lengths as $column_length)
	{
		$row .= PHPTERM_ASCII_COLUMN_CHAR.str_repeat(' ', (PHPTERM_ASCII_PADDING_X * 2) + $column_length);
	}

	$row .= PHPTERM_ASCII_COLUMN_CHAR;

	return $row;
}

function phpterm_ascii_row_header($columns_headers, $columns_lengths)
{
	$row = '';

	foreach($columns_headers as $header)
	{
		$row .= PHPTERM_ASCII_COLUMN_CHAR.str_pad($header, (PHPTERM_ASCII_PADDING_X * 2) + $columns_lengths[$header], ' ', STR_PAD_BOTH);
	}

	$row .= PHPTERM_ASCII_COLUMN_CHAR;

	return $row;
}

function phpterm_ascii_row_cells($row_cells, $columns_headers, $columns_lengths, $row_length_limit = 24, $html_special_chars = true)
{
	$row = '';

	foreach ($columns_headers as $header)
	{
		$row_cells[$header] = str_replace(array("\r\n", "\n", "\t"), ' ', $row_cells[$header]);

		if (strlen($row_cells[$header]) > $row_length_limit)
		{
			$row_cells[$header] = substr($row_cells[$header], 0, $row_length_limit - 3).'...';
		}

		if ($html_special_chars)
		{
			$row_cells[$header] = htmlspecialchars($row_cells[$header]);
		}

		$row .= PHPTERM_ASCII_COLUMN_CHAR.str_repeat(' ', PHPTERM_ASCII_PADDING_X).str_pad($row_cells[$header], PHPTERM_ASCII_PADDING_X + $columns_lengths[$header], ' ', STR_PAD_RIGHT);
	}

	$row .= PHPTERM_ASCII_COLUMN_CHAR;

	return $row;
}
