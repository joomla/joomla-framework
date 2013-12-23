<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Event\Tests;

use Joomla\Event\ListenersPriorityQueue;
use Joomla\Event\Tests\Fixtures\ChildListener;
use Joomla\Event\Tests\Fixtures\SomethingListener;
use Joomla\Event;

/**
 * Tests for the ListenersPriorityQueue class.
 *
 * @since  1.0
 */
class ListenersPriorityQueueTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Object under tests.
	 *
	 * @var    ListenersPriorityQueue
	 *
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Provide all possible callable listeners.
	 *
	 * @return  array
	 *
	 * @since   1.1
	 */
	public function getListeners()
	{
		return array(
			// Function
			array(
				1,
				'Joomla\Event\Tests\Fixtures\onFunction'
			),
			// Closure
			array(
				1,
				function (Event $e) {}
			),
			// Static method from class
			array(
				2,
				array('Joomla\Event\Tests\Fixtures\SomethingListener', 'onStatic')
			),
			// Static method from object
			array(
				2,
				array(new SomethingListener, 'onStatic')
			),
			// Static call
			array(
				-5,
				'Joomla\Event\Tests\Fixtures\SomethingListener::onStatic'
			),
			// Relative static call from class
			array(
				-5,
				array('Joomla\Event\Tests\Fixtures\ChildListener', 'parent::onStatic')
			),
			// Relative static call from object
			array(
				-5,
				array(new ChildListener, 'parent::onStatic')
			),
		);
	}

	/**
	 * Test the add method.
	 *
	 * @param   integer   $priority  The priority
	 * @param   callable  $listener  The listener
	 *
	 * @dataProvider getListeners
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAdd($priority, $listener)
	{
		$this->instance->add($listener, $priority);

		$this->assertTrue($this->instance->has($listener));
		$this->assertEquals($priority, $this->instance->getPriority($listener));
	}

	/**
	 * Test adding a listener will have no effect.
	 *
	 * @param   integer   $priority  The priority
	 * @param   callable  $listener  The listener
	 *
	 * @dataProvider getListeners
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddExisting($priority, $listener)
	{
		$this->instance->add($listener, $priority);
		$this->instance->add($listener, -5000);

		$this->assertTrue($this->instance->has($listener));
		$this->assertEquals($priority, $this->instance->getPriority($listener));
	}

	/**
	 * Test the getPriority method when the listener wasn't added.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPriorityNonExisting()
	{
		$this->assertNull($this->instance->getPriority('Joomla\Event\Tests\Fixtures\onFunction'));
		$this->assertFalse($this->instance->getPriority('Joomla\Event\Tests\Fixtures\onFunction', false));
	}

	/**
	 * Test adding a listener will have no effect.
	 *
	 * @param   integer   $priority  The priority
	 * @param   callable  $listener  The listener
	 *
	 * @dataProvider getListeners
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRemove($priority, $listener)
	{
		$this->instance->add($listener, $priority);
		$this->instance->remove($listener);

		$this->assertFalse($this->instance->has($listener));
		$this->assertNull($this->instance->getPriority($listener));
	}

	/**
	 * Registers and returns an array of listeners in the order
	 * they will be sorted by the priority queue.
	 *
	 * @return  array
	 */
	private function getAndSetOrderedListeners()
	{
		$listeners = array(
			0 => 'Joomla\Event\Tests\Fixtures\onFunction',
			1 => function (Event $e) {},
			2 => array('Joomla\Event\Tests\Fixtures\SomethingListener', 'onStatic'),
			3 => array(new SomethingListener, 'onStatic'),
			4 => 'Joomla\Event\Tests\Fixtures\SomethingListener::onStatic',
			5 => array('Joomla\Event\Tests\Fixtures\ChildListener', 'parent::onStatic'),
			6 => array(new ChildListener, 'parent::onStatic'),
		);

		$this->instance->add($listeners[0], 10);
		$this->instance->add($listeners[1], 10);
		$this->instance->add($listeners[3], 0);
		$this->instance->add($listeners[2], 10);
		$this->instance->add($listeners[6], -10);
		$this->instance->add($listeners[4], 0);
		$this->instance->add($listeners[5], -5);

		return $listeners;
	}

	/**
	 * Test the getAll method.
	 * All listeners with the same priority must be sorted in the order
	 * they were added.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetAll()
	{
		$this->assertEmpty($this->instance->getAll());

		$listeners = $this->getAndSetOrderedListeners();

		$this->assertEquals($listeners, $this->instance->getAll());
	}

	/**
	 * Test the getIterator method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetIterator()
	{
		$expectedListeners = $this->getAndSetOrderedListeners();

		$listeners = array();

		foreach ($this->instance as $listener)
		{
			$listeners[] = $listener;
		}

		$this->assertEquals($expectedListeners, $listeners);
	}

	/**
	 * Test that ListenersPriorityQueue is not a heap.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetIteratorMultipleIterations()
	{
		$expectedListeners = $this->getAndSetOrderedListeners();

		$this->assertEquals($expectedListeners, array_values(iterator_to_array($this->instance->getIterator())));
		$this->assertEquals($expectedListeners, array_values(iterator_to_array($this->instance->getIterator())));
	}

	/**
	 * Test the count method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCount()
	{
		$this->assertCount(0, $this->instance);

		$listener1 = 'Joomla\Event\Tests\Fixtures\onFunction';
		$listener2 = 'onOtherFunction';

		$this->instance->add($listener1, 0);
		$this->instance->add($listener2, 0);

		$this->assertCount(2, $this->instance);
	}

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		$this->instance = new ListenersPriorityQueue;
	}
}
