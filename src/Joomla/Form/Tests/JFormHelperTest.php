<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Form\Rule;
use Joomla\Form\FormHelper;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormHelperTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the Form::addFieldPath method.
	 *
	 * This method is used to add additional lookup paths for field helpers.
	 *
	 * @return void
	 */
	public function testAddFieldPath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addFieldPath();

		// The default path is the class file folder/forms
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/field';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries fields path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addFieldPath(__DIR__);
		$paths = FormHelper::addFieldPath();

		$this->assertThat(
			in_array(__DIR__, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Tests the Form::addFormPath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 *
	 * @return void
	 */
	public function testAddFormPath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addFormPath();

		// The default path is the class file folder/forms
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/form';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries forms path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addFormPath(__DIR__);
		$paths = FormHelper::addFormPath();

		$this->assertThat(
			in_array(__DIR__, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Tests the Form::addRulePath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 *
	 * @return void
	 */
	public function testAddRulePath()
	{
		// Check the default behaviour.
		$paths = FormHelper::addRulePath();

		// The default path is the class file folder/rules
		// use of realpath to ensure test works for on all platforms
		$valid = dirname(__DIR__) . '/rule';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The libraries rule path should be included by default.'
		);

		// Test adding a custom folder.
		FormHelper::addRulePath(__DIR__);
		$paths = FormHelper::addRulePath();

		$this->assertThat(
			in_array(__DIR__, $paths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' An added path should be in the returned array.'
		);
	}

	/**
	 * Test the Form::loadFieldType method.
	 *
	 * @return void
	 */
	public function testLoadFieldType()
	{
		$this->assertThat(
			FormHelper::loadFieldType('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' loadFieldType should return false if class not found.'
		);

		$this->assertThat(
			(FormHelper::loadFieldType('list') instanceof \Joomla\Form\Field_List),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct class.'
		);

		// Add custom path.
		FormHelper::addFieldPath(__DIR__ . '/_testfields');

		include_once '_testfields/test.php';
		$this->assertThat(
			(FormHelper::loadFieldType('test') instanceof \Joomla\Form\Field_Test),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		include_once '_testfields/bar.php';
		$this->assertThat(
			(FormHelper::loadFieldType('foo.bar') instanceof \Foo\Form\Field_Bar),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		include_once '_testfields/modal/foo.php';
		$this->assertThat(
			(FormHelper::loadFieldType('modal_foo') instanceof \Joomla\Form\Field_Modal_Foo),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);

		include_once '_testfields/modal/bar.php';
		$this->assertThat(
			(FormHelper::loadFieldType('foo.modal_bar') instanceof \Foo\Form\Field_Modal_Bar),
			$this->isTrue(),
			'Line:' . __LINE__ . ' loadFieldType should return the correct custom class.'
		);
	}

	/**
	 * Test for Form::loadRuleType method.
	 *
	 * @return void
	 */
	public function testLoadRuleType()
	{
		// Test error handling.

		$this->assertThat(
			FormHelper::loadRuleType('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Loading an unknown rule should return false.'
		);

		// Test loading a custom rule.

		FormHelper::addRulePath(__DIR__ . '/_testrules');

		$this->assertThat(
			(FormHelper::loadRuleType('custom') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading a known rule should return a rule object.'
		);

		// Test all the stock rules load.

		$this->assertThat(
			(FormHelper::loadRuleType('boolean') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the boolean rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('email') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the email rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('equals') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the equals rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('options') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the options rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('color') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the color rule should return a rule object.'
		);

		$this->assertThat(
			(FormHelper::loadRuleType('tel') instanceof Rule),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Loading the tel rule should return a rule object.'
		);
	}
}
