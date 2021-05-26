<?php

/*
	This code is a part of WordPress core and credit goes to WordPress team.
	The license under which the WordPress software is released is the GPLv2 (or later) from the Free Software Foundation.
	https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

	wp-admin/includes/class-wp-filesystem-base.php
	wp-admin/includes/class-wp-filesystem-direct.php
*/


if ( ! defined('FS_CHMOD_DIR'))
{
	define('FS_CHMOD_DIR', (fileperms(ABSPATH) & 0777 | 0755));
}

if ( ! defined('FS_CHMOD_FILE'))
{
	define('FS_CHMOD_FILE', (fileperms(ABSPATH.'phpterminal.php') & 0777 | 0644 ));
}

function mbstring_binary_safe_encoding($reset = false)
{
	static $encodings  = array();
	static $overloaded = null;

	if (is_null($overloaded))
	{
		$overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
	}

	if (false === $overloaded)
	{
		return;
	}

	if ( ! $reset)
	{
		$encoding = mb_internal_encoding();
		array_push($encodings, $encoding);
		mb_internal_encoding('ISO-8859-1');
	}

	if ($reset && $encodings)
	{
		$encoding = array_pop($encodings);
		mb_internal_encoding($encoding);
	}
}

function reset_mbstring_encoding()
{
	mbstring_binary_safe_encoding(true);
}

function phpterm_gethchmod($file)
{
	$perms = intval(phpterm_getchmod($file), 8);

	if (($perms & 0xC000) == 0xC000)
	{ // Socket.
		$info = 's';
	} elseif (($perms & 0xA000) == 0xA000)
	{ // Symbolic Link.
		$info = 'l';
	} elseif (($perms & 0x8000) == 0x8000)
	{ // Regular.
		$info = '-';
	} elseif (($perms & 0x6000) == 0x6000)
	{ // Block special.
		$info = 'b';
	} elseif (($perms & 0x4000) == 0x4000)
	{ // Directory.
		$info = 'd';
	} elseif (($perms & 0x2000) == 0x2000)
	{ // Character special.
		$info = 'c';
	} elseif (($perms & 0x1000) == 0x1000)
	{ // FIFO pipe.
		$info = 'p';
	} else
	{ // Unknown.
		$info = 'u';
	}

	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-' ));

	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-' ));

	$info .= (($perms & 0x0004) ? 'r' : '-' );
	$info .= (($perms & 0x0002) ? 'w' : '-' );
	$info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

	return $info;
}

function phpterm_getnumchmodfromh($mode)
{
	$realmode = '';
	$legal = array('', 'w', 'r', 'x', '-');
	$attarray = preg_split('//', $mode);

	for ($i = 0, $c = count( $attarray ); $i < $c; $i++)
	{
		$key = array_search($attarray[$i], $legal, true);

		if ($key)
		{
			$realmode .= $legal[$key];
		}
	}

	$mode  = str_pad($realmode, 10, '-', STR_PAD_LEFT);
	$trans = array
	(
		'-' => '0',
		'r' => '4',
		'w' => '2',
		'x' => '1',
	);

	$mode  = strtr($mode, $trans);

	$newmode = $mode[0];
	$newmode .= $mode[1] + $mode[2] + $mode[3];
	$newmode .= $mode[4] + $mode[5] + $mode[6];
	$newmode .= $mode[7] + $mode[8] + $mode[9];

	return $newmode;
}

function phpterm_is_binary($text)
{
	return (bool)preg_match('|[^\x20-\x7E]|', $text);
}

function phpterm_get_contents($file)
{
	return file_get_contents($file);
}

function phpterm_put_contents($file, $contents, $mode = false)
{
	$fp = fopen($file, 'wb');

	if ( ! $fp)
	{
		return false;
	}

	mbstring_binary_safe_encoding();

	$data_length = strlen($contents);

	$bytes_written = fwrite($fp, $contents);

	reset_mbstring_encoding();

	fclose($fp);

	if ($data_length !== $bytes_written)
	{
		return false;
	}

	phpterm_chmod($file, $mode);

	return true;
}

function phpterm_cwd()
{
	return getcwd();
}

function phpterm_chdir($dir)
{
	return chdir($dir);
}

function phpterm_chgrp($file, $group, $recursive = false)
{
	if ( ! phpterm_exists($file))
	{
		return false;
	}

	if ( ! $recursive)
	{
		return chgrp($file, $group);
	}

	if ( ! phpterm_is_dir($file))
	{
		return chgrp($file, $group);
	}

	$file = rtrim($file, '/\\' ).'/';
	$filelist = phpterm_dirlist($file);

	foreach ($filelist as $filename)
	{
		phpterm_chgrp($file.$filename, $group, $recursive);
	}

	return true;
}

function phpterm_chmod($file, $mode = false, $recursive = false)
{
	if ( ! $mode)
	{
		if (phpterm_is_file($file))
		{
			$mode = FS_CHMOD_FILE;
		} elseif (phpterm_is_dir($file))
		{
			$mode = FS_CHMOD_DIR;
		} else
		{
			return false;
		}
	}

	if ( ! $recursive || ! phpterm_is_dir($file))
	{
		return chmod($file, $mode);
	}

	$file = rtrim($file, '/\\' ).'/';
	$filelist = phpterm_dirlist($file);

	foreach ((array)$filelist as $filename => $filemeta)
	{
		phpterm_chmod($file.$filename, $mode, $recursive);
	}

	return true;
}

function phpterm_chown($file, $owner, $recursive = false)
{
	if ( ! phpterm_exists($file))
	{
		return false;
	}

	if ( ! $recursive)
	{
		return chown($file, $owner);
	}

	if ( ! phpterm_is_dir($file))
	{
		return chown($file, $owner);
	}

	$filelist = phpterm_dirlist($file);

	foreach ($filelist as $filename)
	{
		phpterm_chown($file.'/'.$filename, $owner, $recursive);
	}

	return true;
}

function phpterm_owner($file)
{
	$owneruid = fileowner($file);

	if ( ! $owneruid)
	{
		return false;
	}

	if ( ! function_exists('posix_getpwuid'))
	{
		return $owneruid;
	}

	$ownerarray = posix_getpwuid($owneruid);

	if ( ! $ownerarray)
	{
		return false;
	}

	return $ownerarray['name'];
}

function phpterm_getchmod($file)
{
	return substr(decoct(fileperms($file)), -3);
}

function phpterm_group($file)
{
	$gid = filegroup($file);

	if ( ! $gid)
	{
		return false;
	}

	if ( ! function_exists('posix_getgrgid'))
	{
		return $gid;
	}

	$grouparray = posix_getgrgid($gid);

	if ( ! $grouparray)
	{
		return false;
	}

	return $grouparray['name'];
}

function phpterm_copy($source, $destination, $overwrite = false, $mode = false)
{
	if ( ! $overwrite && phpterm_exists($destination))
	{
		return false;
	}

	$rtval = copy($source, $destination);

	if ($mode)
	{
		phpterm_chmod($destination, $mode);
	}

	return $rtval;
}

function phpterm_move($source, $destination, $overwrite = false)
{
	if ( ! $overwrite && phpterm_exists($destination))
	{
		return false;
	}

	if (rename($source, $destination))
	{
		return true;
	}

	if (phpterm_copy($source, $destination, $overwrite) && phpterm_exists($destination))
	{
		phpterm_delete($source);

		return true;
	} else
	{
		return false;
	}
}

function phpterm_delete($file, $recursive = false, $type = false)
{
	if (empty($file))
	{
		return false;
	}

	$file = str_replace('\\', '/', $file);

	if ('f' === $type || phpterm_is_file($file))
	{
		return unlink($file);
	}

	if ( ! $recursive && phpterm_is_dir($file))
	{
		return rmdir($file);
	}

	$file = rtrim($file, '/\\' ).'/';
	$filelist = phpterm_dirlist($file, true);

	$retval = true;

	if (is_array($filelist))
	{
		foreach ($filelist as $filename => $fileinfo)
		{
			if ( ! phpterm_delete($file.$filename, $recursive, $fileinfo['type']))
			{
				$retval = false;
			}
		}
	}

	if (file_exists($file) && ! rmdir($file))
	{
		$retval = false;
	}

	return $retval;
}

function phpterm_exists($file)
{
	return file_exists($file);
}

function phpterm_is_file($file)
{
	return is_file($file);
}

function phpterm_is_dir($path)
{
	return is_dir($path);
}

function phpterm_is_readable($file)
{
	return is_readable($file);
}

function phpterm_is_writable($file)
{
	return is_writable($file);
}

function phpterm_atime($file)
{
	return fileatime($file);
}

function phpterm_mtime($file)
{
	return filemtime($file);
}

function phpterm_size($file)
{
	return filesize($file);
}

function phpterm_touch($file, $time = 0, $atime = 0)
{
	if (0 == $time)
	{
		$time = time();
	}

	if (0 == $atime)
	{
		$atime = time();
	}

	return touch($file, $time, $atime);
}

function phpterm_mkdir($path, $chmod = false, $chown = false, $chgrp = false)
{
	$path = rtrim($path, '/\\');

	if (empty($path))
	{
		return false;
	}

	if ( ! $chmod)
	{
		$chmod = FS_CHMOD_DIR;
	}

	if ( ! mkdir($path))
	{
		return false;
	}

	phpterm_chmod($path, $chmod);

	if ($chown)
	{
		phpterm_chown($path, $chown);
	}

	if ($chgrp)
	{
		phpterm_chgrp($path, $chgrp);
	}

	return true;
}

function phpterm_rmdir($path, $recursive = false)
{
	return phpterm_delete($path, $recursive);
}

function phpterm_dirlist($path, $include_hidden = true, $recursive = false)
{
	if (phpterm_is_file($path))
	{
		$limit_file = basename($path);
		$path = dirname($path);
	} else
	{
		$limit_file = false;
	}

	if ( ! phpterm_is_dir($path) || ! phpterm_is_readable($path))
	{
		return false;
	}

	$dir = dir($path);

	if ( ! $dir)
	{
		return false;
	}

	$ret = array();

	while (false !== ($entry = $dir->read()))
	{
		$struc = array();
		$struc['name'] = $entry;

		if ('.' === $struc['name'] || '..' === $struc['name'])
		{
			continue;
		}

		if ( ! $include_hidden && '.' === $struc['name'][0])
		{
			continue;
		}

		if ($limit_file && $struc['name'] != $limit_file)
		{
			continue;
		}

		$struc['perms'] = phpterm_gethchmod($path.'/'.$entry);
		$struc['permsn'] = phpterm_getnumchmodfromh($struc['perms']);
		$struc['number'] = false;
		$struc['owner'] = phpterm_owner($path.'/'.$entry);
		$struc['group'] = phpterm_group($path.'/'.$entry);
		$struc['size'] = phpterm_size( $path.'/'.$entry);
		$struc['lastmodunix'] = phpterm_mtime($path.'/'.$entry);
		$struc['lastmod'] = gmdate('M j', $struc['lastmodunix']);
		$struc['time'] = gmdate('h:i:s', $struc['lastmodunix']);
		$struc['type'] = phpterm_is_dir($path.'/'.$entry) ? 'd' : 'f';

		if ('d' === $struc['type'])
		{
			if ($recursive)
			{
				$struc['files'] = phpterm_dirlist($path.'/'.$struc['name'], $include_hidden, $recursive);
			} else
			{
				$struc['files'] = array();
			}
		}

		$ret[$struc['name']] = $struc;
	}

	$dir->close();
	unset($dir);

	return $ret;
}
