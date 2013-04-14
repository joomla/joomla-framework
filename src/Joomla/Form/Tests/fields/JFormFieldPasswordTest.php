<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestHelper;
use Joomla\Form\Field_Password;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormFieldPasswordTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up dependancies for the test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		include_once dirname(__DIR__) . '/inspectors.php';
	}

	/**
	 * Test the getInput method.
	 *
	 * @return void
	 */
	public function testGetInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load('<form><field name="password" type="password" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Password($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->assertThat(
			strlen($field->input),
			$this->greaterThan(0),
			'Line:' . __LINE__ . ' The getInput method should return something without error.'
		);

		// TODO: Should check all the attributes have come in properly.
	}
}
