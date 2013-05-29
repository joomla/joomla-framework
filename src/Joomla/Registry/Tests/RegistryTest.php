<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;
use Joomla\Registry\RegistryFactory;
use Joomla\Test\TestHelper;

/**
 * Test class for Registry.
 *
 * @since  1.0
 */
class RegistryTest extends PHPUnit_Framework_TestCase
{
	/*
	 * @var  Joomla\Registry\Registry
	 */
	protected $object;

	public function setUp()
	{
		$this->object = RegistryFactory::getRegistry();
	}

	/**
	 * Test the Joomla\Registry\Registry::__clone method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::__clone
	 * @since   1.0
	 */
	public function test__clone()
	{
		$this->object->loadArray(array('a' => '123', 'b' => '456'));
		$this->object->set('foo', 'bar');
		$b = clone $this->object;

		$this->assertThat(
			serialize($this->object),
			$this->equalTo(serialize($b))
		);

		$this->assertThat(
			$this->object,
			$this->logicalNot($this->identicalTo($b)),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::__toString method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::__toString
	 * @since   1.0
	 */
	public function test__toString()
	{
		$this->object->set('foo', 'bar');

		// __toString only allows for a JSON value.
		$this->assertThat(
			(string) $this->object,
			$this->equalTo('{"foo":"bar"}'),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::jsonSerialize method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::jsonSerialize
	 * @since   1.0
	 */
	public function testJsonSerialize()
	{
		if (version_compare(PHP_VERSION, '5.4.0', '<'))
		{
			$this->markTestSkipped('This test requires PHP 5.4 or newer.');
		}

		$this->object->set('foo', 'bar');

		// __toString only allows for a JSON value.
		$this->assertThat(
			json_encode($this->object),
			$this->equalTo('{"foo":"bar"}'),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Tests serializing Registry objects.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSerialize()
	{
		$this->object->set('foo', 'bar');

		$serialized = serialize($this->object);
		$b = unserialize($serialized);

		// __toString only allows for a JSON value.
		$this->assertThat(
			$b,
			$this->equalTo($this->object),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::def method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::def
	 * @since   1.0
	 */
	public function testDef()
	{
		$this->assertThat(
			$this->object->def('foo', 'bar'),
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . '. def should return default value'
		);

		$this->assertThat(
			$this->object->get('foo'),
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . '. default should now be the current value'
		);
	}

	/**
	 * Tet the Joomla\Registry\Registry::bindData method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::bindData
	 * @since   1.0
	 */
	public function testBindData()
	{
		$a = $this->object;
		$parent = new stdClass;

		TestHelper::invoke($a, 'bindData', $parent, 'foo');
		$this->assertThat(
			$parent->{0},
			$this->equalTo('foo'),
			'Line: ' . __LINE__ . ' The input value should exist in the parent object.'
		);

		TestHelper::invoke($a, 'bindData', $parent, array('foo' => 'bar'));
		$this->assertThat(
			$parent->{'foo'},
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . ' The input value should exist in the parent object.'
		);

		TestHelper::invoke($a, 'bindData', $parent, array('level1' => array('level2' => 'value2')));
		$this->assertThat(
			$parent->{'level1'}->{'level2'},
			$this->equalTo('value2'),
			'Line: ' . __LINE__ . ' The input value should exist in the parent object.'
		);

		TestHelper::invoke($a, 'bindData', $parent, array('intarray' => array(0, 1, 2)));
		$this->assertThat(
			$parent->{'intarray'},
			$this->equalTo(array(0, 1, 2)),
			'Line: ' . __LINE__ . ' The un-associative array should bind natively.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::exists method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::exists
	 * @since   1.0
	 */
	public function testExists()
	{
		$a = $this->object;
		$a->set('foo', 'bar1');
		$a->set('config.foo', 'bar2');
		$a->set('deep.level.foo', 'bar3');

		$this->assertThat(
			$a->exists('foo'),
			$this->isTrue(),
			'Line: ' . __LINE__ . ' The path should exist, returning true.'
		);

		$this->assertThat(
			$a->exists('config.foo'),
			$this->isTrue(),
			'Line: ' . __LINE__ . ' The path should exist, returning true.'
		);

		$this->assertThat(
			$a->exists('deep.level.foo'),
			$this->isTrue(),
			'Line: ' . __LINE__ . ' The path should exist, returning true.'
		);

		$this->assertThat(
			$a->exists('deep.level.bar'),
			$this->isFalse(),
			'Line: ' . __LINE__ . ' The path should not exist, returning false.'
		);

		$this->assertThat(
			$a->exists('bar.foo'),
			$this->isFalse(),
			'Line: ' . __LINE__ . ' The path should not exist, returning false.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::get method
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->object->set('foo', 'bar');
		$this->assertEquals('bar', $this->object->get('foo'), 'Line: ' . __LINE__ . ' get method should work.');
		$this->assertNull($this->object->get('xxx.yyy'), 'Line: ' . __LINE__ . ' get should return null when not found.');
	}

	/**
	 * Test the Joomla\Registry\Registry::loadArray method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::loadArray
	 * @since   1.0
	 */
	public function testLoadArray()
	{
		$array = array(
			'foo' => 'bar'
		);
		$result = $this->object->loadArray($array);

		// Result is always true, no error checking in method.

		// Test getting a known value.
		$this->assertThat(
			$this->object->get('foo'),
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::loadFile method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::loadFile
	 * @since   1.0
	 */
	public function testLoadFile()
	{
		$registry = $this->object;

		// Result is always true, no error checking in method.

		// JSON.
		$result = $registry->loadFile(__DIR__ . '/Stubs/jregistry.json');

		// Test getting a known value.
		$this->assertThat(
			$registry->get('foo'),
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . '.'
		);

		// INI.
		$result = $registry->loadFile(__DIR__ . '/Stubs/jregistry.ini', 'ini');

		// Test getting a known value.
		$this->assertThat(
			$registry->get('foo'),
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . '.'
		);

		// INI + section.
		$result = $registry->loadFile(__DIR__ . '/Stubs/jregistry.ini', 'ini', array('processSections' => true));

		// Test getting a known value.
		$this->assertThat(
			$registry->get('section.foo'),
			$this->equalTo('bar'),
			'Line: ' . __LINE__ . '.'
		);

		// XML and PHP versions do not support stringToObject.
	}

	/**
	 * Test the Joomla\Registry\Registry::loadString() method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::loadString
	 * @since   1.0
	 */
	public function testLoadString()
	{
		$registry = $this->object;
		$result = $registry->loadString('foo="testloadini1"', 'INI');

		// Test getting a known value.
		$this->assertThat(
			$registry->get('foo'),
			$this->equalTo('testloadini1'),
			'Line: ' . __LINE__ . '.'
		);

		$result = $registry->loadString("[section]\nfoo=\"testloadini2\"", 'INI');

		// Test getting a known value.
		$this->assertThat(
			$registry->get('foo'),
			$this->equalTo('testloadini2'),
			'Line: ' . __LINE__ . '.'
		);

		$result = $registry->loadString("[section]\nfoo=\"testloadini3\"", 'INI', array('processSections' => true));

		// Test getting a known value after processing sections.
		$this->assertThat(
			$registry->get('section.foo'),
			$this->equalTo('testloadini3'),
			'Line: ' . __LINE__ . '.'
		);

		$string = '{"foo":"testloadjson"}';

		$registry = RegistryFactory::getRegistry();
		$result = $registry->loadString($string);

		// Result is always true, no error checking in method.

		// Test getting a known value.
		$this->assertThat(
			$registry->get('foo'),
			$this->equalTo('testloadjson'),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::loadObject method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::loadObject
	 * @since   1.0
	 */
	public function testLoadObject()
	{
		$object = new stdClass;
		$object->foo = 'testloadobject';

		$registry = $this->object;
		$result = $registry->loadObject($object);

		// Result is always true, no error checking in method.

		// Test getting a known value.
		$this->assertThat(
			$registry->get('foo'),
			$this->equalTo('testloadobject'),
			'Line: ' . __LINE__ . '.'
		);

		// Test case from Tracker Issue 22444
		$registry = RegistryFactory::getRegistry();
		$object = new stdClass;
		$object2 = new stdClass;
		$object2->test = 'testcase';
		$object->test = $object2;
		$registry->loadObject($object);
		$this->assertEquals(
			'{"test":{"test":"testcase"}}',
			(string)$registry,
			'Line: ' . __LINE__ . '. Should load object successfully');
	}

	/**
	 * Test the Joomla\Registry\Registry::merge method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::merge
	 * @since   1.0
	 */
	public function testMerge()
	{
		$array1 = array(
			'foo' => 'bar',
			'hoo' => 'hum',
			'dum' => array(
				'dee' => 'dum'
			)
		);

		$array2 = array(
			'foo' => 'soap',
			'dum' => 'huh'
		);
		$registry1 = RegistryFactory::getRegistry();
		$registry1->loadArray($array1);

		$registry2 = RegistryFactory::getRegistry();
		$registry2->loadArray($array2);

		$registry1->merge($registry2);

		// Test getting a known value.
		$this->assertThat(
			$registry1->get('foo'),
			$this->equalTo('soap'),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$registry1->get('dum'),
			$this->equalTo('huh'),
			'Line: ' . __LINE__ . '.'
		);

		// Test merge with zero and blank value
		$json1 = '{"param1":1, "param2":"value2"}';
		$json2 = '{"param1":2, "param2":"", "param3":0, "param4":-1, "param5":1}';

		$a = RegistryFactory::getRegistry();
		$a->loadString($json1);
		$b = RegistryFactory::getRegistry();
		$b->loadString($json2, 'JSON');
		$a->merge($b);

		// New param with zero value should show in merged registry
		$this->assertEquals(2, $a->get('param1'), '$b value should override $a value');
		$this->assertEquals('value2', $a->get('param2'), '$a value should override blank $b value');
		$this->assertEquals(0, $a->get('param3'), '$b value of 0 should override $a value');
		$this->assertEquals(-1, $a->get('param4'), '$b value of -1 should override $a value');
		$this->assertEquals(1, $a->get('param5'), '$b value of 1 should override $a value');
	}

	/**
	 * Test the Joomla\Registry\Registry::offsetExists method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::offsetExists
	 * @since   1.0
	 */
	public function testOffsetExists()
	{
		$this->assertTrue(empty($this->object['foo.bar']));

		$this->object->set('foo.bar', 'value');

		$this->assertTrue(isset($this->object['foo.bar']), 'Checks a known offset by isset.');
		$this->assertFalse(isset($this->object['goo.car']), 'Checks an uknown offset.');
	}

	/**
	 * Test the Joomla\Registry\Registry::offsetGet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::offsetGet
	 * @since   1.0
	 */
	public function testOffsetGet()
	{
		$this->object->set('foo.bar', 'value');

		$this->assertEquals('value', $this->object['foo.bar'], 'Checks a known offset.');
		$this->assertNull($this->object['goo.car'], 'Checks a unknown offset.');
	}

	/**
	 * Test the Joomla\Registry\Registry::offsetSet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::offsetSet
	 * @since   1.0
	 */
	public function testOffsetSet()
	{
		$this->object['foo.bar'] = 'value';
		$this->assertEquals('value', $this->object->get('foo.bar'), 'Checks the set.');
	}

	/**
	 * Test the Joomla\Registry\Registry::offsetUnset method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::offsetUnset
	 * @since   1.0
	 */
	public function testOffsetUnset()
	{
		$this->object->set('foo.bar', 'value');

		unset($this->object['foo.bar']);
		$this->assertFalse(isset($this->object['foo.bar']));
	}

	/**
	 * Test the Joomla\Registry\Registry::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->object->set('foo', 'testsetvalue1');

		$this->assertThat(
			$this->object->set('foo', 'testsetvalue2'),
			$this->equalTo('testsetvalue2'),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::toArray method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::toArray
	 * @since   1.0
	 */
	public function testToArray()
	{
		$a = $this->object;
		$a->set('foo1', 'testtoarray1');
		$a->set('foo2', 'testtoarray2');
		$a->set('config.foo3', 'testtoarray3');

		$expected = array(
			'foo1' => 'testtoarray1',
			'foo2' => 'testtoarray2',
			'config' => array('foo3' => 'testtoarray3')
		);

		$this->assertThat(
			$a->toArray(),
			$this->equalTo($expected),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::toObject method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::toObject
	 * @since   1.0
	 */
	public function testToObject()
	{
		$a = $this->object;
		$a->set('foo1', 'testtoobject1');
		$a->set('foo2', 'testtoobject2');
		$a->set('config.foo3', 'testtoobject3');

		$expected = new stdClass;
		$expected->foo1 = 'testtoobject1';
		$expected->foo2 = 'testtoobject2';
		$expected->config = new StdClass;
		$expected->config->foo3 = 'testtoobject3';

		$this->assertThat(
			$a->toObject(),
			$this->equalTo($expected),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Registry::toString method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Registry::toString
	 * @since   1.0
	 */
	public function testToString()
	{
		$a = $this->object;
		$a->set('foo1', 'testtostring1');
		$a->set('foo2', 'testtostring2');
		$a->set('config.foo3', 'testtostring3');

		$this->assertThat(
			trim($a->toString('JSON')),
			$this->equalTo(
				'{"foo1":"testtostring1","foo2":"testtostring2","config":{"foo3":"testtostring3"}}'
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			trim($a->toString('INI')),
			$this->equalTo(
				"foo1=\"testtostring1\"\nfoo2=\"testtostring2\"\n\n[config]\nfoo3=\"testtostring3\""
			),
			'Line: ' . __LINE__ . '.'
		);
	}
}
