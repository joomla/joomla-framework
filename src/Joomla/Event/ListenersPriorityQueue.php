<?php
/**
 * Part of the Joomla Framework Event Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Event;

/**
 * A class containing an inner listeners priority queue that can be iterated multiple times.
 * One instance of ListenersPriorityQueue is used per Event in the Dispatcher.
 *
 * @since  1.0
 */
class ListenersPriorityQueue implements \IteratorAggregate, \Countable
{
	/**
	 * The inner priority queue.
	 *
	 * @var    \SplPriorityQueue
	 * @since  1.0
	 */
	private $queue;

	/**
	 * A copy of the listeners contained in the queue
	 * that is used when detaching them to
	 * recreate the queue or to see if the queue contains
	 * a given listener.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $listeners = array();

	/**
	 * The listeners priorities indexed
	 * with $listeners.
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	private $priorities = array();

	/**
	 * A decreasing counter used to compute
	 * the internal priority as an array because
	 * SplPriorityQueue dequeues elements with the same priority.
	 *
	 * @var    integer
	 * @since  1.0
	 */
	private $counter = PHP_INT_MAX;

	/**
	 * Constructor.
	 *
	 * @since  1.0
	 */
	public function __construct()
	{
		$this->queue = new \SplPriorityQueue;
	}

	/**
	 * Add a listener with the given priority only if not already present.
	 *
	 * @param   callable  $listener  The listener.
	 * @param   integer   $priority  The listener priority.
	 *
	 * @return  ListenersPriorityQueue  This method is chainable.
	 *
	 * @since   1.0
	 */
	public function add($listener, $priority)
	{
		if (!in_array($listener, $this->listeners, true))
		{
			// Compute the internal priority as an array.
			$priority = array($priority, $this->counter--);

			$this->listeners[] = $listener;
			$this->priorities[] = $priority;
			$this->queue->insert($listener, $priority);
		}

		return $this;
	}

	/**
	 * Remove a listener from the queue.
	 *
	 * @param   callable  $listener  The listener.
	 *
	 * @return  ListenersPriorityQueue  This method is chainable.
	 *
	 * @since   1.0
	 */
	public function remove($listener)
	{
		$index = array_search($listener, $this->listeners, true);

		if (is_numeric($index))
		{
			unset($this->listeners[$index]);
			unset($this->priorities[$index]);

			$this->queue = new \SplPriorityQueue;

			foreach ($this->listeners as $key => $listener)
			{
				$this->queue->insert($listener, $this->priorities[$key]);
			}
		}

		return $this;
	}

	/**
	 * Tell if the listener exists in the queue.
	 *
	 * @param   callable  $listener  The listener.
	 *
	 * @return  boolean  True if it exists, false otherwise.
	 *
	 * @since   1.0
	 */
	public function has($listener)
	{
		return in_array($listener, $this->listeners, true);
	}

	/**
	 * Get the priority of the given listener.
	 *
	 * @param   callable  $listener  The listener.
	 * @param   mixed     $default   The default value to return if the listener doesn't exist.
	 *
	 * @return  mixed  The listener priority if it exists, null otherwise.
	 *
	 * @since   1.0
	 */
	public function getPriority($listener, $default = null)
	{
		$index = array_search($listener, $this->listeners, true);

		if (is_numeric($index))
		{
			return $this->priorities[$index][0];
		}

		return $default;
	}

	/**
	 * Get all listeners contained in this queue, sorted according to their priority.
	 *
	 * @return  object[]  An array of listeners.
	 *
	 * @since   1.0
	 */
	public function getAll()
	{
		$listeners = array();

		// Get a clone of the queue.
		$queue = $this->getIterator();

		foreach ($queue as $listener)
		{
			$listeners[] = $listener;
		}

		return $listeners;
	}

	/**
	 * Get the inner queue with its cursor on top of the heap.
	 *
	 * @return  \SplPriorityQueue  The inner queue.
	 *
	 * @since   1.0
	 */
	public function getIterator()
	{
		// SplPriorityQueue queue is a heap.
		$queue = clone $this->queue;

		if (!$queue->isEmpty())
		{
			$queue->top();
		}

		return $queue;
	}

	/**
	 * Count the number of listeners in the queue.
	 *
	 * @return  integer  The number of listeners in the queue.
	 *
	 * @since   1.0
	 */
	public function count()
	{
		return count($this->queue);
	}
}
