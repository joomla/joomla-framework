<?php
/**
 * Part of the Joomla Framework OAuth2 Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OAuth2;

use Joomla\Application\AbstractWebApplication;
use Joomla\Input\Input;
use Joomla\Http\Http;

/**
 * Joomla Framework class for interacting with an OAuth 2.0 server.
 *
 * @since  1.0
 */
class Client
{
	/**
	 * @var    array  Options for the Client object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $http;

	/**
	 * @var    Input  The input object to use in retrieving GET/POST data.
	 * @since  1.0
	 */
	protected $input;

	/**
	 * @var    AbstractWebApplication  The application object to send HTTP headers for redirects.
	 * @since  1.0
	 */
	protected $application;

	/**
	 * Constructor.
	 *
	 * @param   array                   $options      OAuth2 Client options array
	 * @param   Http                    $http         The HTTP client object
	 * @param   Input                   $input        The input object
	 * @param   AbstractWebApplication  $application  The application object
	 *
	 * @since   1.0
	 */
	public function __construct($options, Http $http, Input $input, AbstractWebApplication $application)
	{
		$this->options = $options;
		$this->http = $http;
		$this->input = $input;
		$this->application = $application;
	}

	/**
	 * Get the access token or redict to the authentication URL.
	 *
	 * @return  string  The access token
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function authenticate()
	{
		if ($data['code'] = $this->input->get('code', false, 'raw'))
		{
			$data['grant_type'] = 'authorization_code';
			$data['redirect_uri'] = $this->getOption('redirecturi');
			$data['client_id'] = $this->getOption('clientid');
			$data['client_secret'] = $this->getOption('clientsecret');
			$response = $this->http->post($this->getOption('tokenurl'), $data);

			if ($response->code >= 200 && $response->code < 400)
			{
				if ($response->headers['Content-Type'] == 'application/json')
				{
					$token = array_merge(json_decode($response->body, true), array('created' => time()));
				}
				else
				{
					parse_str($response->body, $token);
					$token = array_merge($token, array('created' => time()));
				}

				$this->setToken($token);

				return $token;
			}
			else
			{
				throw new \RuntimeException('Error code ' . $response->code . ' received requesting access token: ' . $response->body . '.');
			}
		}

		if ($this->getOption('sendheaders'))
		{
			$this->application->redirect($this->createUrl());
		}

		return false;
	}

	/**
	 * Verify if the client has been authenticated
	 *
	 * @return  boolean  Is authenticated
	 *
	 * @since   1.0
	 */
	public function isAuthenticated()
	{
		$token = $this->getToken();

		if (!$token || !array_key_exists('access_token', $token))
		{
			return false;
		}
		elseif (array_key_exists('expires_in', $token) && $token['created'] + $token['expires_in'] < time() + 20)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Create the URL for authentication.
	 *
	 * @return  \Joomla\Http\Response  The HTTP response
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function createUrl()
	{
		if (!$this->getOption('authurl') || !$this->getOption('clientid'))
		{
			throw new \InvalidArgumentException('Authorization URL and client_id are required');
		}

		$url = $this->getOption('authurl');

		if (strpos($url, '?'))
		{
			$url .= '&';
		}
		else
		{
			$url .= '?';
		}

		$url .= 'response_type=code';
		$url .= '&client_id=' . urlencode($this->getOption('clientid'));

		if ($this->getOption('redirecturi'))
		{
			$url .= '&redirect_uri=' . urlencode($this->getOption('redirecturi'));
		}

		if ($this->getOption('scope'))
		{
			$scope = is_array($this->getOption('scope')) ? implode(' ', $this->getOption('scope')) : $this->getOption('scope');
			$url .= '&scope=' . urlencode($scope);
		}

		if ($this->getOption('state'))
		{
			$url .= '&state=' . urlencode($this->getOption('state'));
		}

		if (is_array($this->getOption('requestparams')))
		{
			foreach ($this->getOption('requestparams') as $key => $value)
			{
				$url .= '&' . $key . '=' . urlencode($value);
			}
		}

		return $url;
	}

	/**
	 * Send a signed Oauth request.
	 *
	 * @param   string  $url      The URL for the request.
	 * @param   mixed   $data     The data to include in the request
	 * @param   array   $headers  The headers to send with the request
	 * @param   string  $method   The method with which to send the request
	 * @param   int     $timeout  The timeout for the request
	 *
	 * @return  string  The URL.
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 * @throws  \RuntimeException
	 */
	public function query($url, $data = null, $headers = array(), $method = 'get', $timeout = null)
	{
		$token = $this->getToken();

		if (array_key_exists('expires_in', $token) && $token['created'] + $token['expires_in'] < time() + 20)
		{
			if (!$this->getOption('userefresh'))
			{
				return false;
			}

			$token = $this->refreshToken($token['refresh_token']);
		}

		if (!$this->getOption('authmethod') || $this->getOption('authmethod') == 'bearer')
		{
			$headers['Authorization'] = 'Bearer ' . $token['access_token'];
		}
		elseif ($this->getOption('authmethod') == 'get')
		{
			if (strpos($url, '?'))
			{
				$url .= '&';
			}
			else
			{
				$url .= '?';
			}

			$url .= $this->getOption('getparam') ? $this->getOption('getparam') : 'access_token';
			$url .= '=' . $token['access_token'];
		}

		switch ($method)
		{
			case 'head':
			case 'get':
			case 'delete':
			case 'trace':
				$response = $this->http->$method($url, $headers, $timeout);
				break;

			case 'post':
			case 'put':
			case 'patch':
				$response = $this->http->$method($url, $data, $headers, $timeout);
				break;

			default:
				throw new \InvalidArgumentException('Unknown HTTP request method: ' . $method . '.');
		}

		if ($response->code < 200 || $response->code >= 400)
		{
			throw new \RuntimeException('Error code ' . $response->code . ' received requesting data: ' . $response->body . '.');
		}

		return $response;
	}

	/**
	 * Get an option from the Client instance.
	 *
	 * @param   string  $key  The name of the option to get
	 *
	 * @return  mixed  The option value
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Set an option for the Client instance.
	 *
	 * @param   string  $key    The name of the option to set
	 * @param   mixed   $value  The option value to set
	 *
	 * @return  Client  This object for method chaining
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}

	/**
	 * Get the access token from the Client instance.
	 *
	 * @return  array  The access token
	 *
	 * @since   1.0
	 */
	public function getToken()
	{
		return $this->getOption('accesstoken');
	}

	/**
	 * Set an option for the Client instance.
	 *
	 * @param   array  $value  The access token
	 *
	 * @return  Client  This object for method chaining
	 *
	 * @since   1.0
	 */
	public function setToken($value)
	{
		if (is_array($value) && !array_key_exists('expires_in', $value) && array_key_exists('expires', $value))
		{
			$value['expires_in'] = $value['expires'];
			unset($value['expires']);
		}

		$this->setOption('accesstoken', $value);

		return $this;
	}

	/**
	 * Refresh the access token instance.
	 *
	 * @param   string  $token  The refresh token
	 *
	 * @return  array  The new access token
	 *
	 * @since   1.0
	 * @throws  \Exception
	 * @throws  \RuntimeException
	 */
	public function refreshToken($token = null)
	{
		if (!$this->getOption('userefresh'))
		{
			throw new \RuntimeException('Refresh token is not supported for this OAuth instance.');
		}

		if (!$token)
		{
			$token = $this->getToken();

			if (!array_key_exists('refresh_token', $token))
			{
				throw new \RuntimeException('No refresh token is available.');
			}

			$token = $token['refresh_token'];
		}

		$data['grant_type'] = 'refresh_token';
		$data['refresh_token'] = $token;
		$data['client_id'] = $this->getOption('clientid');
		$data['client_secret'] = $this->getOption('clientsecret');
		$response = $this->http->post($this->getOption('tokenurl'), $data);

		if ($response->code >= 200 || $response->code < 400)
		{
			if ($response->headers['Content-Type'] == 'application/json')
			{
				$token = array_merge(json_decode($response->body, true), array('created' => time()));
			}
			else
			{
				parse_str($response->body, $token);
				$token = array_merge($token, array('created' => time()));
			}

			$this->setToken($token);

			return $token;
		}
		else
		{
			throw new \Exception('Error code ' . $response->code . ' received refreshing token: ' . $response->body . '.');
		}
	}
}
