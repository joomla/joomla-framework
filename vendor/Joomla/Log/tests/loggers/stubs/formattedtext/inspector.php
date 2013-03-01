<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  Log
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Inspector classes for the JLog package.
 */

/**
 * JLogLoggerFormattedTextInspector class.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Log
 * @since       11.1
 */
class JLogLoggerFormattedTextInspector extends Joomla\Log\Logger\Formattedtext
{
	public $file;

	public $format = '{DATETIME}	{PRIORITY}	{CATEGORY}	{MESSAGE}';

	public $options;

	public $fields;

	public $path;
}
