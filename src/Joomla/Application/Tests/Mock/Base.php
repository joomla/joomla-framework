<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests\Mock;

/**
 * Class to mock \Joomla\Application\Base.
 *
 * @since  1.0
 */
class Base
{
	/**
	 * Creates and instance of the mock JApplicationBase object.
	 *
	 * @param   \PHPUnit_Framework_TestCase  $test     A test object.
	 * @param   array                        $options  A set of options to configure the mock.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public static function create(\PHPUnit_Framework_TestCase $test, $options = array())
	{
		// Set expected server variables.
		if (!isset($_SERVER['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = 'localhost';
		}

		// Collect all the relevant methods in JApplicationBase (work in progress).
		$methods = array(
			'close',
			'doExecute',
			'execute',
			'fetchConfigurationData',
			'loadConfiguration',
			'get',
			'set'
		);

		// Create the mock.
		$mockObject = $test->getMock(
			'Joomla\\Application\\Base',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			true
		);

		return $mockObject;
	}
}
