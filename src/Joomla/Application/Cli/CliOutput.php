<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Cli;

/**
 * Class CliOutput
 *
 * @since  1.0
 */
abstract class CliOutput
{
	/**
	 * Color processing object
	 *
	 * @var    ColorProcessor
	 * @since  1.0
	 */
	protected $processor;

	/**
	 * Write a string to an output handler.
	 *
	 * @param   string   $text  The text to display.
	 * @param   boolean  $nl    True (default) to append a new line at the end of the output string.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @codeCoverageIgnore
	 */
	abstract public function out($text = '', $nl = true);
}
