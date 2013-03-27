<?php
/**
 * Bootstrap file for the Joomla Platform.  Including this file into your application will make Joomla
 * Platform libraries available for use.
 *
 * @package    Joomla\Framework
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Set the platform root path as a constant if necessary.
if (!defined('JPATH_FRAMEWORK'))
{
	define('JPATH_FRAMEWORK', __DIR__);
}

// Detect the native operating system type.
$os = strtoupper(substr(PHP_OS, 0, 3));

if (!defined('IS_WIN'))
{
	define('IS_WIN', ($os === 'WIN') ? true : false);
}
if (!defined('IS_UNIX'))
{
	define('IS_UNIX', (IS_WIN === false) ? true : false);
}

// Include the composer autoloader.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Import the library loader if necessary. Don't try to autoload it yet.
if (!class_exists('JLoader', false))
{
	require_once JPATH_FRAMEWORK . '/loader.php';
}

JLoader::setup();
