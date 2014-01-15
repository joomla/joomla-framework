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
	public $instance;

	/**
	 * @var    String Cache Classname to test
	 * @since  1.0
	 */
	public $cacheClass = 'Joomla\\Cache\\Tests\\ConcreteCache';

	/**
	 * @var    Array
	 * @since  1.0
	 */
	public $cacheOptions = array();

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
	 * Tests the the Joomla\Cache\Cache::get method..
	 *
	 * @return  void
	 *
	 * @coversNothing
	 * @since   1.0
	 */
	public function testGet()
	{
		$cacheInstance = $this->instance;
		$cacheInstance->clear();
		$cacheInstance->set('foo', 'bar');
		$this->hitKey('foo', 'bar');
		$this->missKey('foobar', 'foobar');
	}

	/**
	 * Checks to ensure a that $key is not set at all in the Cache
	 *
	 * @param   string $key Key of cache item to check
	 * @param   string $value Value cache item should be
	 * @return  void
	 *
	 * @since   1.1
	 */
	protected function missKey($key = '', $value = '')
	{
		$cacheInstance = $this->instance;
		$cacheItem = $cacheInstance->get($key);
		$cacheValue = $cacheItem->getValue();
		$cacheKey = $cacheItem->getKey();
		$cacheHit = $cacheItem->isHit();
		$this->assertThat($cacheKey, $this->equalTo($key), __LINE__);
		$this->assertThat($cacheValue, $this->equalTo(null), __LINE__);
		$this->assertThat($cacheHit, $this->equalTo(false), __LINE__);
	}

	/**
	 * Checks to ensure a that $key is set to $value in the Cache
	 *
	 * @param   string $key Key of cache item to check
	 * @param   string $value Value cache item should be
	 * @return  void
	 *
	 * @since   1.1
	 */
	protected function hitKey($key = '', $value = '')
	{
		$cacheInstance = $this->instance;
		$cacheItem = $cacheInstance->get($key);
		$cacheKey = $cacheItem->getKey();
		$cacheValue = $cacheItem->getValue();
		$cacheHit = $cacheItem->isHit();
		$this->assertThat($cacheKey, $this->equalTo($key), __LINE__);
		$this->assertThat($cacheValue, $this->equalTo($value), __LINE__);
		$this->assertThat($cacheHit, $this->equalTo(true), __LINE__);
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
		$cacheInstance = $this->instance;
		$cacheInstance->clear();
		$result = $cacheInstance->set('fooSet', 'barSet');
		$this->assertThat($result, $this->equalTo(true), __LINE__);
		$fooValue = $cacheInstance->get('fooSet')->getValue();
		$this->assertThat($fooValue, $this->equalTo('barSet'), __LINE__);
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
		$cacheInstance = $this->instance;
		$cacheInstance->clear();
		$samples = array( 'foo' => 'foo', 'bar' => 'bar', 'hello' => 'world');
		$moreSamples = $samples;
		$moreSamples['next'] = 'bar';
		$lessSamples = $samples;
		$badSampleKeys = array( 'foobar', 'barfoo', 'helloworld');

		// Pop an item from the array
		array_pop($lessSamples);
		$keys = array_keys($samples);

		foreach ($samples as $key => $value)
		{
			$cacheInstance->set($key, $value);
		}

		$results = $cacheInstance->getMultiple($keys);
		$this->assertSameSize($samples, $results, __LINE__);
		$this->assertNotSameSize($moreSamples, $results, __LINE__);
		$this->assertNotSameSize($lessSamples, $results, __LINE__);

		foreach ($results as $item)
		{
			$itemKey = $item->getKey();
			$itemValue = $item->getValue();
			$sampleValue = $samples[$itemKey];
			$this->assertThat($itemValue, $this->equalTo($sampleValue), __LINE__);
		}

		// Even if no keys are set, we should still$ have an array of keys
		$badResults = $cacheInstance->getMultiple($badSampleKeys);
		$this->assertSameSize($badSampleKeys, $badResults, __LINE__);
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
		$cacheInstance = $this->instance;
		$cacheInstance->clear();
		$samples = array( 'foo' => 'bars', 'goo' => 'google', 'hello' => 'world');

		foreach ($samples as $key => $value)
		{
			$cacheInstance->set($key, $value);
		}

		$sampleKeys = array_merge(
			array_keys($samples),
		array('foobar'));
		$results = $cacheInstance->removeMultiple($sampleKeys);

		foreach ($results as $key => $removed)
		{
			$msg = "Removal of $key was $removed::";

			if (array_key_exists($key, $samples))
			{
				$this->assertThat($removed, $this->equalTo(true), $msg . __LINE__);
			} else {
				$this->assertThat($removed, $this->equalTo(false), $msg . __LINE__);
			}

		}

	}

	/**
	 * Tests the Joomla\Cache\Cache::remove method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::remove
	 * @since   1.0
	 */
	public function testRemove()
	{
		$cacheInstance = $this->instance;
		$cacheInstance->clear();
		$samples = array( 'foo2' => 'bars', 'goo2' => 'google', 'hello2' => 'world');

		foreach ($samples as $key => $value)
		{
			$cacheInstance->set($key, $value);
		}

		$getFoo = $cacheInstance->get('foo2');
		$this->assertThat($getFoo->isHit(), $this->equalTo(true), __LINE__);
		$removeFoo = $cacheInstance->remove('foo2');
		$this->assertThat($removeFoo, $this->equalTo(true), __LINE__);
		$removeFoobar = $cacheInstance->remove('foobar');
		$this->assertThat($removeFoobar, $this->equalTo(false), __LINE__);
		$getResult = $cacheInstance->get('foo2');
		$this->assertThat($getResult->isHit(), $this->equalTo(false), __LINE__);
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
		$cacheInstance = $this->instance;
		$this->assertSame($cacheInstance, $cacheInstance->setOption('foo', 'bar'), 'Checks chaining');
		$this->assertEquals('bar', $cacheInstance->getOption('foo'));
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
		$cacheInstance = $this->instance;
		$cacheInstance->clear();
		$samples = array( 'foo' => 'fooSet', 'bar' => 'barSet', 'hello' => 'worldSet');
		$keys = array_keys($samples);
		$result = $cacheInstance->setMultiple($samples, 50);
		$this->assertThat($result, $this->isTrue(), __LINE__);
		$i = 0;

		foreach($keys as $key)
		{
			$cacheValue = $cacheInstance->get($key)->getValue();
			$sampleValue = $samples[$key];
			$this->assertThat($cacheValue, $this->equalTo($sampleValue), __LINE__);
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
		$cacheInstance = $this->instance;
		$cacheClass = get_class($cacheInstance);
		$interfaces = class_implements($cacheClass);
		$psrInterface = 'Psr\\Cache\\CacheInterface';
		$targetClass = $this->cacheClass;
		$this->assertArrayHasKey($psrInterface, $interfaces, __LINE__);
		$cacheClass = get_class($cacheInstance);
		$this->assertThat($cacheClass, $this->equalTo($targetClass), __LINE__);

		$this->assertInternalType('boolean', $cacheInstance->clear(), 'Checking clear.');
		$this->assertInternalType('boolean', $cacheInstance->set('foo', 'bar'), 'Checking set.');
		$this->assertInternalType('string', $cacheInstance->get('foo')->getValue(), 'Checking get.');
		$this->assertInternalType('boolean', $cacheInstance->remove('foo'), 'Checking remove.');
		$this->assertInternalType('boolean', $cacheInstance->setMultiple(array('foo' => 'bar')), 'Checking setMultiple.');
		$this->assertInternalType('array', $cacheInstance->getMultiple(array('foo')), 'Checking getMultiple.');
		$this->assertInternalType('array', $cacheInstance->removeMultiple(array('foo')), 'Checking removeMultiple.');
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
		$options = $this->cacheOptions;
		$className = $this->cacheClass;

		try
		{
			$cacheInstance = new $className($options);
		}
		catch (\RuntimeException $e)
		{
			$this->markTestSkipped();
		}
		$this->instance =& $cacheInstance;
		parent::setUp();
	}
}
