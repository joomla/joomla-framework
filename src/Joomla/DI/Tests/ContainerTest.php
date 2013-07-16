<?php
/**
 * @copyright  Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\DI\Tests;

use Joomla\DI\Container;

interface StubInterface {}

class Stub1 implements StubInterface {}

class Stub2 implements StubInterface
{
	protected $stub;

	public function __construct(StubInterface $stub)
	{
		$this->stub = $stub;
	}
}

class Stub3
{
	protected $stub;
	protected $stub2;

	public function __construct(StubInterface $stub, StubInterface $stub2)
	{
		$this->stub = $stub;
		$this->stub2 = $stub2;
	}
}

class Stub4 implements StubInterface {}

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
	 * @since   1.0
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
	 * @since   1.0
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
	 * @since   1.0
	 */
	public function testConstructor()
	{
		$this->assertAttributeEquals(array('default.shared' => true), 'config', $this->fixture);
	}

	/**
	 * Tests the constructor.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testConstructorWithConfig()
	{
		$this->fixture = new Container(array('foo' => 'bar'));

		$this->assertAttributeEquals(array('default.shared' => true, 'foo' => 'bar'), 'config', $this->fixture);
	}

	/**
	 * Tests the buildObject with no dependencies.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testBuildObjectNoDependencies()
	{
		$object = $this->fixture->buildObject('Joomla\\DI\\Tests\\Stub1');

		$this->assertInstanceOf('Joomla\\DI\\Tests\\Stub1', $object);
	}

	/**
	 * Tests the buildObject, getting dependency from the container.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testBuildObjectGetDependencyFromContainer()
	{
		$this->fixture['Joomla\\DI\\Tests\\StubInterface'] = function () {
			return new Stub1;
		};

		$object = $this->fixture->buildObject('Joomla\\DI\\Tests\\Stub2');

		$this->assertAttributeInstanceOf('Joomla\\DI\\Tests\\Stub1', 'stub', $object);
	}

	/**
	 * Tests the buildObject, getting dependency names from param array.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testBuildObjectGetDependencyFromParamsAsDependencyName()
	{
		$object = $this->fixture->buildObject('Joomla\\DI\\Tests\\Stub2', array('stub' => 'Joomla\\DI\\Tests\\Stub1'));

		$this->assertAttributeInstanceOf('Joomla\\DI\\Tests\\Stub1', 'stub', $object);
	}

	/**
	 * Tests the buildObject, getting dependency names from param array.
	 * This really tests have 2 implementations for the same typehinted interface.
	 * It's partly a "scope test".
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testBuildObjectGetDependencyFromParamsAsDependencyNameSameInterface()
	{
		$this->fixture->set('Joomla\\DI\\Tests\\StubInterface', function () { return new Stub4; });

		$object = $this->fixture->buildObject('Joomla\\DI\\Tests\\Stub3', array(
			'stub' => 'Joomla\\DI\\Tests\\Stub1',
			'stub2' => 'Joomla\\DI\\Tests\\Stub2'
		));

		$this->assertAttributeInstanceOf('Joomla\\DI\\Tests\\Stub1', 'stub', $object);
		$this->assertAttributeInstanceOf('Joomla\\DI\\Tests\\Stub2', 'stub2', $object);
	}

	/**
	 * Tests the buildObject, getting dependency names from param array as an object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testBuildObjectGetDependencyFromParamsAsDependencyObject()
	{
		$stub1 = new Stub1;
		$object = $this->fixture->buildObject('Joomla\\DI\\Tests\\Stub2', array('stub' => $stub1));

		$this->assertAttributeInstanceOf('Joomla\\DI\\Tests\\Stub1', 'stub', $object);
		$this->assertAttributeSame($stub1, 'stub', $object);
	}

	/**
	 * Tests the buildSharedObject.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testBuildSharedObject()
	{
		$object = $this->fixture->buildSharedObject('Joomla\\DI\\Tests\\Stub1');

		$this->assertSame($object, $this->fixture->get('Joomla\\DI\\Tests\\Stub1'));
	}

	/**
	 * Tests the set method with bad callback.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \UnexpectedValueException
	 */
	public function testSetInvalidCallback()
	{
		$this->fixture->set('foo', 'bar');
	}

	/**
	 * Tests the set method with already set key.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \OutOfBoundsException
	 */
	public function testSetAlreadySet()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });
		$this->fixture->set('foo', function () { return new \stdClass; });
	}

	/**
	 * Tests the set method as default shared.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
	 * @since   1.0
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
	 * @since   1.0
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
	 * @since   1.0
	 */
	public function testGetNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$this->assertNotSame($this->fixture->get('foo'), $this->fixture->get('foo'));
	}

	/**
	 * Tests the get method on a non-existant offset.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \InvalidArgumentException
	 */
	public function testGetNotExists()
	{
		$foo = $this->fixture->get('foo');
	}

	/**
	 * Test the get method on a non-existant offest that happens to be a class name.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetNotExistsButIsClass()
	{
		$object = $this->fixture->get('Joomla\\DI\\Tests\\Stub1');

		$this->assertInstanceOf('Joomla\\DI\\Tests\\Stub1', $object);
	}

	/**
	 * Tests the get method for passing the
	 * Joomla\DI\Container instance to the callback.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPassesContainerInstanceShared()
	{
		$this->fixture->set('foo', function ($c) { return $c; });

		$this->assertSame($this->fixture, $this->fixture['foo']);
	}

	/**
	 * Tests the get method for passing the
	 * Joomla\DI\Container instance to the callback.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPassesContainerInstanceNotShared()
	{
		$this->fixture->set('foo', function ($c) { return $c; }, false);

		$this->assertSame($this->fixture, $this->fixture['foo']);
	}

	/**
	 * Tests the getNew method which will always return a 
	 * new instance, even if the $key was set to be shared.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetNewInstance()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$this->assertNotSame($this->fixture->getNewInstance('foo'), $this->fixture->getNewInstance('foo'));
	}

	/**
	 * Tests the setConfig method.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
	 * @since   1.0
	 */
	public function testGetConfig()
	{
		$this->assertSame(array('default.shared' => true), $this->fixture->getConfig());
	}

	/**
	 * Tests the setParam method.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
	 * @since   1.0
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
	 * @since   1.0
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
	 * @since   1.0
	 */
	public function testOffsetExistsFalse()
	{
		$this->assertFalse(isset($this->fixture['foo']));
	}

	/**
	 * Tests the offsetGet method on a non-existant offset.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \InvalidArgumentException
	 */
	public function testOffsetGetNotExists()
	{
		$foo = $this->fixture['foo'];
	}

	/**
	 * Tests the offsetGet method on a non-existant offset that happens to be a class name.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testOffsetGetNotExistsButIsClass()
	{
		$object = $this->fixture['Joomla\\DI\\Tests\\Stub1'];

		$this->assertInstanceOf('Joomla\\DI\\Tests\\Stub1', $object);
	}

	/**
	 * Tests the offsetGet method shared.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
	 * @since   1.0
	 */
	public function testOffsetGetExistsNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$this->assertNotSame($this->fixture['foo'], $this->fixture['foo']);
	}

	/**
	 * Tests the offsetSet method shared.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
	 * @since   1.0
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
	 * @since   1.0
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
