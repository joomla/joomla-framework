<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Google
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google;

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;

/**
 * Joomla Platform class for interacting with the Google APIs.
 *
 * @property-read  JGoogleData    $data    Google API object for data.
 * @property-read  JGoogleEmbed   $embed   Google API object for embed generation.
 *
 * @package     Joomla.Platform
 * @subpackage  Google
 * @since       12.3
 */
class Google
{
	/**
	 * @var    JRegistry  Options for the Google object.
	 * @since  12.3
	 */
	protected $options;

	/**
	 * @var    JAuth  The authentication client object to use in sending authenticated HTTP requests.
	 * @since  12.3
	 */
	protected $auth;

	/**
	 * @var    JGoogleData  Google API object for data request.
	 * @since  12.3
	 */
	protected $data;

	/**
	 * @var    JGoogleEmbed  Google API object for embed generation.
	 * @since  12.3
	 */
	protected $embed;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry  $options  Google options object.
	 * @param   JAuth      $auth     The authentication client object.
	 *
	 * @since   12.3
	 */
	public function __construct(Registry $options = null, Auth $auth = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->auth  = isset($auth) ? $auth : new Auth\Oauth2($this->options);
	}

	/**
	 * Method to create JGoogleData objects
	 *
	 * @param   string     $name     Name of property to retrieve
	 * @param   JRegistry  $options  Google options object.
	 * @param   JAuth      $auth     The authentication client object.
	 *
	 * @return  JGoogleData  Google data API object.
	 *
	 * @since   12.3
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
	 * Method to create JGoogleEmbed objects
	 *
	 * @param   string     $name     Name of property to retrieve
	 * @param   JRegistry  $options  Google options object.
	 *
	 * @return  JGoogleEmbed  Google embed API object.
	 *
	 * @since   12.3
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
	 * Get an option from the JGoogle instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   12.3
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the JGoogle instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  JGoogle  This object for method chaining.
	 *
	 * @since   12.3
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
