<?php
/**
 * Part of the Joomla Framework DI Package
 *
 * @copyright  Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\DI;

class Container implements \ArrayAccess
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
	 * Holds config options accessible within the passed callback.
	 *
	 * @var    array  $config
	 *
	 * @since  1.0
	 */
	protected $config = array('default.shared' => true);

	/**
	 * Constructor for the DI Container
	 *
	 * @param  array  $config  Array of configuration parameters.
	 *
	 * @since  1.0
	 */
	public function __construct(array $config = array())
	{
		$this->setConfig($config);
	}

	/**
	 * Build an object of class $key;
	 *
	 * @param   string   $key                The class name to build.
	 * @param   array    $constructorParams  Array of named parameters to pass to constructor.
	 * @param   boolean  $shared             True to create a shared resource.
	 *
	 * @return  object  Instance of class specified by $key with all dependencies injected.
	 *
	 * @since   1.0
	 */
	public function buildObject($key, array $constructorParams = array(), $shared = false)
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
			$newInstanceArgs = $this->getMethodArgs($constructor, $constructorParams);

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
	public function buildSharedObject($key, $constructorParams = array())
	{
		return $this->buildObject($key, $constructorParams, true);
	}

	/**
	 * Build an array of constructor parameters.
	 *
	 * @param   \ReflectionMethod  $method  Method for which to build the argument array.
	 * @param   array              $params  Array of parameters from which to pull named dependencies.
	 *
	 * @return  array  Array of arguments to pass to the method.
	 */
	protected function getMethodArgs(\ReflectionMethod $method, array $params)
	{
		$methodArgs = array();

		foreach ($method->getParameters() as $param)
		{
			$dependency = $param->getClass();
			$dependencyVarName = $param->getName();
			$dependencyClassName = $dependency->getName();

			// If the dependency var name has been specified in the params array, use it.
			if (isset($params[$dependencyVarName]))
			{
				if (is_object($params[$dependencyVarName]))
				{
					$depObject = $params[$dependencyVarName];
				}
				else
				{
					$depObject = $this->buildObject($params[$dependencyVarName]);
				}

				// If the object is an instance of the expected class, use it.
				if ($depObject instanceof $dependencyClassName)
				{
					$methodArgs[] = $depObject;
					continue;
				}
			}

			// If the dependency class name is registered with the container, use it.
			if (isset($this->dataStore[$dependencyClassName]))
			{
				$depObject = $this->get($dependencyClassName);

				if ($depObject instanceof $dependencyClassName)
				{
					$methodArgs[] = $depObject;
					continue;
				}
			}

			// You shouldn't hint against implementations, but in case you have.
			if (class_exists($dependencyClassName))
			{
				$methodArgs[] = $this->buildObject($dependencyClassName);
				continue;
			}

			// Finally, if there is a default parameter, let's use it.
			if ($param->isOptional())
			{
				$methodArgs[] = $param->getDefaultValue();
				continue;
			}
		}

		return $methodArgs;
	}

	/**
	 * Method to set the key and callback to the dataStore array.
	 *
	 * @param   string    $key       Name of dataStore key to set.
	 * @param   callable  $callback  Callable function to run when requesting the specified $key.
	 * @param   mixed     $shared    True to create and store a shared instance.
	 *
	 * @return  Joomla\DI\Container  This instance to support chaining.
	 *
	 * @since   1.0
	 */
	public function set($key, $callback, $shared = null)
	{
		if (isset($this->dataStore[$key]))
		{
			throw new \OutOfBoundsException(sprintf('Key %s has already been assigned.', $key));
		}

		if (!is_callable($callback))
		{
			throw new \UnexpectedValueException('Provided value is not a valid callback.');
		}

		if (is_null($shared))
		{
			$shared = $this->config['default.shared'];
		}

		$this->dataStore[$key] = array(
			'callback' => $callback,
			'shared' => $shared
		);

		return $this;
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
		if (!isset($this->dataStore[$key]))
		{
			// If the key hasn't been set, try to build it.
			$object = $this->buildObject($key);

			if ($object !== false)
			{
				return $object;
			}

			throw new \InvalidArgumentException(sprintf('Key %s has not been registered with the container.', $key));
		}

		if ($this->dataStore[$key]['shared'])
		{
			if (!isset($this->instances[$key]) || $forceNew)
			{
				$this->instances[$key] = $this->dataStore[$key]['callback']($this);
			}

			return $this->instances[$key];
		}

		return $this->dataStore[$key]['callback']($this);
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

	/**
	 * Method to set an array of config options.
	 *
	 * @param   array  $config  Associative array to merge with the internal config.
	 *
	 * @return  Joomla\DI\Container  This instance to support chaining.
	 *
	 * @since   1.0
	 */
	public function setConfig(array $config)
	{
		$this->config = array_merge($this->config, $config);

		return $this;
	}

	/**
	 * Method to retrieve the entire config array.
	 *
	 * @return  array  The config array for this instance.
	 *
	 * @since   1.0
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Method to set a single config option.
	 *
	 * @param   string  $key    Name of config key.
	 * @param   mixed   $value  Value of config key.
	 *
	 * @return  Joomla\DI\Container  This instance to support chaining.
	 *
	 * @since   1.0
	 */
	public function setParam($key, $value)
	{
		$this->config[$key] = $value;

		return $this;
	}

	/**
	 * Method to retrieve a single configuration parameter.
	 *
	 * @param   string  $key  Name of config key to retrieve.
	 *
	 * @return  mixed  Value of config $key or null if not yet set.
	 *
	 * @since   1.0
	 */
	public function getParam($key)
	{
		return isset($this->config[$key]) ? $this->config[$key] : null;
	}

	/**
	 * Whether an offset exists.
	 *
	 * @param   string  $key  Name of the dataStore key to check if exists.
	 *
	 * @return  boolean  True if the specified offset exists.
	 *
	 * @since   1.0
	 */
	public function offsetExists($key)
	{
		return isset($this->dataStore[$key]);
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param   string  $key  Name of the dataStore key to get.
	 *
	 * @return  mixed  Results of running the $callback for the specified $key.
	 *
	 * @since   1.0
	 */
	public function offsetGet($key)
	{
		return $this->get($key);
	}

	/**
	 * Offset to set.
	 *
	 * @param   string    $key       Name of dataStore key to set.
	 * @param   callable  $callback  Callable function to run when requesting $key.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function offsetSet($key, $callback)
	{
		$this->set($key, $callback, $this->config['default.shared']);
	}

	/**
	 * Offset to unset.
	 *
	 * @param   string  $key  Offset to unset.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function offsetUnset($key)
	{
		unset($this->dataStore[$key]);
	}
}

