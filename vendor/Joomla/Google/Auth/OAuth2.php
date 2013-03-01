<?php
/**
 * @package     Joomla\Framework
 * @subpackage  Google
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Auth;


use Joomla\Google\Auth;
use Joomla\OAuth2\Client;
use Joomla\Registry\Registry;

/**
 * Google OAuth authentication class
 *
 * @package     Joomla\Framework
 * @subpackage  Google
 * @since       12.3
 */
class OAuth2 extends Auth
{
	/**
	 * @var    Client  OAuth client for the Google authentication object.
	 * @since  12.3
	 */
	protected $client;

	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  Auth options object.
	 * @param   Client    $client   OAuth client for Google authentication.
	 *
	 * @since   12.3
	 */
	public function __construct(Registry $options = null, Client $client = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->client = isset($client) ? $client : new Client($this->options);
	}

	/**
	 * Method to authenticate to Google
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   12.3
	 */
	public function authenticate()
	{
		$this->googlize();

		return $this->client->authenticate();
	}

	/**
	 * Verify if the client has been authenticated
	 *
	 * @return  boolean  Is authenticated
	 *
	 * @since   12.3
	 */
	public function isAuthenticated()
	{
		return $this->client->isAuthenticated();
	}

	/**
	 * Method to retrieve data from Google
	 *
	 * @param   string  $url      The URL for the request.
	 * @param   mixed   $data     The data to include in the request.
	 * @param   array   $headers  The headers to send with the request.
	 * @param   string  $method   The type of http request to send.
	 *
	 * @return  mixed  Data from Google.
	 *
	 * @since   12.3
	 */
	public function query($url, $data = null, $headers = null, $method = 'get')
	{
		$this->googlize();

		return $this->client->query($url, $data, $headers, $method);
	}

	/**
	 * Method to fill in Google-specific OAuth settings
	 *
	 * @return  Client  Google-configured Oauth2 client.
	 *
	 * @since   12.3
	 */
	protected function googlize()
	{
		if (!$this->client->getOption('authurl'))
		{
			$this->client->setOption('authurl', 'https://accounts.google.com/o/oauth2/auth');
		}

		if (!$this->client->getOption('tokenurl'))
		{
			$this->client->setOption('tokenurl', 'https://accounts.google.com/o/oauth2/token');
		}

		if (!$this->client->getOption('requestparams'))
		{
			$this->client->setOption('requestparams', Array());
		}

		$params = $this->client->getOption('requestparams');

		if (!array_key_exists('access_type', $params))
		{
			$params['access_type'] = 'offline';
		}

		if ($params['access_type'] == 'offline' && $this->client->getOption('userefresh') === null)
		{
			$this->client->setOption('userefresh', true);
		}

		if (!array_key_exists('approval_prompt', $params))
		{
			$params['approval_prompt'] = 'auto';
		}

		$this->client->setOption('requestparams', $params);

		return $this->client;
	}
}
