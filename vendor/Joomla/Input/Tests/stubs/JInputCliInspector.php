<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Input\Cli;

/**
 * Inspector classes for the JInputCLI library.
 *
 * @since  1.0
 */
class JInputCliInspector extends Cli
{
	public $options;

	public $filter;

	public $data;

	public $inputs;

	public static $registered;

	/**
	 * Test...
	 *
	 * @return void
	 */
	public static function register()
	{
		return parent::register();
	}

	/**
	 * Test...
	 *
	 * @return void
	 */
	public function parseArguments()
	{
		return parent::parseArguments();
	}
}
