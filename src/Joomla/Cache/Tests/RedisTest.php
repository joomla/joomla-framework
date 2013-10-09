<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\Redis class.
 *
 * @since  1.0
 */
class RedisTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var    Cache\Redis
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
     * Tests the Joomla\Cache\Redis::get and Joomla\Cache\Redis::set methods.
     *
     * @return  void
     *
     * @covers  Joomla\Cache\Redis::get
     * @covers  Joomla\Cache\Redis::set
     * @since   1.0
     */
    public function testGetAndSet()
    {
        $this->assertTrue($this->instance->set('foo', 'bar'), 'Should store the data properly');
        $this->assertEquals('bar', $this->instance->get('foo')->getValue(), 'Checking get');
    }

    /**
     * Tests the Joomla\Cache\Redis::get and Joomla\Cache\Redis::set methods with timeout
     *
     * @return  void
     *
     * @covers  Joomla\Cache\Redis::get
     * @covers  Joomla\Cache\Redis::set
     * @since   1.0
     */
    public function testGetAndSetWithTimeout()
    {
        $this->assertTrue($this->instance->set('foo', 'bar', 1), 'Should store the data properly');
        sleep(2);
        $this->assertFalse($this->instance->get('foo')->isHit(), 'Checks expired get.');
    }

    /**
     * Tests the Joomla\Cache\Redis::clear method.
     *
     * @return  void
     *
     * @covers  Joomla\Cache\Redis::clear
     * @since   1.0
     */
    public function testClear()
    {
        $this->instance->set('foo', 'bar');
        $this->instance->set('boo', 'car');

        $this->instance->clear();

        $this->assertFalse($this->instance->get('foo')->isHit(), 'Item should have been removed');
        $this->assertFalse($this->instance->get('goo')->isHit(), 'Item should have been removed');
    }

    /**
     * Tests the Joomla\Cache\Redis::exists method.
     *
     * @return  void
     *
     * @covers  Joomla\Cache\Redis::exists
     * @since   1.0
     */
    public function testExists()
    {
        $this->assertFalse($this->instance->exists('foo'), 'Item should not exist');
        $this->instance->set('foo', 'bar');
        $this->assertTrue($this->instance->exists('foo'), 'Item should exist');
    }

    /**
     * Tests the Joomla\Cache\Redis::remove method.
     *
     * @return  void
     *
     * @covers  Joomla\Cache\Redis::remove
     * @since   1.0
     */

    public function testRemove()
    {
        $this->instance->set('foo', 'bar');
        $this->assertTrue($this->instance->get('foo')->isHit(), 'Item should exist');
        $this->instance->remove('foo');
        $this->assertFalse($this->instance->get('foo')->isHit(), 'Item should have been removed');
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
            $this->instance = new Cache\Redis;
        }
        catch (\Exception $e)
        {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /**
     * Flush all data before each test
     *
     * @return  void
     *
     * @since   1.0
     */
    protected function assertPreConditions()
    {
        if ($this->instance)
        {
            $this->instance->clear();
        }
    }

    /**
     * Teardown the test.
     *
     * @return  void
     *
     * @since   1.0
     */
    protected function tearDown()
    {
        if ($this->instance)
        {
            $this->instance->clear();
        }
    }
}