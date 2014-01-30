<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Cli\Output;

use Joomla\Application\Cli\CliOutput;
use Joomla\Application\Cli\ColorStyle;
use Joomla\Application\Cli\ColorProcessor;

/**
 * Class Stdout.
 *
 * @since  1.0
 */
class Stdout extends CliOutput
{
	/**
	 * Constructor
	 *
	 * @since   1.0
	 */
	public function __construct()
	{
		$this->processor = new ColorProcessor;

		$this->addPredefinedStyles();
	}

	/**
	 * Set a processor
	 *
	 * @param   ColorProcessor  $processor  The color processor.
	 *
	 * @return  Stdout  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function setProcessor(ColorProcessor $processor)
	{
		$this->processor = $processor;

		return $this;
	}

	/**
	 * Get a processor
	 *
	 * @return  ColorProcessor
	 *
	 * @since   1.0
	 */
	public function getProcessor()
	{
		return $this->processor;
	}

	/**
	 * Write a string to standard output
	 *
	 * @param   string   $text  The text to display.
	 * @param   boolean  $nl    True (default) to append a new line at the end of the output string.
	 *
	 * @return  Stdout  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function out($text = '', $nl = true)
	{
		fwrite(STDOUT, $this->processor->process($text) . ($nl ? "\n" : null));

		return $this;
	}

	/**
	 * Adds predefined color styles to the ColorProcessor object
	 *
	 * @return  Stdout  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	private function addPredefinedStyles()
	{
		$this->processor->addStyle(
			'info',
			new ColorStyle('green', '', array('bold'))
		);

		$this->processor->addStyle(
			'comment',
			new ColorStyle('yellow', '', array('bold'))
		);

		$this->processor->addStyle(
			'question',
			new ColorStyle('black', 'cyan')
		);

		$this->processor->addStyle(
			'error',
			new ColorStyle('white', 'red')
		);

		return $this;
	}
}
