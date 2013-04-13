<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Options as RuleOptions;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormRuleOptionsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Set up for testing
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * Tear down test
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test the Joomla\Form\Rule\Options::test method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testOptions()
	{
		$rule = new RuleOptions;
		$xml = simplexml_load_string(
			'<form><field name="field1"><option value="value1">Value1</option><option value="value2">Value2</option></field></form>'
		);

		// Test fail conditions.

		$this->assertThat(
			$rule->test($xml->field[0], 'bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		// Test pass conditions.

		$this->assertThat(
			$rule->test($xml->field[0], 'value1'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' value1 should pass and return true.'
		);

		$this->assertThat(
			$rule->test($xml->field[0], 'value2'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' value2 should pass and return true.'
		);
	}
}
