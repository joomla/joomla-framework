<?php
/**
 * @package    Joomla\Framework\Tests
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Model\Tests;

use Joomla\Registry\Registry;

require_once __DIR__ . '/Stubs/BaseModel.php';

/**
 * Tests for the Joomla\Model\Base class.
 *
 * @package  Joomla\Framework\Tests
 * @since    1.0
 */
class ModelBaseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    BaseModel
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertEquals(new Registry, $this->instance->getState(), 'Checks default state.');

		$state = new Registry(array('foo' => 'bar'));
		$class = new BaseModel($state);
		$this->assertEquals($state, $class->getState(), 'Checks state injection.');
	}

	/**
	 * Tests the getState method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::getState
	 * @since   1.0
	 */
	public function testGetState()
	{
		// Reset the state property to a known value.
		$prop = new \ReflectionProperty($this->instance, 'state');
		$prop->setAccessible(true);
		$prop->setValue($this->instance, 'foo');

		$this->assertEquals('foo', $this->instance->getState());
	}

	/**
	 * Tests the setState method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::setState
	 * @since   1.0
	 */
	public function testSetState()
	{
		$state = new Registry(array('foo' => 'bar'));
		$this->instance->setState($state);
		$this->assertSame($state, $this->instance->getState());
	}

	/**
	 * Tests the loadState method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::loadState
	 * @since   1.0
	 */
	public function testLoadState()
	{
		$method = new \ReflectionMethod($this->instance, 'loadState');
		$method->setAccessible(true);

		$this->assertInstanceOf('Joomla\Registry\Registry', $method->invoke($this->instance));
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		$this->instance = new BaseModel;
	}
}
