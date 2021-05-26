<?php

function esc_html__($string, $domain)
{
	$string = (string) $string;

	if (0 === strlen($string))
	{
		return '';
	}

	if ( ! preg_match('/[&<>"\']/', $string))
	{
		return $string;
	}

	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

	return $string;
}

function phpterm_i18n_error($error)
{
	return esc_html__('Error', 'phpterm').': '.$error;
}

function phpterm_i18n_command_not_found($command)
{
	return esc_html__('Error: command not found', 'phpterm').': '.$command;
}

function phpterm_i18n_no_response()
{
	return esc_html__('Error: no response from the server', 'phpterm');
}

function phpterm_i18n_json_syntax()
{
	return esc_html__('Error: failed to parse JSON response', 'phpterm');
}

function phpterm_i18n_filesystem_failed()
{
	return esc_html__('Cannot initialize filesystem', 'phpterm');
}

function phpterm_i18n_authentication_failed()
{
	return esc_html__('Error: authentication failure', 'phpterm');
}

function phpterm_i18n_job_failed()
{
	return esc_html__('Error: failed to initialize job', 'phpterm');
}

function phpterm_i18n_saving()
{
	return esc_html__('Saving...', 'phpterm');
}

function phpterm_i18n_uploading()
{
	return esc_html__('Uploading...', 'phpterm');
}

function phpterm_i18n_processing_download()
{
	return esc_html__('Processing download...', 'phpterm');
}

function phpterm_i18n_jobs_not_writable()
{
	return esc_html__('Error: wp-content/plugins/phpterminal/jobs directory is not writable', 'phpterm');
}

function phpterm_i18n_permission_denied($path)
{
	return esc_html__('Error: permission denied', 'phpterm').': '.$path;
}

function phpterm_i18n_no_file_or_directory($path)
{
	return esc_html__('Error: no such file or directory', 'phpterm').': '.$path;
}

function phpterm_i18n_directory_exists($path)
{
	return esc_html__('Error: directory exists', 'phpterm').': '.$path;
}

function phpterm_i18n_is_directory($path)
{
	return esc_html__('Error: is a directory', 'phpterm').': '.$path;
}

function phpterm_i18n_target_is_directory($path)
{
	return esc_html__('Error: target is a directory', 'phpterm').': '.$path;
}

function phpterm_i18n_directory_not_empty($path)
{
	return esc_html__('Error: directory not empty', 'phpterm').': '.$path;
}

function phpterm_i18n_target_is_not_directory($path)
{
	return esc_html__('Error: target is not a directory', 'phpterm').': '.$path;
}

function phpterm_i18n_omitting_directory($path)
{
	return esc_html__('Error: -r not specified; omitting directory', 'phpterm').': '.$path;
}

function phpterm_i18n_created_directory($dir)
{
	return esc_html__('created directory', 'phpterm').': '.$dir;
}

function phpterm_i18n_file_exists($path)
{
	return esc_html__('Error: file exists', 'phpterm').': '.$path;
}

function phpterm_i18n_is_file($path)
{
	return esc_html__('Error: is a file', 'phpterm').': '.$path;
}

function phpterm_i18n_zip_failed($path)
{
	return esc_html__('Error: failed to create zip archive', 'phpterm').': '.$path;
}

function phpterm_i18n_not_zip($path)
{
	return esc_html__('Error: failed to open zip archive', 'phpterm').': '.$path;
}

function phpterm_i18n_zip_extension()
{
	return esc_html__('Error: zip extensions not available', 'phpterm');
}

function phpterm_i18n_upload_too_large()
{
	return esc_html__('Error: file too large', 'phpterm');
}

function phpterm_i18n_upload_not_completed()
{
	return esc_html__('Error: file upload was not completed', 'phpterm');
}

function phpterm_i18n_upload_empty()
{
	return esc_html__('Error: empty file uploaded', 'phpterm');
}

function phpterm_i18n_internal_error()
{
	return esc_html__('Error: internal error', 'phpterm');
}

function phpterm_i18n_upload_move_failed()
{
	return esc_html__('Error: failed to move uploaded file', 'phpterm');
}

function phpterm_i18n_http_400()
{
	return esc_html__('HTTP Error: bad request', 'phpterm');
}

function phpterm_i18n_http_401()
{
	return esc_html__('HTTP Error: unauthorized', 'phpterm');
}

function phpterm_i18n_http_403()
{
	return esc_html__('HTTP Error: forbidden', 'phpterm');
}

function phpterm_i18n_http_404()
{
	return esc_html__('HTTP Error: not found', 'phpterm');
}

function phpterm_i18n_http_405()
{
	return esc_html__('HTTP Error: method not allowed', 'phpterm');
}

function phpterm_i18n_http_406()
{
	return esc_html__('HTTP Error: not acceptable', 'phpterm');
}

function phpterm_i18n_http_408()
{
	return esc_html__('HTTP Error: request timeout', 'phpterm');
}

function phpterm_i18n_http_500()
{
	return esc_html__('HTTP Error: internal server error', 'phpterm');
}

function phpterm_i18n_http_501()
{
	return esc_html__('HTTP Error: not implemented', 'phpterm');
}

function phpterm_i18n_http_502()
{
	return esc_html__('HTTP Error: bad gateway', 'phpterm');
}

function phpterm_i18n_http_503()
{
	return esc_html__('HTTP Error: service unavailable', 'phpterm');
}

function phpterm_i18n_http_504()
{
	return esc_html__('HTTP Error: gateway timeout', 'phpterm');
}

function phpterm_i18n_http_505()
{
	return esc_html__('HTTP Error: version not supported', 'phpterm');
}

function phpterm_i18n_http_511()
{
	return esc_html__('HTTP Error: network authentication required', 'phpterm');
}

function phpterm_i18n_rows_affected($rows)
{
	return $rows == 1 ? esc_html__('row affected', 'phpterm') : esc_html__('rows affected', 'phpterm');
}

function phpterm_i18n_query_is_empty()
{
	return esc_html__('query is empty', 'phpterm');
}

function phpterm_i18n_removed($file)
{
	return esc_html__('removed', 'phpterm').': '.$file;
}

function phpterm_i18n_changed_mode($file, $mode)
{
	 return esc_html__('changed mode to '.$mode, 'phpterm').': '.$file;
}

function phpterm_i18n_renamed($file, $filename)
{
	 return esc_html__('renamed '.$file.' to '.$filename, 'phpterm');
}

function phpterm_i18n_no_manual($man)
{
	return esc_html__('No manual entry for '.$man, 'phpterm');
}
