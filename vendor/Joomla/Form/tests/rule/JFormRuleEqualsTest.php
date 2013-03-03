<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Form\Rule\Equals as RuleEquals;

/**
 * Test class for JForm.
 *
 * @package  Joomla\Framework\Test
 * @since    11.1
 */
class JFormRuleEqualsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\Equals::test method.
	 *
	 * @return void
	 */
	public function testEquals()
	{
		$rule = new RuleEquals;
		$xml = simplexml_load_string('<form><field name="foo" /></form>');

		// Test fail conditions.

		// Test pass conditions.

		$this->markTestIncomplete();
	}
}
