<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// Search for composer in the package itself
$packageComposerAutoload = __DIR__ . '/../vendor/autoload.php';

if (file_exists($packageComposerAutoload))
{
	return include_once $packageComposerAutoload;
}

// Search for the Composer autoload file
$composerAutoload = __DIR__ . '/../../../../../autoload.php';

if (file_exists($composerAutoload))
{
	return include_once $composerAutoload;
}

include_once __DIR__ . '/../../../../tests/bootstrap.php';
