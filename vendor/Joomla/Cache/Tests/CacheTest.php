<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Test\Helper;

require_once __DIR__ . '/Stubs/Concrete.php';

/**
 * Tests for the Joomla\Cache\Cache class.
 *
 * @since  1.0
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Cache\Cache
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the Joomla\Cache\Cache::__construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		// This checks the default ttl and also that the options registry was initialised.
		$this->assertEquals('900', $this->instance->getOption('ttl'));
	}

	/**
	 * Tests the Joomla\Cache\Cache::delete method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::delete
	 * @since   1.0
	 */
	public function testDelete()
	{
		$this->assertSame($this->instance, $this->instance->delete('delFoo'), 'Checks chaining');
		$this->assertEquals('doDelete-delFoo', $this->instance->do, 'Checks the do method was called correctly');
	}

	/**
	 * Tests the Joomla\Cache\Cache::get method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->instance->get('getFoo');
		$this->assertEquals('doGet-getFoo', $this->instance->do, 'Checks the do method was called correctly');
	}

	/**
	 * Tests the Joomla\Cache\Cache::setOption method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::getOption
	 * @covers  Joomla\Cache\Cache::setOption
	 * @since   1.0
	 */
	public function testSetOption()
	{
		$this->assertSame($this->instance, $this->instance->setOption('foo', 'bar'), 'Checks chaining');
		$this->assertEquals('bar', $this->instance->getOption('foo'));
	}

	/**
	 * Tests the Joomla\Cache\Cache::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->assertSame($this->instance, $this->instance->set('foo', 'bar', 60), 'Checks chaining');
		$this->assertEquals('doSet-foo-bar-60', $this->instance->do, 'Checks the do method was called correctly');
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
		parent::setUp();

		$this->instance = new ConcreteCache;
	}
}
