<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;
require_once __DIR__ . '/Stubs/Concrete.php';

use Joomla\Test\TestHelper;

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
	static protected $className = '\\Joomla\\Cache\\Tests\\ConcreteCache';

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
	 * Tests the the Joomla\Cache\Cache::get method..
	 *
	 * @return  void
	 *
	 * @coversNothing
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->assertInstanceOf(static::$instance, '\\Psr\\Cache\\CacheItemInterface', 'Checking Interface.');
		static::$instance->clear();
		static::$instance->set('for', 'bar');
		$fooValue = static::$instance->get('foo');
		$this->assertThat($fooValue, $this->equalTo('foo'), __LINE__);
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
		$this->assertInstanceOf(static::$instance, '\\Psr\\Cache\\CacheItemInterface', 'Checking Interface.');
		static::$instance->clear();
		static::$instance->set('for', 'barSet');
		$fooValue = static::$instance->get('fooSet');
		$this->assertThat($fooValue, $this->equalTo('fooSet'), __LINE__);
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
		static::$instance->clear();
		$samples = array( 'foo' => 'foo', 'bar' => 'bar', 'hello' => 'world');
		static::$instance->setMultiple(array($samples), 50);
		$result = static::$instance->getMultiple($samples);
		$i = 0;
		foreach($samples as $key => $value)
		{
			$this->assertArrayHasKey($key, $result, "Array item $i missing ".__LINE__);
			$this->assertThat($result[$key], $this->equalTo($value), "Array value $i incorrect ".__LINE__);
			$i++;
		}

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
		static::$instance->set('foo', 'bars');
		static::$instance->set('goo', 'google');
		$result = static::$instance->removeMultiple(array('foo', 'goo'));
		$this->assertThat($result, $this->equalTo(array('foo' => true, 'goo' => true)), __LINE__);
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
		static::$instance->clear();
		$samples = array( 'foo' => 'fooSet', 'bar' => 'barSet', 'hello' => 'worldSet');
		$result = static::$instance->setMultiple($samples, 50);
		$this->assertThat($result, $this->isTrue(), __LINE__);
		$i = 0;
		foreach($samples as $key => $value)
		{
			$cacheValue = static::$instance->get($key);
			$this->assertThat($cacheValue, $this->isEqual($value), "Value number $i incorrect ".__LINE__);
			$i++;
		}

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
		$this->assertInstanceOf(static::$instance, '\\Psr\\Cache\\CacheItemInterface', 'Checking Interface.');
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
			static::$instance = new static::$className;
		}
		catch (\RuntimeException $e)
		{
			$this->markTestSkipped();
		}
		parent::setUp();
	}
}
