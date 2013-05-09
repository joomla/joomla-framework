<?php

namespace Joomla\DI\Tests;

use Joomla\DI\Container;
use Joomla\Test\TestHelper;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
	/**
 	 * Holds the Container instance for testing.
	 *
	 * @var  Joomla\DI\Container
	 */
	protected $fixture;

	public function setUp()
	{
		$this->fixture = new Container;
	}

	public function tearDown()
	{
		$this->fixture = null;
	}

	public function testConstructor()
	{
		$this->markTestIncomplete();
	}

	public function testSetShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertTrue($dataStore['foo']['shared']);
	}

	public function testSetNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$dataStore = $this->readAttribute($this->fixture, 'dataStore');

		$this->assertFalse($dataStore['foo']['shared']);
	}

	public function testGetShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; });

		$this->assertSame($this->fixture->get('foo'), $this->fixture->get('foo'));
	}

	public function testGetNotShared()
	{
		$this->fixture->set('foo', function () { return new \stdClass; }, false);

		$this->assertNotSame($this->fixture->get('foo'), $this->fixture->get('foo'));
	}

	public function testSetConfig()
	{
		$this->fixture->setConfig(array('foo' => 'bar'));

		$this->assertAttributeEquals(array('default.shared' => true, 'foo' => 'bar'), 'config', $this->fixture);
	}

	public function testGetConfig()
	{
		$this->assertSame($this->readAttribute($this->fixture, 'config'), array('default.shared' => true));
	}

	public function testSetParam()
	{
		$this->fixture->setParam('foo', 'bar');

		$this->assertAttributeEquals(array('default.shared' => true, 'foo' => 'bar'), 'config', $this->fixture);
	}

	public function testGetParam()
	{
		$this->assertSame($this->fixture->getParam('default.shared'), true);
	}
}
