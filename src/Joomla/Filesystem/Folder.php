<?php
/**
 * Part of the Joomla Framework Filesystem Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Filesystem;

use Joomla\Factory;
use Joomla\Log\Log;

/**
 * A Folder handling class
 *
 * @since  1.0
 */
abstract class Folder
{
	/**
	 * Copy a folder.
	 *
	 * @param   string   $src          The path to the source folder.
	 * @param   string   $dest         The path to the destination folder.
	 * @param   string   $path         An optional base path to prefix to the file names.
	 * @param   string   $force        Force copy.
	 * @param   boolean  $use_streams  Optionally force folder/file overwrites.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public static function copy($src, $dest, $path = '', $force = false, $use_streams = false)
	{
		@set_time_limit(ini_get('max_execution_time'));

		if ($path)
		{
			$src = Path::clean($path . '/' . $src);
			$dest = Path::clean($path . '/' . $dest);
		}

		// Eliminate trailing directory separators, if any
		$src = rtrim($src, DIRECTORY_SEPARATOR);
		$dest = rtrim($dest, DIRECTORY_SEPARATOR);

		if (!is_dir(Path::clean($src)))
		{
			throw new \RuntimeException('Source folder not found', -1);
		}

		if (is_dir(Path::clean($dest)) && !$force)
		{
			throw new \RuntimeException('Destination folder not found', -1);
		}

		// Make sure the destination exists
		if (!self::create($dest))
		{
			throw new \RuntimeException('Cannot create destination folder', -1);
		}

		if (!($dh = @opendir($src)))
		{
			throw new \RuntimeException('Cannot open source folder', -1);
		}

		// Walk through the directory copying files and recursing into folders.
		while (($file = readdir($dh)) !== false)
		{
			$sfid = $src . '/' . $file;
			$dfid = $dest . '/' . $file;

			switch (filetype($sfid))
			{
				case 'dir':
					if ($file != '.' && $file != '..')
					{
						$ret = self::copy($sfid, $dfid, null, $force, $use_streams);

						if ($ret !== true)
						{
							return $ret;
						}
					}
					break;

				case 'file':
					if ($use_streams)
					{
						$stream = Factory::getStream();

						if (!$stream->copy($sfid, $dfid))
						{
							throw new \RuntimeException('Cannot copy file: ' . $stream->getError(), -1);
						}
					}
					else
					{
						if (!@copy($sfid, $dfid))
						{
							throw new \RuntimeException('Copy file failed', -1);
						}
					}
					break;
			}
		}

		return true;
	}

	/**
	 * Create a folder -- and all necessary parent folders.
	 *
	 * @param   string   $path  A path to create from the base path.
	 * @param   integer  $mode  Directory permissions to set for folders created. 0755 by default.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @since   1.0
	 */
	public static function create($path = '', $mode = 0755)
	{
		static $nested = 0;

		// Check to make sure the path valid and clean
		$path = Path::clean($path);

		// Check if parent dir exists
		$parent = dirname($path);

		if (!is_dir(Path::clean($parent)))
		{
			// Prevent infinite loops!
			$nested++;

			if (($nested > 20) || ($parent == $path))
			{
				Log::add(__METHOD__ . ': Infinite loop detected', Log::WARNING, 'jerror');
				$nested--;

				return false;
			}

			// Create the parent directory
			if (self::create($parent, $mode) !== true)
			{
				// JFolder::create throws an error
				$nested--;

				return false;
			}

			// OK, parent directory has been created
			$nested--;
		}

		// Check if dir already exists
		if (is_dir(Path::clean($path)))
		{
			return true;
		}

		// We need to get and explode the open_basedir paths
		$obd = ini_get('open_basedir');

		// If open_basedir is set we need to get the open_basedir that the path is in
		if ($obd != null)
		{
			if (defined('PHP_WINDOWS_VERSION_MAJOR'))
			{
				$obdSeparator = ";";
			}
			else
			{
				$obdSeparator = ":";
			}

			// Create the array of open_basedir paths
			$obdArray = explode($obdSeparator, $obd);
			$inBaseDir = false;

			// Iterate through open_basedir paths looking for a match
			foreach ($obdArray as $test)
			{
				$test = Path::clean($test);

				if (strpos($path, $test) === 0)
				{
					$inBaseDir = true;
					break;
				}
			}

			if ($inBaseDir == false)
			{
				// Return false for JFolder::create because the path to be created is not in open_basedir
				Log::add(__METHOD__ . ': Path not in open_basedir paths', Log::WARNING, 'jerror');

				return false;
			}
		}

		// First set umask
		$origmask = @umask(0);

		// Create the path
		if (!$ret = @mkdir($path, $mode))
		{
			@umask($origmask);
			Log::add(__METHOD__ . ': Could not create directory.  Path: ' . $path, Log::WARNING, 'jerror');

			return false;
		}

		// Reset umask
		@umask($origmask);

		return $ret;
	}

	/**
	 * Delete a folder.
	 *
	 * @param   string  $path  The path to the folder to delete.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public static function delete($path)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// Sanity check
		if (!$path)
		{
			// Bad programmer! Bad Bad programmer!
			Log::add(__METHOD__ . ': You can not delete a base directory.', Log::WARNING, 'jerror');

			return false;
		}

		try
		{
			// Check to make sure the path valid and clean
			$path = Path::clean($path);
		}
		catch (\UnexpectedValueException $e)
		{
			throw new \UnexpectedValueException($e);
		}

		// Is this really a folder?
		if (!is_dir($path))
		{
			Log::add(sprintf('%1$s: Path is not a folder. Path: %2$s', __METHOD__, $path), Log::WARNING, 'jerror');

			return false;
		}

		// Remove all the files in folder if they exist; disable all filtering
		$files = self::files($path, '.', false, true, array(), array());

		if (!empty($files))
		{
			if (File::delete($files) !== true)
			{
				// File::delete throws an error
				return false;
			}
		}

		// Remove sub-folders of folder; disable all filtering
		$folders = self::folders($path, '.', false, true, array(), array());

		foreach ($folders as $folder)
		{
			if (is_link($folder))
			{
				// Don't descend into linked directories, just delete the link.
				if (File::delete($folder) !== true)
				{
					// File::delete throws an error
					return false;
				}
			}
			elseif (self::delete($folder) !== true)
			{
				// JFolder::delete throws an error
				return false;
			}
		}

		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp.
		if (@rmdir($path))
		{
			return true;
		}
		else
		{
			Log::add(sprintf('%1$s: Could not delete folder. Path: %2$s', __METHOD__, $path), Log::WARNING, 'jerror');
			return false;
		}
	}

	/**
	 * Moves a folder.
	 *
	 * @param   string   $src          The path to the source folder.
	 * @param   string   $dest         The path to the destination folder.
	 * @param   string   $path         An optional base path to prefix to the file names.
	 * @param   boolean  $use_streams  Optionally use streams.
	 *
	 * @return  mixed  Error message on false or boolean true on success.
	 *
	 * @since   1.0
	 */
	public static function move($src, $dest, $path = '', $use_streams = false)
	{
		if ($path)
		{
			$src = Path::clean($path . '/' . $src);
			$dest = Path::clean($path . '/' . $dest);
		}

		if (!is_dir(Path::clean($src)))
		{
			return 'Cannot find source folder';
		}

		if (is_dir(Path::clean($dest)))
		{
			return 'Folder already exists';
		}

		if ($use_streams)
		{
			$stream = Factory::getStream();

			if (!$stream->move($src, $dest))
			{
				return 'Rename failed: ' . $stream->getError();
			}

			return true;
		}
		else
		{
			if (!@rename($src, $dest))
			{
				return 'Rename failed';
			}

			return true;
		}
	}

	/**
	 * Utility function to read the files in a folder.
	 *
	 * @param   string   $path           The path of the folder to read.
	 * @param   string   $filter         A filter for file names.
	 * @param   mixed    $recurse        True to recursively search into sub-folders, or an integer to specify the maximum depth.
	 * @param   boolean  $full           True to return the full path to the file.
	 * @param   array    $exclude        Array with names of files which should not be shown in the result.
	 * @param   array    $excludefilter  Array of filter to exclude
	 *
	 * @return  array  Files in the given folder.
	 *
	 * @since   1.0
	 */
	public static function files($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
		$excludefilter = array('^\..*', '.*~'))
	{
		// Check to make sure the path valid and clean
		$path = Path::clean($path);

		// Is the path a folder?
		if (!is_dir($path))
		{
			Log::add(sprintf('%1$s: Path is not a folder. Path: %2$s', __METHOD__, $path), Log::WARNING, 'jerror');

			return false;
		}

		// Compute the excludefilter string
		if (count($excludefilter))
		{
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		}
		else
		{
			$excludefilter_string = '';
		}

		// Get the files
		$arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, true);

		// Sort the files
		asort($arr);

		return array_values($arr);
	}

	/**
	 * Utility function to read the folders in a folder.
	 *
	 * @param   string   $path           The path of the folder to read.
	 * @param   string   $filter         A filter for folder names.
	 * @param   mixed    $recurse        True to recursively search into sub-folders, or an integer to specify the maximum depth.
	 * @param   boolean  $full           True to return the full path to the folders.
	 * @param   array    $exclude        Array with names of folders which should not be shown in the result.
	 * @param   array    $excludefilter  Array with regular expressions matching folders which should not be shown in the result.
	 *
	 * @return  array  Folders in the given folder.
	 *
	 * @since   1.0
	 */
	public static function folders($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
		$excludefilter = array('^\..*'))
	{
		// Check to make sure the path valid and clean
		$path = Path::clean($path);

		// Is the path a folder?
		if (!is_dir($path))
		{
			Log::add(sprintf('%1$s: Path is not a folder. Path: %2$s', __METHOD__, $path), Log::WARNING, 'jerror');

			return false;
		}

		// Compute the excludefilter string
		if (count($excludefilter))
		{
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		}
		else
		{
			$excludefilter_string = '';
		}

		// Get the folders
		$arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);

		// Sort the folders
		asort($arr);

		return array_values($arr);
	}

	/**
	 * Function to read the files/folders in a folder.
	 *
	 * @param   string   $path                  The path of the folder to read.
	 * @param   string   $filter                A filter for file names.
	 * @param   mixed    $recurse               True to recursively search into sub-folders, or an integer to specify the maximum depth.
	 * @param   boolean  $full                  True to return the full path to the file.
	 * @param   array    $exclude               Array with names of files which should not be shown in the result.
	 * @param   string   $excludefilter_string  Regexp of files to exclude
	 * @param   boolean  $findfiles             True to read the files, false to read the folders
	 *
	 * @return  array  Files.
	 *
	 * @since   1.0
	 */
	protected static function _items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles)
	{
		@set_time_limit(ini_get('max_execution_time'));

		$arr = array();

		// Read the source directory
		if (!($handle = @opendir($path)))
		{
			return $arr;
		}

		while (($file = readdir($handle)) !== false)
		{
			if ($file != '.' && $file != '..' && !in_array($file, $exclude)
				&& (empty($excludefilter_string) || !preg_match($excludefilter_string, $file)))
			{
				// Compute the fullpath
				$fullpath = $path . '/' . $file;

				// Compute the isDir flag
				$isDir = is_dir($fullpath);

				if (($isDir xor $findfiles) && preg_match("/$filter/", $file))
				{
					// (fullpath is dir and folders are searched or fullpath is not dir and files are searched) and file matches the filter
					if ($full)
					{
						// Full path is requested
						$arr[] = $fullpath;
					}
					else
					{
						// Filename is requested
						$arr[] = $file;
					}
				}

				if ($isDir && $recurse)
				{
					// Search recursively
					if (is_int($recurse))
					{
						// Until depth 0 is reached
						$arr = array_merge($arr, self::_items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludefilter_string, $findfiles));
					}
					else
					{
						$arr = array_merge($arr, self::_items($fullpath, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles));
					}
				}
			}
		}

		closedir($handle);

		return $arr;
	}

	/**
	 * Lists folder in format suitable for tree display.
	 *
	 * @param   string   $path      The path of the folder to read.
	 * @param   string   $filter    A filter for folder names.
	 * @param   integer  $maxLevel  The maximum number of levels to recursively read, defaults to three.
	 * @param   integer  $level     The current level, optional.
	 * @param   integer  $parent    Unique identifier of the parent folder, if any.
	 *
	 * @return  array  Folders in the given folder.
	 *
	 * @since   1.0
	 */
	public static function listFolderTree($path, $filter, $maxLevel = 3, $level = 0, $parent = 0)
	{
		$dirs = array();

		if ($level == 0)
		{
			$GLOBALS['_JFolder_folder_tree_index'] = 0;
		}

		if ($level < $maxLevel)
		{
			$folders = self::folders($path, $filter);

			// First path, index foldernames
			foreach ($folders as $name)
			{
				$id = ++$GLOBALS['_JFolder_folder_tree_index'];
				$fullName = Path::clean($path . '/' . $name);
				$dirs[] = array('id' => $id, 'parent' => $parent, 'name' => $name, 'fullname' => $fullName,
					'relname' => str_replace(JPATH_ROOT, '', $fullName));
				$dirs2 = self::listFolderTree($fullName, $filter, $maxLevel, $level + 1, $id);
				$dirs = array_merge($dirs, $dirs2);
			}
		}

		return $dirs;
	}

	/**
	 * Makes path name safe to use.
	 *
	 * @param   string  $path  The full path to sanitise.
	 *
	 * @return  string  The sanitised string.
	 *
	 * @since   1.0
	 */
	public static function makeSafe($path)
	{
		$regex = array('#[^A-Za-z0-9_\\\/\(\)\[\]\{\}\#\$\^\+\.\'~`!@&=;,-]#');

		return preg_replace($regex, '', $path);
	}
}
