<?php

function phpterm_exec_ajax()
{
	if (isset($_POST['input']))
	{
		$result = phpterm_exec($_POST['path'], stripslashes($_POST['input']));

		if (is_array($result))
		{
			echo json_encode($result);
		} else
		{
			echo $result;
		}
	} else if ($_FILES)
	{
		$name = '';

		if ( ! isset($_POST['name']) || empty($_POST['name']))
		{
			$name = 'upload.phpterm';
		} else
		{
			$name = basename($_POST['name']);
		}

		switch ($_FILES['file']['error'])
		{
			case UPLOAD_ERR_OK:
			break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				echo json_encode(array('done' => true, 'result' => phpterm_i18n_upload_too_large()));
				die;
			break;
			case UPLOAD_ERR_PARTIAL:
				echo json_encode(array('done' => true, 'result' => phpterm_i18n_upload_not_completed()));
				die;
			break;
			case UPLOAD_ERR_NO_FILE:
				echo json_encode(array('done' => true, 'result' => phpterm_i18n_upload_empty()));
				die;
			break;
			default:
				echo json_encode(array('done' => true, 'result' => phpterm_i18n_upload_internal_error()));
				die;
			break;
		}

		$abspath = phpterm_path($_POST['path']);

		if ($abspath !== false)
		{
			if (move_uploaded_file($_FILES['file']['tmp_name'], $abspath.$name))
			{
				echo json_encode(array('done' => true));
			} else
			{
				echo json_encode(array('done' => true, 'result' => phpterm_i18n_upload_move_failed()));
			}
		}
	} else
	{
		echo json_encode(array('done' => true));
	}

	die;
}
