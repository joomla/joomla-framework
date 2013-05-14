<?php
/**
 * Part of the Joomla Framework Linkedin Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin;

use Joomla\Registry\Registry;
use Joomla\Http\Http;
use Joomla\Linkedin\OAuth;

/**
 * Linkedin API object class for the Joomla Framework.
 *
 * @since  1.0
 */
abstract class Object
{
	/**
	 * @var    Registry  Options for the Linkedin object.
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
	 * Constructor.
	 *
	 * @param   Registry  $options  Linkedin options object.
	 * @param   Http      $client   The HTTP client object.
	 * @param   OAuth     $oauth    The OAuth client.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $options = null, Http $client = null, OAuth $oauth = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->client = isset($client) ? $client : new Http($this->options);
		$this->oauth = $oauth;
	}

	/**
	 * Method to convert boolean to string.
	 *
	 * @param   boolean  $bool  The boolean value to convert.
	 *
	 * @return  string  String with the converted boolean.
	 *
	 * @since 1.0
	 */
	public function booleanToString($bool)
	{
		if ($bool)
		{
			return 'true';
		}
		else
		{
			return 'false';
		}
	}

	/**
	 * Get an option from the Object instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the Object instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Object  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
