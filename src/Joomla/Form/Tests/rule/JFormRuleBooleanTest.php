<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Boolean as RuleBoolean;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormRuleBooleanTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Form\Rule\Boolean::test method.
	 *
	 * @return void
	 */
	public function testBoolean()
	{
		$rule = new RuleBoolean;
		$xml = simplexml_load_string('<form><field name="foo" /></form>');

		// Test fail conditions.

		$this->assertThat(
			$rule->test($xml->field, 'bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		$this->assertThat(
			$rule->test($xml->field, '0_anything'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		$this->assertThat(
			$rule->test($xml->field, 'anything_1_anything'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		$this->assertThat(
			$rule->test($xml->field, 'anything_true_anything'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		$this->assertThat(
			$rule->test($xml->field, 'anything_false'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		// Test pass conditions.

		$this->assertThat(
			$rule->test($xml->field, 0),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule should pass and return true.'
		);

		$this->assertThat(
			$rule->test($xml->field, '0'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule should pass and return true.'
		);

		$this->assertThat(
			$rule->test($xml->field, 1),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule should pass and return true.'
		);

		$this->assertThat(
			$rule->test($xml->field, '1'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule should pass and return true.'
		);

		$this->assertThat(
			$rule->test($xml->field, 'true'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule should pass and return true.'
		);

		$this->assertThat(
			$rule->test($xml->field, 'false'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule should pass and return true.'
		);
	}
}
