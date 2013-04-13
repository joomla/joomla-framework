<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;
use Joomla\Test\TestHelper;

/**
 * Tests for the Joomla\Cache\Runtime class.
 *
 * @since  1.0
 */
class RuntimeTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Cache\Runtime
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests for the correct Psr\Cache return values.
	 *
	 * @return  void
	 *
	 * @coversNothing
	 * @since   1.0
	 */
	public function testPsrCache()
	{
		$this->assertInternalType('boolean', $this->instance->clear(), 'Checking clear.');
		$this->assertInstanceOf('\Psr\Cache\CacheItemInterface', $this->instance->get('foo'), 'Checking get.');
		$this->assertInternalType('array', $this->instance->getMultiple(array('foo')), 'Checking getMultiple.');
		$this->assertInternalType('boolean', $this->instance->remove('foo'), 'Checking remove.');
		$this->assertInternalType('array', $this->instance->removeMultiple(array('foo')), 'Checking removeMultiple.');
		$this->assertInternalType('boolean', $this->instance->set('for', 'bar'), 'Checking set.');
		$this->assertInternalType('boolean', $this->instance->setMultiple(array('foo' => 'bar')), 'Checking setMultiple.');
	}

	/**
	 * Tests the Joomla\Cache\Runtime::clear method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::clear
	 * @since   1.0
	 */
	public function testClear()
	{
		$this->instance->setMultiple(
			array(
				'foo' => 'bar',
				'goo' => 'car',
			)
		);
		$this->assertEquals('bar', $this->instance->get('foo')->getValue(), 'Checks first item was set.');
		$this->assertEquals('car', $this->instance->get('goo')->getValue(), 'Checks second item was set.');

		$this->instance->clear();

		$this->assertNull($this->instance->get('foo')->getValue(), 'Checks first item was cleared.');
		$this->assertNull($this->instance->get('goo')->getValue(), 'Checks second item was cleared.');
	}

	/**
	 * Tests the Joomla\Cache\Runtime::exists method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::exists
	 * @since   1.0
	 */
	public function testExists()
	{
		$this->assertFalse(TestHelper::invoke($this->instance, 'exists', 'foo'));
		$this->instance->set('foo', 'bar');
		$this->assertTrue(TestHelper::invoke($this->instance, 'exists', 'foo'));
	}

	/**
	 * Tests the Joomla\Cache\Runtime::get method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$item = $this->instance->get('foo');
		$this->assertNull($item->getValue());
		$this->assertFalse($item->isHit());

		$this->instance->set('foo', 'bar');
		$this->assertEquals('bar', $this->instance->get('foo')->getValue());
	}

	/**
	 * Tests the Joomla\Cache\Runtime::remove method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::remove
	 * @since   1.0
	 */
	public function testRemove()
	{
		$this->instance->set('foo', 'bar');
		$this->assertEquals('bar', $this->instance->get('foo')->getValue());

		$this->instance->remove('foo');
		$this->assertNull($this->instance->get('foo')->getValue());
	}

	/**
	 * Tests the Joomla\Cache\Runtime::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->instance->set('foo', 'bar');
		$this->assertEquals('bar', $this->instance->get('foo')->getValue());
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

		try
		{
			$this->instance = new Cache\Runtime;

			// Clear the internal store.
			TestHelper::setValue($this->instance, 'store', array());
		}
		catch (\Exception $e)
		{
			$this->markTestSkipped();
		}
	}
}
