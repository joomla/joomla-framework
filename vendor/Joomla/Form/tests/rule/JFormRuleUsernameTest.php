<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Form\Rule\Username as RuleUsername;

/**
 * Test class for JForm.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Form
 *
 * @since       11.1
 */
class JFormRuleUsernameTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\Username::test method.
	 *
	 * @return void
	 */
	public function testUsername()
	{
		$rule = new RuleUsername;
		$xml = simplexml_load_string('<form><field name="foo" /></form>');

		// Test fail conditions.

		// Test pass conditions.

		$this->markTestIncomplete();
	}
}
