<?php

$PHPTERM_BIN = array();
global $PHPTERM_BIN;

require_once('filesystem.php');
require_once('i18n.php');
require_once('functions.php');
require_once('job.php');
require_once('api.php');

require_once('bin/phpterm.php');
require_once('bin/man.php');
require_once('bin/job.php');
require_once('bin/clear.php');
require_once('bin/pwd.php');
require_once('bin/cd.php');
require_once('bin/ls.php');
require_once('bin/cat.php');
require_once('bin/put.php');
require_once('bin/touch.php');
require_once('bin/mkdir.php');
require_once('bin/rm.php');
require_once('bin/rmdir.php');
require_once('bin/chmod.php');
require_once('bin/sql.php');
require_once('bin/upload.php');
require_once('bin/download.php');
require_once('bin/phpversion.php');
require_once('bin/ini_get.php');
require_once('bin/get_loaded_extensions.php');
require_once('bin/rename.php');
require_once('bin/cp.php');
require_once('bin/mv.php');
require_once('bin/zip.php');
require_once('bin/unzip.php');
require_once('bin/edit.php');

require_once('ajax.php');

phpterm_register('phpterm', 0, '__phpterm_phpterm');
phpterm_register('man', 1, '__phpterm_man', esc_html__('man COMMAND', 'phpterm'));
phpterm_register('job', 1, '__phpterm_job');
phpterm_register('clear', 0, '__phpterm_clear');
phpterm_register('pwd', 0, '__phpterm_pwd');
phpterm_register('cd', 1, '__phpterm_cd', esc_html__('cd DIRECTORY', 'phpterm'));
phpterm_register('ls', 0, '__phpterm_ls', esc_html__('ls [OPTION]... FILE...', 'phpterm'));
phpterm_register('cat', 1, '__phpterm_cat', esc_html__('cat [OPTION]... FILE...', 'phpterm'));
phpterm_register('edit', 1, '__phpterm_edit', esc_html__('edit FILE', 'phpterm'));
phpterm_register('put', 2, '__phpterm_put', esc_html__('put [FILENAME] [STRING]', 'phpterm'));
phpterm_register('touch', 1, '__phpterm_touch', esc_html__('touch [OPTION]... FILE...', 'phpterm'));
phpterm_register('mkdir', 1, '__phpterm_mkdir', esc_html__('mkdir [OPTION]... DIRECTORY...', 'phpterm'));
phpterm_register('rm', 1, '__phpterm_rm', esc_html__('rm [OPTION]... FILE...', 'phpterm'));
phpterm_register('rmdir', 1, '__phpterm_rmdir', esc_html__('rmdir [OPTION]... DIRECTORY...', 'phpterm'));
phpterm_register('chmod', 2, '__phpterm_chmod', esc_html__('chmod [OPTION]... OCTAL-MODE FILE...', 'phpterm'));
phpterm_register('sql', 0, '__phpterm_sql', esc_html__('sql [OPTION]... "QUERY"', 'phpterm'));
phpterm_register('upload', 0, '__phpterm_upload');
phpterm_register('download', 1, '__phpterm_download', esc_html__('download FILE', 'phpterm'));
phpterm_register('ini_get', 1, '__phpterm_ini_get', esc_html__('ini_get OPTION', 'phpterm'));
phpterm_register('get_loaded_extensions', 0, '__phpterm_get_loaded_extensions');
phpterm_register('phpversion', 0, '__phpterm_phpversion');
phpterm_register('rename', 2, '__phpterm_rename', esc_html__('rename [OPTION]... FILE FILENAME', 'phpterm'));
phpterm_register('cp', 2, '__phpterm_cp', esc_html__('cp [OPTION]... SOURCE DEST', 'phpterm'));
phpterm_register('mv', 2, '__phpterm_mv', esc_html__('mv [OPTION]... SOURCE DEST', 'phpterm'));
phpterm_register('zip', 2, '__phpterm_zip', esc_html__('zip [OPTION]... ZIPFILE FILE...', 'phpterm'));
phpterm_register('unzip', 1, '__phpterm_unzip', esc_html__('unzip ZIPFILE', 'phpterm'));

if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') || isset($_GET['_']))
{
	header('Content-Type: text/plain');
	phpterm_exec_ajax();
} else
{
	echo '<!DOCTYPE>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PHPTerminal</title>
		<link rel="stylesheet" href="'.dirname(PHPTERM_URL).'/phpterminal/media/fonts/jetbrainsmono.css" />
		<link rel="stylesheet" href="'.dirname(PHPTERM_URL).'/phpterminal/media/styles/phpterminal.css" />
		<style>
			html, body { margin: 0; padding: 0; }
			#phpterminal, #phpterminal-edit { top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; }
			@media only screen and (max-width: 960px) { #phpterminal, #phpterminal-edit { left: 0 !important;  top: 0 !important; } }
			@media screen and (max-width: 782px) { #phpterminal, #phpterminal-edit { left: 0 !important; top: 0 !important; } }
			#phpterminal-edit { top: 24px !important; bottom: 24px !important; }
			.caret-bar-top { left: 0 !important; top: 0 !important; }
			.caret-bar-bottom { left: 0 !important; bottom: 0 !important; }
		</style>
		<script src="'.dirname(PHPTERM_URL).'/phpterminal/media/scripts/phpterminal.js"></script>
		<script>
			window.onload = function()
			{
				var phpterminal = new PHPTerminal(document.getElementById(\'phpterminal\'),
				{
					ajax_url: \''.''.'\',
					username: \''.PHPTERM_USERNAME.'\',
					host: \'phpterminal\',
					path: \'\',
					upload_limit: '.PHPTERM_UPLOAD_LIMIT.',
					i18n:
					{
						no_response: \''.phpterm_i18n_no_response().'\',
						json_syntax: \''.phpterm_i18n_json_syntax().'\',
						saving: \''.phpterm_i18n_saving().'\',
						uploading: \''.phpterm_i18n_uploading().'\',
						http_400: \''.phpterm_i18n_http_400().'\',
						http_401: \''.phpterm_i18n_http_401().'\',
						http_403: \''.phpterm_i18n_http_403().'\',
						http_404: \''.phpterm_i18n_http_404().'\',
						http_405: \''.phpterm_i18n_http_405().'\',
						http_406: \''.phpterm_i18n_http_406().'\',
						http_408: \''.phpterm_i18n_http_408().'\',
						http_500: \''.phpterm_i18n_http_500().'\',
						http_501: \''.phpterm_i18n_http_501().'\',
						http_502: \''.phpterm_i18n_http_502().'\',
						http_503: \''.phpterm_i18n_http_503().'\',
						http_504: \''.phpterm_i18n_http_504().'\',
						http_505: \''.phpterm_i18n_http_505().'\',
						http_511: \''.phpterm_i18n_http_511().'\'
					}
				});

				phpterminal.exec(\'phpterm\', true);
			};
		</script>
	</head>
	<body>
		'.phpterm_template('terminal.html').'
	</body>
</html>';
}