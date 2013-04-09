<?php
/**
 * Unit test runner bootstrap file for the Joomla Framework.
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 * @link       http://www.phpunit.de/manual/current/en/installation.html
 */

// Fix magic quotes.
@ini_set('magic_quotes_runtime', 0);

// Maximise error reporting.
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);

/*
 * Ensure that required path constants are defined.  These can be overridden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', realpath(__DIR__));
}
if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', realpath(JPATH_TESTS . '/tmp'));
}
if (!defined('JPATH_ROOT'))
{
	define('JPATH_ROOT', JPATH_BASE);
}

// Search for the Composer autoload file
$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists($composerAutoload))
{
	include_once $composerAutoload;
}

// Import the platform.
require_once dirname(__DIR__) . '/src/import.php';

// Register the core Joomla test classes.
JLoader::registerPrefix('Test', __DIR__ . '/core');

/*
 * The following classes still depend on `JVersion` so we must load it until they are dealt with.
 *
 * JFactory
 */
require_once __DIR__ . '/version.php';

/*
 * The PHP garbage collector can be too aggressive in closing circular references before they are no longer needed.  This can cause
 * segfaults during long, memory-intensive processes such as testing large test suites and collecting coverage data.  We explicitly
 * disable garbage collection during the execution of PHPUnit processes so that we (hopefully) don't run into these issues going
 * forwards.  This is only a problem PHP 5.3+.
 */
gc_disable();

// We need this to test JSession for now.  We should really fix this.
// ob_start();
