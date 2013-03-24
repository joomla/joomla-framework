<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Event;

use BadMethodCallException;
use Serializable;
use ArrayAccess;
use Countable;

/**
 * Implementation of an immutable Event.
 * An immutable event cannot be modified after instanciation :
 *
 * - its propagation cannot be stopped
 * - its arguments cannot be modified
 *
 * You may want to use/extend this event when you want to ensure that
 * the listeners won't manipulate it.
 *
 * @since  1.0
 */
class ImmutableEvent implements EventInterface, ArrayAccess, Serializable, Countable
{
	/**
	 * The event name.
	 *
	 * @var    string
	 *
	 * @since  1.0
	 */
	protected $name;

	/**
	 * The event arguments.
	 *
	 * @var    array
	 *
	 * @since  1.0
	 */
	protected $arguments;

	/**
	 * A flag to see if the event propagation is stopped.
	 *
	 * @var    boolean
	 *
	 * @since  1.0
	 */
	protected $stopped = false;

	/**
	 * Constructor.
	 *
	 * @param   string  $name       The event name.
	 * @param   array   $arguments  The event arguments.
	 *
	 * @since   1.0
	 */
	public function __construct($name, array $arguments = array())
	{
		$this->name = $name;
		$this->arguments = $arguments;
	}

	/**
	 * Get the event name.
	 *
	 * @return  string  The event name.
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get an event argument value.
	 *
	 * @param   string  $name     The argument name.
	 * @param   mixed   $default  The default value if not found.
	 *
	 * @return  mixed  The argument value or the default value.
	 *
	 * @since   1.0
	 */
	public function getArgument($name, $default = null)
	{
		if (isset($this->arguments[$name]))
		{
			return $this->arguments[$name];
		}

		return $default;
	}

	/**
	 * Tell if the given event argument exists.
	 *
	 * @param   string  $name  The argument name.
	 *
	 * @return  boolean  True if it exists, false otherwise.
	 *
	 * @since   1.0
	 */
	public function hasArgument($name)
	{
		return isset($this->arguments[$name]);
	}

	/**
	 * Get all event arguments.
	 *
	 * @return  array  An associative array of argument names as keys
	 *                 and their values as values.
	 *
	 * @since   1.0
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * Tell if the event propagation is stopped.
	 *
	 * @return  boolean  True if stopped, false otherwise.
	 *
	 * @since   1.0
	 */
	public function isStopped()
	{
		return true === $this->stopped;
	}

	/**
	 * Count the number of arguments.
	 *
	 * @return  integer  The number of arguments.
	 *
	 * @since   1.0
	 */
	public function count()
	{
		return count($this->arguments);
	}

	/**
	 * Serialize the event.
	 *
	 * @return  string  The serialized event.
	 *
	 * @since   1.0
	 */
	public function serialize()
	{
		return serialize(array($this->name, $this->arguments, $this->stopped));
	}

	/**
	 * Unserialize the event.
	 *
	 * @param   string  $serialized  The serialized event.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function unserialize($serialized)
	{
		list($this->name, $this->arguments, $this->stopped) = unserialize($serialized);
	}

	/**
	 * Set the value of an event argument.
	 *
	 * @param   string  $name   The argument name.
	 * @param   mixed   $value  The argument value.
	 *
	 * @return  void
	 *
	 * @throws  BadMethodCallException
	 *
	 * @since   1.0
	 */
	public function offsetSet($name, $value)
	{
		throw new BadMethodCallException(
			sprintf(
				'Cannot set the argument %s of the immutable event %s.',
				$name,
				$this->name
			)
		);
	}

	/**
	 * Tell if the given event argument exists.
	 *
	 * @param   string  $name  The argument name.
	 *
	 * @return  boolean  True if it exists, false otherwise.
	 *
	 * @since   1.0
	 */
	public function offsetExists($name)
	{
		return $this->hasArgument($name);
	}

	/**
	 * Remove an event argument.
	 *
	 * @param   string  $name  The argument name.
	 *
	 * @return  void
	 *
	 * @throws  BadMethodCallException
	 *
	 * @since   1.0
	 */
	public function offsetUnset($name)
	{
		throw new BadMethodCallException(
			sprintf(
				'Cannot remove the argument %s of the immutable event %s.',
				$name,
				$this->name
			)
		);
	}

	/**
	 * Get an event argument value.
	 *
	 * @param   string  $name  The argument name.
	 *
	 * @return  mixed  The argument value or null if not existing.
	 *
	 * @since   1.0
	 */
	public function offsetGet($name)
	{
		return $this->getArgument($name);
	}
}
