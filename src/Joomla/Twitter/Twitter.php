<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

use Joomla\Http\Http;

/**
 * Joomla Framework class for interacting with a Twitter API instance.
 *
 * @since  1.0
 */
class Twitter
{
	/**
	 * @var    array  Options for the Twitter object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    OAuth  The OAuth client.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    Friends  Twitter API object for friends.
	 * @since  1.0
	 */
	protected $friends;

	/**
	 * @var    Users  Twitter API object for users.
	 * @since  1.0
	 */
	protected $users;

	/**
	 * @var    Help  Twitter API object for help.
	 * @since  1.0
	 */
	protected $help;

	/**
	 * @var    Statuses  Twitter API object for statuses.
	 * @since  1.0
	 */
	protected $statuses;

	/**
	 * @var    Search  Twitter API object for search.
	 * @since  1.0
	 */
	protected $search;

	/**
	 * @var    Favorites  Twitter API object for favorites.
	 * @since  1.0
	 */
	protected $favorites;

	/**
	 * @var    DirectMessages  Twitter API object for direct messages.
	 * @since  1.0
	 */
	protected $directMessages;

	/**
	 * @var    Lists  Twitter API object for lists.
	 * @since  1.0
	 */
	protected $lists;

	/**
	 * @var    Places  Twitter API object for places & geo.
	 * @since  1.0
	 */
	protected $places;

	/**
	 * @var    Trends  Twitter API object for trends.
	 * @since  1.0
	 */
	protected $trends;

	/**
	 * @var    Block  Twitter API object for block.
	 * @since  1.0
	 */
	protected $block;

	/**
	 * @var    Profile  Twitter API object for profile.
	 * @since  1.0
	 */
	protected $profile;

	/**
	 * Constructor.
	 *
	 * @param   OAuth  $oauth    The oauth client.
	 * @param   array  $options  Twitter options array.
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
			$this->options['api.url'] = 'https://api.twitter.com/1.1';
		}
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  Object  Twitter API object (statuses, users, favorites, etc.).
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
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
	 * Get an option from the Twitter instance.
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
	 * Set an option for the Twitter instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Twitter  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}
}
