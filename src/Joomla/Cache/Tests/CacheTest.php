<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Test\TestHelper;

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
	static protected $instance;

	/**
	 * @var    String Cache Classname to test
	 * @since  1.0
	 */
	static protected $className = 'ConcreteCache';

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
		$this->assertEquals('900', static::$instance->getOption('ttl'));
	}

	/**
	 * Tests the Joomla\Cache\Cache::getMultiple method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::getMultiple
	 * @since   1.0
	 */
	public function testGetMultiple()
	{
		$result = static::$instance->getMultiple(array('foo', 'goo'));
		$this->assertArrayHasKey('foo', $result, 'Checks the return array (1).');
		$this->assertArrayHasKey('goo', $result, 'Checks the return array (2).');
		$this->assertInstanceOf('\Psr\Cache\CacheItemInterface', $result['foo'], 'Checks the return type.');
	}

	/**
	 * Tests the Joomla\Cache\Cache::removeMultiple method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::removeMultiple
	 * @since   1.0
	 */
	public function testRemoveMultiple()
	{
		$result = static::$instance->removeMultiple(array('foo', 'goo'));
		$this->assertEquals(array('foo' => true, 'goo' => true), $result);
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
		$this->assertSame(static::$instance, static::$instance->setOption('foo', 'bar'), 'Checks chaining');
		$this->assertEquals('bar', static::$instance->getOption('foo'));
	}

	/**
	 * Tests the Joomla\Cache\Cache::setMultiple method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::setMultiple
	 * @since   1.0
	 */
	public function testSetMultiple()
	{
		$result = static::$instance->setMultiple(array('foo' => 'bar', 'goo' => 'car'), 50);
		$this->assertTrue($result);
	}

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
		$this->assertInstanceOf('\\Psr\\Cache\\CacheItemInterface', static::$instance, 'Checking Interface.');
		$this->assertInternalType('boolean', static::$instance->clear(), 'Checking clear.');
		$this->assertInternalType('boolean', static::$instance->set('for', 'bar'), 'Checking set.');
		$this->assertInternalType('string', static::$instance->get('foo'), 'Checking get.');
		$this->assertInternalType('boolean', static::$instance->remove('foo'), 'Checking remove.');
		$this->assertInternalType('boolean', static::$instance->setMultiple(array('foo' => 'bar')), 'Checking setMultiple.');
		$this->assertInternalType('array', static::$instance->getMultiple(array('foo')), 'Checking getMultiple.');
		$this->assertInternalType('array', static::$instance->removeMultiple(array('foo')), 'Checking removeMultiple.');
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

		try
		{
			static::$instance =  new static::$className;;
		}
		catch (\RuntimeException $e)
		{
			$this->markTestSkipped();
		}

		parent::setUp();
	}
}
