<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler\Tests;

use Joomla\Profiler\Renderer\DefaultRenderer;
use Joomla\Profiler\ProfilePoint;
use Joomla\Profiler\Profiler;

use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Profiler\Profiler.
 *
 * @since  1.0
 */
class ProfilerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Profiler\Profiler
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the constructor.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertEquals('test', TestHelper::getValue($this->instance, 'name'));
		$this->assertInstanceOf('\Joomla\Profiler\Renderer\DefaultRenderer', TestHelper::getValue($this->instance, 'renderer'));
		$this->assertEmpty(TestHelper::getValue($this->instance, 'points'));
		$this->assertFalse(TestHelper::getValue($this->instance, 'memoryRealUsage'));

		$renderer = new DefaultRenderer;
		$pointOne = new ProfilePoint('start');
		$pointTwo = new ProfilePoint('two', 1, 1);
		$points = array($pointOne, $pointTwo);

		$profiler = new Profiler('bar', $renderer, $points, true);
		$this->assertEquals('bar', TestHelper::getValue($profiler, 'name'));
		$this->assertSame($renderer, TestHelper::getValue($profiler, 'renderer'));
		$this->assertEquals($points, TestHelper::getValue($profiler, 'points'));
		$this->assertTrue(TestHelper::getValue($profiler, 'memoryRealUsage'));
	}

	/**
	 * Tests the setPoints method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::setPoints
	 * @since   1.0
	 */
	public function testSetPoints()
	{
		$first = new ProfilePoint('first');
		$second = new ProfilePoint('second', 1.5, 1000);
		$third = new ProfilePoint('third', 2.5, 2000);

		TestHelper::invoke($this->instance, 'setPoints', array($first, $second, $third));

		$this->assertTrue($this->instance->hasPoint('first'));
		$this->assertTrue($this->instance->hasPoint('second'));
		$this->assertTrue($this->instance->hasPoint('third'));

		$this->assertSame($first, $this->instance->getPoint('first'));
		$this->assertSame($second, $this->instance->getPoint('second'));
		$this->assertSame($third, $this->instance->getPoint('third'));
	}

	/**
	 * Tests the setPoints method exception, when
	 * a point already exists with the same name.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::setPoints
	 * @expectedException  \InvalidArgumentException
	 * @since   1.0
	 */
	public function testSetPointsExceptionExisting()
	{
		$first = new ProfilePoint('test');
		$second = new ProfilePoint('test');

		TestHelper::invoke($this->instance, 'setPoints', array($first, $second));
	}

	/**
	 * Tests the setPoints method exception, when
	 * an invalid point is passed.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::setPoints
	 * @expectedException  \InvalidArgumentException
	 * @since   1.0
	 */
	public function testSetPointsExceptionInvalid()
	{
		$first = new ProfilePoint('test');
		$second = 0;

		TestHelper::invoke($this->instance, 'setPoints', array($first, $second));
	}

	/**
	 * Tests the getName method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getName
	 * @since   1.0
	 */
	public function testGetName()
	{
		$this->assertEquals('test', $this->instance->getName());
	}

	/**
	 * Tests the mark method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::mark
	 * @since   1.0
	 */
	public function testMark()
	{
		$this->instance->mark('one');
		$this->instance->mark('two');
		$this->instance->mark('three');

		$this->assertTrue($this->instance->hasPoint('one'));
		$this->assertTrue($this->instance->hasPoint('two'));
		$this->assertTrue($this->instance->hasPoint('three'));

		// Assert the first point has a time and memory = 0
		$firstPoint = $this->instance->getPoint('one');

		$this->assertSame(0.0, $firstPoint->getTime());
		$this->assertSame(0, $firstPoint->getMemoryBytes());

		// Assert the other points have a time and memory != 0
		$secondPoint = $this->instance->getPoint('two');

		$this->assertGreaterThan(0, $secondPoint->getTime());
		$this->assertGreaterThan(0, $secondPoint->getMemoryBytes());

		$thirdPoint = $this->instance->getPoint('three');

		$this->assertGreaterThan(0, $thirdPoint->getTime());
		$this->assertGreaterThan(0, $thirdPoint->getMemoryBytes());

		// Assert the third point has greater values than the second point.
		$this->assertGreaterThan($secondPoint->getTime(), $thirdPoint->getTime());
		$this->assertGreaterThan($secondPoint->getMemoryBytes(), $thirdPoint->getMemoryBytes());
	}

	/**
	 * Tests the mark method exception when a point
	 * already exists with the given name.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::mark
	 * @expectedException  \LogicException
	 * @since   1.0
	 */
	public function testMarkException()
	{
		$this->instance->mark('test');
		$this->instance->mark('test');
	}

	/**
	 * Tests the hasPoint method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::hasPoint
	 * @since   1.0
	 */
	public function testHasPoint()
	{
		$this->assertFalse($this->instance->hasPoint('test'));

		$this->instance->mark('test');
		$this->assertTrue($this->instance->hasPoint('test'));
	}

	/**
	 * Tests the getPoint method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getPoint
	 * @since   1.0
	 */
	public function testGetPoint()
	{
		$this->assertNull($this->instance->getPoint('foo'));

		$this->instance->mark('start');

		$point = $this->instance->getPoint('start');
		$this->assertInstanceOf('\Joomla\Profiler\ProfilePoint', $point);
		$this->assertEquals('start', $point->getName());
	}

	/**
	 * Tests the getTimeBetween method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getTimeBetween
	 * @since   1.0
	 */
	public function testGetTimeBetween()
	{
		$first = new ProfilePoint('start');
		$second = new ProfilePoint('stop', 1.5);

		$profiler = new Profiler('test', null, array($first, $second));

		$this->assertSame(1.5, $profiler->getTimeBetween('start', 'stop'));
		$this->assertSame(1.5, $profiler->getTimeBetween('stop', 'start'));
	}

	/**
	 * Tests the getTimeBetween method exception.
	 * When the second point doesn't exist.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getTimeBetween
	 * @expectedException \LogicException
	 * @since   1.0
	 */
	public function testGetTimeBetweenExceptionSecond()
	{
		$first = new ProfilePoint('start');
		$profiler = new Profiler('test', null, array($first));

		$profiler->getTimeBetween('start', 'bar');
	}

	/**
	 * Tests the getTimeBetween method exception.
	 * When the first point doesn't exist.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getTimeBetween
	 * @expectedException \LogicException
	 * @since   1.0
	 */
	public function testGetTimeBetweenExceptionFirst()
	{
		$first = new ProfilePoint('start');
		$profiler = new Profiler('test', null, array($first));

		$profiler->getTimeBetween('foo', 'start');
	}

	/**
	 * Tests the getMemoryBytesBetween method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getMemoryBytesBetween
	 * @since   1.0
	 */
	public function testGetMemoryBytesBetween()
	{
		$first = new ProfilePoint('start');
		$second = new ProfilePoint('stop', 0, 1000);

		$profiler = new Profiler('test', null, array($first, $second));

		$this->assertSame(1000, $profiler->getMemoryBytesBetween('start', 'stop'));
		$this->assertSame(1000, $profiler->getMemoryBytesBetween('stop', 'start'));
	}

	/**
	 * Tests the getMemoryBytesBetween method exception.
	 * When the second point doesn't exist.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getMemoryBytesBetween
	 * @expectedException \LogicException
	 * @since   1.0
	 */
	public function testGetMemoryBytesBetweenExceptionSecond()
	{
		$first = new ProfilePoint('start');
		$profiler = new Profiler('test', null, array($first));

		$profiler->getMemoryBytesBetween('start', 'bar');
	}

	/**
	 * Tests the getMemoryBytesBetween method exception.
	 * When the first point doesn't exist.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getMemoryBytesBetween
	 * @expectedException \LogicException
	 * @since   1.0
	 */
	public function testGetMemoryBytesBetweenExceptionFirst()
	{
		$first = new ProfilePoint('start');
		$profiler = new Profiler('test', null, array($first));

		$profiler->getMemoryBytesBetween('foo', 'start');
	}

	/**
	 * Tests the getMemoryPeakBytes method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getMemoryPeakBytes
	 * @since   1.0
	 */
	public function testGetMemoryPeakBytes()
	{
		TestHelper::setValue($this->instance, 'memoryPeakBytes', 10);
		$this->assertEquals(10, $this->instance->getMemoryPeakBytes());
	}

	/**
	 * Tests the getPoints method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getPoints
	 * @since   1.0
	 */
	public function testGetPoints()
	{
		TestHelper::setValue($this->instance, 'points', false);
		$this->assertFalse($this->instance->getPoints());
	}

	/**
	 * Tests the setRenderer method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::setRenderer
	 * @since   1.0
	 */
	public function testSetRenderer()
	{
		// Reset the property.
		TestHelper::setValue($this->instance, 'renderer', null);

		$renderer = new DefaultRenderer;

		$this->instance->setRenderer($renderer);

		$this->assertSame($renderer, $this->instance->getRenderer());
	}

	/**
	 * Tests the getRenderer method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getRenderer
	 * @since   1.0
	 */
	public function testGetRenderer()
	{
		TestHelper::setValue($this->instance, 'renderer', true);
		$this->assertTrue($this->instance->getRenderer());
	}

	/**
	 * Tests the render method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::render
	 * @since   1.0
	 */
	public function testRender()
	{
		$mockedRenderer = $this->getMock('\Joomla\Profiler\ProfilerRendererInterface');
		$mockedRenderer->expects($this->once())
			->method('render')
			->with($this->instance);

		$this->instance->setRenderer($mockedRenderer);

		$this->instance->render();
	}

	/**
	 * Tests the __toString method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::__toString
	 * @since   1.0
	 */
	public function test__toString()
	{
		$mockedRenderer = $this->getMock('\Joomla\Profiler\ProfilerRendererInterface');
		$mockedRenderer->expects($this->once())
			->method('render')
			->with($this->instance);

		$this->instance->setRenderer($mockedRenderer);

		$this->instance->__toString();
	}

	/**
	 * Tests the count method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::getIterator
	 * @since   1.0
	 */
	public function testGetIterator()
	{
		// Create 3 points.
		$first = new ProfilePoint('test');
		$second = new ProfilePoint('second', 1.5, 1000);
		$third = new ProfilePoint('third', 2.5, 2000);

		$points = array($first, $second, $third);

		// Create a profiler and inject the points.
		$profiler = new Profiler('test', null, $points);

		$iterator = $profiler->getIterator();

		$this->assertEquals($iterator->getArrayCopy(), $points);
	}

	/**
	 * Tests the count method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\Profiler::count
	 * @since   1.0
	 */
	public function testCount()
	{
		$this->assertCount(0, $this->instance);

		$this->instance->mark('start');
		$this->instance->mark('foo');
		$this->instance->mark('end');

		$this->assertCount(3, $this->instance);
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

		$this->instance = new Profiler('test');
	}
}
