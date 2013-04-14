<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Database\Query\QueryElement;

/**
 * Test class for JDatabaseQueryElement.
 *
 * @since  1.0
 */
class DatabaseQueryElementTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test cases for append and __toString
	 *
	 * Each test case provides
	 * - array    element    the base element for the test, given as hash
	 *                 name => element_name,
	 *                 elements => element array,
	 *                 glue => glue
	 * - array    appendElement    the element to be appended (same format as above)
	 * - array     expected    array of elements that should be the value of the elements attribute after the merge
	 * - string    expected value of __toString() for element after append
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function dataTestAppend()
	{
		return array(
			'array-element' => array(
				array(
					'name' => 'SELECT',
					'elements' => array(),
					'glue' => ','
				),
				array(
					'name' => 'FROM',
					'elements' => array('my_table_name'),
					'glue' => ','
				),
				array(
					'name' => 'FROM',
					'elements' => array('my_table_name'),
					'glue' => ','
				),
				PHP_EOL . 'SELECT ' . PHP_EOL . 'FROM my_table_name',
			),
			'non-array-element' => array(
				array(
					'name' => 'SELECT',
					'elements' => array(),
					'glue' => ','
				),
				array(
					'name' => 'FROM',
					'elements' => array('my_table_name'),
					'glue' => ','
				),
				array(
					'name' => 'FROM',
					'elements' => array('my_table_name'),
					'glue' => ','
				),
				PHP_EOL . 'SELECT ' . PHP_EOL . 'FROM my_table_name',
			)
		);
	}

	/**
	 * Test cases for constructor
	 *
	 * Each test case provides
	 * - array    element    the base element for the test, given as hash
	 *                 name => element_name,
	 *                 elements => array or string
	 *                 glue => glue
	 * - array    expected values in same hash format
	 *
	 * @return array
	 */
	public function dataTestConstruct()
	{
		return array(
			'array-element' => array(
				array(
					'name' => 'FROM',
					'elements' => array('field1', 'field2'),
					'glue' => ','
				),
				array(
					'name' => 'FROM',
					'elements' => array('field1', 'field2'),
					'glue' => ','
				)
			),
			'non-array-element' => array(
				array(
					'name' => 'TABLE',
					'elements' => 'my_table_name',
					'glue' => ','
				),
				array(
					'name' => 'TABLE',
					'elements' => array('my_table_name'),
					'glue' => ','
				)
			)
		);
	}

	/**
	 * Test data for test__toString.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function dataTestToString()
	{
		return array(
			// @todo name, elements, glue, expected.
			array(
				'FROM',
				'table1',
				',',
				PHP_EOL . "FROM table1"
			),
			array(
				'SELECT',
				array('column1', 'column2'),
				',',
				PHP_EOL . "SELECT column1,column2"
			),
			array(
				'()',
				array('column1', 'column2'),
				',',
				PHP_EOL . "(column1,column2)"
			),
			array(
				'CONCAT()',
				array('column1', 'column2'),
				',',
				PHP_EOL . "CONCAT(column1,column2)"
			),
		);
	}

	/**
	 * Test the class constructor.
	 *
	 * @param   array  $element   values for base element
	 * @param   array  $expected  values for expected fields
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider  dataTestConstruct
	 */
	public function test__Construct($element, $expected)
	{
		$baseElement = new QueryElement($element['name'], $element['elements'], $element['glue']);

		$this->assertAttributeEquals(
			$expected['name'], 'name', $baseElement, 'Line ' . __LINE__ . ' name should be set'
		);

		$this->assertAttributeEquals(
			$expected['elements'], 'elements', $baseElement, 'Line ' . __LINE__ . ' elements should be set'
		);

		$this->assertAttributeEquals(
			$expected['glue'], 'glue', $baseElement, 'Line ' . __LINE__ . ' glue should be set'
		);
	}

	/**
	 * Test the __toString magic method.
	 *
	 * @param   string  $name      The name of the element.
	 * @param   mixed   $elements  String or array.
	 * @param   string  $glue      The glue for elements.
	 * @param   string  $expected  The expected value.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider  dataTestToString
	 */
	public function test__toString($name, $elements, $glue, $expected)
	{
		$e = new QueryElement($name, $elements, $glue);

		$this->assertThat(
			(string) $e,
			$this->equalTo($expected)
		);
	}

	/**
	 * Test the append method.
	 *
	 * @param   array   $element   base element values
	 * @param   array   $append    append element values
	 * @param   array   $expected  expected element values for elements field after append
	 * @param   string  $string    expected value of toString (not used in this test)
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider dataTestAppend
	 */

	public function testAppend($element, $append, $expected, $string)
	{
		$baseElement = new QueryElement($element['name'], $element['elements'], $element['glue']);
		$appendElement = new QueryElement($append['name'], $append['elements'], $append['glue']);
		$expectedElement = new QueryElement($expected['name'], $expected['elements'], $expected['glue']);
		$baseElement->append($appendElement);
		$this->assertAttributeEquals(array($expectedElement), 'elements', $baseElement);
	}

	/**
	 * Tests the JDatabaseQueryElement::__clone method properly clones an array.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__clone_array()
	{
		$baseElement = new QueryElement($name = null, $elements = null);

		$baseElement->testArray = array();

		$cloneElement = clone($baseElement);

		$baseElement->testArray[] = 'a';

		$this->assertFalse($baseElement === $cloneElement);
		$this->assertEquals(count($cloneElement->testArray), 0);
	}

	/**
	 * Tests the JDatabaseQueryElement::__clone method properly clones an object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__clone_object()
	{
		$baseElement = new QueryElement($name = null, $elements = null);

		$baseElement->testObject = new \stdClass;

		$cloneElement = clone($baseElement);

		$this->assertFalse($baseElement === $cloneElement);
		$this->assertFalse($baseElement->testObject === $cloneElement->testObject);
	}
}
