<?php

define('ABSPATH', dirname(__FILE__).'/');

define('PHPTERM_PATH', ABSPATH.'phpterminal/');
define('PHPTERM_URL', 'http'.( ! empty($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
define('PHPTERM_VERSION', '1.01');
define('PHPTERM_DOMAIN', 'phpterm');
define('PHPTERM_UPLOAD_LIMIT', 64 * 1024 * 1024);

define('PHPTERM_USERNAME', 'admin');
define('PHPTERM_PASSWORD', 'admin');
define('PHPTERM_SQL_HOSTNAME', 'localhost');
define('PHPTERM_SQL_DATABASE', 'database');
define('PHPTERM_SQL_USERNAME', 'username');
define('PHPTERM_SQL_PASSWORD', 'password');

require_once('phpterminal/i18n.php');

if ( ! isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != PHPTERM_USERNAME || ! isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] != PHPTERM_PASSWORD)
{
	header('WWW-Authenticate: Basic realm="PHPTerminal"');
	header('HTTP/1.0 401 Unauthorized');

	echo '<!DOCTYPE>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PHPTerminal</title>
		<link rel="stylesheet" href="'.dirname(PHPTERM_URL).'/phpterminal/media/fonts/jetbrainsmono.css" />
		<style>
			body { margin: 0; padding: 0; background: #000; color: #fff; font-family: jetbrains_monoregular, monovoid, monospace, monospace; font-size: 16px; line-height: 1.4; white-space: pre; }
			p { margin: 0; padding: 0; }
		</style>
	</head>
	<body><p>'.phpterm_i18n_authentication_failed().'</p></body>
</html>';

	die;
}

require_once('phpterminal/phpterminal.php');
