<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License
 */

namespace Joomla\Model\Tests\Mock;

/**
 * Mock class for \Joomla\Model\AbstractModel.
 *
 * @since  1.0
 */
class Model
{
	/**
	 * Creates and instance of the mock AbstractModel object.
	 *
	 * @param   \PHPUnit_Framework_TestCase  $test  A test object.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public static function create(\PHPUnit_Framework_TestCase $test)
	{
		// Collect all the relevant methods in AbstractModel.
		$methods = array(
			'getState',
			'loadState',
			'setState',
		);

		// Create the mock.
		$mockObject = $test->getMock(
			'Joomla\\Model\\ModelInterface',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		return $mockObject;
	}
}
