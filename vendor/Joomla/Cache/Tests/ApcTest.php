<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\Apc class.
 *
 * @since  1.0
 */
class ApcTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Cache\Apc
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
	 * Tests the Joomla\Cache\Apc::clear method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::clear
	 * @since   1.0
	 */
	public function testClear()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\Apc::exists method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::exists
	 * @since   1.0
	 */
	public function testExists()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\Apc::get method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\Apc::remove method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::remove
	 * @since   1.0
	 */
	public function testRemove()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\Apc::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->markTestIncomplete();
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
			$this->instance = new Cache\Apc;
		}
		catch (\Exception $e)
		{
			$this->markTestSkipped();
		}
	}
}
