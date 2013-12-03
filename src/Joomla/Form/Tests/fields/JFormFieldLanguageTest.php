<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Test\TestDatabase;
use Joomla\Form\Field_Language;

/**
 * Test class for JFormFieldLanguage.
 *
 * @since  1.0
 */
class JFormFieldLanguageTest extends TestDatabase
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
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet  dataset
	 *
	 * @since   1.0
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/testfiles/JFormField.xml');
	}

	/**
	 * Test the getInput method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load('<form><field name="language" type="language" /></form>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$field = new Field_Language($form);

		$this->assertThat(
			$field->setup($form->getXml()->field, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setup method should return true.'
		);

		$this->markTestIncomplete('Problems encountered in next assertion');

		$this->assertThat(
			strlen($field->input),
			$this->greaterThan(0),
			'Line:' . __LINE__ . ' The getInput method should return something without error.'
		);

		// TODO: Should check all the attributes have come in properly.
	}

	/**
	 * Test...
	 *
	 * @return void
	 */
	public function testCreateLanguageList()
	{
		$field = new Field_Language(new JFormInspector('form1'));
		$reflection = new \ReflectionClass($field);
		$method = $reflection->getMethod('createLanguageList');
		$method->setAccessible(true);

		$list = $method->invokeArgs(
			$field,
			array(
				'en-GB',
				__DIR__ . '/data'
			)
		);

		$listCompareEqual = array(
			array(
				'text' => 'English (United Kingdom)',
				'value' => 'en-GB',
				'selected' => 'selected="selected"'
			)
		);

		$this->assertEquals(
			$listCompareEqual,
			$list
		);
	}
}
