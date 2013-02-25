<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Data
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/stubs/buran.php';
require_once __DIR__ . '/stubs/vostok.php';

/**
 * Tests for the JContentHelperTest class.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Data
 * @since       12.3
 */
class JDataSetTest extends PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the object to test.
	 *
	 * @var    JDataSet
	 * @since  12.3
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::__construct
	 * @since   12.3
	 */
	public function test__construct()
	{
		$this->assertEmpty(TestReflection::getValue(new JDataSet, 'objects'), 'New list should have no objects.');

		$input = array(
			'key' => new JData(array('foo' => 'bar'))
		);
		$new = new JDataSet($input);

		$this->assertEquals($input, TestReflection::getValue($new, 'objects'), 'Check initialised object list.');
	}

	/**
	 * Tests the __construct method with an array that does not contain JData objects.
	 *
	 * @return  void
	 *
	 * @covers             JDataSet::__construct
	 * @expectedException  InvalidArgumentException
	 * @since              12.3
	 */
	public function test__construct_array()
	{
		new JDataSet(array('foo'));
	}

	/**
	 * Tests the __construct method with scalar input.
	 *
	 * @return  void
	 *
	 * @covers             JDataSet::__construct
	 * @expectedException  PHPUnit_Framework_Error
	 * @since              12.3
	 */
	public function test__construct_scalar()
	{
		new JDataSet('foo');
	}

	/**
	 * Tests the __call method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::__call
	 * @since   12.3
	 */
	public function test__call()
	{
		$this->assertThat(
			$this->instance->launch('go'),
			$this->equalTo(array(1 => 'go'))
		);
	}

	/**
	 * Tests the __get method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::__get
	 * @since   12.3
	 */
	public function test__get()
	{
		$this->assertThat(
			$this->instance->pilot,
			$this->equalTo(array(0 => null, 1 => 'Yuri Gagarin'))
		);
	}

	/**
	 * Tests the __isset method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::__isset
	 * @since   12.3
	 */
	public function test__isset()
	{
		$this->assertTrue(isset($this->instance->pilot), 'Property exists.');

		$this->assertFalse(isset($this->instance->duration), 'Unknown property');
	}

	/**
	 * Tests the __set method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::__set
	 * @since   12.3
	 */
	public function test__set()
	{
		$this->instance->successful = 'yes';

		$this->assertThat(
			$this->instance->successful,
			$this->equalTo(array(0 => 'yes', 1 => 'YES'))
		);
	}

	/**
	 * Tests the __unset method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::__unset
	 * @since   12.3
	 */
	public function test__unset()
	{
		unset($this->instance->pilot);

		$this->assertNull($this->instance[1]->pilot);
	}

	/**
	 * Tests the count method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::count
	 * @since   12.3
	 */
	public function testCount()
	{
		$this->assertCount(2, $this->instance);
	}

	/**
	 * Tests the clear method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::clear
	 * @since   12.3
	 */
	public function testClear()
	{
		$this->assertGreaterThan(0, count($this->instance), 'Check there are objects set.');
		$this->instance->clear();
		$this->assertCount(0, $this->instance, 'Check the objects were cleared.');
	}

	/**
	 * Tests the current method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::current
	 * @since   12.3
	 */
	public function testCurrent()
	{
		$object = $this->instance[0];

		$this->assertThat(
			$this->instance->current(),
			$this->equalTo($object)
		);

		$new = new JDataSet(array('foo' => new JData));

		$this->assertThat(
			$new->current(),
			$this->equalTo(new JData)
		);
	}

	/**
	 * Tests the dump method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::dump
	 * @since   12.3
	 */
	public function testDump()
	{
		$this->assertEquals(
			array(
				new stdClass,
				(object) array(
					'mission' => 'Vostok 1',
					'pilot' => 'Yuri Gagarin',
				),
			),
			$this->instance->dump()
		);
	}

	/**
	 * Tests the jsonSerialize method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::jsonSerialize
	 * @since   12.3
	 */
	public function testJsonSerialize()
	{
		$this->assertEquals(
			array(
				new stdClass,
				(object) array(
					'mission' => 'Vostok 1',
					'pilot' => 'Yuri Gagarin',
				),
			),
			$this->instance->jsonSerialize()
		);
	}

	/**
	 * Tests the key method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::key
	 * @since   12.3
	 */
	public function testKey()
	{
		$this->assertEquals(0, $this->instance->key());
	}

	/**
	 * Tests the keys method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::keys
	 * @since   12.3
	 */
	public function testKeys()
	{
		$instance = new JDataSet;
		$instance['key1'] = new JData;
		$instance['key2'] = new JData;

		$this->assertEquals(array('key1', 'key2'), $instance->keys());
	}

	/**
	 * Tests the next method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::next
	 * @since   12.3
	 */
	public function testNext()
	{
		$this->instance->next();
		$this->assertThat(
			TestReflection::getValue($this->instance, 'current'),
			$this->equalTo(1)
		);

		$this->instance->next();
		$this->assertThat(
			TestReflection::getValue($this->instance, 'current'),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the offsetExists method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::offsetExists
	 * @since   12.3
	 */
	public function testOffsetExists()
	{
		$this->assertTrue($this->instance->offsetExists(0));
		$this->assertFalse($this->instance->offsetExists(2));
		$this->assertFalse($this->instance->offsetExists('foo'));
	}

	/**
	 * Tests the offsetGet method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::offsetGet
	 * @since   12.3
	 */
	public function testOffsetGet()
	{
		$this->assertInstanceOf('JDataBuran', $this->instance->offsetGet(0));
		$this->assertInstanceOf('JDataVostok', $this->instance->offsetGet(1));
		$this->assertNull($this->instance->offsetGet('foo'));
	}

	/**
	 * Tests the offsetSet method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::OffsetSet
	 * @since   12.3
	 */
	public function testOffsetSet()
	{
		$this->instance->offsetSet(0, new JData);
		$objects = TestReflection::getValue($this->instance, 'objects');

		$this->assertEquals(new JData, $objects[0], 'Checks explicit use of offsetSet.');

		$this->instance[] = new JData;
		$this->assertInstanceOf('JData', $this->instance[1], 'Checks the array push equivalent with [].');

		$this->instance['foo'] = new JData;
		$this->assertInstanceOf('JData', $this->instance['foo'], 'Checks implicit usage of offsetSet.');
	}

	/**
	 * Tests the offsetSet method for an expected exception
	 *
	 * @return  void
	 *
	 * @covers             JDataSet::OffsetSet
	 * @expectedException  InvalidArgumentException
	 * @since              12.3
	 */
	public function testOffsetSet_exception1()
	{
		// By implication, this will call offsetSet.
		$this->instance['foo'] = 'bar';
	}

	/**
	 * Tests the offsetUnset method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::OffsetUnset
	 * @since   12.3
	 */
	public function testOffsetUnset()
	{
		$this->instance->offsetUnset(0);
		$objects = TestReflection::getValue($this->instance, 'objects');

		$this->assertFalse(isset($objects[0]));
	}

	/**
	 * Tests the offsetRewind method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::rewind
	 * @since   12.3
	 */
	public function testOffsetRewind()
	{
		TestReflection::setValue($this->instance, 'current', 'foo');

		$this->instance->rewind();
		$this->assertEquals(0, $this->instance->key());

		$this->instance->clear();
		$this->assertFalse($this->instance->key());
	}

	/**
	 * Tests the valid method.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::valid
	 * @since   12.3
	 */
	public function testValid()
	{
		$this->assertTrue($this->instance->valid());

		TestReflection::setValue($this->instance, 'current', null);

		$this->assertFalse($this->instance->valid());
	}

	/**
	 * Test that JDataSet::_initialise method indirectly.
	 *
	 * @return  void
	 *
	 * @covers  JDataSet::_initialise
	 * @since   12.3
	 */
	public function test_initialise()
	{
		$this->assertInstanceOf('JDataBuran', $this->instance[0]);
		$this->assertInstanceOf('JDataVostok', $this->instance[1]);
	}

	/*
	 * Ancillary tests.
	 */

	/**
	 * Tests using JDataSet in a foreach statement.
	 *
	 * @return  void
	 *
	 * @coversNothing  Integration test.
	 * @since          12.3
	 */
	public function test_foreach()
	{
		// Test multi-item list.
		$tests = array();

		foreach ($this->instance as $key => $object)
		{
			$tests[] = $object->mission;
		}

		$this->assertEquals(array(null, 'Vostok 1'), $tests);

		// Tests single item list.
		$this->instance->clear();
		$this->instance['1'] = new JData;
		$runs = 0;

		foreach ($this->instance as $key => $object)
		{
			$runs++;
		}

		$this->assertEquals(1, $runs);

		// Exhaustively testing unsetting within a foreach.
		$this->instance['2'] = new JData;
		$this->instance['3'] = new JData;
		$this->instance['4'] = new JData;
		$this->instance['5'] = new JData;

		$runs = 0;

		foreach ($this->instance as $k => $v)
		{
			$runs++;

			if ($k != 3)
			{
				unset($this->instance[$k]);
			}
		}

		$this->assertFalse($this->instance->offsetExists(1), 'Index 1 should have been unset.');
		$this->assertFalse($this->instance->offsetExists(2), 'Index 2 should have been unset.');
		$this->assertTrue($this->instance->offsetExists(3), 'Index 3 should be set.');
		$this->assertFalse($this->instance->offsetExists(4), 'Index 4 should have been unset.');
		$this->assertFalse($this->instance->offsetExists(5), 'Index 5 should have been unset.');
		$this->assertCount(1, $this->instance);
		$this->assertEquals(5, $runs, 'Oops, the foreach ran too many times.');
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since  12.3
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new JDataSet(
			array(
				new JDataBuran,
				new JDataVostok(array('mission' => 'Vostok 1', 'pilot' => 'Yuri Gagarin')),
			)
		);
	}
}
