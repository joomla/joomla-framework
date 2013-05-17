<?php
/**
 * Part of the Joomla Framework Linkedin Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

use Joomla\Oauth1\Client;
use Joomla\Registry\Registry;
use Joomla\Http\Http;
use Joomla\Input\Input;
use Joomla\Application\AbstractWebApplication;
use \DomainException;

/**
 * Joomla Framework class for generating Twitter API access token.
 *
 * @since  1.0
 */
class OAuth extends Client
{
	/**
	* @var   Registry Options for the Twitter Oauth object.
	* @since 1.0
	*/
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   Registry                $options      JTwitterOauth options object.
	 * @param   Http                    $client       The HTTP client object.
	 * @param   Input                   $input        The input object.
	 * @param   AbstractWebApplication  $application  The application object.
	 *
	 * @since 1.0
	 */
	public function __construct(Registry $options = null, Http $client = null, Input $input = null, AbstractWebApplication $application = null)
	{
		$this->options = isset($options) ? $options : new Registry;

		$this->options->def('accessTokenURL', 'https://api.twitter.com/oauth/access_token');
		$this->options->def('authenticateURL', 'https://api.twitter.com/oauth/authenticate');
		$this->options->def('authoriseURL', 'https://api.twitter.com/oauth/authorize');
		$this->options->def('requestTokenURL', 'https://api.twitter.com/oauth/request_token');

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
	 * @param   string         $url       The request URL.
	 * @param   JHttpResponse  $response  The response to validate.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 * @throws DomainException
	 */
	public function validateResponse($url, $response)
	{
		if (strpos($url, 'verify_credentials') === false && $response->code != 200)
		{
			$error = json_decode($response->body);

			if (property_exists($error, 'error'))
			{
				throw new DomainException($error->error);
			}
			else
			{
				$error = $error->errors;
				throw new DomainException($error[0]->message, $error[0]->code);
			}
		}
	}
}
