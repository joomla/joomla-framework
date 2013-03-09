<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test class for JFormFieldLanguage.
 *
 * @since    1.0
 */
class JFormFieldLanguageTest extends TestCaseDatabase
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
	 * @return  xml  dataset
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

		$field = new JFormFieldLanguage($form);

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
}
