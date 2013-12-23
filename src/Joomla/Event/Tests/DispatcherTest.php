<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Event\Tests;

require_once __DIR__ . '/Fixtures/functions.php';

use Joomla\Event\Dispatcher;
use Joomla\Event\Event;
use Joomla\Event\EventInterface;
use Joomla\Event\EventImmutable;
use Joomla\Event\Priority;
use Joomla\Event\Tests\Fixtures\FirstListener;
use Joomla\Event\Tests\Fixtures\SecondListener;
use Joomla\Event\Tests\Fixtures\SomethingListener;
use Joomla\Event\Tests\Fixtures\ThirdListener;
use Joomla\Event\Tests\Fixtures\ChildListener;

/**
 * Tests for the Dispatcher class.
 *
 * @since  1.0
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Object under tests.
	 *
	 * @var    Dispatcher
	 *
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Test the setEvent method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::setEvent
	 * @since   1.0
	 */
	public function testSetEvent()
	{
		$event = new Event('onTest');
		$this->instance->setEvent($event);
		$this->assertTrue($this->instance->hasEvent('onTest'));
		$this->assertSame($event, $this->instance->getEvent('onTest'));

		$immutableEvent = new EventImmutable('onAfterSomething');
		$this->instance->setEvent($immutableEvent);
		$this->assertTrue($this->instance->hasEvent('onAfterSomething'));
		$this->assertSame($immutableEvent, $this->instance->getEvent('onAfterSomething'));

		// Setting an existing event will replace the old one.
		$eventCopy = new Event('onTest');
		$this->instance->setEvent($eventCopy);
		$this->assertTrue($this->instance->hasEvent('onTest'));
		$this->assertSame($eventCopy, $this->instance->getEvent('onTest'));
	}

	/**
	 * Test the addEvent method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::addEvent
	 * @since   1.0
	 */
	public function testAddEvent()
	{
		$event = new Event('onTest');
		$this->instance->addEvent($event);
		$this->assertTrue($this->instance->hasEvent('onTest'));
		$this->assertSame($event, $this->instance->getEvent('onTest'));

		$immutableEvent = new EventImmutable('onAfterSomething');
		$this->instance->addEvent($immutableEvent);
		$this->assertTrue($this->instance->hasEvent('onAfterSomething'));
		$this->assertSame($immutableEvent, $this->instance->getEvent('onAfterSomething'));

		// Adding an existing event will have no effect.
		$eventCopy = new Event('onTest');
		$this->instance->addEvent($eventCopy);
		$this->assertTrue($this->instance->hasEvent('onTest'));
		$this->assertSame($event, $this->instance->getEvent('onTest'));
	}

	/**
	 * Test the hasEvent method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::hasEvent
	 * @since   1.0
	 */
	public function testHasEvent()
	{
		$this->assertFalse($this->instance->hasEvent('onTest'));

		$event = new Event('onTest');
		$this->instance->addEvent($event);
		$this->assertTrue($this->instance->hasEvent($event));
	}

	/**
	 * Test the getEvent method when the event doesn't exist.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::getEvent
	 * @since   1.0
	 */
	public function testGetEventNonExisting()
	{
		$this->assertNull($this->instance->getEvent('non-existing'));
		$this->assertFalse($this->instance->getEvent('non-existing', false));
	}

	/**
	 * Test the removeEvent method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::removeEvent
	 * @since   1.0
	 */
	public function testRemoveEvent()
	{
		// No exception.
		$this->instance->removeEvent('non-existing');

		$event = new Event('onTest');
		$this->instance->addEvent($event);

		// Remove by passing the instance.
		$this->instance->removeEvent($event);
		$this->assertFalse($this->instance->hasEvent('onTest'));

		$this->instance->addEvent($event);

		// Remove by name.
		$this->instance->removeEvent('onTest');
		$this->assertFalse($this->instance->hasEvent('onTest'));
	}

	/**
	 * Test the getEvents method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::getEvents
	 * @since   1.0
	 */
	public function testGetEvents()
	{
		$this->assertEmpty($this->instance->getEvents());

		$event1 = new Event('onBeforeTest');
		$event2 = new Event('onTest');
		$event3 = new Event('onAfterTest');

		$this->instance->addEvent($event1)
			->addEvent($event2)
			->addEvent($event3);

		$expected = array(
			'onBeforeTest' => $event1,
			'onTest' => $event2,
			'onAfterTest' => $event3
		);

		$this->assertSame($expected, $this->instance->getEvents());
	}

	/**
	 * Test the clearEvents method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::clearEvents
	 * @since   1.0
	 */
	public function testClearEvents()
	{
		$event1 = new Event('onBeforeTest');
		$event2 = new Event('onTest');
		$event3 = new Event('onAfterTest');

		$this->instance->addEvent($event1)
			->addEvent($event2)
			->addEvent($event3);

		$this->instance->clearEvents();

		$this->assertFalse($this->instance->hasEvent('onBeforeTest'));
		$this->assertFalse($this->instance->hasEvent('onTest'));
		$this->assertFalse($this->instance->hasEvent('onAfterTest'));
		$this->assertEmpty($this->instance->getEvents());
	}

	/**
	 * Test the countEvents method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::countEvents
	 * @since   1.0
	 */
	public function testCountEvents()
	{
		$this->assertEquals(0, $this->instance->countEvents());

		$event1 = new Event('onBeforeTest');
		$event2 = new Event('onTest');
		$event3 = new Event('onAfterTest');

		$this->instance->addEvent($event1)
			->addEvent($event2)
			->addEvent($event3);

		$this->assertEquals(3, $this->instance->countEvents());
	}

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
				'onSomething',
				'Joomla\Event\Tests\Fixtures\onFunction'
			),
			// Closure
			array(
				1,
				'onBeforeSomething',
				function (Event $e) {}
			),
			// Static method from class
			array(
				2,
				'onSomething',
				array('Joomla\Event\Tests\Fixtures\SomethingListener', 'onStatic')
			),
			// Static method from object
			array(
				2,
				'onBeforeSomething',
				array(new SomethingListener, 'onStatic')
			),
			// Static call
			array(
				-5,
				'onBeforeSomething',
				'Joomla\Event\Tests\Fixtures\SomethingListener::onStatic'
			),
			// Relative static call from class
			array(
				-5,
				'onAfterSomething',
				array('Joomla\Event\Tests\Fixtures\ChildListener', 'parent::onStatic')
			),
			// Relative static call from object
			array(
				-5,
				'onAfterSomething',
				array(new ChildListener, 'parent::onStatic')
			),
		);
	}

	/**
	 * Test the addListener method.
	 *
	 * @param   integer   $priority   The priority
	 * @param   string    $eventName  The event name
	 * @param   callable  $listener   The listener
	 *
	 * @return  void
	 *
	 * @dataProvider getListeners
	 *
	 * @covers  Joomla\Event\Dispatcher::addListener
	 * @since   1.0
	 */
	public function testAddListener($priority, $eventName, $listener)
	{
		$this->instance->addListener($listener, $eventName, $priority);

		$this->assertTrue($this->instance->hasListener($listener));
		$this->assertTrue($this->instance->hasListener($listener, $eventName));
		$this->assertEquals($priority, $this->instance->getListenerPriority($listener, $eventName));
	}

	/**
	 * Test the addListener method with an invalid listener.
	 *
	 * @expectedException  \InvalidArgumentException
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::addListener
	 * @since   1.0
	 */
	public function testAddListenerInvalidListenerException()
	{
		$this->instance->addListener(1, 'onSomething');
	}

	/**
	 * Test the triggerEvent method with no listeners listening to the event.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::triggerEvent
	 * @since   1.0
	 */
	public function testTriggerEventNoListeners()
	{
		$this->assertInstanceOf('Joomla\Event\Event', $this->instance->triggerEvent('onTest'));

		$event = new Event('onTest');
		$this->assertSame($event, $this->instance->triggerEvent($event));
	}

	/**
	 * Test the trigger event method with listeners having the same priority.
	 * We expect the listener to be called in the order they were added.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::triggerEvent
	 * @since   1.0
	 */
	public function testTriggerEventSamePriority()
	{
		$first = array(new FirstListener, 'onSomething');
		$second = array(new SecondListener, 'onSomething');
		$third = array(new ThirdListener, 'onSomething');

		$fourth = function (Event $event) {
			$listeners = $event->getArgument('listeners');
			$listeners[] = 'fourth';
			$event->setArgument('listeners', $listeners);
		};

		$fifth = function (Event $event) {
			$listeners = $event->getArgument('listeners');
			$listeners[] = 'fifth';
			$event->setArgument('listeners', $listeners);
		};

		$this->instance->addListener($first, 'onSomething')
			->addListener($second, 'onSomething')
			->addListener($third, 'onSomething')
			->addListener($fourth, 'onSomething')
			->addListener($fifth, 'onSomething');

		// Inspect the event arguments to know the order of the listeners.
		/** @var $event Event */
		$event = $this->instance->triggerEvent('onSomething');

		$listeners = $event->getArgument('listeners');

		$this->assertEquals(
			$listeners,
			array('first', 'second', 'third', 'fourth', 'fifth')
		);
	}

	/**
	 * Test the trigger event method with listeners having different priorities.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::triggerEvent
	 * @since   1.0
	 */
	public function testTriggerEventDifferentPriorities()
	{
		$first = array(new FirstListener, 'onSomething');
		$second = array(new SecondListener, 'onSomething');
		$third = array(new ThirdListener, 'onSomething');

		$fourth = function (Event $event) {
			$listeners = $event->getArgument('listeners');
			$listeners[] = 'fourth';
			$event->setArgument('listeners', $listeners);
		};

		$fifth = function (Event $event) {
			$listeners = $event->getArgument('listeners');
			$listeners[] = 'fifth';
			$event->setArgument('listeners', $listeners);
		};

		$this->instance->addListener($fourth, 'onSomething', Priority::BELOW_NORMAL);
		$this->instance->addListener($fifth, 'onSomething', Priority::BELOW_NORMAL);
		$this->instance->addListener($first, 'onSomething', Priority::HIGH);
		$this->instance->addListener($second, 'onSomething', Priority::HIGH);
		$this->instance->addListener($third, 'onSomething', Priority::ABOVE_NORMAL);

		// Inspect the event arguments to know the order of the listeners.
		/** @var $event Event */
		$event = $this->instance->triggerEvent('onSomething');

		$listeners = $event->getArgument('listeners');

		$this->assertEquals(
			$listeners,
			array('first', 'second', 'third', 'fourth', 'fifth')
		);
	}

	/**
	 * Test the trigger event method with a listener stopping the event propagation.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::triggerEvent
	 * @since   1.0
	 */
	public function testTriggerEventStopped()
	{
		$first = array(new FirstListener, 'onSomething');
		$second = array(new SecondListener, 'onSomething');
		$third = array(new ThirdListener, 'onSomething');

		$stopper = function (Event $event) {
			$event->stop();
		};

		$this->instance->addListener($first, 'onSomething')
			->addListener($second, 'onSomething')
			->addListener($stopper, 'onSomething')
			->addListener($third, 'onSomething');

		/** @var $event Event */
		$event = $this->instance->triggerEvent('onSomething');

		$listeners = $event->getArgument('listeners');

		// The third listener was not called because the stopper stopped the event.
		$this->assertEquals(
			$listeners,
			array('first', 'second')
		);
	}

	/**
	 * Test the triggerEvent method with a previously registered event.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Event\Dispatcher::triggerEvent
	 * @since   1.0
	 */
	public function testTriggerEventRegistered()
	{
		$event = new Event('onSomething');

		$mockedListener = $this->getMock('Joomla\Event\Test\Fixtures\SomethingListener', array('onSomething'));
		$mockedListener->expects($this->once())
			->method('onSomething')
			->with($event);

		$this->instance->addEvent($event);
		$this->instance->addListener(array($mockedListener, 'onSomething'), 'onSomething');

		$this->instance->triggerEvent('onSomething');
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
		$this->instance = new Dispatcher;
	}
}
