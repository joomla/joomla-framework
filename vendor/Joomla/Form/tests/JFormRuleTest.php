<?php
/**
 * @package     JoomlaFrameworkTests
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Form\Rule;

/**
 * Test class for JForm.
 *
 * @package     JoomlaFrameworkTests
 * @subpackage  Form
 *
 * @since       11.1
 *
 * @return void
 */
class FormRuleTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Rule
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Rule;
	}

	/**
	 * Test...
	 *
	 * @todo Implement testTest().
	 *
	 * @return void
	 */
	public function testTest()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
