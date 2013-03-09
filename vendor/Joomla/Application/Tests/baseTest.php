<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests;

include_once __DIR__ . '/Stubs/BaseInspector.php';

/**
 * Test class for Joomla\Application\Base.
 *
 * @since    1.0
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the object to test.
	 *
	 * @var    BaseInspector
	 * @since  1.0
	 */
	protected $class;

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		// Create the class object to be tested.
		$this->class = new BaseInspector;
	}

	/**
	 * Test the close function
	 *
	 * @return  void
	 */
	public function testClose()
	{
		// Make sure the application is not already closed.
		$this->assertSame(
			$this->class->closed,
			null,
			'Checks the application doesn\'t start closed.'
		);

		$this->class->close(3);

		// Make sure the application is closed with code 3.
		$this->assertSame(
			$this->class->closed,
			3,
			'Checks the application was closed with exit code 3.'
		);
	}
}
