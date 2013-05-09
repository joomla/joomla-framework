<?php
/**
 * @copyright  Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\DI\Tests;

use Joomla\DI\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
	/**
 	 * Holds the Container instance for testing.
	 *
	 * @var  Joomla\DI\Container
	 */
	protected $fixture;

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function setUp()
	{
		$this->fixture = new Container;
	}

	/**
	 * Tear down the tests.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function tearDown()
	{
		$this->fixture = null;
	}

	/**
	 * Tests the constructor.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testConstructor()
	{
		$this->assertAttributeEquals(array('default.shared' => true), 'config', $this->fixture);
	}

	/**
	 * Tests the set method as default shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testSetShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertTrue($dataStore['foo']['shared']);
	}

	/**
	 * Tests the set method not shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testSetNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertFalse($dataStore['foo']['shared']);
	}

	/**
	 * Tests the get method shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testGetShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$this->assertSame($this->fixture->get('foo'), $this->fixture->get('foo'));
	}

	/**
	 * Tests the get method not shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testGetNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$this->assertNotSame($this->fixture->get('foo'), $this->fixture->get('foo'));
	}

	/**
	 * Tests the setConfig method.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testSetConfig()
	{
		$this->fixture->setConfig(array('foo' => 'bar'));

		$this->assertAttributeEquals(array('default.shared' => true, 'foo' => 'bar'), 'config', $this->fixture);
	}

	/**
	 * Tests the getConfig method.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testGetConfig()
	{
		$this->assertSame($this->readAttribute($this->fixture, 'config'), array('default.shared' => true));
	}

	/**
	 * Tests the setParam method.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testSetParam()
	{
		$this->fixture->setParam('foo', 'bar');

		$this->assertAttributeEquals(array('default.shared' => true, 'foo' => 'bar'), 'config', $this->fixture);
	}

	/**
	 * Tests the getParam method.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testGetParam()
	{
		$this->assertSame($this->fixture->getParam('default.shared'), true);
	}

	/**
	 * Tests the offsetExists method true.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetExistsTrue()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$this->assertTrue(isset($this->fixture['foo']));
	}

	/**
	 * Tests the offsetExists method false.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetExistsFalse()
	{
		$this->assertFalse(isset($this->fixture['foo']));
	}

	/**
	 * Tests the offsetGet method shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetGetExistsShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$this->assertInstanceOf('stdClass', $this->fixture['foo']);
	}

	/**
	 * Tests the offsetGet method not shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetGetExistsNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$this->assertNotSame($this->fixture['foo'], $this->fixture['foo']);
	}

	/**
	 * Tests the offsetGet method on a non-existant offset.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 *
	 * @expectedException  \InvalidArgumentException
	 */
	public function testOffsetGetNotExists()
	{
		$foo = $this->fixture['foo'];
	}

	/**
	 * Tests the offsetSet method shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetSetShared()
	{
		$this->fixture['foo'] = function () { return new \stdClass; };

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertTrue($dataStore['foo']['shared']);
	}

	/**
	 * Tests the offsetSet method not shared.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetSetNotShared()
	{
		$this->fixture->setParam('default.shared', false);

		$this->fixture['foo'] = function () { return new \stdClass; };

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertFalse($dataStore['foo']['shared']);
	}

	/**
	 * Tests the offsetSet method.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testOffsetUnset()
	{
		$this->fixture['foo'] = function () { return new \stdClass; };

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertTrue(array_key_exists('foo', $dataStore));

		unset($this->fixture['foo']);

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertFalse(array_key_exists('foo', $dataStore));
	}
}
