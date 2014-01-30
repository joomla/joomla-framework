<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

use Joomla\Http\Response;
use Joomla\Uri\Uri;
use Joomla\Registry\Registry;

/**
 * GitHub API object class for the Joomla Framework.
 *
 * @since  1.0
 */
abstract class AbstractGithubObject
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    string
	 * @since  1.0
	 */
	protected $package = '';

	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  GitHub options object.
	 * @param   Http      $client   The HTTP client object.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $options = null, Http $client = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->client = isset($client) ? $client : new Http($this->options);

		$this->package = get_class($this);
		$this->package = substr($this->package, strrpos($this->package, '\\') + 1);
	}

	/**
	 * Method to build and return a full request URL for the request.  This method will
	 * add appropriate pagination details if necessary and also prepend the API url
	 * to have a complete URL for the request.
	 *
	 * @param   string   $path   URL to inflect
	 * @param   integer  $page   Page to request
	 * @param   integer  $limit  Number of results to return per page
	 *
	 * @return  string   The request URL.
	 *
	 * @since   1.0
	 */
	protected function fetchUrl($path, $page = 0, $limit = 0)
	{
		// Get a new Uri object fousing the api url and given path.
		$uri = new Uri($this->options->get('api.url') . $path);

		if ($this->options->get('gh.token', false))
		{
			// Use oAuth authentication - @todo set in request header ?
			$uri->setVar('access_token', $this->options->get('gh.token'));
		}
		else
		{
			// Use basic authentication
			if ($this->options->get('api.username', false))
			{
				$uri->setUser($this->options->get('api.username'));
			}

			if ($this->options->get('api.password', false))
			{
				$uri->setPass($this->options->get('api.password'));
			}
		}

		// If we have a defined page number add it to the JUri object.
		if ($page > 0)
		{
			$uri->setVar('page', (int) $page);
		}

		// If we have a defined items per page add it to the JUri object.
		if ($limit > 0)
		{
			$uri->setVar('per_page', (int) $limit);
		}

		return (string) $uri;
	}

	/**
	 * Process the response and decode it.
	 *
	 * @param   Response  $response      The response.
	 * @param   integer   $expectedCode  The expected "good" code.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	protected function processResponse(Response $response, $expectedCode = 200)
	{
		// Validate the response code.
		if ($response->code != $expectedCode)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			$message = isset($error->message) ? $error->message : 'Invalid response received from GitHub.';
			throw new \DomainException($message, $response->code);
		}

		return json_decode($response->body);
	}
}
