<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Form\Form;
use Joomla\Form\FormHelper;
use Joomla\Form\Rule;

/**
 * Test class for JForm.
 *
 * @since  1.0
 */
class JFormTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test showXml
	 *
	 * @param   string  $form  The form.
	 *
	 * @return void
	 */
	private function _showXml($form)
	{
		$dom = new \DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($form->getXml()->asXML());
		echo $dom->saveXML();
	}

	/**
	 * Set up for testing
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		include_once 'inspectors.php';
		include_once 'JFormDataHelper.php';
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
	 * Test the Form::addNode method.
	 *
	 * @return void
	 */
	public function testAddNode()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><fields /></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><field name="foo" /></form>');

		if ($xml1 === false || $xml2 === false)
		{
			$this->fail('Error in text XML data');
		}

		JFormInspector::addNode($xml1->fields, $xml2->field);

		$fields = $xml1->xpath('fields/field[@name="foo"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The field should be added, ungrouped.'
		);
	}

	/**
	 * Tests the Form::bind method.
	 *
	 * This method is used to load data into the JForm object.
	 *
	 * @return void
	 */
	public function testBind()
	{
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$bindDocument;

		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$data = array(
			'title' => 'Joomla Framework',
			'author' => 'Should not bind',
			'params' => array(
				'show_title' => 1,
				'show_abstract' => 0,
				'show_author' => 1,
				'categories' => array(
					1,
					2
				),
				'keywords' => array('en-GB' => 'Joomla', 'fr-FR' => 'Joomla')
			)
		);

		$this->assertThat(
			$form->bind($data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$data = $form->getData();
		$this->assertThat(
			$data->get('title'),
			$this->equalTo('Joomla Framework'),
			'Line:' . __LINE__ . ' The data should bind to form field elements.'
		);

		$this->assertThat(
			$data->get('author'),
			$this->isNull(),
			'Line:' . __LINE__ . ' The data should not bind to unknown form field elements.'
		);

		$this->assertThat(
			is_array($data->get('params.categories')),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The categories param should be an array.'
		);
	}

	/**
	 * Testing methods used by the instantiated object.
	 *
	 * @return void
	 */
	public function testConstruct()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			($form instanceof Form),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The JForm constuctor should return a JForm object.'
		);

		// Check the integrity of the options.

		$options = $form->getOptions();
		$this->assertThat(
			isset($options['control']),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The JForm object should contain an options array with a control setting.'
		);

		$options = $form->getOptions();
		$this->assertThat(
			$options['control'],
			$this->isFalse(),
			'Line:' . __LINE__ . ' The control setting should be false by default.'
		);

		$form = new JFormInspector('form1', array('control' => 'jform'));

		$options = $form->getOptions();
		$this->assertThat(
			$options['control'],
			$this->equalTo('jform'),
			'Line:' . __LINE__ . ' The control setting should be what is passed in the constructor.'
		);
	}

	/**
	 * Test for Form::filter method.
	 *
	 * @return void
	 */
	public function testFilter()
	{
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->load(JFormDataHelper::$filterDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$data = array(
			'word' => 'Joomla! Framework',
			'author' => 'Should not bind',
			'params' => array(
				'show_title' => 1,
				'show_author' => false,
			),
			'default' => ''
		);

		$filtered = $form->filter($data);

		$this->assertThat(
			is_array($filtered),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The filtered result should be an array.'
		);

		// Test that filtering is occuring (not that all filters work - done in testFilterField).

		$this->assertThat(
			$filtered['word'],
			$this->equalTo('JoomlaFramework'),
			'Line:' . __LINE__ . ' The variable should be filtered by the "word" filter.'
		);

		$this->assertThat(
			isset($filtered['author']),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A variable in the data not present in the form should not exist.'
		);

		$this->assertThat(
			$filtered['params']['show_title'],
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The nested variable should be present.'
		);

		$this->assertThat(
			$filtered['params']['show_author'],
			$this->equalTo(0),
			'Line:' . __LINE__ . ' The nested variable should be present.'
		);
	}

	/**
	 * Test for Form::filterField method.
	 *
	 * @return void
	 */
	public function testFilterField()
	{
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->load(JFormDataHelper::$filterDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$input = '<script>alert();</script> <p>Some text.</p>';

		$this->assertThat(
			$form->filterField($form->findField('function'), $input),
			$this->equalTo('function'),
			'Line:' . __LINE__ . ' The function filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('int'), 'A1B2C3'),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The "int" filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('method'), $input),
			$this->equalTo('method'),
			'Line:' . __LINE__ . ' The class method filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('raw'), $input),
			$this->equalTo($input),
			'Line:' . __LINE__ . ' "The safehtml" filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('safehtml'), $input),
			$this->equalTo('alert(); <p>Some text.</p>'),
			'Line:' . __LINE__ . ' "The safehtml" filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('unset'), $input),
			$this->equalTo(null),
			'Line:' . __LINE__ . ' The value should be unset.'
		);

		$this->assertThat(
			$form->filterField($form->findField('word'), $input),
			$this->equalTo('scriptalertscriptpSometextp'),
			'Line:' . __LINE__ . ' The "word" filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('url'), 'http://example.com'),
			$this->equalTo('http://example.com'),
			'Line:' . __LINE__ . ' A field with a valid protocol should return as is.'
		);

		$this->assertThat(
			$form->filterField($form->findField('url'), 'http://<script>alert();</script> <p>Some text.</p>'),
			$this->equalTo('http://alert(); Some text.'),
			'Line:' . __LINE__ . ' A "url" with scripts should be should be filtered.'
		);

		$this->assertThat(
			$form->filterField($form->findField('url'), 'https://example.com'),
			$this->equalTo('https://example.com'),
			'Line:' . __LINE__ . ' A field with a valid protocol that is not http should return as is.'
		);

		$this->assertThat(
			$form->filterField($form->findField('url'), 'example.com'),
			$this->equalTo('http://example.com'),
			'Line:' . __LINE__ . ' A field without a protocol should return with a http:// protocol.'
		);

		$this->assertThat(
			$form->filterField($form->findField('url'), 'hptarr.com'),
			$this->equalTo('http://hptarr.com'),
			'Line:' . __LINE__ . ' A field without a protocol and starts with t should return with a http:// protocol.'
		);

		$this->assertThat(
			$form->filterField($form->findField('url'), ''),
			$this->equalTo(''),
			'Line:' . __LINE__ . ' An empty "url" filter return nothing.'
		);

		$this->assertThat(
			$form->filterField($form->findField('default'), $input),
			$this->equalTo('alert(); Some text.'),
			'Line:' . __LINE__ . ' The default strict filter should be correctly applied.'
		);

		$this->assertThat(
			$form->filterField($form->findField('tel'), '222.3333333333'),
			$this->equalTo('222.3333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), '+222.3333333333'),
			$this->equalTo('222.3333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), '+2,2,2.3,3,3,3,3,3,3,3,3,3,3,3'),
			$this->equalTo('222.333333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), '33333333333'),
			$this->equalTo('.33333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), '222333333333333'),
			$this->equalTo('222.333333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), '1 (202) 555-5555'),
			$this->equalTo('1.2025555555'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), '+222.33333333333x444'),
			$this->equalTo('222.33333333333'),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
		$this->assertThat(
			$form->filterField($form->findField('tel'), 'ABCabc/?.!*x'),
			$this->equalTo(''),
			'Line:' . __LINE__ . ' The tel filter should be correctly applied.'
		);
	}

	/**
	 * Test the Form::findField method.
	 *
	 * @return void
	 */
	public function testFindField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$findFieldDocument;

		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertThat(
			$form->findField('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' An ungrouped field that does not exist should return false.'
		);

		$this->assertThat(
			$form->findField('title', 'bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' An field in a group that does not exist should return false.'
		);

		// Test various find combinations.

		$field = $form->findField('title', null);
		$this->assertThat(
			(string) $field['place'],
			$this->equalTo('root'),
			'Line:' . __LINE__ . ' A known ungrouped field should load successfully.'
		);

		$field = $form->findField('title', 'params');
		$this->assertThat(
			(string) $field['place'],
			$this->equalTo('child'),
			'Line:' . __LINE__ . ' A known grouped field should load successfully.'
		);

		$field = $form->findField('alias');
		$this->assertThat(
			(string) $field['name'],
			$this->equalTo('alias'),
			'Line:' . __LINE__ . ' A known field in a fieldset should load successfully.'
		);

		$field = $form->findField('show_title', 'params');
		$this->assertThat(
			(string) $field['default'],
			$this->equalTo('1'),
			'Line:' . __LINE__ . ' A known field in a group fieldset should load successfully.'
		);
	}

	/**
	 * Tests the Form::findFieldsByFieldset method.
	 *
	 * @return void
	 */
	public function testFindFieldsByFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$findFieldsByFieldsetDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertThat(
			$form->findFieldsByFieldset('bogus'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' An unknown fieldset should return an empty array.'
		);

		// Test regular usage.

		$this->assertThat(
			count($form->findFieldsByFieldset('params-basic')),
			$this->equalTo(3),
			'Line:' . __LINE__ . ' The params-basic fieldset has 3 fields.'
		);

		$this->assertThat(
			count($form->findFieldsByFieldset('params-advanced')),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The params-advanced fieldset has 2 fields.'
		);
	}

	/**
	 * Test the Form::findFieldsByGroup method.
	 *
	 * @return void
	 */
	public function testFindFieldsByGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$findFieldsByGroupDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertThat(
			$form->findFieldsByGroup('bogus'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' A group that does not exist should return an empty array.'
		);

		// Test all fields.

		$this->assertThat(
			count($form->findFieldsByGroup()),
			$this->equalTo(11),
			'Line:' . __LINE__ . ' There are 9 field elements in total.'
		);

		// Test ungrouped fields.

		$this->assertThat(
			count($form->findFieldsByGroup(false)),
			$this->equalTo(4),
			'Line:' . __LINE__ . ' There are 4 ungrouped field elements.'
		);

		// Test grouped fields.

		$this->assertThat(
			count($form->findFieldsByGroup('details')),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The details group has 2 field elements.'
		);

		$this->assertThat(
			count($form->findFieldsByGroup('params')),
			$this->equalTo(3),
			'Line:' . __LINE__ . ' The params group has 3 field elements, including one nested in a fieldset.'
		);

		// Test nested fields.

		$this->assertThat(
			count($form->findFieldsByGroup('level1', true)),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' There should be 2 nested fields.'
		);
	}

	/**
	 * Test the Form::findGroup method.
	 *
	 * @return void
	 */
	public function testFindGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$findGroupDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->findGroup('bogus'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' A group that does not exist should return an empty array.'
		);

		$this->assertThat(
			count($form->findGroup('params')),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The group should have one element.'
		);

		$this->assertThat(
			$form->findGroup('bogus.data'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' A group path that does not exist should return an empty array.'
		);

		// Check that an existant field returns something.
		$this->assertThat(
			count($form->findGroup('params.cache')),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The group should have one element.'
		);
	}

	/**
	 * Test for Form::getErrors method.
	 *
	 * @return void
	 */
	public function testGetErrors()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$validateDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$fail = array(
			'boolean' => 'comply',
			'required' => '',
		);

		$this->assertThat(
			$form->validate($fail),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Validating this data should fail.'
		);

		$errors = $form->getErrors($fail);
		$this->assertThat(
			count($errors),
			$this->equalTo(3),
			'Line:' . __LINE__ . ' This data should invoke 3 errors.'
		);

		$this->assertThat(
			$errors[0] instanceof \Exception,
			$this->isTrue(),
			'Line:' . __LINE__ . ' The errors should be exception objects.'
		);
	}

	/**
	 * Test the Form::getField method.
	 *
	 * @return void
	 */
	public function testGetField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$getFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Check for errors.

		$this->assertThat(
			$form->getField('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A field that does not exist should return false.'
		);

		$this->assertThat(
			$form->getField('show_title'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A field that does exists in a group, without declaring the group, should return false.'
		);

		$this->assertThat(
			$form->getField('show_title', 'bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A field in a group that does not exist should return false.'
		);

		// Checking value defaults.

		$this->assertThat(
			$form->getField('title')->value,
			$this->equalTo(''),
			'Line:' . __LINE__ . ' Prior to binding data, the defaults in the field should be used.'
		);

		$this->assertThat(
			$form->getField('show_title', 'params')->value,
			$this->equalTo(1),
			'Line:' . __LINE__ . ' Prior to binding data, the defaults in the field should be used.'
		);

		// Check values after binding.

		$data = array(
			'title' => 'The title',
			'show_title' => 3,
			'params' => array(
				'show_title' => 2,
			)
		);

		$this->assertThat(
			$form->bind($data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The input data should bind successfully.'
		);

		$this->assertThat(
			$form->getField('title')->value,
			$this->equalTo('The title'),
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);

		$this->assertThat(
			$form->getField('show_title', 'params')->value,
			$this->equalTo(2),
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);

		// Check binding with an object.

		$data = new \stdClass;
		$data->title = 'The new title';
		$data->show_title = 5;
		$data->params = new \stdClass;
		$data->params->show_title = 4;

		$this->assertThat(
			$form->bind($data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The input data should bind successfully.'
		);

		$this->assertThat(
			$form->getField('title')->value,
			$this->equalTo('The new title'),
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);

		$this->assertThat(
			$form->getField('show_title', 'params')->value,
			$this->equalTo(4),
			'Line:' . __LINE__ . ' Check the field value bound correctly.'
		);
	}

	/**
	 * Test for Form::getFieldAttribute method.
	 *
	 * @return void
	 */
	public function testGetFieldAttribute()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$getFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test error handling.

		$this->assertThat(
			$form->getFieldAttribute('bogus', 'unknown', 'Help'),
			$this->equalTo('Help'),
			'Line:' . __LINE__ . ' The default value of the unknown field should be returned.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'unknown', 'Use this'),
			$this->equalTo('Use this'),
			'Line:' . __LINE__ . ' The default value of the unknown attribute should be returned.'
		);

		// Test general usage.

		$this->assertThat(
			$form->getFieldAttribute('title', 'description'),
			$this->equalTo('The title.'),
			'Line:' . __LINE__ . ' The value of the attribute should be returned.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'description', 'Use this'),
			$this->equalTo('The title.'),
			'Line:' . __LINE__ . ' The value of the attribute should be returned.'
		);
	}

	/**
	 * Test the Form::getFormControl method.
	 *
	 * @return void
	 */
	public function testGetFormControl()
	{
		$form = new Form('form8ion');

		$this->assertThat(
			$form->getFormControl(),
			$this->equalTo(''),
			'Line:' . __LINE__ . ' A form control that has not been specified should return nothing.'
		);

		$form = new Form('form8ion', array('control' => 'jform'));

		$this->assertThat(
			$form->getFormControl(),
			$this->equalTo('jform'),
			'Line:' . __LINE__ . ' The form control should agree with the options passed in the constructor.'
		);
	}

	/**
	 * Test for Form::getInstance.
	 *
	 * @return void
	 */
	public function testGetInstance()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Test for Form::getGroup method.
	 *
	 * @return void
	 */
	public function testGetGroup()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$findFieldsByGroupDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test error handling.

		$this->assertThat(
			$form->getGroup('bogus'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' A group that does not exist should return an empty array.'
		);

		// Test general usage.

		$this->assertThat(
			count($form->getGroup('params')),
			$this->equalTo(3),
			'Line:' . __LINE__ . ' The params group should have 3 field elements.'
		);

		$this->assertThat(
			count($form->getGroup('level1', true)),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The level1 group should have 2 nested field elements.'
		);

		$this->assertThat(
			array_keys($form->getGroup('level1', true)),
			$this->equalTo(array('level1_field1', 'level1_level2_field2')),
			'Line:' . __LINE__ . ' The level1 group should have 2 nested field elements.'
		);

		$this->assertThat(
			count($form->getGroup('level1.level2')),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The level2 group should have 1 field element.'
		);
	}

	/**
	 * Test for Form::getInput method.
	 *
	 * @return void
	 */
	public function testGetInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->getInput('title', null, 'The Title'),
			$this->equalTo('<input type="text" name="title" id="title_id" value="The Title" class="inputbox required"/>'),
			'Line:' . __LINE__ . ' The method should return a simple input text field.'
		);

		$this->assertThat(
			$form->getInput('show_title', 'params', '0'),
			$this->equalTo(
				'<fieldset id="params_show_title" class="radio">' .
					'<input type="radio" id="params_show_title0" name="params[show_title]" value="1"/>' .
					'<label for="params_show_title0">' . Text::_('JYes') . '</label>' .
					'<input type="radio" id="params_show_title1" name="params[show_title]" value="0" checked="checked"/>' .
					'<label for="params_show_title1">' . Text::_('JNo') . '</label>' .
					'</fieldset>'
			),
			'Line:' . __LINE__ . ' The method should return a radio list.'
		);

		$form = new JFormInspector('form1', array('control' => 'jform'));

		$this->assertThat(
			$form->load(JFormDataHelper::$loadFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->getInput('colours', 'params', 'blue'),
			$this->equalTo(
				'<select id="jform_params_colours" name="jform[params][colours][]" multiple="multiple">' .
					"\n" . '	<option value="red">Red</option>' .
					"\n" . '	<option value="blue" selected="selected">Blue</option>' .
					"\n" . '	<option value="green">Green</option>' .
					"\n" . '	<option value="yellow">Yellow</option>' .
					"\n" . '</select>' .
					"\n"
			),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test translate default
		$this->assertThat(
			$form->getInput('translate_default'),
			$this->equalTo(
				'<input type="text" name="jform[translate_default]" id="jform_translate_default" value="DEFAULT_KEY"/>'
			),
			'Line:' . __LINE__ .
			' The method should return a simple input text field whose value is untranslated since the DEFAULT_KEY does not exist in the language.'
		);

		$lang = Language::getInstance();
		$debug = $lang->setDebug(true);
		$this->assertThat(
			$form->getInput('translate_default'),
			$this->equalTo(
				'<input type="text" name="jform[translate_default]" id="jform_translate_default" value="??DEFAULT_KEY??"/>'
			),
			'Line:' . __LINE__ . ' The method should return a simple input text field whose value is marked untranslated.'
		);

		$lang->load('form_test', __DIR__);
		$this->assertThat(
			$form->getInput('translate_default'),
			$this->equalTo(
				'<input type="text" name="jform[translate_default]" id="jform_translate_default" value="My Default"/>'
			),
			'Line:' . __LINE__ . ' The method should return a simple input text field whose value is translated.'
		);
		$lang->setDebug($debug);
	}

	/**
	 * Test for Form::getLabel method.
	 *
	 * @return void
	 */
	public function testGetLabel()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$expected = '<label id="title_id-lbl" for="title_id" class="hasTip required" ' .
				'title="Title::The title.">Title<span class="star">&#160;*</span></label>';

		$this->assertThat(
			$form->getLabel('title'),
			$this->equalTo($expected),
			'Line:' . __LINE__ . ' The method should return a simple label field.'
		);
	}

	/**
	 * Test the Form::getName method.
	 *
	 * @return void
	 */
	public function testGetName()
	{
		$form = new Form('form1');

		$this->assertThat(
			$form->getName(),
			$this->equalTo('form1'),
			'Line:' . __LINE__ . ' The form name should agree with the argument passed in the constructor.'
		);
	}

	/**
	 * Test for Form::getValue method.
	 *
	 * @return void
	 */
	public function testGetValue()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$data = array(
			'title' => 'Avatar',
		);

		$this->assertThat(
			$form->bind($data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$this->assertThat(
			$form->getValue('title'),
			$this->equalTo('Avatar'),
			'Line:' . __LINE__ . ' The bind value should be returned.'
		);
	}

	/**
	 * Test for Form::getFieldset method.
	 *
	 * @return void
	 */
	public function testGetFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$getFieldsetDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->getFieldset('bogus'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' A fieldset that does not exist should return an empty array.'
		);

		$this->assertThat(
			count($form->getFieldset('params-basic')),
			$this->equalTo(4),
			'Line:' . __LINE__ . ' There are 3 field elements in a fieldset and 1 field element marked with the fieldset attribute.'
		);
	}

	/**
	 * Test for Form::getFieldsets method.
	 *
	 * @return void
	 */
	public function testGetFieldsets()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$getFieldsetsDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$sets = $form->getFieldsets();
		$this->assertThat(
			count($sets),
			$this->equalTo(3),
			'Line:' . __LINE__ . ' The source data has 3 fieldsets in total.'
		);

		$this->assertThat(
			$sets['params-advanced']->name,
			$this->equalTo('params-advanced'),
			'Line:' . __LINE__ . ' Ensure the fieldset name is correct.'
		);

		$this->assertThat(
			$sets['params-advanced']->label,
			$this->equalTo('Advanced Options'),
			'Line:' . __LINE__ . ' Ensure the fieldset label is correct.'
		);

		$this->assertThat(
			$sets['params-advanced']->description,
			$this->equalTo('The advanced options'),
			'Line:' . __LINE__ . ' Ensure the fieldset description is correct.'
		);

		// Test loading by group.

		$this->assertThat(
			$form->getFieldsets('bogus'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' A fieldset that in a group that does not exist should return an empty array.'
		);

		$sets = $form->getFieldsets('details');
		$this->assertThat(
			count($sets),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The details group has one field marked with a fieldset'
		);

		$this->assertThat(
			$sets['params-legacy']->name,
			$this->equalTo('params-legacy'),
			'Line:' . __LINE__ . ' Ensure the fieldset name is correct.'
		);
	}

	/**
	 * Test the Form::load method.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 *
	 * @return void
	 */
	public function testLoad()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			($form->getXML() instanceof \SimpleXMLElement),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The internal XML should be a SimpleXMLElement object.'
		);

		// Test replace false.

		$this->assertThat(
			$form->load(JFormDataHelper::$loadMergeDocument, false),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/field')),
			$this->equalTo(4),
			'Line:' . __LINE__ . ' There are 2 new ungrouped field and one existing field should merge, resulting in 4 total.'
		);

		// Test replace true (default).

		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->load(JFormDataHelper::$loadMergeDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// @todo remove: $this->_showXml($form);die;

		$this->assertThat(
			count($form->findFieldsByGroup(false)),
			$this->equalTo(6),
			'Line:' . __LINE__ . ' There are 2 original ungrouped fields, 1 replaced and 4 new, resulting in 6 total.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('//fields[@name]')),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The XML has 2 fields tags with a name attribute.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('//fields[@name="params"]/field')),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The params fields have been merged ending with 2 elements.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/fields[@name="params"]/field[@name="show_abstract"]')),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' The show_title in the params group has been replaced by show_abstract.'
		);

		$originalform = new JFormInspector('form1');
		$originalform->load(JFormDataHelper::$loadDocument);
		$originalset = $originalform->getXML()->xpath('/form/fields/field');
		$set = $form->getXML()->xpath('/form/fields/field');

		for ($i = 0; $i < count($originalset); $i++)
		{
			$this->assertThat(
				(string) ($originalset[$i]->attributes()->name) == (string) ($set[$i]->attributes()->name),
				$this->isTrue(),
				'Line:' . __LINE__ . ' Replace should leave fields in the original order.'
			);
		}
	}

	/**
	 * Test the Form::load method for cases of unexpected or bad input.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 *
	 * @return void
	 */
	public function testLoad_BadInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(123),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A non-string should return false.'
		);

		$this->assertThat(
			$form->load('junk'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' An invalid string should return false.'
		);

		$this->assertThat(
			$form->getXml(),
			$this->isNull(),
			'Line:' . __LINE__ . ' The internal XML should be false as returned from simplexml_load_string.'
		);

		$this->assertThat(
			$form->load('<notform><test /></notform>'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Invalid root node name from string should still load.'
		);

		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form'),
			'Line:' . __LINE__ . ' The internal XML should still be named "form".'
		);

		// Test for irregular object input.

		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(simplexml_load_string('<notform><test /></notform>')),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Invalid root node name from XML object should still load.'
		);

		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form'),
			'Line:' . __LINE__ . ' The internal XML should still be named "form".'
		);
	}

	/**
	 * Test the Form::load method for XPath data.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 *
	 * @return void
	 */
	public function testLoad_XPath()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadXPathDocument, true, '/extension/fields'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form'),
			'Line:' . __LINE__ . ' The internal XML should still be named "form".'
		);

		// @todo remove: $this->_showXml($form);die;
		$this->assertThat(
			count($form->getXml()->fields->fields),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The test data has 2 fields.'
		);
	}

	/**
	 * Test for Form::loadField method.
	 *
	 * @return void
	 */
	public function testLoadField()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Error handling.

		$this->assertThat(
			$form->loadField('bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' An unknown field should return false.'
		);

		// Test correct usage.

		$field = $form->getField('title');
		$field = $form->loadField($field);
	}

	/**
	 * Test the Form::loadFile method.
	 *
	 * This method loads a file and passes the string to the Form::load method.
	 *
	 * @return void
	 */
	public function testLoadFile()
	{
		$form = new JFormInspector('form1');

		// Test for files that don't exist.

		$this->assertThat(
			$form->loadFile('/tmp/example.xml'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A file path that does not exist should return false.'
		);

		$this->assertThat(
			$form->loadFile('notfound'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' A file name that does not exist should return false.'
		);

		// Testing loading a file by full path.

		$this->assertThat(
			$form->loadFile(__DIR__ . '/example.xml'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML file by full path should load successfully.'
		);

		$this->assertThat(
			($form->getXML() instanceof \SimpleXMLElement),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should parse successfully.'
		);

		// Testing loading a file by file name.

		$form = new JFormInspector('form1');
		FormHelper::addFormPath(__DIR__);

		$this->assertThat(
			$form->loadFile('example'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML file by name should load successfully.'
		);

		$this->assertThat(
			($form->getXML() instanceof \SimpleXMLElement),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should parse successfully.'
		);
	}

	/**
	 * Test the Form::mergeNode method.
	 *
	 * @return void
	 */
	public function testMergeNode()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><field name="foo" /></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><field name="bar" type="text" /></form>');

		if ($xml1 === false || $xml2 === false)
		{
			$this->fail('Line:' . __LINE__ . ' Error in text XML data');
		}

		JFormInspector::mergeNode($xml1->field, $xml2->field);

		$fields = $xml1->xpath('field[@name="foo"] | field[@type="text"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' Existing attribute "name" should merge, new attribute "type" added.'
		);
	}

	/**
	 * Test the Form::mergeNode method.
	 *
	 * @return void
	 */
	public function testMergeNodes()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><fields><field name="foo" /></fields></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><fields><field name="foo" type="text" /><field name="soap" /></fields></form>');

		if ($xml1 === false || $xml2 === false)
		{
			$this->fail('Line:' . __LINE__ . ' Error in text XML data');
		}

		JFormInspector::mergeNodes($xml1->fields, $xml2->fields);

		$this->assertThat(
			count($xml1->xpath('fields/field')),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' The merge should have two field tags, one existing, one new.'
		);

		$this->assertThat(
			count($xml1->xpath('fields/field[@name="foo"] | fields/field[@type="text"]')),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' A field of the same name should merge.'
		);

		$this->assertThat(
			count($xml1->xpath('fields/field[@name="soap"]')),
			$this->equalTo(1),
			'Line:' . __LINE__ . ' A new field should be added.'
		);
	}

	/**
	 * Test for Form::removeField method.
	 *
	 * @return void
	 */
	public function testRemoveField()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->removeField('title'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The removeField method should return true.'
		);

		$this->assertThat(
			$form->findField('title'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The field should be removed.'
		);

		$this->assertThat(
			$form->removeField('show_title', 'params'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The removeField method should return true.'
		);

		$this->assertThat(
			$form->findField('show_title', 'params'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The field should be removed.'
		);
	}

	/**
	 * Test for Form::removeGroup method.
	 *
	 * @return void
	 */
	public function testRemoveGroup()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->removeGroup('params'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The removeGroup method should return true.'
		);

		$this->assertThat(
			$form->findGroup('params'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' The group should be removed, returning an empty array.'
		);
	}

	/**
	 * Test for Form::setField method.
	 *
	 * @return void
	 */
	public function testReset()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$data = array(
			'title' => 'Joomla Framework',
			'params' => array(
				'show_title' => 2
			)
		);

		$this->assertThat(
			$form->bind($data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The data should bind successfully.'
		);

		$this->assertThat(
			$form->getValue('title'),
			$this->equalTo('Joomla Framework'),
			'Line:' . __LINE__ . ' Confirm the field value is set.'
		);

		$this->assertThat(
			$form->getValue('show_title', 'params'),
			$this->equalTo(2),
			'Line:' . __LINE__ . ' Confirm the field value is set.'
		);

		// Test reset on the data only.

		$this->assertThat(
			$form->reset(),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The reset method should return true.'
		);

		$this->assertThat(
			$form->getField('title'),
			$this->logicalNot($this->isFalse()),
			'Line:' . __LINE__ . ' The field should still exist.'
		);

		$this->assertThat(
			$form->getValue('title'),
			$this->equalTo(null),
			'Line:' . __LINE__ . ' The field value should be reset.'
		);

		$this->assertThat(
			$form->getValue('show_title', 'params'),
			$this->equalTo(null),
			'Line:' . __LINE__ . ' The field value should be reset.'
		);

		// Test reset of data and the internal XML.

		$this->assertThat(
			$form->reset(true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The reset method should return true.'
		);

		$this->assertThat(
			$form->getField('title'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' The known field should be removed.'
		);

		$this->assertThat(
			$form->findGroup('params'),
			$this->equalTo(array()),
			'Line:' . __LINE__ . ' The known group should be removed, returning an empty array.'
		);
	}

	/**
	 * Test for Form::setField method.
	 *
	 * @return void
	 */
	public function testSetField()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$xml1 = simplexml_load_string('<form><field name="title" required="true" /></form>');

		if ($xml1 === false)
		{
			$this->fail('Error in text XML data');
		}

		// Test without replace.

		$this->assertThat(
			$form->setField($xml1->field[0], null, false),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setField method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'required', 'default'),
			$this->equalTo('default'),
			'Line:' . __LINE__ . ' The label should contain just the field name.'
		);

		// Test with replace.

		$this->assertThat(
			$form->setField($xml1->field[0], null, true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setField method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'required', 'default'),
			$this->equalTo('true'),
			'Line:' . __LINE__ . ' The label should contain just the new label.'
		);
	}

	/**
	 * Test for Form::setFieldAttribute method.
	 *
	 * @return void
	 */
	public function testSetFieldAttribute()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$this->assertThat(
			$form->setFieldAttribute('title', 'label', 'The Title'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'label'),
			$this->equalTo('The Title'),
			'Line:' . __LINE__ . ' The new value should be set.'
		);

		$this->assertThat(
			$form->setFieldAttribute('show_title', 'label', 'Show Title', 'params'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('show_title', 'label', 'default', 'params'),
			$this->equalTo('Show Title'),
			'Line:' . __LINE__ . ' The new value of the grouped field should be set.'
		);
	}

	/**
	 * Test for Form::setFields method.
	 *
	 * @return void
	 */
	public function testSetFields()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$xml1 = simplexml_load_string('<form><field name="title" required="true" /><field name="ordering" /></form>');

		if ($xml1 === false)
		{
			$this->fail('Error in text XML data');
		}

		// Test without replace.

		$this->assertThat(
			$form->setFields($xml1->field, null, false),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The setFields method should return true.'
		);

		$this->assertThat(
			$form->getFieldAttribute('title', 'required', 'default'),
			$this->equalTo('default'),
			'Line:' . __LINE__ . ' The label should contain just the field name.'
		);

		$this->assertThat(
			$form->getField('ordering'),
			$this->logicalNot($this->isFalse()),
			'Line:' . __LINE__ . ' The label should contain just the field name.'
		);
	}

	/**
	 * Test for Form::setValue method.
	 *
	 * @return void
	 */
	public function testSetValue()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		// Test error handling.

		$this->assertThat(
			$form->setValue('bogus', null, 'Unknown'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' An unknown field cannot have its value set.'
		);

		// Test regular usage.

		$this->assertThat(
			$form->setValue('title', null, 'The Title'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Should return true for a known field.'
		);

		$this->assertThat(
			$form->getValue('title', null, 'default'),
			$this->equalTo('The Title'),
			'Line:' . __LINE__ . ' The new value should return.'
		);

		$this->assertThat(
			$form->setValue('show_title', 'params', '3'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Should return true for a known field.'
		);

		$this->assertThat(
			$form->getValue('show_title', 'params', 'default'),
			$this->equalTo('3'),
			'Line:' . __LINE__ . ' The new value should return.'
		);
	}

	/**
	 * Test for Form::syncPaths method.
	 *
	 * @return void
	 */
	public function testSyncPaths()
	{
		$form = new JFormInspector('testSyncPaths');

		$this->assertThat(
			$form->load(JFormDataHelper::$syncPathsDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$fieldPaths = FormHelper::addFieldPath();
		$formPaths = FormHelper::addFormPath();
		$rulePaths = FormHelper::addRulePath();

		$this->assertThat(
			in_array(JPATH_ROOT . '/field1', $fieldPaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The field path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/field2', $fieldPaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The field path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/field3', $fieldPaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The field path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/form1', $formPaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The form path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/form2', $formPaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The form path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/form3', $formPaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The form path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/rule1', $rulePaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/rule2', $rulePaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule path from the XML file should be present.'
		);

		$this->assertThat(
			in_array(JPATH_ROOT . '/rule3', $rulePaths),
			$this->isTrue(),
			'Line:' . __LINE__ . ' The rule path from the XML file should be present.'
		);
	}

	/**
	 * Test for Form::validate method.
	 *
	 * @return void
	 */
	public function testValidate()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$validateDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$pass = array(
			'boolean' => 'false',
			'optional' => 'Optional',
			'required' => 'Supplied',
			'group' => array(
				'level1' => 'open'
			)
		);

		$fail = array(
			'boolean' => 'comply',
			'required' => '',
		);

		// Test error conditions.

		$this->assertThat(
			$form->validate($pass, 'bogus'),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Validating an unknown group should return false.'
		);

		$this->assertThat(
			$form->validate($fail),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Any validation failures should return false.'
		);

		// Test expected behaviour.

		$this->assertThat(
			$form->validate($pass),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Validation on this data should pass.'
		);

		$this->assertThat(
			$form->validate($pass, 'group'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Validating an unknown group should return false.'
		);
	}

	/**
	 * Test for Form::validateField method.
	 *
	 * return   void
	 *
	 * @covers  Joomla\Form\Form::validateField
	 * @since   1.0
	 *
	 * @return void
	 */
	public function testValidateField()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$validateFieldDocument),
			$this->isTrue(),
			'Line:' . __LINE__ . ' XML string should load successfully.'
		);

		$xml = $form->getXML();

		// Test error handling.

		$field = array_pop($xml->xpath('fields/field[@name="boolean"]'));
		$result = $form->validateField($field);
		$this->assertThat(
			$result instanceof \UnexpectedValueException,
			$this->isTrue(),
			'Line:' . __LINE__ . ' A failed validation should return an exception.'
		);

		$field = array_pop($xml->xpath('fields/field[@name="required"]'));
		$result = $form->validateField($field);
		$this->assertThat(
			$result instanceof \RuntimeException,
			$this->isTrue(),
			'Line:' . __LINE__ . ' A required field missing a value should return an exception.'
		);

		// Test general usage.

		$field = array_pop($xml->xpath('fields/field[@name="boolean"]'));
		$this->assertThat(
			$form->validateField($field, null, 'true'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' A field with a passing validate attribute set should return true.'
		);

		$field = array_pop($xml->xpath('fields/field[@name="optional"]'));
		$this->assertThat(
			$form->validateField($field),
			$this->isTrue(),
			'Line:' . __LINE__ . ' A field without required set should return true.'
		);

		$field = array_pop($xml->xpath('fields/field[@name="required"]'));
		$this->assertThat(
			$form->validateField($field, null, 'value'),
			$this->isTrue(),
			'Line:' . __LINE__ . ' A required field with a value should return true.'
		);
	}

	/**
	 * Test for Form::validateField method for missing rule exception.
	 *
	 * return   void
	 *
	 * @covers  Joomla\Form\Form::validateField
	 * @since   1.0
	 *
	 * @expectedException  \UnexpectedValueException
	 *
	 * @return void
	 */
	public function testValidateField_missingRule()
	{
		$form = new JFormInspector('form1');
		$form->load(JFormDataHelper::$validateFieldDocument);
		$xml = $form->getXML();

		$field = array_pop($xml->xpath('fields/field[@name="missingrule"]'));
		$result = $form->validateField($field, null, 'value');
	}
}
