<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

include_once __DIR__ . '/stubs/JApplicationBaseInspector.php';

/**
 * Test class for JApplicationBase.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Application
 * @since       12.1
 */
class JApplicationBaseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the object to test.
	 *
	 * @var    JApplicationBaseInspector
	 * @since  11.3
	 */
	protected $class;

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	protected function setUp()
	{
		parent::setUp();

		// Create the class object to be tested.
		$this->class = new JApplicationBaseInspector;
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

	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   11.1
	 */
	protected function tearDown()
	{
		parent::tearDown();
	}
}
