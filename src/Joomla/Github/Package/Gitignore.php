<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package;

use Joomla\Github\Package;

/**
 * GitHub API Gitignore class for the Joomla Platform.
 *
 * The .gitignore Templates API lists and fetches templates from the GitHub .gitignore repository.
 *
 * @documentation http://developer.github.com/v3/gitignore/
 * @documentation https://github.com/github/gitignore
 *
 * @since  1.0
 */
class Gitignore extends Package
{
	/**
	 * Listing available templates
	 *
	 * List all templates available to pass as an option when creating a repository.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function getList()
	{
		// Build the request path.
		$path = '/gitignore/templates';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Get a single template
	 *
	 * @param   string   $name  The name of the template
	 * @param   boolean  $raw   Raw output
	 *
	 * @throws \DomainException
	 * @since  1.0
	 *
	 * @return mixed|string
	 */
	public function get($name, $raw = false)
	{
		// Build the request path.
		$path = '/gitignore/templates/' . $name;

		$headers = array();

		if ($raw)
		{
			$headers['Accept'] = 'application/vnd.github.raw+json';
		}

		$response = $this->client->get($this->fetchUrl($path), $headers);

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error   = json_decode($response->body);
			$message = (isset($error->message)) ? $error->message : 'Invalid response';

			throw new \DomainException($message, $response->code);
		}

		return ($raw) ? $response->body : json_decode($response->body);
	}
}
