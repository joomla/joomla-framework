<?php
/**
 * Part of the Joomla Framework Linkedin Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin;

use Joomla\Http\Http;

/**
 * Joomla Framework class for interacting with a Linkedin API instance.
 *
 * @since  1.0
 */
class Linkedin
{
	/**
	 * @var    array  Options for the Linkedin object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    OAuth The OAuth client.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    People  Linkedin API object for people.
	 * @since  1.0
	 */
	protected $people;

	/**
	 * @var    Groups  Linkedin API object for groups.
	 * @since  1.0
	 */
	protected $groups;

	/**
	 * @var    Companies  Linkedin API object for companies.
	 * @since  1.0
	 */
	protected $companies;

	/**
	 * @var    Jobs  Linkedin API object for jobs.
	 * @since  1.0
	 */
	protected $jobs;

	/**
	 * @var    Stream  Linkedin API object for social stream.
	 * @since  1.0
	 */
	protected $stream;

	/**
	 * @var    Communications  Linkedin API object for communications.
	 * @since  1.0
	 */
	protected $communications;

	/**
	 * Constructor.
	 *
	 * @param   OAuth  $oauth    The Linkedin OAuth client.
	 * @param   array  $options  Linkedin options array.
	 * @param   Http   $client   The HTTP client object.
	 *
	 * @since   1.0
	 */
	public function __construct(OAuth $oauth = null, $options = array(), Http $client = null)
	{
		$this->oauth = $oauth;
		$this->options = $options;
		$this->client  = $client;

		// Setup the default API url if not already set.
		if (!isset($this->options['api.url']))
		{
			$this->options['api.url'] = 'https://api.linkedin.com';
		}
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  Object  Linkedin API object (statuses, users, favorites, etc.).
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException If $name is not a valid sub class.
	 */
	public function __get($name)
	{
		$class = __NAMESPACE__ . '\\' . ucfirst(strtolower($name));

		if (class_exists($class) && property_exists($this, $name))
		{
			if (false == isset($this->$name))
			{
				$this->$name = new $class($this->options, $this->client, $this->oauth);
			}

			return $this->$name;
		}

		throw new \InvalidArgumentException(sprintf('Argument %s produced an invalid class name: %s', $name, $class));
	}

	/**
	 * Get an option from the Linkedin instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Set an option for the Linkedin instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Linkedin  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}
}
