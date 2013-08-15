<?php
/**
 * Part of the Joomla Framework DI Package
 *
 * @copyright  Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\DI;

use Joomla\DI\Exception\DependencyResolutionException;

class Container
{
	/**
	 * Holds the shared instances.
	 *
	 * @var    array  $instances
	 *
	 * @since  1.0
	 */
	private $instances = array();

	/**
	 * Holds the keys, their callbacks, and whether or not
	 * the item is meant to be a shared resource.
	 *
	 * @var    array  $dataStore
	 *
	 * @since  1.0
	 */
	private $dataStore = array();

	/**
	 * Parent for hierarchical containers.
	 *
	 * @var    Container
	 *
	 * @since  1.0
	 */
	private $parent;

	/**
	 * Constructor for the DI Container
	 *
	 * @param  Container  $parent  Parent for hierarchical containers.
	 *
	 * @since  1.0
	 */
	public function __construct(Container $parent = null)
	{
		$this->parent = $parent;
	}

	/**
	 * Build an object of class $key;
	 *
	 * @param   string   $key     The class name to build.
	 * @param   boolean  $shared  True to create a shared resource.
	 *
	 * @return  object  Instance of class specified by $key with all dependencies injected.
	 *
	 * @since   1.0
	 */
	public function buildObject($key, $shared = false)
	{
		try
		{
			$reflection = new \ReflectionClass($key);
		}
		catch (\ReflectionException $e)
		{
			return false;
		}

		$constructor = $reflection->getConstructor();

		// If there are no parameters, just return a new object.
		if (is_null($constructor))
		{
			$callback = function () use ($key) { return new $key; };
		}
		else
		{
			$newInstanceArgs = $this->getMethodArgs($constructor);

			// Create a callable for the dataStore
			$callback = function () use ($reflection, $newInstanceArgs) {
				return $reflection->newInstanceArgs($newInstanceArgs);
			};
		}

		return $this->set($key, $callback, $shared)->get($key);
	}

	/**
	 * Convenience method for building a shared object.
	 *
	 * @param   string   $key                The class name to build.
	 * @param   array    $constructorParams  Array of named parameters to pass to constructor.
	 * @param   boolean  $shared             True to create a shared resource.
	 *
	 * @return  object  Instance of class specified by $key with all dependencies injected.
	 *
	 * @since   1.0
	 */
	public function buildSharedObject($key)
	{
		return $this->buildObject($key, true);
	}

	/**
	 * Create a child Container with a new property scope that
	 * that has the ability to access the parent scope when resolving.
	 *
	 * @return Container
	 */
	public function createChild()
	{
		return new static($this);
	}

	/**
	 * Extend a defined service Closure by wrapping the existing one with a new Closure.  This
	 * works very similar to a decorator pattern.  Note that this only works on service Closures
	 * that have been defined in the current Provider, not parent providers.
	 *
	 * @param   string   $key       The unique identifier for the Closure or property.
	 * @param   \Closure  $callable  A Closure to wrap the original service Closure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function extend($key, \Closure $callable)
	{
		$raw = $this->getRaw($key);

		if (is_null($raw))
		{
			throw new \InvalidArgumentException(sprintf('The requested key %s does not exist to extend.', $key));
		}

		$closure = function ($c) use($callable, $raw) {
			return $callable($raw['callback']($c), $c);
		};

		$this->set($key, $closure, $raw['shared']);
	}

	/**
	 * Build an array of constructor parameters.
	 *
	 * @param   \ReflectionMethod  $method  Method for which to build the argument array.
	 * @param   array              $params  Array of parameters from which to pull named dependencies.
	 *
	 * @return  array  Array of arguments to pass to the method.
	 */
	protected function getMethodArgs(\ReflectionMethod $method)
	{
		$methodArgs = array();

		foreach ($method->getParameters() as $param)
		{
			$dependency = $param->getClass();
			$dependencyVarName = $param->getName();

			// If we have a dependency, that means it has been type-hinted.
			if (!is_null($dependency))
			{
				$dependencyClassName = $dependency->getName();

				// If the dependency class name is registered with this container or a parent, use it.
				if ($this->getRaw($dependencyClassName) !== null)
				{
					$depObject = $this->get($dependencyClassName);
				}
				else
				{
					$depObject = $this->buildObject($dependencyClassName);
				}

				if ($depObject instanceof $dependencyClassName)
				{
					$methodArgs[] = $depObject;
					continue;
				}
			}

			// Finally, if there is a default parameter, use it.
			if ($param->isOptional())
			{
				$methodArgs[] = $param->getDefaultValue();
				continue;
			}

			// Couldn't resolve dependency, and no default was provided.
			throw new DependencyResolutionException(sprintf('Could not resolve dependency: %s', $dependencyVarName));
		}

		return $methodArgs;
	}

	/**
	 * Method to set the key and callback to the dataStore array.
	 *
	 * @param   string    $key        Name of dataStore key to set.
	 * @param   callable  $callback   Callable function to run when requesting the specified $key.
	 * @param   boolean   $shared     True to create and store a shared instance.
	 * @param   boolean   $protected  True to protect this item from being overwritten. Useful for services.
	 *
	 * @return  \Joomla\DI\Container  This instance to support chaining.
	 *
	 * @throws  \OutOfBoundsException      Thrown if the provided key is already set and is protected.
	 * @throws  \UnexpectedValueException  Thrown if the provided callback is not valid.
	 *
	 * @since   1.0
	 */
	public function set($key, $value, $shared = false, $protected = false)
	{
		if (isset($this->dataStore[$key]) && $this->dataStore[$key]['protected'] === true)
		{
			throw new \OutOfBoundsException(sprintf('Key %s is protected and can\'t be overwritten.', $key));
		}

		// If the provided $value is not a closure, make it one now for easy resolution.
		if (!($value instanceof \Closure))
		{
			$value = function () use ($value) {
				return $value;
			};
		}

		$this->dataStore[$key] = array(
			'callback' => $value,
			'shared' => $shared,
			'protected' => $protected
		);

		return $this;
	}

	/**
	 * Convenience method for creating protected keys.
	 *
	 * @param   string    $key       Name of dataStore key to set.
	 * @param   callable  $callback  Callable function to run when requesting the specified $key.
	 * @param   bool      $shared    True to create and store a shared instance.
	 *
	 * @return  \Joomla\DI\Container  This instance to support chaining.
	 *
	 * @since   1.0
	 */
	public function protect($key, $callback, $shared = false)
	{
		return $this->set($key, $callback, $shared, true);
	}

	/**
	 * Convenience method for creating shared keys.
	 *
	 * @param   string    $key        Name of dataStore key to set.
	 * @param   callable  $callback   Callable function to run when requesting the specified $key.
	 * @param   bool      $protected  True to create and store a shared instance.
	 *
	 * @return  \Joomla\DI\Container  This instance to support chaining.
	 *
	 * @since   1.0
	 */
	public function share($key, $callback, $protected = false)
	{
		return $this->set($key, $callback, true, $protected);
	}

	/**
	 * Method to retrieve the results of running the $callback for the specified $key;
	 *
	 * @param   string   $key       Name of the dataStore key to get.
	 * @param   boolean  $forceNew  True to force creation and return of a new instance.
	 *
	 * @return  mixed   Results of running the $callback for the specified $key.
	 *
	 * @since   1.0
	 */
	public function get($key, $forceNew = false)
	{
		$raw = $this->getRaw($key);

		if (is_null($raw))
		{
			throw new \InvalidArgumentException(sprintf('Key %s has not been registered with the container.', $key));
		}

		if ($raw['shared'])
		{
			if (!isset($this->instances[$key]) || $forceNew)
			{
				$this->instances[$key] = $raw['callback']($this);
			}

			return $this->instances[$key];
		}

		return $raw['callback']($this);
	}

	/**
	 * Get the raw data assigned to a key.
	 *
	 * @param   string  $key  The key for which to get the stored item.
	 *
	 * @return  mixed
	 */
	protected function getRaw($key)
	{
		if (isset($this->dataStore[$key]))
		{
			return $this->dataStore[$key];
		}
		elseif ($this->parent instanceof Container)
		{
			return $this->parent->getRaw($key);
		}

		return null;
	}

	/**
	 * Method to force the container to return a new instance
	 * of the results of the callback for requested $key.
	 *
	 * @param   string  $key  Name of the dataStore key to get.
	 *
	 * @return  mixed   Results of running the $callback for the specified $key.
	 *
	 * @since   1.0
	 */
	public function getNewInstance($key)
	{
		return $this->get($key, true);
	}
}

