<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

use Joomla\OAuth1\Client;
use Joomla\Http\Http;
use Joomla\Input\Input;
use Joomla\Application\AbstractWebApplication;

/**
 * Joomla Framework class for generating Twitter API access token.
 *
 * @since  1.0
 */
class OAuth extends Client
{
	/**
	 * @var    array  Options for the Twitter OAuth object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   array                   $options      OAuth options array.
	 * @param   Http                    $client       The HTTP client object.
	 * @param   Input                   $input        The input object.
	 * @param   AbstractWebApplication  $application  The application object.
	 *
	 * @since 1.0
	 */
	public function __construct($options, Http $client, Input $input, AbstractWebApplication $application)
	{
		$this->options = $options;

		if (!isset($this->options['accessTokenURL']))
		{
			$this->options['accessTokenURL'] = 'https://api.twitter.com/oauth/access_token';
		}

		if (!isset($this->options['authenticateURL']))
		{
			$this->options['authenticateURL'] = 'https://api.twitter.com/oauth/authenticate';
		}

		if (!isset($this->options['authoriseURL']))
		{
			$this->options['authoriseURL'] = 'https://api.twitter.com/oauth/authorize';
		}

		if (!isset($this->options['requestTokenURL']))
		{
			$this->options['requestTokenURL'] = 'https://api.twitter.com/oauth/request_token';
		}

		// Call the OAuth1 Client constructor to setup the object.
		parent::__construct($this->options, $client, $input, $application);
	}

	/**
	 * Method to verify if the access token is valid by making a request.
	 *
	 * @return  boolean  Returns true if the access token is valid and false otherwise.
	 *
	 * @since   1.0
	 */
	public function verifyCredentials()
	{
		$token = $this->getToken();

		// Set the parameters.
		$parameters = array('oauth_token' => $token['key']);

		// Set the API base
		$path = 'https://api.twitter.com/1.1/account/verify_credentials.json';

		// Send the request.
		$response = $this->oauthRequest($path, 'GET', $parameters);

		// Verify response
		if ($response->code == 200)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Ends the session of the authenticating user, returning a null cookie.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function endSession()
	{
		$token = $this->getToken();

		// Set parameters.
		$parameters = array('oauth_token' => $token['key']);

		// Set the API base
		$path = 'https://api.twitter.com/1.1/account/end_session.json';

		// Send the request.
		$response = $this->oauthRequest($path, 'POST', $parameters);

		return json_decode($response->body);
	}

	/**
	 * Method to validate a response.
	 *
	 * @param   string                 $url       The request URL.
	 * @param   \Joomla\Http\Response  $response  The response to validate.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 * @throws \DomainException
	 */
	public function validateResponse($url, $response)
	{
		if (strpos($url, 'verify_credentials') === false && $response->code != 200)
		{
			$error = json_decode($response->body);

			if (property_exists($error, 'error'))
			{
				throw new \DomainException($error->error);
			}
			else
			{
				$error = $error->errors;
				throw new \DomainException($error[0]->message, $error[0]->code);
			}
		}
	}
}
