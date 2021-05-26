<?php

function phpterm_job_create()
{
	$job = tempnam(PHPTERM_PATH.'jobs', 'phpterm_');

	if ($job !== false)
	{
		return basename($job);
	}

	return false;
}

function phpterm_job_append($job, $task)
{
	$jobpath = PHPTERM_PATH.'jobs/'.$job;

	$tasks = phpterm_get_contents($jobpath);

	if ($tasks === false)
	{
		$tasks = '';
	}

	phpterm_put_contents($jobpath, json_encode($task).( ! empty($tasks) ? "\n" : '').$tasks);
}

function phpterm_job_prepend($job, $task)
{
	$jobpath = PHPTERM_PATH.'jobs/'.$job;

	$tasks = phpterm_get_contents($jobpath);

	if ($tasks === false)
	{
		$tasks = '';
	}

	phpterm_put_contents($jobpath, $tasks.( ! empty($tasks) ? "\n" : '').json_encode($task));
}

function phpterm_job_get($job)
{
	$jobpath = PHPTERM_PATH.'jobs/'.$job;

	$tasks = phpterm_get_contents($jobpath);

	if ($tasks !== false && ! empty($tasks));
	{
		$tasks = explode("\n", $tasks);

		if (count($tasks) > 0)
		{
			$task = array_pop($tasks);
			$task = json_decode($task, true);

			phpterm_put_contents($jobpath, implode("\n", $tasks));

			if (empty($tasks))
			{
				phpterm_delete($jobpath);
			}

			return $task;
		}
	}

	phpterm_delete($jobpath);

	return false;
}
