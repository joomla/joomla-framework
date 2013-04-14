<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Rule\Color as RuleColor;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormRuleColorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * set up for testing
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * Tear down test
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test the Joomla\Form\Rule\Color::test method.
	 *
	 * @return void
	 */
	public function testColor()
	{
		$rule = new RuleColor;
		$xml = simplexml_load_string('<form><field name="color" /></form>');

		// Test fail conditions.
		$this->assertThat(
			$rule->test($xml->field[0], 'bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The rule should fail and return false.'
		);

		// Test pass conditions.
		$this->assertThat(
			$rule->test($xml->field[0], '#000000'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The basic rule should pass and return true.'
		);
	}

	/**
	 * Test...
	 *
	 * @return array
	 */
	public function colorData()
	{
		return array(
			array('#000000', true),
			array('#', false),
			array('#000', true),
			array('#FFFFFF', true),
			array('#EEE', true),
			array('#A0A0A0', true),
			array('#GGGGGG', false),
			array('FFFFFF', false),
			array('#GGG', false),
			array('', true)
		);
	}

	/**
	 * Test...
	 *
	 * @param   string  $color           @todo
	 * @param   string  $expectedResult  @todo
	 *
	 * @dataProvider colorData
	 *
	 * @return void
	 */
	public function testColorData($color, $expectedResult)
	{
		$rule = new RuleColor;
		$xml = simplexml_load_string('<form><field name="color1" /></form>');
		$this->assertThat(
			$rule->test($xml->field[0], $color),
			$this->equalTo($expectedResult),
			$color . ' should have returned ' . ($expectedResult ? 'true' : 'false') . ' but did not'
		);
	}
}
