<?php
/**
 * Bootstrap file for the Joomla Platform.  Including this file into your application will make Joomla
 * Platform libraries available for use.
 *
 * @package    Joomla.Platform
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Global application exit.
 *
 * This function provides a single exit point for the platform.
 *
 * @param   mixed  $message  Exit code or string. Defaults to zero.
 *
 * @return  void
 *
 * @codeCoverageIgnore
 * @since   11.1
 */
function jexit($message = 0)
{
	exit($message);
}

/**
 * Intelligent file importer.
 *
 * @param   string  $path  A dot syntax path.
 *
 * @return  boolean  True on success.
 *
 * @since   11.1
 */
function jimport($path)
{
	return Joomla\Loader::import($path);
}