<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;
use Joomla\Test\Helper;

/**
 * Tests for the Joomla\Cache\Runtime class.
 *
 * @since  1.0
 */
class RuntimeTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Cache\Runtime
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the Joomla\Cache\Runtime::doDelete method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::doDelete
	 * @since   1.0
	 */
	public function testDoDelete()
	{
		$this->instance->set('foo', 'bar');
		$this->assertEquals('bar', $this->instance->get('foo'));

		$this->instance->delete('foo');
		$this->assertNull($this->instance->get('foo'));
	}

	/**
	 * Tests the Joomla\Cache\Runtime::doGet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::doGet
	 * @since   1.0
	 */
	public function testDoGet()
	{
		$this->assertNull($this->instance->get('foo'));

		$this->instance->set('foo', 'bar');
		$this->assertEquals('bar', $this->instance->get('foo'));
	}

	/**
	 * Tests the Joomla\Cache\Runtime::doSet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Runtime::doSet
	 * @since   1.0
	 */
	public function testDoSet()
	{
		$this->instance->set('foo', 'bar');
		$this->assertEquals('bar', $this->instance->get('foo'));
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
		$this->assertFalse(Helper::invoke($this->instance, 'exists', 'foo'));
		$this->instance->set('foo', 'bar');
		$this->assertTrue(Helper::invoke($this->instance, 'exists', 'foo'));
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

			Helper::setValue($this->instance, 'store', array());
		}
		catch (\Exception $e)
		{
			$this->markTestSkipped();
		}
	}
}
