<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field_Checkbox;

/**
 * Test class for JFormFieldCheckbox.
 *
 * @since  1.0
 */
class JFormFieldCheckboxTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependencies for the test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		include_once dirname(__DIR__) . '/inspectors.php';
	}

	/**
	 * Test the getInput method where there is no value from the element
	 * and no checked attribute.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInputNoValueNoChecked()
	{
		$formField = new Field_Checkbox;

		// Test with no checked element
		$element = simplexml_load_string(
			'<field name="color" type="checkbox" value="red" />');
		TestHelper::setValue($formField, 'element', $element);
		TestHelper::setValue($formField, 'id', 'myTestId');
		TestHelper::setValue($formField, 'name', 'myTestName');

		$this->assertEquals(
			'<input type="checkbox" name="myTestName" id="myTestId" value="red" />',
			TestHelper::invoke($formField, 'getInput'),
			'The field with no value and no checked attribute did not produce the right html'
		);
	}

	/**
	 * Test the getInput method where there is a value from the element
	 * and no checked attribute.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInputValueNoChecked()
	{
		$formField = new Field_Checkbox;

		// Test with no checked element
		$element = simplexml_load_string(
			'<field name="color" type="checkbox" value="red" />');
		TestHelper::setValue($formField, 'element', $element);
		TestHelper::setValue($formField, 'id', 'myTestId');
		TestHelper::setValue($formField, 'name', 'myTestName');
		TestHelper::setValue($formField, 'value', 'red');

		$this->assertEquals(
			'<input type="checkbox" name="myTestName" id="myTestId" value="red" checked="checked" />',
			TestHelper::invoke($formField, 'getInput'),
			'The field with a value and no checked attribute did not produce the right html'
		);
	}

	/**
	 * Test the getInput method where there is a checked attribute
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInputNoValueChecked()
	{
		$formField = new Field_Checkbox;

		// Test with checked element
		$element = simplexml_load_string(
			'<field name="color" type="checkbox" value="red" checked="checked" />');
		TestHelper::setValue($formField, 'element', $element);
		TestHelper::setValue($formField, 'id', 'myTestId');
		TestHelper::setValue($formField, 'name', 'myTestName');

		$this->assertEquals(
			'<input type="checkbox" name="myTestName" id="myTestId" value="red" checked="checked" />',
			TestHelper::invoke($formField, 'getInput'),
			'The field with no value and the checked attribute did not produce the right html'
		);
	}

	/**
	 * Test the getInput method where the field is disabled
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInputDisabled()
	{
		$formField = new Field_Checkbox;

		// Test with checked element
		$element = simplexml_load_string(
			'<field name="color" type="checkbox" value="red" disabled="true" />');
		TestHelper::setValue($formField, 'element', $element);
		TestHelper::setValue($formField, 'id', 'myTestId');
		TestHelper::setValue($formField, 'name', 'myTestName');

		$this->assertEquals(
			'<input type="checkbox" name="myTestName" id="myTestId" value="red" disabled="disabled" />',
			TestHelper::invoke($formField, 'getInput'),
			'The field set to disabled did not produce the right html'
		);
	}
}
