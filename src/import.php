<?php
/**
 * Bootstrap file for the Joomla Framework.  Including this file into your application will make Joomla
 * Framework libraries available for use.
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

// Include the composer autoloader.
//require_once dirname(__DIR__) . '/vendor/autoload.php';

// Import the library loader if necessary. Don't try to autoload it yet.
if (!class_exists('JLoader', false))
{
	require_once JPATH_FRAMEWORK . '/loader.php';
}

JLoader::setup();
