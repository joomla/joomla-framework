<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
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
class JApplicationBaseTest extends TestCase
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
