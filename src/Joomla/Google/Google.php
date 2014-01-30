<?php
/**
 * Part of the Joomla Framework Google Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google;

/**
 * Joomla Framework class for interacting with the Google APIs.
 *
 * @property-read  Data   $data    Google API object for data.
 * @property-read  Embed  $embed   Google API object for embed generation.
 *
 * @since  1.0
 */
class Google
{
	/**
	 * @var    array  Options for the Google object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Auth  The authentication client object to use in sending authenticated HTTP requests.
	 * @since  1.0
	 */
	protected $auth;

	/**
	 * @var    Data  Google API object for data request.
	 * @since  1.0
	 */
	protected $data;

	/**
	 * @var    Embed  Google API object for embed generation.
	 * @since  1.0
	 */
	protected $embed;

	/**
	 * Constructor.
	 *
	 * @param   array  $options  Google options object.
	 * @param   Auth   $auth     The authentication client object.
	 *
	 * @since   1.0
	 */
	public function __construct($options = array(), Auth $auth = null)
	{
		$this->options = $options;
		$this->auth  = isset($auth) ? $auth : new Auth\OAuth2($this->options);
	}

	/**
	 * Method to create Data objects
	 *
	 * @param   string  $name     Name of property to retrieve
	 * @param   array   $options  Google options object.
	 * @param   Auth    $auth     The authentication client object.
	 *
	 * @return  Data  Google data API object.
	 *
	 * @since   1.0
	 */
	public function data($name, $options = null, $auth = null)
	{
		if ($this->options && !$options)
		{
			$options = $this->options;
		}

		if ($this->auth && !$auth)
		{
			$auth = $this->auth;
		}

		switch (strtolower($name))
		{
			case 'plus':
				return new Data\Plus($options, $auth);

			case 'picasa':
				return new Data\Picasa($options, $auth);

			case 'adsense':
				return new Data\Adsense($options, $auth);

			case 'calendar':
				return new Data\Calendar($options, $auth);

			default:
				return null;
		}
	}

	/**
	 * Method to create Embed objects
	 *
	 * @param   string  $name     Name of property to retrieve
	 * @param   array   $options  Google options object.
	 *
	 * @return  Embed  Google embed API object.
	 *
	 * @since   1.0
	 */
	public function embed($name, $options = null)
	{
		if ($this->options && !$options)
		{
			$options = $this->options;
		}

		switch (strtolower($name))
		{
			case 'maps':
				return new Embed\Maps($options);

			case 'analytics':
				return new Embed\Analytics($options);

			default:
				return null;
		}
	}

	/**
	 * Get an option from the Google instance.
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
	 * Set an option for the Google instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Google  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}
}
