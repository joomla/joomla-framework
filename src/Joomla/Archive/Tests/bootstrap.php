<?php
/**
 * Part of the Joomla Framework Archive Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

if (!defined('JPATH_ROOT'))
{
	define('JPATH_ROOT', __DIR__);
}

$autoload = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoload))
{
	include_once $autoload;
}
